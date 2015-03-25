<?php

include('../../../connect/db-connect.php');
include('../../../connect/members.php');
include('../../../connect/functions.php');
include('word-match.php');
include('tweets_json.php');

$lookingFor = $_POST['lookingFor'];
$userCity = $_POST['City'];
$userState = $_POST['State'];
$userLatitude = $_POST['latitude'];
$userLongitude = $_POST['longitude'];
$maxDistance = $_POST['maxDistance'];
$postTimeLimit = $_POST['postTimeLimit'];

if(!$lookingFor) return;

validateUser();

$result = '';

$query = mysql_query("SELECT * FROM venue WHERE twitter!='' LIMIT 2");

$x2 = 0;//venue number

while($get_array = mysql_fetch_array($query)){

$venueName = $get_array['name'];

$city = $get_array['city'];
$state = $get_array['state'];

$location = $get_array['house']." ".$get_array['street']." ".$city." ".$state;

$screenName = $get_array['twitter'];

$website = $get_array['website'];
if($website) $website = "<div class='venue-website-div'><a href='".$website."' class='buttonLink' target='_blank'>Website</a></div>";


$lat = $get_array['lat'];
$lon = $get_array['lon'];
//determine if within range
$distance = getDistanceFromLatLonInKm($userLatitude,$userLongitude,$lat,$lon)*0.621371;
if(strtolower($userCity.$userState)==strtolower($city.$state)|| $distance<=$maxDistance){

$response = getTweets($screenName);

$responseJson = '{"data":'.$response.'}';//make json

$responseArray = json_decode($responseJson,true);

$numberOfLoops = count($responseArray['data']);

$x = 0;
$postCounts = false;//set to true if this post is added
$x3 = 0;//this is to keep track of loops that count 

while($x<$numberOfLoops){

//get time created (get time first so this post can not be added if it's past post time limit)
$twitterTimeSeconds = strtotime($responseArray['data'][$x]['created_at']);
$twitterTimeHoursAgo = round((time()-$twitterTimeSeconds)/60/60);
$twitterTimeYear = date('Y',$twitterTimeSeconds);//get year this was posted
$currentYear = date('Y');//get this year
if($twitterTimeYear == $currentYear) $twitterTimeDate = date('D M j, g:i a',$twitterTimeSeconds);//leave out year of years are the same
else $twitterTimeDate = date('D M j Y g:i a',$twitterTimeSeconds);

//determine if post is within post time limit
$postTimeLimitSeconds = ($postTimeLimit*24*60*60);//get number of seconds in the limit
$postTimeLimitSecondsAgo = time()-$postTimeLimitSeconds;//get the number of seconds ago post time limit was from now
if(!$postTimeLimit || $twitterTimeSeconds>=$postTimeLimitSecondsAgo){//determine if seconds ago the post was made exceeds the limit in seconds

//get tweet
$post = $responseArray['data'][$x]['text'];
//remove that last link in tweets
$linkPos = strrpos($post,'http://t.co/');
$post = substr($post,0,$linkPos);

$searchMatch = searchMatch($post,$lookingFor);
if($searchMatch){

$postCounts = true;
$x3++;

if($x3==1){//this must be done once
$result .= '<div>
<div class="venue-name-div"><div class="venue-name">'.$venueName.'</div> <div>'.$location.'</div> '.$website.' </div>'; 
//get profile pic
$twitterProfilePic = "<img src='".$responseArray['data'][0]['user']['profile_image_url']."'/>";	
}//if



if($x3>1){$hide='hide'; $notFirst = 'not-first-tweet';}//if
else{$hide=''; $notFirst = '';}//else	

$result .= '<div id="tweet-container'.$x2.$x.'" class="'.$hide.' '.$notFirst.'">';
	
//get name
$twitterName = $responseArray['data'][$x]['user']['name'];
//get screen name (@)
$twitterScreenName = $responseArray['data'][$x]['user']['screen_name'];

//$twitterTweet = preg_replace('((mailto\:|(news|(ht|f)tp(s?))\://){1}\S+)','',$twitterTweet);
//make addresses hyperlinks
if(substr_count($post,'http')!=0) $post = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $post);
elseif(substr_count($post,'www.')!=0) $post = preg_replace('@(www.([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="http://$1" target="_blank">$1</a>', $post);

//get image
$twitterImage = $responseArray['data'][$x]['entities']['media'][0]['media_url'];
if($twitterImage) $twitterImage = "<img src='".$twitterImage.":small' class='twitter-images' onClick=\"highLightImage('".$twitterImage."')\"/>";

if($twitterTimeHoursAgo<21) $timePosted = $twitterTimeHoursAgo.' h'; 
else $timePosted = $twitterTimeDate;

$result .= "

<div onClick='sharePost(".$x2.",".$x.",\"twitter\")' class='share-button-post'>Share</div>

<div style='clear:both;'></div>

<div class='twitter-profile-pic result-spacing inline'>".$twitterProfilePic."</div>

<div class='inline'>

<div class='result-spacing align-left username'>".$twitterName." @".$twitterScreenName." - ".$timePosted."</div>

<div class='result-spacing align-left tweet-message'>".$post."</div>

<div style='clear:both;'></div>

<div class='result-spacing'>".$twitterImage."</div>

</div><!--inline-->

<hr style='clear:both;'>

";

$result .= '</div><!--tweet-container-->';

}//if $searchMatch
}//if $postSecondsAgo<$postTimeLimitSeconds

$x++;

}//while

if($postCounts){
if($x3>1) $result .= "<div onclick='showMoreTweets(".$x2.",".$x.")' id='showMoreTweets".$x2.$x."' class='more-tweets' numberOfTweets='".$x."' venueNumber='".$x2."'>More ".$venueName." tweets <br><img class='show-more-arrow' src='../../pics/down-icon.png' /> </div> ";

$result .= '</div>';
}//if

$x2++;

}//if $distance<=$maxDistance

}//while

//if nothing was found set to blank
if(!$twitterTimeSeconds) $result = '';

$return['results'] = $result;

echo json_encode($return);

?>