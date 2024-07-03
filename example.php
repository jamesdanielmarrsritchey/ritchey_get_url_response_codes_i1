<?php
$location = realpath(dirname(__FILE__));
require_once $location . '/ritchey_get_url_response_codes_i1_v1.php';
$return = ritchey_get_url_response_codes_i1_v1("{$location}/temporary/bookmarks-2024-07-03.txt", TRUE);
if (is_array($return) === TRUE){
	//print_r($return) . PHP_EOL;
	foreach ($return as &$item){
		$item = explode(',', $item);
		$item = 'URL: ' . $item[0] . ', RESPONSE CODE: ' . $item[1];
		echo $item . PHP_EOL;
	}
} else if ($return === TRUE) {
	echo "TRUE" . PHP_EOL;
} else {
	echo "FALSE" . PHP_EOL;
}
?>