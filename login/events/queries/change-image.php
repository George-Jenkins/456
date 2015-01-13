<?php
include('../../../connect/db-connect.php');

$loginID = cleanInput($_POST['z']);
$eventID = cleanInput($_POST['eventID']);

$imageName = $_FILES['change-image']['name'];
$imageTmpName = $_FILES['change-image']['tmp_name'];
$imageError = $_FILES['change-image']['error'];


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

//make sure user is creator of event
$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID' AND email='$email'");
$numrows = mysql_num_rows($query);
if($numrows==0){
echo "<script>
parent.sendFeedback('not creator')
</script>";
return;
}//if

if($imageError >0 || getimagesize($imageTmpName)===FALSE){
	
echo "<script>
parent.sendFeedback('error')
</script>";
return;
}//if

//get image folder name
$query = mysql_query("SELECT * FROM profile_images WHERE email='$email'");

$get = mysql_fetch_assoc($query);
$folder = $get['folder_name'];

$query = mysql_query("SELECT * FROM events WHERE event_id='$eventID' AND email='$email'");
$numrows = mysql_num_rows($query);
if($numrows==0){
	return;
}//if

$get = mysql_fetch_assoc($query);
$oldImage = $get['image'];
//unlink old img
unlink("../../profile/pics/".$folder."/".$oldImage);

//rename image
$extension = end(explode('.',$imageName));
while(true){//make scan dir to make sure the file doesn't exist already
	$rand1 = mt_rand(1,1000);
	$rand2 = mt_rand(1,1000);
	$images = scandir("../../profile/pics/".$folder."/");
	$nameTaken = false;
	$new_name = $rand1.$rand2.'.'.$extension;
	foreach($images as $image){
		
		if($image==$new_name) $nameTaken = true;
	}//foreach
	if($nameTaken == false) break;
}//while

//put image to db
mysql_query("UPDATE events SET image='$new_name' WHERE event_id='$eventID' AND email='$email'");

move_uploaded_file($imageTmpName,"../../profile/pics/".$folder."/".$new_name);

echo "<script>
parent.sendFeedback('../profile/pics/".$folder."/".$new_name."')
</script>";
?>