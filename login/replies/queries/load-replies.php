<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$loop = $_POST['loop'];

include('../../../connect/members.php');

$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$numrows = mysql_num_rows($query);
if($numrows==0) return;

//get email
$get = mysql_fetch_assoc($query);
$email = $get['email'];

$x=0;

$query = mysql_query("SELECT * FROM posts WHERE email='$email' ORDER BY time DESC");

$haveReplies = false;

//get all posts by the user that have replies that haven't been checked
while($get_array = mysql_fetch_array($query)){
	
	$post = $get_array['post'];
	$group = $get_array['group_id'];
	$id = $get_array['id'];
	//set postID. this is the id that will be in the url. it must be the id of the original post and not of a reply
	$originalPostID = $get_array['originalPostID'];
	if($originalPostID!=0) $postID = $originalPostID;
	else $postID = $id;
	//get the names of people who replied and whose replies haven't been checked
	$query2 = mysql_query("SELECT * FROM posts WHERE reply_id='$id' AND email!='$email' AND checked='false'");
	$numrows2 = mysql_num_rows($query2);//get numrows to make sure this post has replies
	
	if($numrows2!=0){
		
	//indicate that replies have been found
	if($loop=='first') $haveReplies = true;	
		
	$email_list = '';
	$replierName='';
	while($get_array2 = mysql_fetch_array($query2)){
		
		$replierEmail = $get_array2['email'];
		if(substr_count($email_list,"---".$replierEmail."---")==0){//see if this person has been counted as someone who replies already
			
		if($replierEmail) $email_list .= "---".$replierEmail."---";//if not add them to this list then finish the loop

		$replierName .= $get_array2['poster'].', ';
		}//if substr count
	}//while
		
	//remove last comma
	$lastCommaPos = strrpos($replierName,',');
	$replierName = substr_replace($replierName,'',$lastCommaPos,1);
	
	//replace last comma with "and"
	$comma_pos = strrpos($replierName,',');
	if($comma_pos>0) $replierName = substr_replace($replierName,' and ',$comma_pos, 2);
	
	//decide if "View reply" should be plural
	if(substr_count($replierName,'and')>0) $viewReply = "View replies";
	else $viewReply = "View reply";
	
	//determine address to post
	$address = "group.html?";//if creator
	//if not creator
	$query2 = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
	$get2 = mysql_fetch_assoc($query2);
	$creatorEmail = $get2['created_by'];
	if($email!=$creatorEmail) $address = "group-member.html?";
	//if not member
	$query2 = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email='$email' AND approved!='no'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows==0) $address = "group-view.html?";
	
	$alertIcon = '<img src="../../pics/new-message-icon.png"/>';
	
	//these posts have replies that haven't been checked
	//I'll get all unchecked replies on first loop
	if($loop=='first') $return['replies'] .= "<div id='hiding-div".$x."' class='hide'> 
	<div id='reply-div".$x."' class='reply-div'>
	<span class='inline'>".$alertIcon." ".$replierName." replied to your post.</span> <a href='../group/".$address.$group."&".$postID."' class='inline buttonLink'>".$viewReply."</a>
	<div id='post-div".$x."' class='post'>".$post."</div>
	
	<div id='show-more".$x."' class='show-more hide functionLink buttonLink' onclick='showMore(".$x.")'>Show more</div>
	</div><!-----reply-div----->
	<p>
	</div><!---hiding-div----->
	";
	
$x++;

	}//if numrows2
	
}//while

$y=0;//this y will just be used to count how many checked replies have been queried. The x counts all replies including checked

//now, get all posts by the user that have replies that haven been checked
$query = mysql_query("SELECT * FROM posts WHERE email='$email' ORDER BY time DESC");
while($get_array = mysql_fetch_array($query)){
	
	$post = $get_array['post'];
	$group = $get_array['group_id'];
	$id = $get_array['id'];
	//get the names of people who replied and whose replies haven't been checked
	$query2 = mysql_query("SELECT * FROM posts WHERE reply_id='$id' AND email!='$email' AND checked='true'");
	$numrows2 = mysql_num_rows($query2);//get numrows to make sure this post has replies
	
	if($numrows2!=0){
	
	//indicate that replies have been found
	$haveReplies = true;	
		
	$email_list = '';
	$replierName='';
	while($get_array2 = mysql_fetch_array($query2)){
		
		$replierEmail = $get_array2['email'];
		if(substr_count($email_list,"---".$replierEmail."---")==0){//see if this person has been counted as someone who replies already
			
		if($replierEmail) $email_list .= "---".$replierEmail."---";//if not add them to this list then finish the loop

		$replierName .= $get_array2['poster'].', ';
		}//if substr count
	}//while
	
	//remove last comma
	$lastCommaPos = strrpos($replierName,',');
	$replierName = substr_replace($replierName,'',$lastCommaPos,1);
	
	//replace last comma with "and"
	$comma_pos = strrpos($replierName,',');
	if($comma_pos>0) $replierName = substr_replace($replierName,' and ',$comma_pos, 2);
	
	//decide if "View reply" should be plural
	if(substr_count($replierName,'and')>0) $viewReply = "View replies";
	else $viewReply = "View reply";
	
	//determine address to post
	$address = "group.html?";//if creator
	//if not creator
	$query2 = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
	$get2 = mysql_fetch_assoc($query2);
	$creatorEmail = $get2['created_by'];
	if($email!=$creatorEmail) $address = "group-member.html?";
	//if not member
	$query2 = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email='$email' AND approved!='no'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows==0) $address = "group-view.html?";
	
	//these posts have replies that haven't been checked
		//on first loop I'll get the first six. On second I'll get the rest
	if($loop=='first' && $y<=5 || $loop=='second' && $y>5) $return['replies'] .= "<div id='hiding-div".$x."' class='hide'>
	<div id='reply-div".$x."' class='reply-div'>
	<span class='inline'>".$replierName." replied to your post.</span> <a href='../group/".$address.$group."&".$id."' class='inline buttonLink'>".$viewReply."</a>
	<div id='post-div".$x."' class='post'>".$post."</div>
	
	<div id='show-more".$x."' class='show-more hide functionLink buttonLink' onclick='showMore(".$x.")'>Show more</div>
	</div><!-----reply-div----->
	<p>
	</div><!---hiding-div----->
	";
	
$x++;
$y++;
	}//if numrows2
	
}//while

if($haveReplies == true && !$return['replies']) $return['replies'] ='';//this can be the case on second loop. (something has to be returned)

if($haveReplies == false) $return['replies'] = 'Currently no replies';

echo json_encode($return);

?>