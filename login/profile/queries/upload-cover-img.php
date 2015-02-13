<?php
include('../../../connect/db-connect.php');
include('../../../connect/functions.php');

$loginID = cleanInput($_POST['z']);

$cover_file = $_FILES['cover-photo-file']['name'];
$cover_tmp = $_FILES['cover-photo-file']['tmp_name'];
$cover_error = $_FILES['cover-photo-file']['error'];

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

if($cover_error>0 || getimagesize($cover_tmp)===FALSE){
	
echo "<script>
parent.coverPhoto('".$cover_error."')
</script>";

return;
}//if

//make new name for image
$extension = end(explode('.',$cover_file));
while(true){
	$rand1 = rand(1,1000);
	$rand2 = rand(1,1000);
	$new_name = $rand1.$rand2.'.'.$extension;
	$nameTaken = false;
	$images = scandir('../pics/'.$folder.'/');
	foreach($images as $image){
		if($image==$new_name) $nameTaken = true;
	}//foreach
	if($nameTaken == false) break;
}//while

$query = mysql_query("SELECT * FROM profile_cover_photo WHERE email='$email'");
$numrows = mysql_num_rows($query);

if($numrows==0){
	mysql_query("INSERT INTO profile_cover_photo VALUES('','$email','$new_name')");
}//if
else
{	
	
	mysql_query("UPDATE profile_cover_photo SET img='$new_name' WHERE email='$email'");	
}//else

//get img folder name
$query = mysql_query("SELECT * FROM profile_images WHERE email='$email'");
$get = mysql_fetch_assoc($query);
$folder = $get['folder_name'];

//move file to folder
move_uploaded_file($cover_tmp,'../pics/'.$folder.'/'.$new_name);

//resize image
ak_img_resize('../pics/'.$folder.'/'.$new_name, '../pics/'.$folder.'/'.$new_name, 1150, 1150, $extension);

echo "<script>
parent.coverPhoto('pics/".$folder."/".$new_name."')
</script>";

?>
