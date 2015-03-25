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

$clientID = 'c89af8c355e34dfbaa3702c67b2b67b4';//got this when registering the developer account

$query = mysql_query("SELECT * FROM venue WHERE instagram!='' LIMIT 2");

$x2 = 0;//venue number

while($get_array = mysql_fetch_array($query)){

$venueName = $get_array['name'];

$userName = $get_array['instagram'];

$website = $get_array['website'];
if($website) $website = "<div class='venue-website-div'><a href='".$website."' class='buttonLink' target='_blank'>Website</a></div>";

$city = $get_array['city'];
$state = $get_array['state'];

$location = $get_array['house']." ".$get_array['street']." ".$city." ".$state;

$lat = $get_array['lat'];
$lon = $get_array['lon'];
//determine if within range
$distance = getDistanceFromLatLonInKm($userLatitude,$userLongitude,$lat,$lon)*0.621371;
if(strtolower($userCity.$userState)==strtolower($city.$state)|| $distance<=$maxDistance){

$getUserID = file_get_contents('https://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.$clientID);//this will get the id of the user (club)
$getUserID = json_decode($getUserID,true);
$userID = $getUserID['data'][0]['id'];

$instagramPage = file_get_contents('https://api.instagram.com/v1/users/'.$userID.'/media/recent/?client_id='.$clientID);//this will get the feed of user

$instagramArray = json_decode($instagramPage,true);

$numberOfLoops = count($instagramArray['data']);

$x = 0;

$postCounts = false;//set to true if this post is added

$x3 = 0;//this is to keep track of loops that count 

while($x<$numberOfLoops){

//get time created (get time first so this post can not be added if it's past post time limit)
$instagramTimeSeconds = $instagramArray['data'][$x]['created_time'];
$instagramTimeHoursAgo = round((time()-$instagramTimeSeconds)/60/60);
$instagramTimeYear = date('Y',$instagramTimeSeconds);//get year this was posted
$currentYear = date('Y');//get this year
if($instagramTimeYear == $currentYear) $instagramTimeDate = date('D M j, g:i a',$instagramTimeSeconds);//leave out year of years are the same
else $instagramTimeDate = date('D M j Y g:i a',$instagramTimeSeconds);

//determine if post is within post time limit
$postTimeLimitSeconds = ($postTimeLimit*24*60*60);//get number of seconds in the limit
$postTimeLimitSecondsAgo = time()-$postTimeLimitSeconds;//get the number of seconds ago post time limit was from now
if(!$postTimeLimit || $instagramTimeSeconds>=$postTimeLimitSecondsAgo){//determine if seconds ago the post was made exceeds the limit in seconds

//get text
$post = $instagramArray['data'][$x]['caption']['text'];

$searchMatch = searchMatch($post,$lookingFor);
if($searchMatch){

$postCounts = true;
$x3++; 

if($x3==1){//this must be done once
$result .= '<div>
<div class="venue-name-div"><div class="venue-name">'.$venueName.'</div> <div>'.$location.'</div> '.$website.' </div>
'; 	
}//if
	
if($x3>1){$hide='hide'; $notFirst = 'not-first-instagram';}//if
else{$hide=''; $notFirst = '';}//else	
		
$result .= '<div id="instagram-container'.$x2.$x.'" class="'.$hide.' '.$notFirst.'">';		
		
//get profile pic
$instagramProfilePic = "<img src='".$instagramArray['data'][$x]['caption']['from']['profile_picture']."' />";

//make addresses hyperlinks
if(substr_count($post,'http')!=0) $post = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $post);
elseif(substr_count($post,'www.')!=0) $post = preg_replace('@(www.([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="http://$1" target="_blank">$1</a>', $post);

//get username
$instagramUsername = $instagramArray['data'][$x]['caption']['from']['username'];
//get image
$instagramImage = $instagramArray['data'][$x]['images']['standard_resolution']['url'];

if($instagramImage) $instagramImage = "<img src='".$instagramImage."' class='instagram-images' onClick=\"highLightImage('".$instagramImage."')\"/>";

if($instagramTimeHoursAgo<21) $timePosted = $instagramTimeHoursAgo.' h'; 
else $timePosted = $instagramTimeDate;

$result .= "
<div onClick='sharePost(".$x2.",".$x.",\"instagram\")' class='share-button-post'>Share</div>

<div style='clear:both;'></div>

<div class='instagram-profile-pic result-spacing inline'>".$instagramProfilePic."</div>

<div class='inline'>

<div class='result-spacing align-left username'>".$instagramUsername." - ".$timePosted."</div>

<div class='result-spacing align-left instagram-message'>".$post."</div>

<div style='clear:both;'></div>

<div class='result-spacing'>".$instagramImage."</div>

</div>

<hr style='clear:both;'>

";

$result .= '</div><!--instagram-container-->';

}//if $searchMatch
}//if !$postTimeLimit || $instagramTimeSeconds>=$postTimeLimitSecondsAgo

$x++;

}//while

if($postCounts){

if($x3>1) $result .= "<div onclick='showMoreInstagram(".$x2.",".$x.")' id='showMoreInstagram".$x2.$x."' class='more-instagram' numberOfInstagram='".$x."' venueNumber='".$x2."'>More ".$venueName." posts <br><img class='show-more-arrow' src='../../pics/down-icon.png' /> </div> ";

$result .= '</div>';
	
}//if

$x2++;

}//if $distance<=$maxDistance

}//while

//if nothing was found set to blank
if(!$instagramTimeSeconds) $result = '';

$return['results'] = $result;

echo json_encode($return);

?>