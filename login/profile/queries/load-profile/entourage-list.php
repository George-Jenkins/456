<?php
include('../../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

include('../../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");
$get = mysql_fetch_assoc($query);
$email = $get['email'];

//get entourages
$query = mysql_query("SELECT * FROM group_members WHERE email='$email'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	
	$return['error'] = 'no groups';
	echo json_encode($return);
	return;
}//if

while($get_array = mysql_fetch_array($query)){
	
	$group_id = $get_array['group_id'];
	$approved = $get_array['approved'];
	
	$pending = '';
	if($approved=='no') $pending = '<span class="red">(Pending)</span>';
	
	$query2 = mysql_query("SELECT * FROM groups WHERE group_id='$group_id'");
	
	$get = mysql_fetch_assoc($query2);
	
	$group_name = $get['group_name'];
	$image_folder = $get['image_folder'];
	$group_image = $get['group_img'];
	
	$image_path = "../group/pics/".$image_folder."/".$group_image."";
	if(!$group_image) $image_path = "../../pics/nightclub3.jpg";
	
	$return['list'] .= "
	<div class='entourage-list'>
	
	<a href='../group/group.html?".$group_id."'>
		<div class='group-name buttonLink'><span id='groupID".$group_id."' class='ent-alert'></span>
	".$group_name." ".$pending."
		</div>
	</a>
	<a href='../group/group.html?".$group_id."'>
		<div style='background-image:url(".$image_path.")' class='entourage-list-img'></div>
	</a>
	</div>
	";
	
}//while

echo json_encode($return);

?>