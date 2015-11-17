<?php
$i = 0;
foreach ($result['path'] as $id) {
	echo $id.": ".$result['pathNames'][$id]."<br />";
	if ($i < count ($result['path']) - 1 ) {
		for ($j = 0; $j < 4; $j++) {
				echo str_repeat("&nbsp;", 10)."||<br />";
		}
		echo str_repeat("&nbsp;", 10)."\/<br />";
	}	
	$i++;
}
