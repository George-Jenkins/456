<?php
//this file uses file_get_contents to see if changes were made

$result = file_get_contents('https://twitter.com/'.$screenName);

$result = trim(preg_replace('/\s+/', ' ', $result));

preg_match_all('#"ProfileTweet-originalAuthorLink(.*?)ProfileTweet-authorDetails"#',$result,$pageArray);
if($pageArray){
//make sure barcode post and not follower post
$x2 = 0;	

while($x2<20){

$pageSection = $pageArray[1][$x2];

if(substr_count(strtolower($pageSection),'@</span>'.strtolower($screenName))>0) break;//only the user's post will have this string	

$x2++;
}

//get post name
preg_match('#ProfileTweet-fullname u-linkComplex-target" data-aria-label-part>(.*?)</b#',$pageSection,$postNameArray);
$postName = $postNameArray[1];

//get screen name
preg_match('#<span class="at">@</span>(.*?)</span>#',$pageSection,$screenNameArray);
$screenName = '@'.$screenNameArray[1];

//get tweet
preg_match('#data-aria-label-part="0">(.*?)</p>#',$pageSection,$tweetArray);//seconds pattern was <div class="js-tweet-details but not all posts had that
$post = $tweetArray[1];
$post = strip_tags($post);//remove html tags

//get image
preg_match('#class="TwitterPhoto-mediaSource" src="(.*?)"#',$pageSection,$imageArray);
$image = $imageArray[1];
$image = str_replace(':large','',$image);
$image = str_replace(':small','',$image);

//get time posted
preg_match('#data-time="(.*?)"#',$pageSection,$timeStampArray);
$timeSeconds = $timeStampArray[1];
$timeDate = date('D M j g:i a',$timeSeconds);
$timeDateWithYear = date('D M j, Y g:i a',$timeSeconds);

}//if $pageArray

//create json string for first post on page
$jsonString = mysql_real_escape_string('{"content":{"time":"'.$timeSeconds.'","post":"'.strip_tags($post).'"}}');

//see if old first post is same as this first post (new post was made)
$query2 = mysql_query("SELECT * FROM venue WHERE name='$venueName'");
$getTwitterPost = mysql_fetch_assoc($query2);
$oldPost = mysql_real_escape_string($getTwitterPost['twitter_post']);
//if posts are different update db
if($oldPost!=$jsonString){
	
	mysql_query("UPDATE venue SET twitter_post='".$jsonString."' WHERE name='".$venueName."'");
	$postChanged = true;
}//if
else $postChanged = false;


?>


