$(document).ready(function(){

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
	
	$('#profile-pic-form').submit()
	

	$('#profile-pic-z').val('')
	
	})
})//ready
	
	
function finishProfileImage(feedback){

	if(feedback=='wrong z'){
		window.location = "../../../member-login.html";
		return;
	}//if

	if(feedback=='error'){
		 $('#profile-submit-feedback').addClass('red').html('That may not be<br> an image file.')
		 $('#image-loading').hide()
		 return;
	}//if
	
	$('#profile-pic-div').css('background-image','url('+feedback+')');
	$('#image-loading').hide()
	 $('#profile-submit-feedback').removeClass('red').html('')
	 
}//function