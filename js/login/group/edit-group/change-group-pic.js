$(document).ready(function(){
	
	$('#group-pic').change(function(){
		
	//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
		
		//show loading icon
		$('#ajax-loader').show()
		$('#upload-pic-feedback').addClass('upload-loading-image').removeClass('red').html('').show()
	
		var z = getZ()
		$('#group-pic-z').val(z)
	
		$('#upload-group-pic').submit()
		
		$('#group-pic-z').val('')
	})//change
})//ready


function sendFeedback(feedback){
	
	//clear z
	$('#group-pic-z').val('')
	
	//redirect if wrong z
	if(feedback=='wrong z'){
		window.location = "../../../member-login.html";
		return;
	}//if
	
	//if error
	if(feedback=='error'){
		 $('#upload-pic-feedback').removeClass('upload-loading-image').addClass('red').html('Not an image file').show()
		 $('#ajax-loader').hide()
		 return;
	}
	
	//if group doesn't exist
	if(feedback=='wrong group'){
		window.location = "../../profile/profile";
		return;
	}
		$('#group-image-div').css('background-image','url('+feedback+')')
		$('#ajax-loader').hide()
		$('#upload-pic-feedback').hide()	

	
}//function