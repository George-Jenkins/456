//this is path to post for apps
if(pathForPost){ 
postPath = 'http://ritzkey.com/login/group/';
var action = $('#upload-group-pic').attr('action')
$('#upload-group-pic').attr('action',postPath+action)
}//if	
	
	$('#group-pic').change(function(){
		
	//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
		
		//show loading icon
		$('#ajax-loader').show()
		$('#upload-pic-feedback').addClass('upload-loading-image').removeClass('red').html('').show()
	
		var z = getZ()
		$('#group-pic-z').val(z)
	
		//this will be used below. It's for apps
		var imageName = $('#group-image-div').css('background-image').split('/');
		imageName = imageName[imageName.length-1].split(')')[0];
	
		$('#upload-group-pic').submit()
		
		$('#group-pic-z').val('')
		
//if on app the db must be queried to see if image has been changed
if(mobileView){
	
var x = 0;
var interval = setInterval(function(){
	
	var z = getZ()	
	
	var group_id = getGroupID();
	$.post('http://ritzkey.com/login/group/queries/check-image-change-mobile.php',{z:z, imagePosition:'group',group_id:group_id},function(data){
		
		if(imageName != data.currentImage){
			
			$('#group-image-div').css('background-image','url(http://ritzkey.com/login/group/pics/'+data.folderName+'/'+data.currentImage+')')
	
			$('#ajax-loader').hide()
			$('#upload-pic-feedback').hide()	
			//clear z
			$('#group-pic-z').val('')
			clearInterval(interval);
			return;
		}//if
		
	},'json')//post
x++;
//if 16 seconds have passed asstume there was an error
if(x==15){
$('#upload-pic-feedback').removeClass('upload-loading-image').addClass('red').html('Error').show()
$('#ajax-loader').hide()
//clear z
$('#group-pic-z').val('')
clearInterval(interval);	
return;
}		
},1000)//set interval
}//if mobileView	 			
		
	})//change


function sendFeedback(feedback){
	
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';	
else postPath = '';	
	
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
		$('#group-image-div').css('background-image','url('+postPath+feedback+')')
		$('#ajax-loader').hide()
		$('#upload-pic-feedback').hide()	

	
}//function