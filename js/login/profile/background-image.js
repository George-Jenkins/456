//this is path to post for apps
if(pathForPost){ 
postPath = 'http://ritzkey.com/login/profile/';
var action = $('#background-img-form').attr('action')
$('#background-img-form').attr('action',postPath+action)
}//if
	
$('#background-uploader').change(function(){
		
	//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
		
	$('#load-icon1').show()
	
	var z = getZ();
	$('#background-img-z').val(z)
	
	$('#background-img-form').submit()
	$('#background-message').removeClass('red').html('').hide()
	$('#background-img-z').val('')

	
})//change



function uploadBackground(feedback){
	
//this is needed so app can access image
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';
	
	if(feedback=="wrong z"){
		
		window.location = "../../../member-login.html";
		return;
	}//if
	
	if(feedback=='not image'){
		$('#load-icon1').hide()
		$('#background-message').addClass('red').html('Not an image').show()
		return;
	}//if
	
	
	$('body').css('background-image','url('+postPath+feedback+')')
	
	$('#load-icon1').hide()
	
	$('#background-img-z').val('')
	
}//function


//if on app let cordova framework handle image upload
if(mobileView){
	
$('#background-uploader').click(function(e){
	
e.preventDefault()	
	
navigator.camera.getPicture(onSuccess, onFail, { quality: 50,
    destinationType: Camera.DestinationType.FILE_URI,
	sourceType : Camera.PictureSourceType.PHOTOLIBRARY 
});

function onSuccess(imageData) {
	$('#load-icon1').show()
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
options.fileKey = "background-uploader";
options.fileName = imageData.substr(imageData.lastIndexOf('/') + 1);
options.mimeType = "image/jpeg";
var params = {z:z};

options.params = params;

var win = function (r) {//this is success callback for FileTranser

var x = 0;
var interval = setInterval(function(){
	
	var z = getZ()
	
	$.post('http://ritzkey.com/login/profile/queries/check-image-change-mobile.php',{z:z, imagePosition:'background'},function(data){
		
		if(imageName != data.currentImage){
			
			$('body').css('background-image','url(http://ritzkey.com/login/profile/pics/'+data.folderName+'/'+data.currentImage+')')
	
			$('#load-icon1').hide()
	
			$('#background-img-z').val('')
			
			clearInterval(interval);
			return;
		}//if
		
	},'json')//post
x++;
//if 16 seconds have passed asstume there was an error
if(x==15){
	
$('#load-icon1').hide()
$('#background-message').addClass('red').html('Error').show()
clearInterval(interval);	
return;
}		
},1000)//set interval

}

var fail = function (error) {
    alert("An error has occurred: Code = " + error.code);
}
	         
var ft = new FileTransfer();
ft.upload(imageData, encodeURI('http://ritzkey.com/login/profile/queries/upload-background-img.php'), win, fail, options);
	
}//uploadImage function
		
})//click
}//if

