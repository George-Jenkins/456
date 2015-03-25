<?php

header('Content-Type: text/html');
$ch = curl_init('https://instagram.com/'.$userName);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');
$content = curl_exec($ch); 

//remove white space
$content = trim(preg_replace('/\s+/', ' ', $content));

preg_match('#window._sharedData = (.*?);</script>#',$content,$contentArray);

if($contentArray){

$content = json_decode($contentArray[1],true);

//get post
$post = $content['entry_data']['UserProfile'][0]['userMedia'][0]['caption']['text'];

//get time 
$timeSeconds = $content['entry_data']['UserProfile'][0]['userMedia'][0]['caption']['created_time'];

//get image
$image = $content['entry_data']['UserProfile'][0]['userMedia'][0]['images']['standard_resolution']['url'];

}//if $contentArray

//create json string for first post on page
$jsonString = mysql_real_escape_string('{"content":{"time":"'.$timeSeconds.'","post":"'.strip_tags($post).'"}}');
//get the last first post stored in db
$query2 = mysql_query("SELECT * FROM venue WHERE name='".$venueName."'");
$getInstagramPost = mysql_fetch_assoc($query2);
$oldPost = mysql_real_escape_string($getInstagramPost['instagram_post']);
//if posts are different update db
if($oldPost!=$jsonString){
	mysql_query("UPDATE venue SET instagram_post='".$jsonString."' WHERE name='".$venueName."'");
	$postChanged = true;
}//if
else $postChanged = false;

?>