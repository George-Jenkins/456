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
	
	//this will be used below. It's for apps
	var imageName = $('#event-image').css('background-image').split('/');
	imageName = imageName[imageName.length-1].split(')')[0];
	
	$('#change-image-form').submit()

//if on app the db must be queried to see if image has been changed
if(mobileView){
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
}//if mobileView
	
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