<?php

$handle = curl_init();

curl_setopt_array(
	$handle,
	array(
	CURLOPT_URL => 'http://www.topix.com/',
	CURLOPT_POST => true, 
	CURLOPT_POSTFIELDS => 'city/list',
	CURLOPT_RETURNTRANSFER => true
	)

);

$response = curl_exec($handle);
curl_close($handle);

echo $response;

?>