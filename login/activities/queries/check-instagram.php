<?php
include('../../../connect/db-connect.php');
include('../../../connect/members.php');
include('../../../connect/functions.php');
include('word-match.php');

$result = '';

$clientID = 'c89af8c355e34dfbaa3702c67b2b67b4';//got this when registering the developer account

$query = mysql_query("SELECT * FROM venue WHERE instagram!=''");

while($get_array = mysql_fetch_array($query)){

$venueName = $get_array['name'];

$userName = $get_array['instagram'];

include('get-instagram.php');//this page will curl the instagram page to see if there are changes

if($postChanged){//if post changed (new post)

$city = $get_array['city'];
$state = $get_array['state'];

$location = $get_array['house']." ".$get_array['street']." ".$city." ".$state;

$getUserID = file_get_contents('https://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.$clientID);//this will get the id of the user (club)
$getUserID = json_decode($getUserID,true);
$userID = $getUserID['data'][0]['id'];

$instagramPage = file_get_contents('https://api.instagram.com/v1/users/'.$userID.'/media/recent/?client_id='.$clientID);//this will get the feed of user

$instagramArray = json_decode($instagramPage,true);

$numberOfLoops = count($instagramArray['data']);

$x = $numberOfLoops-1;

while($x>=0){

//get time created (get time first so this post can not be added if it's past post time limit)
$instagramTimeSeconds = $instagramArray['data'][$x]['created_time'];

//make sure within right timespan
$postTimeLimitSeconds = (4*24*60*60);//get number of seconds in 4 days
$postTimeLimitSecondsAgo = time()-$postTimeLimitSeconds;
if($instagramTimeSeconds>=$postTimeLimitSecondsAgo){

//get text
$post = $instagramArray['data'][$x]['caption']['text'];

$searchMatch = searchMatch($post);

if($searchMatch){
		
//get profile pic
$instagramProfilePic = $instagramArray['data'][$x]['caption']['from']['profile_picture'];

//make addresses hyperlinks
if(substr_count($post,'http')!=0) $post = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $post);
elseif(substr_count($post,'www.')!=0) $post = preg_replace('@(www.([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="http://$1" target="_blank">$1</a>', $post);

$post = mysql_real_escape_string($post);//have to escape post for some posts

//get username
$instagramUsername = $instagramArray['data'][$x]['caption']['from']['username'];
//get image
$instagramImage = $instagramArray['data'][$x]['images']['standard_resolution']['url'];

//put everything in json
$jsonStore = '{"venuePost":{"network":"instagram","time_created":"'.$instagramTimeSeconds.'","postingName":"'.$instagramUsername.'","userName":"'.$instagramUsername.'","profile_pic":"'.$instagramProfilePic.'","post":"'.$post.'","image":"'.$instagramImage.'"}}';

$jsonStore = mysql_real_escape_string($jsonStore);

//post event to chatwalls
include('post-venue-event.php');

}//if $instagramTimeSeconds>=$postTimeLimitSecondsAgo
}//if $searchMatch

$x--;

}//while


}//if $postChanged

}//while



?>