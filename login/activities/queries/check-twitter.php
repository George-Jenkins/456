<?php
include('../../../connect/db-connect.php');
include('../../../connect/members.php');
include('../../../connect/functions.php');
include('word-match.php');
include('tweets_json.php');


$query = mysql_query("SELECT * FROM venue WHERE twitter!=''");

while($get_array = mysql_fetch_array($query)){

$venueName = $get_array['name'];

$city = $get_array['city'];
$state = $get_array['state'];

$location = $get_array['house']." ".$get_array['street']." ".$city." ".$state;

$screenName = $get_array['twitter'];

include('get-twitter.php');//check to see if new tweets

if($postChanged){//if post changed (new post)
		
$website = $get_array['website'];

$response = getTweets($screenName);

$responseJson = '{"data":'.$response.'}';//make json

$responseArray = json_decode($responseJson,true);

$numberOfLoops = count($responseArray['data']);

$x = $numberOfLoops-1;

while($x>=0){

//get time created 
$twitterTimeSeconds = strtotime($responseArray['data'][$x]['created_at']);

//determine if post is within post time limit
$postTimeLimitSeconds = (4*24*60*60);//get number of seconds in 4 days
$postTimeLimitSecondsAgo = time()-$postTimeLimitSeconds;//get the number of seconds ago post time limit was from now
if($twitterTimeSeconds>=$postTimeLimitSecondsAgo){//determine if seconds ago the post was made exceeds the limit in seconds

//get tweet
$post = $responseArray['data'][$x]['text'];
//remove that last link in tweets
$linkPos = strrpos($post,'http://t.co/');
$post = substr($post,0,$linkPos);

$searchMatch = searchMatch($post);

if($searchMatch){

//get profile pic
$twitterProfilePic = $responseArray['data'][0]['user']['profile_image_url'];	

//get name
$twitterName = $responseArray['data'][$x]['user']['name'];
//get screen name (@)
$twitterScreenName = $responseArray['data'][$x]['user']['screen_name'];

//make addresses in posts hyperlinks
if(substr_count($post,'http')!=0) $post = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $post);
elseif(substr_count($post,'www.')!=0) $post = preg_replace('@(www.([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="http://$1" target="_blank">$1</a>', $post);

$post = mysql_real_escape_string($post);//have to escape post for some posts

//get image
$twitterImage = $responseArray['data'][$x]['entities']['media'][0]['media_url'];

//put everything in json
$jsonStore = '{"venuePost":{"network":"twitter","time_created":"'.$twitterTimeSeconds.'","postingName":"'.$twitterName.'","userName":"'.$twitterScreenName.'","profile_pic":"'.$twitterProfilePic.'","post":"'.$post.'","image":"'.$twitterImage.'"}}';

$jsonStore = mysql_real_escape_string($jsonStore);

//post event to chatwalls
include('post-venue-event.php');

}//if $searchMatch
}//if $postSecondsAgo<$postTimeLimitSeconds

$x--;

}//while


}//if $postChanged

}//while


?>