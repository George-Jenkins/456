<?php
require 'tmhOAuth.php'; // Get it from: https://github.com/themattharris/tmhOAuth

// Use the data from http://dev.twitter.com/apps to fill out this info
// notice the slight name difference in the last two items)

function getTweets($screenName){

$connection = new tmhOAuth(array(
  'consumer_key' => 'WjlolCAsBMhW9vbuaMZbK0fP0',
	'consumer_secret' => 'X8k0cq0b87lboj511Qtdf2RGtuDX3BVXYuzGCqFDO0X7i7qEfy',
	'user_token' => '604279349-xqPKBI9pi6Qe400Pn3jAFuSNhcmMEcTSqQMrnMFq', //access token
	'user_secret' => '8AQRgNaiMzMBYmM3VHF8Ri5OlRXZdyqTsTIrXb4ZIE06G' //access token secret
));

// set up parameters to pass
$parameters = array();

if ($count) {
	$parameters['count'] = strip_tags($count);
}

if ($screenName) {
	$parameters['screen_name'] = strip_tags($screenName);
}

if ($_GET['twitter_path']) { $twitter_path = $_GET['twitter_path']; }  else {
	$twitter_path = '1.1/statuses/user_timeline.json';
}

$http_code = $connection->request('GET', $connection->url($twitter_path), $parameters );

if ($http_code === 200) { // if everything's good
	$response = strip_tags($connection->response['response']);

	if ($_GET['callback']) { // if we ask for a jsonp callback function
		echo $_GET['callback'],'(', $response,');';
	} else {
		return $response;	
	}
} else {
	echo "Error ID: ",$http_code, "<br>\n";
	echo "Error: ",$connection->response['error'], "<br>\n";
}

}//function
// You may have to download and copy http://curl.haxx.se/ca/cacert.pem