<?php
include('../../../connect/db-connect.php');

$profile_pic = $_FILES['profile-pic-upload']['name'];
$profile_pic_tmp = $_FILES['profile-pic-upload']['tmp_name'];
$profile_pic_error = $_FILES['profile-pic-upload']['error'];

$loginID = cleanInput($_POST['z']);

$errors='';

include('../../../connect/members.php');

//get email
$query = mysql_query("SELECT * FROM login_id WHERE login_id='$loginID'");

//handle redirect if wrong id
$numrows = mysql_num_rows($query);
if($numrows==0){
	echo "<script>
parent.uploadBackground('wrong z')
</script>";
	return;
}//if

$get = mysql_fetch_assoc($query);
$email = $get['email'];
$name = $get['name'];

if($profile_pic_error>0 || getimagesize($profile_pic_tmp)===FALSE){
	
	$errors = 'error';
}//if

if($errors!=''){
	
	echo "<script>
	parent.finishProfileImage('".$errors."');
	</script>";
	return;
}//if

//get images folder
$query = mysql_query("SELECT * FROM profile_images WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$folder_name = $get['folder_name'];

//rename image
$extension = end(explode('.',$profile_pic));
while(true){
	$rand1 = rand(1,1000);
	$rand2 = rand(1,1000);
	$new_name = $rand1.$rand2.'.'.$extension;
	$images = scandir('../pics/'.$folder_name.'/');
	$nameTaken = false;
	foreach($images as $image){
		if($image==$new_name) $nameTaken = true;
	}//foreach
	if($nameTaken == false) break;
}//while


$query = mysql_query("SELECT * FROM profile_picture WHERE email='$email'");

$numrows = mysql_num_rows($query);

if($numrows==0){
	mysql_query("INSERT INTO profile_picture VALUES('','$email','$new_name')");
}
else
{
	//get old img
	$query = mysql_query("SELECT * FROM profile_picture WHERE email='$email'");
	$get = mysql_fetch_assoc($query);
	$oldImage = $get['img'];
	
	//unlink old image
	unlink('../pics/'.$folder_name.'/'.$oldImage);

	mysql_query("UPDATE profile_picture SET img='$new_name' WHERE email='$email'");
}


move_uploaded_file($profile_pic_tmp,'../pics/'.$folder_name.'/'.$new_name);

echo "<script>
	parent.finishProfileImage('pics/".$folder_name."/".$new_name."');
	</script>";
?>