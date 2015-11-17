<?php
/**
 * SixDegrees class to implement Six Degrees of Separation algorithm
 * @copyright (c)2011-2015 Hendricson.com
 * @license GNU GPL version 3 or any later version
 */

class SixDegrees {
	
var $_chainLength = 0;
var $_brokenLink = false;
const _maximumCircleRadius = 4; //maximum radius of social circles generated or a user

/*********************************************************************/
/*	METHODS TO GENERATE CIRCLES UP TO THE _maximumCircleRadius ORDER */
/*********************************************************************/
	 
/**
* Method to generate array of friends for Nth order, where N is size of an array $arr
*
* @param   array			An array of circles, users and their friends.
* @param   integer  		Maximum circle number we want to build.
*
* @return  null
*/	 
static private function _organizeArray(&$arr) {
	$ff = array();	
	$label = max(array_keys($arr));//$label equals N, where N is the current circle
		foreach ($arr[$label] as $cs_k => $cs_v) {// loop through the friends of $cs_k that are in $label circle (with friends' ids kept in $cs_v)
		$tt = array();
		foreach ($cs_v as $v) {

				if (isset($arr[1][$v]) && count($arr[1][$v]) > 0) {//is anyone out there in the 1st circle of $cs_v?

					foreach ($arr[1][$v] as $v1) {// loop through the 1st circle friends of $cs_v

						$t = true;//by default, we need everyone in this circle
						
						//But we don't want those who are already in one of the previous circles of $cs_k
						for ($i = 1; $i <= $label; $i++) {	
							if (in_array($v1, $arr[$i][$cs_k])) $t = false;	//no, we don't want those
						}
						if ($v1 == $cs_k) $t = false;//we also don't want $cs_k himself
						
						if ($t) $ff[$cs_k][] = $v1; //if all is good, add to the result
					}
				}
		}
	}

	$arr[$label+1] = $ff;
}


/**
 * Method to generate list of friends for 1..$upto circles.
 *
 * @param   array			An array of circles, users and their friends.
 * @param   integer  		Maximum circle number we want to build.
 *
 * @return  null
 */
static private function _prepareCircles(&$f, $upto = self::_maximumCircleRadius){
	$n = max(array_keys($f));
	if ($n == $upto) return;
	SixDegrees::_organizeArray($f);
	SixDegrees::_prepareCircles($f, $upto); 
	return;
}

/**
 * Method to generate list of friends up to _maximumCircleRadius'th order (_maximumCircleRadius'th circle) for each user,
 * and save it to the database
 * 
 * @param	MySQLi			Database handler
 * 
 * @return  null
 */
static public function prepareFriendList($db) {
	
	//read list of connections from the DB
	$result = $db->query("SELECT id, connect_from, connect_to FROM connections ORDER BY connect_from ASC");	
	while ( $row = $result->fetch_object() ) {
		$connections[] = $row;
	}
	
	//start: prepare list of friends of the 1st order (1st circle)
	$friends = array();
	foreach ($connections as $c) {
		$friends[1][$c->connect_from][]	= $c->connect_to;	
	}	
	SixDegrees::_prepareCircles($friends);
	//end: prepare list of friends of the 1st order (1st circle)
	
	//start: for each user, convert array of friends of different orders into a list 
	foreach ($friends as $circle => $users) {
		foreach ($users as $id_user => $f) {
			$f = array_unique($f);
			sort($f);
			$friends[$circle][$id_user] = implode($f, ',');
		}
	}
	//end: for each user, convert array of friends of different orders into a list
	
	foreach ($friends[1] as $id_user => $v) {//for each user, save their circles to the database
		if (!empty($v)) {			
			$t = $db->prepare("INSERT INTO friends SET id_user = '$id_user',
			friends1 = '".$friends[1][$id_user]."', 
			friends2 = '".$friends[2][$id_user]."',
			friends3 = '".$friends[3][$id_user]."',
			friends4 = '".$friends[4][$id_user]."' 
			ON DUPLICATE KEY UPDATE 
			friends1 = '".$friends[1][$id_user]."', 
			friends2 = '".$friends[2][$id_user]."',
			friends3 = '".$friends[3][$id_user]."',
			friends4 = '".$friends[4][$id_user]."' 
			");		
			$t->execute();
		}
	}
	
}	
	
/**************************************/	
/*	METHODS TO BUILD CONNECTION CHAIN */
/**************************************/

/**
 * Method to generate a chain $stack between two given users X and Y labeled as $name.
 *
 * @param	array			Array of N circles for user X (each circle here is an array of X's friends' IDs of up to Nth order)
 * @param	array			Array of N circles for user Y (each circle here is an array of Y's friends' IDs of up to Nth order)
 * @param   integer			User X's level under consideration
 * @param   integer  		User Y's level under consideration
 * @param   array  			Two dimensional array which contains a $name chain between users X and Y 
 * @param   string  		Name of the chain
 *
 * @return  null
 */

private function _reverseSearch ($x, $y, $level, $level1, &$stack, $name) {

	if (is_array($x[$level - 1]) && is_array($y[$level1])) {
		$intersection = array_intersect ($x[$level - 1], $y[$level1]);

		if (!empty($intersection) && is_array($intersection)) {
			reset($intersection);
			$stack[$name][] = current($intersection);//$intersection[0];
			if ($level - 1 > 1) SixDegrees::_reverseSearch ($x, $y, $level-1, $level1+1, $stack, $name);
		}
	} else {
		$this->_brokenLink = true;	// if either $x or $y is not an array, then probably they're not bi-directionally connected
	}
}	
	
/**
 * Method to generate array of IDs of the users that are in a chain between the two given users $ufrom and $uto.
 *
 * @param	MySQLi			Database handler
 * @param   object			Table record for user $ufrom
 * @param   object			Table record for user $uto
 * @param   integer  		Radius of the minimum intersection circle we want to find
 * @param   array  			This is to be used internally only, should be left empty
 * @param   array  			This is to be used internally only, should be left empty
 *
 * @return  null
 */
private function _findIntersection ($db, $ufrom, $uto, $level, $from = array(), $to = array()) {

	$t = "friends".$level;//name of a table field where list of friends of $level are kept
	$from[$level] = explode(',', $ufrom->$t);
	$to[$level] = explode(',', $uto->$t);	
	
	//the simplest case is when $ufrom and $uto are mutual friends
	if ($level == 1 && (in_array($ufrom->id_user, $to[$level]) || in_array($uto->id_user, $from[$level]))) {
		$this->_chainLength = 1;
		return array($ufrom->id_user, $uto->id_user);	
	}

	//see if $ufrom's circle of radius $level intersects with any of the circles of $uto of smaller or equal radius
	for ($k = 1; $k <= $level; $k++) {
		$intersection = array_intersect ($from[$level], $to[$k]);	
		if (!empty($intersection) && is_array($intersection)) {
			reset($intersection);//set internal pointer to the first element of an array
		    $this->_chainLength = $k + $level;//the length of the chain between $ufrom to $uto

			//if there's an intersection with $level equal 1, then there's just one person in between $ufrom and $uto 
			if ($level == 1) return array($ufrom->id_user, current($intersection), $uto->id_user);
			
			//$ufrom and $uto may have several people in common in the circle of the same radius
			//So we just pick the first person in $intersection array
			$result = $db->query("SELECT * FROM friends WHERE id_user = '".current($intersection)."'");
			$ucommon = $result->fetch_object();
			
			//convert available friend levels of $ucommon to array
			for ($i = 1; $i < self::_maximumCircleRadius+1; $i++) {
				$common[$i] = explode(',', $ucommon->{"friends".$i}); 
			}
			
			$stack = array('to' => array(), 'from' => array() );
			SixDegrees::_reverseSearch ($from, $common, $level, 1, $stack, 'from');//build a chain from $ucommon to $ufrom
			SixDegrees::_reverseSearch ($to, $common, $k, 1, $stack, 'to');//build a chain from $ucommon to $uto
			
		
			//start: build the resulting array
			$result = array($ufrom->id_user);
			$j = count($stack['from'])-1;
			while ($j >= 0 && isset($stack['from'][$j])) {//build a chain from $ufrom to $ucommon
				$result[] = $stack['from'][$j];
				$j--;
			}
			$result[] = current($intersection);	//add $ucommon himself to the chain	 		
			for ($j = 0; $j < count($stack['to']); $j++) {//build a chain from $ucommon to $uto
				$result[] = $stack['to'][$j];
			}
			$result[] = $uto->id_user;
			//end: build the resulting array

			return $result;
		
		}
	}
	if ($level < self::_maximumCircleRadius) {// we can continue until we reach _maximumCircleRadius
		return $this->_findIntersection ($db, $ufrom, $uto, $level+1, $from, $to);
	} else {
		return null;
	}
}	
	
/**
 * Public method to generate the associative array with a chain from user with ID $id_from to user with ID $id_to
 *
 * @param	integer			ID of the user from whom we want to build the chain
 * @param   integer			ID of the user to whom we want to build the chain
 * @param	MySQLi			Database handler
 *
 * @return  array			array which contains the chain (IDs, names, and other data)
 */
static function buildConnection ($id_from, $id_to, $db) {
		$result = array();

		$r = $db->query("SELECT * FROM friends WHERE id_user = '$id_from'");
		$ufrom = $r->fetch_object();

		$r = $db->query("SELECT * FROM friends WHERE id_user = '$id_to'");
		$uto = $r->fetch_object();	
			
		//start: find possible intersection of circles of the two users
		$connections = new self();

		$result['path'] = $connections->_findIntersection ($db, $ufrom, $uto, 1);
		$result['brokenLink'] = $connections->_brokenLink; //if true, $ufrom and $uto are not connected
		$result['chainLength'] = $connections->_chainLength; //length of the chain
		//end: find possible intersection of circles of the two users
	
		if (!empty($result['path'])) {//if there's a chain exist, find out the names
			$path = implode(',', $result['path']);
			$r = $db->query("SELECT id, name FROM users WHERE id IN ($path)");
			$names = array();
			while ( $row = $r->fetch_object() ) {
				$names[$row->id] = $row->name;
			}
			$result['pathNames'] = $names;//the names of users in the chain			
		}
	
		return $result;
	}

}