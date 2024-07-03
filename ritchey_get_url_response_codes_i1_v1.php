<?php
//Name: Ritchey Get URL Response Codes i1 v1
//Description: For a list of URLs, get the HTTP response codes. On success returns an array, or "TRUE" if no URLs were found. Returns "FALSE" on failure.
//Notes: Optional arguments can be "NULL" to skip them in which case they will use default values. URLs must start with either "https://" or "http://". File must contain one URL per line.
//Dependencies: This function relies on PHP's libcurl functions.
//Arguments: 'source_file' (required) is the path to the plain-text file containing the list of URLs. 'display_errors' (optional) indicates if errors should be displayed.
//Arguments (Script Friendly):source_file:path:required,display_errors:bool:optional
//Content:
//<value>
if (function_exists('ritchey_get_url_response_codes_i1_v1') === FALSE){
function ritchey_get_url_response_codes_i1_v1($source_file, $display_errors = NULL){
	# Check Variables
	$errors = array();
	$location = realpath(dirname(__FILE__));
	if (@is_file($source_file) === FALSE){
		$errors[] = 'source_file';
	}
	if ($display_errors === NULL){
		$display_errors = FALSE;
	} else if ($display_errors === TRUE){
		#Do Nothing
	} else if ($display_errors === FALSE){
		#Do Nothing
	} else {
		$errors[] = "display_errors";
	}
	# Task
	if (@empty($errors) === TRUE){
		$result = array();
		## Read the file one line at a time.
		$n = 1;
		$handle = @fopen($source_file, 'r');
		while (@feof($handle) !== TRUE) {
			$line = @fgets($handle);
			$url = trim($line);
			$n++;
			if (substr($line, 0, 7) === 'http://' or substr($line, 0, 8) === 'https://'){
				### Use CURL to get the status code of the URL
				$curl = curl_init();
				#### Set the URL to fetch
				curl_setopt($curl, CURLOPT_URL, $url);
				#### Return the transfer as a string instead of outputting it
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				#### Include the header in the output
				curl_setopt($curl, CURLOPT_HEADER, true);
				#### Execute the cURL session
				$response = curl_exec($curl);
				#### Check if an error occurred
				if (curl_errno($curl)) {
					$errors[] = "task - curl - " . curl_error($curl);
					goto result;
				} else {
    				// Get the HTTP response code
    				$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    				$result[] = $url . ',' . $httpCode;
				}
				curl_close($curl);
			} else {
				$errors[] = "task - line {$n} - '{$url}'";
				goto result;
			}
		}
		@fclose($handle);
	}
	result:
	# Display Errors
	if ($display_errors === TRUE){
		if (@empty($errors) === FALSE){
			$message = @implode(", ", $errors);
			if (function_exists('ritchey_get_url_response_codes_i1_v1_format_error') === FALSE){
				function ritchey_get_url_response_codes_i1_v1_format_error($errno, $errstr){
					echo $errstr;
				}
			}
			set_error_handler("ritchey_get_url_response_codes_i1_v1_format_error");
			trigger_error($message, E_USER_ERROR);
		}
	}
	# Return
	if (@empty($errors) === TRUE){
		if (@empty($result) === TRUE){
			return TRUE;
		} else {
			return $result;
		}
	} else {
		return FALSE;
	}
}
}
//</value>
?>