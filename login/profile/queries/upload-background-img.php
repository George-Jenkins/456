<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);

$img_name = $_FILES['background-uploader']['name'];
$img_tmp_name = $_FILES['background-uploader']['tmp_name'];
$img_error = $_FILES['background-uploader']['error'];

if($img_error>0 || getimagesize($img_tmp_name)===FALSE){
echo "<script>
parent.uploadBackground('not image')
</script>";

return;
}//if

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

//rename image
while(true){
	$rand1 = rand(1,1000);
	$rand2 = rand(1,1000);
	$extension = end(explode('.',$img_name));
	$new_name = $rand1.$rand2.'.'.$extension;
	$nameTaken = false;
	$images = scandir('../pics/'.$folder_name.'/');
	foreach($images as $image){
		if($image == $new_name) $nameTaken = true;
	}//foreach
	if($nameTaken == false) break;
}//while

//check to see if anything has been added to db before
$query = mysql_query("SELECT * FROM profile_background_img WHERE email='$email'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	
	mysql_query("INSERT INTO profile_background_img VALUES('','$email','$new_name')");
}//if
else{
	
	//get old img
	$query = mysql_query("SELECT * FROM profile_background_img WHERE email='$email'");
	$get = mysql_fetch_assoc($query);
	$oldImage = $get['img'];	
	
	mysql_query("UPDATE profile_background_img SET img='$new_name' WHERE email='$email'");
}//else

	//get user's directory
	$query = mysql_query("SELECT * FROM profile_images WHERE email='$email'");
	$get = mysql_fetch_assoc($query);
	$folder_name = $get['folder_name'];
	
	//unlink old image
	unlink('../pics/'.$folder_name.'/'.$oldImage);
	
	move_uploaded_file($img_tmp_name,'../pics/'.$folder_name.'/'.$new_name);

	echo "<script>
parent.uploadBackground('pics/".$folder_name."/".$new_name."')
</script>";

?>