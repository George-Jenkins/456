<?php
include('../../../connect/db-connect.php');
include('../../../connect/members.php');
include('../../../connect/functions.php');
include('word-match.php');

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

$appID = '1390775684571046';
$appSecret = 'd030dccd278389083f515aaf2083ad9c';

$query = mysql_query("SELECT * FROM venue WHERE facebook!='' LIMIT 2");

//declare this array. graph api urls will be added to it
$aURLs = array();

while($get_array = mysql_fetch_array($query)){

$venueName = $get_array['name'];

$userName = $get_array['facebook'];

$location = $get_array['house']." ".$get_array['street']." ".$city." ".$state;

$lat = $get_array['lat'];
$lon = $get_array['lon'];
//determine if within range
$distance = getDistanceFromLatLonInKm($userLatitude,$userLongitude,$lat,$lon)*0.621371;
if(strtolower($userCity.$userState)==strtolower($city.$state)|| $distance<=$maxDistance){


$urlArray = 'https://graph.facebook.com/v2.2/'.$userName.'/feed?access_token='.$appID.'|'.$appSecret;
$aURLs[] = $urlArray; // array of URLs

$urlArrayProfilePics = 'https://graph.facebook.com/v2.2/'.$userName.'/picture?redirect=false&access_token='.$appID.'|'.$appSecret;
$aURLs[] = $urlArrayProfilePics; //this array with contain profile pics

}//if $distance<=$maxDistance

}//while

//Get json string for each venue's page
$mh = curl_multi_init(); // init the curl Multi
$aCurlHandles = array(); // create an array for the individual curl handles
foreach ($aURLs as $id=>$url) { //add the handles for each url
 $ch = curl_init(); // init curl, and then setup your options
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // returns the result - very important
 curl_setopt($ch, CURLOPT_HEADER, 0); // no headers in the output
 $aCurlHandles[$url] = $ch;
 curl_multi_add_handle($mh,$ch);
}  
$active = null;
do {
$mrc = curl_multi_exec($mh, $active);
} 
while ($mrc == CURLM_CALL_MULTI_PERFORM);
while ($active && $mrc == CURLM_OK) {
if (curl_multi_select($mh) != -1) {
do {
$mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);
}
}
//add facebook json data to an array
$htmlArray = array();
$htmlProfilePics = array();
foreach ($aCurlHandles as $url=>$ch) {
	
if(substr_count($url,'picture?redirect=false')==0){
//if this url isn't of a profle pic add to $htmlArray else add to $htmlProfilePics
preg_match('#v2.2/(.*?)/feed#',$url,$addUsername);
$addUsername = $addUsername[1];
$htmlArray[$addUsername] = curl_multi_getcontent($ch);

}//if	
else $htmlProfilePics[$url] = curl_multi_getcontent($ch);

}//foreach



//get the images in each post
$aURLsFeedPics = array();
foreach($htmlArray as $htmlKey=>$htmlValue){

$imageURLArray = json_decode($htmlValue,true);

$x = 0;
$numberOfLoops = count($imageURLArray['data']);

while($x<$numberOfLoops){

$objectID = $imageURLArray['data'][$x]['object_id'];

$aURLsFeedPics[] = 'https://graph.facebook.com/v2.2/'.$objectID.'/picture?redirect=false&access_token='.$appID.'|'.$appSecret;

$x++;
}//while

}//foreach

//Get json string for each venue's feed images
$mh = curl_multi_init(); // init the curl Multi
$aCurlHandles = array(); // create an array for the individual curl handles
foreach ($aURLsFeedPics as $id=>$url) { //add the handles for each url
 $ch = curl_init(); // init curl, and then setup your options
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // returns the result - very important
 curl_setopt($ch, CURLOPT_HEADER, 0); // no headers in the output
 $aCurlHandles[$url] = $ch;
 curl_multi_add_handle($mh,$ch);
}  
$active = null;
do {
$mrc = curl_multi_exec($mh, $active);
} 
while ($mrc == CURLM_CALL_MULTI_PERFORM);
while ($active && $mrc == CURLM_OK) {
if (curl_multi_select($mh) != -1) {
do {
$mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);
}
}
//add facebook json data to an array
$htmlFeedPics = array();
foreach ($aCurlHandles as $url=>$ch) {
$htmlFeedPics[$url] = curl_multi_getcontent($ch); //this is used below to get the profile's image
}//foreach


$x2 = 0;//venue number

