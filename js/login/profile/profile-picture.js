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
	
//if on app the db must be queried to see if image has been changed
if(mobileView){
	
var x = 0;
var interval = setInterval(function(){
	
	var z = getZ()	
	
	$.post('http://ritzkey.com/login/profile/queries/check-image-change-mobile.php',{z:z, imagePosition:'profile'},function(data){
		
		if(imageName != data.currentImage){
			
			$('#profile-pic-div').css('background-image','url(http://ritzkey.com/login/profile/pics/'+data.folderName+'/'+data.currentImage+')').show()
	
			$('#image-loading').hide()
			$('#profile-submit-feedback').removeClass('red').html('')
			clearInterval(interval);
			return;
		}//if
		
	},'json')//post
x++;
//if 16 seconds have passed asstume there was an error
if(x==15){
	
$('#profile-submit-feedback').addClass('red').html('Error')
$('#image-loading').hide()
clearInterval(interval);	
return;
}		
},1000)//set interval
}//if mobileView	 	
	
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