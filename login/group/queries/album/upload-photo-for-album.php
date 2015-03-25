<?php
include('../../../../connect/db-connect.php');
include('../../../../connect/functions.php');

$loginID = cleanInput($_POST['z']);
$group = cleanInput($_POST['group-id']);

$pic_name = $_FILES['upload-album']['name'];
$pic_tmp = $_FILES['upload-album']['tmp_name'];
$pic_error = $_FILES['upload-album']['error'];

include('../../../../connect/members.php');

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

//make sure user is in group
$query = mysql_query("SELECT * FROM group_members WHERE group_id='$group' AND email='$email' AND approved!='no'");
$numrows = mysql_num_rows($query);
if($numrows==0){
echo "<script>
parent.sendFeedbackBackground('not member')
</script>";
return;
}//if

if($pic_error>0 || getimagesize($pic_tmp)===FALSE){
	
echo "<script>
parent.sendFeedback('error','','')
</script>";
return;
}//if

//get image folder name
$query = mysql_query("SELECT * FROM groups WHERE group_id='$group'");
$get = mysql_fetch_assoc($query);
$folder = $get['image_folder'];

//rename image
$extension = end(explode('.',$pic_name));
while(true){
	$rand1 = rand(1,1000);
	$rand2 = rand(1,1000);
	$new_name = $rand1.$rand2.'.'.$extension;
	$nameTaken = false;
	$images = scandir("../../pics/".$folder."/");
	foreach($images as $image){
		if($image==$new_name) $nameTaken = true;
	}//foreach
	if($nameTaken == false) break;
}//while

//added image to group album table
$date = date('Y-m-d');
$time = time();
mysql_query("INSERT INTO group_album VALUES ('','$email','$group','$new_name','','$date','$time')");

//get id of new row
$query = mysql_query("SELECT * FROM group_album WHERE email='$email' AND image='$new_name'");
$get = mysql_fetch_assoc($query);
$id = $get['id'];

//get name of person who uploaded
$query2 = mysql_query("SELECT * FROM members WHERE email='$email'");
$get2 = mysql_fetch_assoc($query2);
$name = ucwords($get2['name']);

move_uploaded_file($pic_tmp,"../../pics/".$folder."/".$new_name);

//create smaller image
ak_img_resize("../../pics/".$folder."/".$new_name, "../../pics/".$folder."/small-".$new_name, 400, 400, $extension);

$imagePath = "pics/".$folder."/small-".$new_name;
$functionImagePath = "pics/".$folder."/".$new_name;

//since this was uploaded by user they can edit it
$deleteable = 'true';
$editCaption = 'true';

$return = "<div id='".$functionImagePath."' id2='".$id."' onClick=showImage('".$functionImagePath."') class='group-photos-inline' deletable='".$deleteable."' editCaption='".$editCaption."' style='background-image:url(".$imagePath.")'></div>";

$linkToProfile = "<a href='../profile/profile.html'>".$name."</a>";	
$uploadedByDiv = "<div uploadedByID='".$id."' class='uploaded-by hide'>Uploaded by ".$linkToProfile."</div>";

$returnCaption = "<div captionID='".$id."' class='caption hide'></div>";

echo "<script>
parent.sendFeedback(\"".$return."\",\"".$returnCaption."\",\"".$uploadedByDiv."\")
</script>";
?>