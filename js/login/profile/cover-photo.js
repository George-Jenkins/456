//this is path to post for apps
if(pathForPost){ 
postPath = 'http://ritzkey.com/login/profile/';
var action = $('#cover-photo-form').attr('action')
$('#cover-photo-form').attr('action',postPath+action)
}//if

//show where cover photo is on hover
$('#what-is-cover').click(function(){
	
	$('#cover-photo-div').show()
	$('#hide-example-cover').show()
	$('#what-is-cover').hide()
	
	$('#profile-title').html('Profile')
	$('body').animate({scrollTop:0},200)
})//show sample cover

$('#hide-example-cover').click(function(){
	
	$('#cover-photo-div').hide()
	$('#hide-example-cover').hide()
	$('#what-is-cover').show()
	
	var name = localStorage.getItem('loginName');
	$('#profile-title').html(name)
	
})//hide sample cover

//detect change in value and submit
$('#cover-photo-file').change(function(){
	
	//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
	
	var z = getZ()
	$('#cover-img-z').val(z)
	
	$('#cover-photo-form').submit()
	$('#load-icon2').show()
	$('#add-cover-feedback').removeClass('red').html('').hide()
	$('#cover-img-z').val('')
	 
	 
})//change


//delete cover
$('#delete-cover-span').click(function(){

if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';
	
	var z = getZ();
	
	$('#load-icon2').show()
	
	$.post(postPath+'queries/delete-cover-img.php',{z:z},function(data){
	
		if(data){
		
			$('#add-cover-span').show()
			$('#what-is-cover').show()
			$('#hide-example-cover').hide()
			$('#delete-cover-span').hide()
			$('#load-icon2').hide()
			$('#cover-photo-div').css('background-image','url(pics/nightclub2-1.jpg)').hide()
			var name = localStorage.getItem('loginName');
			$('#profile-title').html(name)
		}//if
	})//post
})//click


function coverPhoto(feedback){

//this is needed so app can access image
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';
	
	if(feedback=="wrong z"){
		
		window.location = "../../../member-login.html";
		return;
	}//if
	
	if(feedback=='error'){
		$('#load-icon2').hide()
		 $('#add-cover-feedback').addClass('red').html('Not an image').show()
		 return;
	}//if
	
	$('#cover-photo-div').css('background-image','url('+postPath+feedback+')').show()
	
	//hide the what's this stuff
	$('#add-cover-span').hide()
	$('#delete-cover-span').show()
	$('#load-icon2').hide()
	$('#cover-photo-file').val('')
	$('#profile-title').html('Profile')
	
}//function


//if on app let cordova framework handle image upload
if(mobileView){
	
$('#cover-photo-file').click(function(e){
	
e.preventDefault()	
	
navigator.camera.getPicture(onSuccess, onFail, { quality: 50,
    destinationType: Camera.DestinationType.FILE_URI,
	sourceType : Camera.PictureSourceType.PHOTOLIBRARY 
});

function onSuccess(imageData) {
	$('#load-icon2').show()
	uploadImage(imageData)
	
}

function onFail(message) {
    //alert('Failed because: ' + message);
}

<!--upload image-->
function uploadImage(imageData){

//this will be used below. It's for apps
var imageName = $('#cover-photo-div').css('background-image').split('/');
imageName = imageName[imageName.length-1].split(')')[0];

var z = getZ();

var options = new FileUploadOptions();
options.fileKey = "cover-photo-file";
options.fileName = imageData.substr(imageData.lastIndexOf('/') + 1);
options.mimeType = "image/jpeg";
var params = {z:z};

options.params = params;

var win = function (r) {//this is success callback for FileTranser

var x = 0;
var interval = setInterval(function(){
	
	var z = getZ()	
	
	$.post('http://ritzkey.com/login/profile/queries/check-image-change-mobile.php',{z:z, imagePosition:'cover'},function(data){
		
		if(imageName != data.currentImage){
			
			$('#cover-photo-div').css('background-image','url(http://ritzkey.com/login/profile/pics/'+data.folderName+'/'+data.currentImage+')').show()
	
			$('#add-cover-span').hide()
			$('#delete-cover-span').show()
			$('#load-icon2').hide()
			$('#cover-photo-file').val('')
			$('#profile-title').html('Profile')
			
			clearInterval(interval);
			return;
		}//if
		
	},'json')//post
x++;
//if 16 seconds have passed asstume there was an error
if(x==15){
	
('#load-icon2').hide()
$('#add-cover-feedback').addClass('red').html('Error').show()
clearInterval(interval);	
return;
}		
},1000)//set interval

}

var fail = function (error) {
    alert("An error has occurred: Code = " + error.code);
}
	         
var ft = new FileTransfer();
ft.upload(imageData, encodeURI('http://ritzkey.com/login/profile/queries/upload-cover-img.php'), win, fail, options);
	
}//uploadImage function
		
})//click
}//if

