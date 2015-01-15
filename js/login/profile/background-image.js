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
	
	if(feedback=="wrong z"){
		
		window.location = "../../../member-login.html";
		return;
	}//if
	
	if(feedback=='not image'){
		$('#load-icon1').hide()
		$('#background-message').addClass('red').html('Not an image').show()
		return;
	}//if
	
	$('body').addClass('profile-background').css('background-image','url('+feedback+')')
	
	$('#load-icon1').hide()
	
	$('#background-img-z').val('')
}//function