<?php

/**
 * Endpoint of the Algorithm demo
 * @copyright (c)2011-2015 Hendricson.com
 * @license GNU GPL version 3 or any later version
 */

require_once('config.php');
require_once('class.php');

$task = isset($_POST['task']) ? $_POST['task'] : '';
$ufrom = isset($_POST['ufrom']) ? (int)$_POST['ufrom'] : 72;
$uto = isset($_POST['uto']) ? (int)$_POST['uto'] : 117;

$db = new MySQLi(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306);
if ($db->connect_error) {
	echo "Not connected, error: ".$db->connect_error;
} else {
	switch ($task) {
		case 'generate':
			SixDegrees::prepareFriendList($db);
			echo "<div style='color:green;'>Done!</div>";
			break;
		case 'build':
			$result = SixDegrees::buildConnection($ufrom, $uto, $db);
			if (!empty($result) && is_array($result) && is_array($result['path']) && count($result['path']) > 0) {
				echo "<h3>Generated chain:</h3>";
				include 'html/result.tpl.php';
				echo "<h3>Ready to build some more?</h3>";
			} elseif (!$result['brokenLink']) {
				echo "<div style='color:red;'>Users are not connected</div>";				
			} else {
				echo "<div style='color:red;'>Could not generate the chain</div>";
			}			
			break;
		default:
			
	}
	echo "<h3>Six Degrees of Separation Algorithm Demo</h3>";
	include 'html/main.tpl.php';	
}

