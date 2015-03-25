<?php
include('../../../connect/db-connect.php');
include('../../../connect/functions.php');

$loginID = cleanInput($_POST['z']);
$group = cleanInput($_POST['group-id']);

$pic_file = $_FILES['group-pic']['name'];
$pic_tmp = $_FILES['group-pic']['tmp_name'];
$pic_error = $_FILES['group-pic']['error'];

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){
echo "<script>
parent.sendFeedback('wrong z')
</script>";
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//make sure user is creator of group
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group' AND created_by='$email'");
$numrows = mysql_num_rows($query);
if($numrows==0){
echo "<script>
parent.sendFeedbackBackground('not creator')
</script>";
return;
}//if

if($pic_error>0 || getimagesize($pic_tmp)===FALSE){
	
echo "<script>
parent.sendFeedback('error')
</script>";
return;
}//if

//get image folder name
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group' AND created_by='$email'");

//if user didn't create group or group doesn't exist stop
$numrows = mysql_num_rows($query);
if($numrows==0){
	echo "<script>
parent.sendFeedback('wrong group')
</script>";
	return;
}//if 

$get = mysql_fetch_assoc($query);
$folder = $get['image_folder'];

//rename image
$extension = end(explode('.',$pic_file));
while(true){
	$rand1 = rand(1,1000);
	$rand2 = rand(1,1000);
	$new_name = $rand1.$rand2.'.'.$extension;
	$nameTaken = false;
	$images = scandir("../pics/".$folder."/");
	foreach($images as $image){
		if($image==$new_name) $nameTaken = true;
	}//foreach
	if($nameTaken == false) break;
}//while

$oldImage = $get['group_img'];
//unlink old img
unlink("../pics/".$folder."/".$oldImage);

//put image to db
mysql_query("UPDATE groups SET group_img='$new_name' WHERE group_id='$group' AND created_by='$email'");

move_uploaded_file($pic_tmp,"../pics/".$folder."/".$new_name);

//resize image
ak_img_resize("../pics/".$folder."/".$new_name, "../pics/".$folder."/".$new_name, 400, 400, $extension);

echo "<script>
parent.sendFeedback('pics/".$folder."/".$new_name."')
</script>";
?>