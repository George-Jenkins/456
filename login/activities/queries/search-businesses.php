<?php

//Twitter

$screenName = 'echostage';
//$count = 1;
include('tweets_json.php');

$response = getTweets($screenName);

$response;

echo $responseJson = '{"data":'.$response.'}';//make json

$responseArray = json_decode($responseJson,true);

$numberOfLoops = count($responseArray['data']);

$x = 0;

while($x<$numberOfLoops){
	
//get profile pic
$twitterProfilePic = "<img src='".$responseArray['data'][$x]['user']['profile_image_url']."'/><p>";
//get time created
$twitterTimeSeconds = strtotime($responseArray['data'][$x]['created_at']);
$twitterTimeHoursAgo = round((time()-$twitterTimeSeconds)/60/60);
$twitterTimeDate = date('D M j Y g:i a',$twitterTimeSeconds)."<p>";
//get name
$twitterName = $responseArray['data'][$x]['user']['name']."<p>";
//get screen name (@)
$twitterScreenName = $responseArray['data'][$x]['user']['screen_name']."<p>";
//get tweet
$twitterTweet = $responseArray['data'][$x]['text']."<p>";
//get image
$twitterImage = "<img src='".$responseArray['data'][$x]['extended_entities']['media'][0]['media_url']."'/><p>";

$x++;
}//while


//FACEBOOK
include('../../../facebook-php-sdk/src/Facebook/FacebookSession.php');
include('../../../facebook-php-sdk/src/Facebook/FacebookRequest.php');

include('../../../facebook-php-sdk/src/Facebook/FacebookResponse.php');
include('../../../facebook-php-sdk/src/Facebook/FacebookSDKException.php');

include('../../../facebook-php-sdk/src/Facebook/FacebookRedirectLoginHelper.php');
include('../../../facebook-php-sdk/src/Facebook/FacebookRequestException.php');
//include('../../../facebook-php-sdk/src/Facebook/FacebookAuthorizationException.php');
//echo "test";
include('../../../facebook-php-sdk/src/Facebook/GraphObject.php');

include('../../../facebook-php-sdk/src/Facebook/GraphUser.php');
include('../../../facebook-php-sdk/src/Facebook/GraphSessionInfo.php');
include('../../../facebook-php-sdk/src/Facebook/Entities/AccessToken.php');
include('../../../facebook-php-sdk/src/Facebook/HttpClients/FacebookCurl.php');
include('../../../facebook-php-sdk/src/Facebook/HttpClients/FacebookHttpable.php');
include('../../../facebook-php-sdk/src/Facebook/HttpClients/FacebookCurlHttpClient.php');

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphSessionInfo;
use Facebook\FacebookHttpable;
use Facebook\FacebookCurlHttpClient;
use Facebook\FacebookCurl;
use Facebook\GraphObject;
use Facebook\GraphUser;


$appID = '1390775684571046';
$appSecret = 'd030dccd278389083f515aaf2083ad9c';
$redirectURL = 'http://ritzkey.com/login/activities/queries/search-businesses.php';

FacebookSession::setDefaultApplication($appID, $appSecret);

$helper = new FacebookRedirectLoginHelper($redirectURL);
// Use the login url on a link or button to redirect to Facebook for authentication

/* PHP SDK v4.0.0 */
/* make the API call */
$session = new FacebookSession($appID."|".$appSecret);

$request = new FacebookRequest(
  $session,
  'GET',
  '/BarcodeDC/posts'
);

$response = $request->execute();
$graphObject = $response->getGraphObject();
//$graphObject = $response->getGraphObject(GraphUser::className());

/* handle the result */
$test = $graphObject->asArray();
//print_r($test['data'][4]->object_id);
$test['data']->to;
$graphObject->getName();

$faceBookPage = file_get_contents('https://graph.facebook.com/v2.2/Barcodedc/feed?access_token='.$appID.'|'.$appSecret);
$faceBookPage = $faceBookPage;

$faceBookPage = json_decode($faceBookPage,true);

//get number of loops
$numberOfLoops = count($faceBookPage['data']);

$x = 0;

while($x<$numberOfLoops){

$seeIfGuest = $faceBookPage['data'][$x]['to'];//if this is not the user (club) but a guest posting there will be a "to" object in the data object
if(!$seeIfGuest){
$faceBookName = $faceBookPage['data'][$x]['from']['name'];
$faceBookPost = $faceBookPage['data'][$x]['message'];
$faceBookTimeSeconds = $faceBookPage['data'][$x]['created_time'];
	//turn seconds into hours ago and time/date
$faceBookTimeHoursAgo = round((time()-strtotime($faceBookTimeSeconds))/60/60);
$faceBookTimeDate = date('D M j Y g:i a');
$faceBookPicture = $faceBookPage['data'][$x]['picture'];//this isn't doing anything
	//get large picture
	$objectID = $faceBookPage['data'][$x]['object_id'];
	$facebookImagePage = stripslashes(file_get_contents('https://graph.facebook.com/v2.2/'.$objectID.'/picture?type=normal&redirect=false&access_token='.$appID.'|'.$appSecret));
	$facebookImagePage = json_decode($facebookImagePage,true);
	$facebookLargeImage = $facebookImagePage['data']['url'];
}//if



$x++;
}//while

//get profile pic
$faceBookPicPage = file_get_contents('https://graph.facebook.com/v2.2/Barcodedc/picture?redirect=false&access_token='.$appID.'|'.$appSecret);
$faceBookPicPage = json_decode(stripslashes($faceBookPicPage),true);
$faceBookProfilePic = $faceBookPicPage['data']['url'];


//INSTAGRAM
//if access token is needed refer to this http://jelled.com/instagram/access-token

$clientID = 'c89af8c355e34dfbaa3702c67b2b67b4';//got this when registering the developer account
$userName = 'theparkat14th';

$getUserID = file_get_contents('https://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.$clientID);//this will get the id of the user (club)
$getUserID = json_decode($getUserID,true);
$userID = $getUserID['data'][0]['id'];

$instagramPage = file_get_contents('https://api.instagram.com/v1/users/'.$userID.'/media/recent/?client_id='.$clientID);//this will get the feed of user

$instagramArray = json_decode($instagramPage,true);

$numberOfLoops = count($instagramArray['data']);

$x = 0;

while($x<$numberOfLoops){
//get profile pic
$instagramProfilePicture = "<img src='".$instagramArray['data'][$x]['caption']['from']['profile_picture']."' /><p>";
//get time created
$instagramTimeSeconds = $instagramArray['data'][$x]['created_time'];
$instagramTimeHoursAgo = round((time()-$instagramTimeSeconds)/60/60);
$instagramTimeDate = date('D M j Y g:i a',$instagramTimeSeconds)."<p>";
//get text
$instagramText = $instagramArray['data'][$x]['caption']['text']."<p>";
//get username
$instagramUsername = $instagramArray['data'][$x]['caption']['from']['username']."<p>";
//get image
$instagramImage = "<img src='".$instagramArray['data'][$x]['images']['standard_resolution']['url']."' /><p>";

$x++;

}//while

?>



















