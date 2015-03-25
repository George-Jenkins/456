<?php

header('Content-Type: text/html');
$ch = curl_init('https://www.facebook.com/'.$userName);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');
$result = curl_exec($ch); 
//remove white space
$result = trim(preg_replace('/\s+/', ' ', $result));

//divide all posts
preg_match_all('#clearfix _5x46(.*?)uiUfi UFIContainer _5pc9 _5vsj _5v9k#',$result,$curlPageArray);

if($curlPageArray){

//$pageSection = $curlPageArray[1][0];

$arrayLength = count($curlPageArray[1]);

$x=0;

$pageSection = $curlPageArray[1][$x];

//get posting name
preg_match('#ver.php:PagePostsPagelet&quot;&\#125;">(.*?)</a></span><#',$pageSection,$postNameArray);
$postName = $postNameArray[1];

//get profile image
preg_match('#<img class="_s0 _5xib _5sq7 _rw img" src="(.*?)" alt#',$pageSection,$profileImageArray);
$profileImage = $profileImageArray[1];

//get post
preg_match('#\#123;&quot;tn&quot;:&quot;K&quot;&\#125;"><p>(.*?)</p></div><div>#',$pageSection,$postArray);//second pattern was </p></div><div><div data-ft="&\#123; but not all posts have that
$post = $postArray[1];

//get image
if(substr_count($pageSection,'scaledImageFitWidth')>0) preg_match('#img class="scaledImageFitWidth img" src="(.*?)" alt="#',$pageSection,$imageArray);
else preg_match('#img class="scaledImageFitHeight img" src="(.*?)" style#',$pageSection,$imageArray);
$imageSmall = $imageArray[1];

	//strip the info after the ?
$imageNoPostInfo = explode('?',$imageSmall);
$imageNoPostInfo = $imageNoPostInfo[0];
	//get just the name of the image
$imageName = end(explode('/',$imageNoPostInfo));
	//add to the name this pattern to get whats in between this image and "&amp" this is the post info needed to get the large image
$imageName.='%3Foh%3D';
preg_match("#".$imageName."(.*?)&amp#",$pageSection,$largeInfoArray);
$largeInfoArray = $largeInfoArray[1];
	//replace curtain characters that I guess are escaped
$largeInfoArray = str_ireplace('%26','&',$largeInfoArray);
$largeInfo = str_ireplace('%3D','=',$largeInfoArray);
	//remove image sizes like s480x480/ from the path. Must be done to get the large image
$getImageSize = str_replace(':/','',$imageNoPostInfo);
preg_match_all('#\/(.*?)\/#',$getImageSize,$imageSizeArray);
$imageSize = $imageSizeArray[1][2];
$largeImageNoPostInfo = str_ireplace(''.$imageSize.'/','',$imageNoPostInfo);
$largeImage = "<img src='".$largeImageNoPostInfo."?oh=".$largeInfo."' />";

//get time
preg_match('#data-utime="(.*?)"#',$pageSection,$timeStampArray);
$timeSeconds = $timeStampArray[1];
$timeDay = date('D M j g:i a',$timeSeconds);
$timeDayWithYear = date('D M j, Y g:i a',$timeSeconds);

}//if $curlPageArray

//create json string for first post on page
$jsonString = mysql_real_escape_string('{"content":{"time":"'.$timeSeconds.'","post":"'.strip_tags($post).'"}}');
//get the last first post stored in db
$query2 = mysql_query("SELECT * FROM venue WHERE name='".$venueName."'");
$getFacebookPost = mysql_fetch_assoc($query2);
$oldPost = mysql_real_escape_string($getFacebookPost['facebook_post']);
//if posts are different update db
if($oldPost!=$jsonString){
	mysql_query("UPDATE venue SET facebook_post='".$jsonString."' WHERE name='".$venueName."'");
	$postChanged = true;
	$equal='yes';
}//if
else $postChanged = false;

curl_close($ch);

?>