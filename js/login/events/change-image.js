$('#change-image').change(function(){

//this is path to post for apps
if(pathForPost){ 
postPath = 'http://ritzkey.com/login/events/';
var action = $('#change-image-form').attr('action')
$('#change-image-form').attr('action',postPath+action)
}//if
else
{
postPath = '';
}
	
	$('#error-msg1').html('').hide()
	
	$('#change-image-loader').show()
	
	var z = getZ()
	var eventID = getEventID()
	
	$('#eventZ').val(z)
	$('#imageEventID').val(eventID)
	
	$('#change-image-form').submit()

	
})//change	
	

function sendFeedback(feedback){

//this is path to post for apps
if(pathForPost){ 
postPath = 'http://ritzkey.com/login/events/';
}//if
else
{
postPath = '';
}

	$('#change-image-loader').hide()
	
	if(feedback == 'wrong z'){
		 window.location = "/member-login.html";
		 return;
	}
	if(feedback == 'not creator' || feedback == 'wrong group'){
		 window.location = "../../profile/profile.html";
		 return;
	}
	if(feedback == 'error'){
		
		$('#error-msg1').html('Not an image').show()
		return;
	}//if
	
	$('#event-image').css('background-image','url('+postPath+feedback+')')
	
}//function


//if on app let cordova framework handle image upload
if(mobileView){
	
$('#change-image').click(function(e){
	
e.preventDefault()	
	
navigator.camera.getPicture(onSuccess, onFail, { quality: 50,
    destinationType: Camera.DestinationType.FILE_URI,
	sourceType : Camera.PictureSourceType.PHOTOLIBRARY 
});

function onSuccess(imageData) {
	$('#change-image-loader').show()
	uploadImage(imageData)
	
}

function onFail(message) {
    //alert('Failed because: ' + message);
}

<!--upload image-->
function uploadImage(imageData){

//this will be used below. It's for apps
var imageName = $('#event-image').css('background-image').split('/');
imageName = imageName[imageName.length-1].split(')')[0];

var z = getZ();
var event_id = getEventID();

var options = new FileUploadOptions();
options.fileKey = "change-image";
options.fileName = imageData.substr(imageData.lastIndexOf('/') + 1);
options.mimeType = "image/jpeg";
var params = {z:z, "eventID":event_id};

options.params = params;

var win = function (r) {//this is success callback for FileTranser

var x = 0;
var interval = setInterval(function(){
	
	var z = getZ()
	var event_id = getEventID();
	
	$.post('http://ritzkey.com/login/events/queries/check-image-change-mobile.php',{z:z, imagePosition:'event', event_id:event_id},function(data){
		
		if(imageName != data.currentImage){
			
			$('#event-image').css('background-image','url(http://ritzkey.com/login/profile/pics/'+data.folderName+'/'+data.currentImage+')')
	
			$('#change-image-loader').hide()
			
			clearInterval(interval);
			return;
		}//if
		
	},'json')//post
x++;
//if 16 seconds have passed asstume there was an error
if(x==15){	
$('#change-image-loader').hide()
$('#error-msg1').html('Error').show()
clearInterval(interval);	
return;
}		
},1000)//set interval

}

var fail = function (error) {
    alert("An error has occurred: Code = " + error.code);
}
	         
var ft = new FileTransfer();
ft.upload(imageData, encodeURI('http://ritzkey.com/login/events/queries/change-image.php'), win, fail, options);
	
}//uploadImage function
		
})//click
}//if