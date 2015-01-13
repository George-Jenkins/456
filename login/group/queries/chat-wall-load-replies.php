<?php

while($get_array2 = mysql_fetch_array($query3)){
	
	$post = $get_array2['post'];
	$time = $get_array2['time'];
	$id = $get_array2['id'];
	$replyID = $get_array2['reply_id'];
	$originalPostID = $get_array2['originalPostID'];
	$dbemail = $get_array2['email'];
	
	
	//get the name of the person being replied to
	$query2 = mysql_query("SELECT * FROM posts WHERE id='$replyID'");
	$get2 = mysql_fetch_assoc($query2);
	$person_getting_reply = $get2['poster'];
	$person_being_replied_to_email = $get2['email'];
	//get poster's name
	$query2 = mysql_query("SELECT * FROM members WHERE email='$dbemail'");
	$get2 = mysql_fetch_assoc($query2);
	$posters_name = $get2['name'];
	$memberID = $get2['id'];
	//determine link to profile of person being replied to
	$query2 = mysql_query("SELECT * FROM members WHERE email='$person_being_replied_to_email'");
	$get2 = mysql_fetch_assoc($query2);
	$person_being_replied_toID = $get2['id'];
	if($person_being_replied_to_email == $email) $profile_link_reply = "../profile/profile.html";
	else $profile_link_reply = "../profile/profile-view.html?".$person_being_replied_toID;
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
	$query2 = mysql_query("SELECT * FROM posts WHERE email='$email' AND id='$originalPostID'");//to see if this is a reply to something user posts
	$numrows2 = mysql_num_rows($query2);
	$query2_2 = mysql_query("SELECT * FROM posts WHERE email='$email' AND reply_id='$id'");//to see if user ever replied to this post
	$numrows2_2 = mysql_num_rows($query2_2);
	if($numrows2==0 && $numrows2_2==0 && $email!=$dbemail) $notInvolved = 'notInvolved';//create class called involved so I know if user was involved
	else $notInvolved = '';
	//determine link to profile
	if($dbemail == $email) $profile_link = "../profile/profile.html";
	else $profile_link = "../profile/profile-view.html?".$memberID;
	
	$return['replyID'.$y] = $replyID;
	
	$delete = '';
	if($email==$dbemail) $delete = "<!-----handle deleting------>
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
	</span>";
	
	//determine if user can reply
	$replyButton = '';
	$reply = '';
	$query2 = mysql_query("SELECT * FROM group_members WHERE email='$email' AND group_id='$group'");
	$numrows2 = mysql_num_rows($query2);
	if($numrows2!=0){
	$replyButton = "<table class='reply-table'>
		<tr>
		<td id='reply".$id."' onclick='replyBox(".$id.",".$time.")' class='functionLink buttonLink'>Reply</td>
		<td id='cancel-reply".$id."' onclick='cancelReply(".$id.")' class='functionLink buttonLink hide'>Cancel Reply</td>
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
	</div><!----text-box---->";	
	}//if
	
	//add padding only if it's the first reply
	$padding='';
	if($originalPostID==$replyID) $padding='padding-right:5px;padding-left:5px';
	
	//set the person who is being replied to
	if($person_being_replied_to_email!=$dbemail) $reply_to = "reply to <a href='".$profile_link_reply."'>".$person_getting_reply."</a>";
	else $reply_to = "";
	
	//make name possessive
	if(substr($posters_name,-1)=='s') $possessive="'";
	else $possessive="'s";
	if($dbemail==$person_being_replied_to_email) $possessive="";
	
	$return['reply'.$y] .= "<div id='post".$id."' class='reply-div ".$time." ".$originalPostID." post-div-delete".$id."  ".$notInvolved."' style='".$padding."'>
	<div class='post-time'>".date('M j',$time)."</div>
	
	
	<div class='poster-name'><a href='".$profile_link."'>".$posters_name.$possessive."</a> ".$reply_to."</div>
	
	<a href='".$profile_link."'>
		<div class='profile-pic' style='background-image:url(../profile/pics/".$profile_img_path.")'></div>
	</a>
	
	<span id='message-span".$id."' class='message-span getReplyScrollHeight".$y."'>".$post."</span>
	
	<!---decide if to show the show more button---->
	<script>
	var scrollHeight = $('#message-span".$id."')[0].scrollHeight
	if(scrollHeight>105) $('#etc-div".$id."').show()
	</script>
	
	<div id='etc-div".$id."' class='etc-div hide gotReplyScrollHeight".$y."'>
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

	</div><!-----post id----->";
	
	//remove email from group email
	$query2 = mysql_query("SELECT * FROM posts WHERE id='$id'");
	$get2 = mysql_fetch_assoc($query2); 
	$group_emails = $get2['group_emails'];
	$group_emails_for_pulse = $get2['group_emails_for_pulse'];
	$new_group_emails = str_ireplace("---".$email."---",'',$group_emails);
	$new_group_emails_for_pulse = str_ireplace("---".$email."---",'',$group_emails_for_pulse);
	mysql_query("UPDATE posts SET group_emails='$new_group_emails', group_emails_for_pulse='$new_group_emails_for_pulse' WHERE id='$id' AND time='$time'");
	
	$y++;
	
	$return['replyNum'] = $y; 
}//while

?>