//this is path to post for apps
if(pathForPost){ 
postPath = 'http://ritzkey.com/login/profile/';
var action = $('#profile-pic-form').attr('action')
$('#profile-pic-form').attr('action',postPath+action)
}//if


$('#profile-pic-upload').change(function(){
	
	//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
	
	$('#image-loading').show()
	
	var z = getZ();
	$('#profile-pic-z').val(z)
	
	//this will be used below. It's for apps
	var imageName = $('#profile-pic-div').css('background-image').split('/');
	imageName = imageName[imageName.length-1].split(')')[0];
	
	$('#profile-pic-form').submit()
	

	$('#profile-pic-z').val('')
	 	
	
})//change


function finishProfileImage(feedback){
//this is needed so app can access image
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';

	if(feedback=='wrong z'){
		window.location = "../../../member-login.html";
		return;
	}//if

	if(feedback=='error'){
		 $('#profile-submit-feedback').addClass('red').html('That may not be<br> an image file.')
		 $('#image-loading').hide()
		 return;
	}//if
	
	$('#profile-pic-div').css('background-image','url('+postPath+feedback+')');
	$('#image-loading').hide()
	$('#profile-submit-feedback').removeClass('red').html('')
	 
}//function



//if on app let cordova framework handle image upload
if(mobileView){
	
$('#profile-pic-upload').click(function(e){
	
e.preventDefault()	
	
navigator.camera.getPicture(onSuccess, onFail, { quality: 50,
    destinationType: Camera.DestinationType.DATA_URL,
	sourceType : Camera.PictureSourceType.PHOTOLIBRARY 
});

function onSuccess(imageData) {
	uploadImage(imageData)
}

function onFail(message) {
    alert('Failed because: ' + message);
}

<!--upload image-->
function uploadImage(imageData){
//alert(imageData)
var z = getZ()
alert(2)
var options = new FileUploadOptions();
options.fileKey = "profile-pic-upload";
options.fileName = imageData.substr(imageData.lastIndexOf('/') + 1);
options.mimeType = "image/jpeg";

alert(options.fileName)
var win = function (r) {//this is success callback for FileTranser
alert('win')
}

var fail = function (error) {
    alert("An error has occurred: Code = " + error.code);
}
	
var ft = new FileTransfer();
ft.upload(imageData, encodeURI('http://ritzkey.com/login/profile/queries/test-upload.php'), win, fail, options);
	
}//uploadImage function
		
})//click
}//if