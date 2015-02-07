//this is path to post for apps
if(pathForPost){ 
postPath = 'http://ritzkey.com/login/group/';
var action = $('#upload-background-form').attr('action')
$('#upload-background-form').attr('action',postPath+action)
}//if	
	
	$('#upload-background').change(function(){
		
		$('#background-img-feedback').removeClass().html('').hide()
		
		if(!localStorage.getItem('i')) window.location = '../../member-login.html';
		
		$('#load-icon1').show()
		
		var z = getZ()
		$('#background-pic-z').val(z)
		
		$('#upload-background-form').submit()
		
		$('#background-pic-z').val('')	 	
		
})//change


function sendFeedbackBackground(feedback){

if(pathForPost) postPath = 'http://ritzkey.com/login/group/';	
else postPath = '';	
	
	//clear z
	$('#background-pic-z').val('')
	$('#load-icon1').hide()
	
	//redirect if wrong z
	if(feedback=='wrong z'){
		window.location = "../../../member-login.html";
		return;
	}//if
	
	//if error
	if(feedback=='error'){
		 $('#background-img-feedback').addClass('red').html('Not an image file').show()
		 return;
	}
	
	//if group doesn't exist
	if(feedback=='wrong group'){
		window.location = "../../profile/profile";
		return;
	}
	
	
		$('body').addClass('group-background').css('background-image','url('+postPath+feedback+')')
		$('#ajax-loader1').hide()
		$('#background-img-feedback').removeClass().html('').hide()
	
	
}//function




//if on app let cordova framework handle image upload
if(mobileView){
	
$('#upload-background').click(function(e){
	
e.preventDefault()	
	
navigator.camera.getPicture(onSuccess, onFail, { quality: 50,
    destinationType: Camera.DestinationType.FILE_URI,
	sourceType : Camera.PictureSourceType.PHOTOLIBRARY 
});

function onSuccess(imageData) {
	$('#ajax-loader1').show()
	uploadImage(imageData)
	
}

function onFail(message) {
    //alert('Failed because: ' + message);
}

<!--upload image-->
function uploadImage(imageData){

//this will be used below. It's for apps
var imageName = $('body').css('background-image').split('/');
imageName = imageName[imageName.length-1].split(')')[0];

var z = getZ();

var options = new FileUploadOptions();
options.fileKey = "upload-background";
options.fileName = imageData.substr(imageData.lastIndexOf('/') + 1);
options.mimeType = "image/jpeg";
var params = {z:z};

options.params = params;

var win = function (r) {//this is success callback for FileTranser

var x = 0;
var interval = setInterval(function(){
	
	var z = getZ()	
	
	var group_id = getGroupID();
	$.post('http://ritzkey.com/login/group/queries/check-image-change-mobile.php',{z:z, imagePosition:'background',group_id:group_id},function(data){
		
		if(imageName != data.currentImage){
			
			$('body').addClass('group-background').css('background-image','url(http://ritzkey.com/login/group/pics/'+data.folderName+'/'+data.currentImage+')')
	
			$('#ajax-loader1').hide()
			$('#background-img-feedback').removeClass().html('').hide()
			//clear z
			$('#background-pic-z').val('')
			clearInterval(interval);
			return;
		}//if
		
	},'json')//post
x++;
//if 16 seconds have passed asstume there was an error
if(x==15){
$('#load-icon1').hide()	
$('#background-img-feedback').addClass('red').html('Error').show()
//clear z
$('#background-pic-z').val('')
clearInterval(interval);	
return;
}		
},1000)//set interval

}

var fail = function (error) {
    alert("An error has occurred: Code = " + error.code);
}
	         
var ft = new FileTransfer();
ft.upload(imageData, encodeURI('http://ritzkey.com/login/group/queries/upload-background-image.php'), win, fail, options);
	
}//uploadImage function
		
})//click
}//if

