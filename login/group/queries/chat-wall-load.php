<?php
include('../../../connect/db-connect.php');

$group = cleanInput($_POST['group']);
$loginID = cleanInput($_POST['z']);
$loop = $_POST['loop'];//this will equal an id, 'first' or 'second'. 'first' loads the first 23 and 'second' loads the rest. if it's an id it loads just that post
$postPath = $_POST['postPath'];//this is posted when postPath is provided
$showNumber = $_POST['showNumber'];//this is the number of posts show at a time

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$get = mysql_fetch_assoc($query);
$email = $get['email'];

//verify group exists
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	$return['error'] = 'no group';
	echo json_encode($return);
	return;
}//if

//set time zone
$query = mysql_query("SELECT * FROM account_settings WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$timezone = $get['timezone'];
date_default_timezone_set($timezone);

//this handles user viewing specific replies
$query = mysql_query("SELECT * FROM posts WHERE group_id='$group' AND reply_id='0' ORDER BY time DESC, id DESC");
//if $loop is an id just get that id
if($loop!='first' && $loop!='second'){
	 $query = mysql_query("SELECT * FROM posts WHERE id='$loop' AND group_id='$group' AND reply_id='0' ORDER BY time DESC, id DESC");
	 $idLoop = true;
}//if

$x=0;
$y = 0;

while($get_array = mysql_fetch_array($query)){
	
	$post = $get_array['post'];
	$time = $get_array['time'];
	$id = $get_array['id'];
	$dbemail = $get_array['email'];
	
	//get poster's name
	$query2 = mysql_query("SELECT * FROM members WHERE email='$dbemail'");
	$get2 = mysql_fetch_assoc($query2);
	$posters_name = $get2['name'];
	$memberID = $get2['id'];
	//gender
	$query2 = mysql_query("SELECT * FROM members WHERE email='$dbemail'");
	$get2 = mysql_fetch_assoc($query2);
	$gender = $get2['gender'];
	//get image folder
	$query2 = mysql_query("SELECT * FROM profile_images WHERE email='$dbemail'");
	$get2 = mysql_fetch_assoc($query2);
	$img_folder = $get2['folder_name'];
	//get profile image
	$query2 = mysql_query("SELECT * FROM profile_picture WHERE email='$dbemail'");
	$get2 = mysql_fetch_assoc($query2);
	$profile_img = $get2['img'];
	$profile_img_path = $img_folder."/".$profile_img;
	if(!$profile_img) $profile_img_path = $gender."-head.png";
	//determine if user was involved in this post. 
	$query2 = mysql_query("SELECT * FROM posts WHERE email='$email' AND originalPostID='$id'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2==0 && $email!=$dbemail) $notInvolved = 'notInvolved';//create class called involved so I know if user was involved
	else $notInvolved = '';
	//determine link to profile
	if($dbemail == $email) $profile_link = "../profile/profile.html";
	else $profile_link = "../profile/profile-view.html?".$memberID;
	//determine if there are replies to this post
	$query2 = mysql_query("SELECT * FROM posts WHERE originalPostID='$id'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2!=0) $showReplies = "<div id='show-replies".$id."' class='show-replies functionLink' onclick='showReples(".$id.")'>Show replies</div>";
	else $showReplies = "";
	
	//determine if user can delete post
	$delete = '';
	if($email==$dbemail){ $delete = "<!-----handle deleting------>
	<span class='delete-span'>
	<table>
	<tr>
	<td id='delete-show".$id."' onclick='show_delete(".$id.")' class='functionLink buttonLink'>Delete</td>
	</tr>
	</table>
	
	<table id='delete-yes-no".$id."' class='hide'>
	<tr>
		<td><b>You sure?</b></td>
		<td id='yes-delete".$id."' onclick='yes_delete(".$id.",".$time.")' class='functionLink buttonLink'>Yes</td>
		<td><div style='width:15px;'></div></td>
		<td id='no-delete".$id."' onclick='no_delete(".$id.",".$time.")' class='functionLink buttonLink noButton'>No</td>
	</tr>
	</table>
	</span>
	";
	}//if
	
	//determine if user can reply
	$replyButton = '';
	$reply = '';
	$query2 = mysql_query("SELECT * FROM group_members WHERE email='$email' AND group_id='$group'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2!=0){
	$replyButton = "<table class='reply-table'>
		<tr>
		<td id='reply".$id."' onClick='replyBox(".$id.",".$time.")' class='functionLink buttonLink'>Reply</td>
		<td id='cancel-reply".$id."' onClick='cancelReply(".$id.")' class='functionLink buttonLink hide'>Cancel Reply</td>
		</tr>
	</table>";
	$reply = "<div id='text-box".$id."' class='reply-text-box hide'>
	<form id='chat-form".$id."' onSubmit='return false'>
    <textarea id='message".$id."' class='message-textarea'></textarea>
    </textarea>
    <div class='button-div'>
    	<span class='general-submit'><input type='submit' id='chat-send".$id."' value='Send' onClick='submitReply(".$id.",".$time.")'/></span>
		<img id='replyLoader".$id."' src='../../pics/ajax-loader2.gif' class='hide small-processing-img'>
    </div><!---button-div----->
    </form>
	</div><!----text-box---->
	";	
	}//if
	
	//hide the ones past the limit
	if($x>=$showNumber) $hide='hide';
	
	
	if($loop=='first' || $loop == 'second' && $x>$showNumber || $idLoop==true) $return['post'] .= "<div id='post".$id."' class='post-div time".$time." ".$hide." post-div".$x." ".$id." post-div-delete".$id." ".$notInvolved."'>
	
	<div class='post-time'>".date('M j',$time)."</div>
	
	<div class='poster-name'><a href='".$profile_link."'>".$posters_name."</a></div>
	
	<a href='".$profile_link."'>
		<div class='profile-pic' style='background-image:url(".$postPath."../profile/pics/".$profile_img_path.")'></div>
	</a>
	
	<span id='message-span".$id."' class='message-span getPostScrollHeight".$x."'>".$post."</span>
	
	<!---decide if to show the show more button---->
	<script>
	var scrollHeight = $('#message-span".$id."')[0].scrollHeight
	if(scrollHeight>105) $('#etc-div".$id."').show()
	</script>
	
	<div id='etc-div".$id."' class='etc-div hide gotPostScrollHeight".$x."'>
		<span id='show-more-link".$id."' onClick='showMoreLink(".$id.")'><a href='' onclick='return false' class='buttonLink'>Show more ...</a></span>
	</div><!---etc-div---->
	
	<div style='clear:both'></div>
	
	<div class='reply-delete-div'>
	<!----reply button------->
	".$replyButton."
	
	<!-----handle deleting------>
	".$delete."
	</div><!-----reply-delete-div------>
	
	<!-------reply box-------->
	".$reply."
	
	<!------show replies------->
	".$showReplies."
	</div><!-----post id----->
	";
	
	//remove email from group email
	$query2 = mysql_query("SELECT * FROM posts WHERE id='$id'");
	$get2 = mysql_fetch_assoc($query2); 
	$group_emails = $get2['group_emails'];
	$group_emails_for_pulse = $get2['group_emails_for_pulse'];
	$new_group_emails = str_ireplace("---".$email."---",'',$group_emails);
	$new_group_emails_for_pulse = str_ireplace("---".$email."---",'',$group_emails_for_pulse);
	mysql_query("UPDATE posts SET group_emails='$new_group_emails', group_emails_for_pulse='$new_group_emails_for_pulse' WHERE id='$id' AND time='$time'");
	
	//get replies for this post
	$query3 = mysql_query("SELECT * FROM posts WHERE originalPostID='$id'");
	if($loop=='first' || $loop=='second' && $x>$showNumber || $idLoop==true) include('chat-wall-load-replies.php');
	
	//stop to load the first few posts. The second loop loads the rest
	if($loop=='first' && $x==$showNumber) break;
		
		
	$x++;
	
}//while

$return['limit'] = mysql_num_rows($query);

//get last id. Important when deleting
$query = mysql_query("SELECT * FROM posts WHERE group_id='$group' AND id = (SELECT MAX(id) FROM posts) LIMIT 1;");
$get = mysql_fetch_assoc($query);
$return['lastID'] = $get['id'];

//get first id. Important when deleting
$query = mysql_query("SELECT * FROM posts WHERE group_id='$group' AND id = (SELECT MIN(id) FROM posts) LIMIT 1;");
$get = mysql_fetch_assoc($query);
$return['firstID'] = $get['id'];

echo json_encode($return);

?>