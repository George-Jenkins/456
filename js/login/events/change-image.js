//this is path to post for apps
if(pathForPost){ 
postPath = 'http://ritzkey.com/login/events/';
var action = $('#change-image-form').attr('action')
$('#change-image-form').attr('action',postPath+action)
}//if

$('#change-image').change(function(){
	
	$('#error-msg1').html('').hide()
	
	$('#change-image-loader').show()
	
	var z = getZ()
	var eventID = getEventID()
	
	$('#eventZ').val(z)
	$('#imageEventID').val(eventID)
	
	$('#change-image-form').submit()
	
})//change	
	

function sendFeedback(feedback){
	
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
	
	$('#event-image').css('background-image','url('+feedback+')')
	
}//function