foreach($htmlArray as $htmlKey=>$htmlValue){

$query = mysql_query("SELECT * FROM venue WHERE facebook='$htmlKey'");
$get = mysql_fetch_assoc($query);
$venueName = $get['name'];
$location = $get['house']." ".$get['street']." ".$get['city']." ".$get['state'];
$website = $get['website'];
if($website) $website = "<div class='venue-website-div'><a href='".$website."' class='buttonLink' target='_blank'>Website</a></div>";


//put each pages json into array one at a time
$faceBookPageArray = json_decode($htmlValue,true);

//loop through to pull data out of this json string
$numberOfLoops = count($faceBookPageArray['data']);

$x = 0;

$postCounts = false;//set to true if this post is added

$x3 = 0;//this is to keep track of loops that count 

while($x<$numberOfLoops){

//get time created (get time first so this post can not be added if it's past post time limit)
$faceBookTime = $faceBookPageArray['data'][$x]['created_time'];
$faceBookTimeSeconds = strtotime($faceBookTime);
	//turn seconds into hours ago and time/date
$faceBookTimeHoursAgo = round((time()-$faceBookTimeSeconds)/60/60);
$faceBookTimeYear = date('Y',$faceBookTimeSeconds);//get year this was posted
$currentYear = date('Y');//get this year
if($faceBookTimeYear == $currentYear) $faceBookTimeDate = date('D M j, g:i a',$faceBookTimeSeconds);//leave out year of years are the same
else $faceBookTimeDate = date('D M j Y g:i a',$faceBookTimeSeconds);

//determine if post is within post time limit
$postTimeLimitSeconds = ($postTimeLimit*24*60*60);//get number of seconds in the limit
$postTimeLimitSecondsAgo = time()-$postTimeLimitSeconds;//get the number of seconds ago post time limit was from now
if(!$postTimeLimit || strtotime($faceBookTime)>=$postTimeLimitSecondsAgo){//determine if seconds ago the post was made exceeds the limit in seconds

//get post
$post = $faceBookPageArray['data'][$x]['message'];

$searchMatch = searchMatch($post,$lookingFor);
if($searchMatch){

$postCounts = true;
$x3++;

if($x3==1){//this must be done once
$result .= '<div>
<div class="venue-name-div"><div class="venue-name">'.$venueName.'</div> <div>'.$location.'</div> '.$website.' </div>'; 	
}//if

if($x3>1){$hide='hide'; $notFirst = 'not-first-facebook';}//if
else{$hide=''; $notFirst = '';}//else	
		
$result .= '<div id="facebook-container'.$x2.$x.'" class="'.$hide.' '.$notFirst.'">';	

//get profile image
$profileImageKey = $htmlProfilePics['https://graph.facebook.com/v2.2/'.$htmlKey.'/picture?redirect=false&access_token='.$appID.'|'.$appSecret];
$profileImageKey = json_decode($profileImageKey,true);
$faceBookProfilePic = "<img src='".$profileImageKey['data']['url']."' />";

$faceBookName = $faceBookPageArray['data'][$x]['from']['name'];

//make addresses links
if(substr_count($post,'http')!=0) $post = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $post);
elseif(substr_count($post,'www.')!=0)  $post = preg_replace('@(www.([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="http://$1" target="_blank">$1</a>', $post);

//get picture for this post
$faceBookObjectID = $faceBookPageArray['data'][$x]['object_id'];
$faceBookObjectIDKey = 'https://graph.facebook.com/v2.2/'.$faceBookObjectID.'/picture?redirect=false&access_token='.$appID.'|'.$appSecret;;
$htmlFeedPicsArray = json_decode($htmlFeedPics[$faceBookObjectIDKey],true);
$facebookLargeImage = '';
if($faceBookObjectID) $facebookLargeImage = "<img src='".$htmlFeedPicsArray['data']['url']."' class='facebook-images' onClick=\"highLightImage('".$htmlFeedPicsArray['data']['url']."')\"/>";

if($faceBookTimeHoursAgo<21) $timePosted = $faceBookTimeHoursAgo.' h'; 
else $timePosted = $faceBookTimeDate;

$result .= "
<div onClick='sharePost(".$x2.",".$x.",\"facebook\")' class='share-button-post'>Share</div>

<div style='clear:both;'></div>

<div class='facebook-profile-pic result-spacing inline'>".$faceBookProfilePic."</div>

<div class='inline' >

<div class='result-spacing align-left username'>".$htmlKey." - ".$timePosted."</div>

<div class='result-spacing align-left facebook-message'>".$post."</div>

<div style='clear:both;'></div>

<div class='result-spacing'>".$facebookLargeImage."</div>

</div>

<hr style='clear:both;'>

";

$result .= '</div><!--facebook-container-->';

}//if $searchMatch
}//if !$postTimeLimit || strtotime($faceBookTime)>=$postTimeLimitSecondsAgo

$x++;

}//while

if($postCounts){

if($x3>1) $result .= "<div onclick='showMoreFacebook(".$x2.",".$x.")' id='showMoreFacebook".$x2.$x."' class='more-facebook' numberOfFacebook='".$x."' venueNumber='".$x2."'>More ".$venueName." posts <br><img class='show-more-arrow' src='../../pics/down-icon.png' /> </div> ";

$result .= '</div>';
	
}//if

$x2++;

}//foreach

//if nothing was found set to blank
if(!$faceBookTime) $result = '';

$return['results'] = $result;

echo json_encode($return);

?>