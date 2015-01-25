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