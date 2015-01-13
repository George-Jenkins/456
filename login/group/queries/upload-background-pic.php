<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$group = cleanInput($_POST['group-id']);

$pic_file = $_FILES['upload-background']['name'];
$pic_tmp = $_FILES['upload-background']['tmp_name'];
$pic_error = $_FILES['upload-background']['error'];

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){
echo "<script>
parent.sendFeedbackBackground('wrong z')
</script>";
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];

//make sure user is creator of group
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group' and created_by='$email'");
$numrows = mysql_num_rows($query);
if($numrows==0){
echo "<script>
parent.sendFeedbackBackground('not creator')
</script>";
return;
}//if


if($pic_error>0 || getimagesize($pic_tmp)===FALSE){
	
echo "<script>
parent.sendFeedbackBackground('error')
</script>";
return;
}//if

//get image folder name
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group' AND created_by='$email'");

//if user didn't create group or group doesn't exist stop
$numrows = mysql_num_rows($query);
if($numrows==0){
	echo "<script>
parent.sendFeedbackBackground('wrong group')
</script>";
	return;
}//if 

$get = mysql_fetch_assoc($query);
$folder = $get['image_folder'];

//unlink old image
$oldImage = $get['background_img'];
unlink("../pics/".$folder."/".$oldImage);

//rename image
$extension = end(explode('.',$pic_file));
while(true){
	$rand1 = rand(1,1000);
	$rand2 = rand(1,1000);
	$new_name = $rand1.$rand2.'.'.$extension;
	$images = scandir("../pics/".$folder."/");
	$nameTaken = false;
	foreach($images as $image){
		if($image==$nameTaken) $nameTaken = true;
	}//foreach
	if($nameTaken == false) break;
}//while


//put image to db
mysql_query("UPDATE groups SET background_img='$new_name' WHERE group_id='$group' AND created_by='$email'");

move_uploaded_file($pic_tmp,"../pics/".$folder."/".$new_name);

echo "<script>
parent.sendFeedbackBackground('pics/".$folder."/".$new_name."')
</script>";
?>