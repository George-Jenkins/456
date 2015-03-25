<?php
include('../../../connect/db-connect.php');
include('../../../connect/members.php');
include('../../../connect/functions.php');
include('word-match.php');

$appID = '1390775684571046';
$appSecret = 'd030dccd278389083f515aaf2083ad9c';

$query = mysql_query("SELECT * FROM venue WHERE facebook!=''");

while($get_array = mysql_fetch_array($query)){

$venueName = $get_array['name'];

$userName = $get_array['facebook'];

$location = $get_array['house']." ".$get_array['street']." ".$city." ".$state;

include('get-facebook.php');

if($postChanged){

if(substr_count($userName,'/')>0) $graphUserName = end(explode('/',$userName));//some pages have sfsdf/sfsdf/1234 when I only need the 1234 for this
else $graphUserName = $userName;

$pageArray = file_get_contents('https://graph.facebook.com/v2.2/'.$graphUserName.'/feed?access_token='.$appID.'|'.$appSecret);

$profilePicsArray = file_get_contents('https://graph.facebook.com/v2.2/'.$graphUserName.'/picture?redirect=false&access_token='.$appID.'|'.$appSecret);
$profilePicArray = json_decode($profilePicsArray,true);
$profilePicImage = $profilePicArray['data']['url'];

$pageArray = json_decode($pageArray,true);
//loop through to pull data out of this json string
$numberOfLoops = count($pageArray['data']);

$x = $numberOfLoops-1;

while($x>=0){

//get time created (get time first so this post can not be added if it's past post time limit)
$faceBookTime = $pageArray['data'][$x]['created_time'];
$faceBookTimeSeconds = strtotime($faceBookTime);

//determine if post is within post time limit
$postTimeLimitSeconds = (4*24*60*60);//get number of seconds in 4 days
$postTimeLimitSecondsAgo = time()-$postTimeLimitSeconds;//get the number of seconds ago post time limit was from now
if($faceBookTimeSeconds>=$postTimeLimitSecondsAgo){//determine if seconds ago the post was made exceeds the limit in seconds

//get post
$post = $pageArray['data'][$x]['message'];

$searchMatch = searchMatch($post);

if($searchMatch){	

//get profile image
$pageImageArray = $htmlProfilePics['https://graph.facebook.com/v2.2/'.$graphUserName.'/picture?redirect=false&access_token='.$appID.'|'.$appSecret];
$pageImageArray = json_decode($profileImageKey,true);
$faceBookProfilePic = $profileImageKey['data']['url'];

$faceBookName = $pageArray['data'][$x]['from']['name'];

//make addresses links
if(substr_count($post,'http')!=0) $post = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $post);
elseif(substr_count($post,'www.')!=0)  $post = preg_replace('@(www.([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="http://$1" target="_blank">$1</a>', $post);

$post = mysql_real_escape_string($post);

//get picture for this post
$faceBookObjectID = $pageArray['data'][$x]['object_id'];
$faceBookObjectIDArray = file_get_contents('https://graph.facebook.com/v2.2/'.$faceBookObjectID.'/picture?redirect=false&access_token='.$appID.'|'.$appSecret);
$faceBookObjectIDArray = json_decode($faceBookObjectIDArray,true);
$faceBookPostImage = $faceBookObjectIDArray['data']['url'];

//put everything in json
$jsonStore = '{"venuePost":{"network":"facebook","time_created":"'.$faceBookTimeSeconds.'","postingName":"'.$faceBookName.'","userName":"'.$userName.'","profile_pic":"'.$profilePicImage.'","post":"'.$post.'","image":"'.$faceBookPostImage.'"}}';

$jsonStore = mysql_real_escape_string($jsonStore);

//post event to chatwalls
include('post-venue-event.php');

}//if $searchMatch
}//if !$postTimeLimit || strtotime($faceBookTime)>=$postTimeLimitSecondsAgo


$x--;


}//if $postChanged

}//while


}//while






?>