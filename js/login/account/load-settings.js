//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/account/';
else postPath = '';
	
	var z = getZ()
	
	$.post(postPath+'queries/load-settings.php',{z:z},function(data){
		
		$('#loader2').hide()
		
		$('#settings-div').show(function(){
			
		$('#change-email').val(data.userEmail)
		
		if(data.reply_email_setting=='true') $('#reply-email-yes').attr('checked',true)
		else $('#reply-email-no').attr('checked',true)
			
		})//show
	
},'json')//post
	
