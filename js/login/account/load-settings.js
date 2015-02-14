//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/account/';
else postPath = '';
	
	var z = getZ()
	
	$.post(postPath+'queries/load-settings.php',{z:z},function(data){

		$('#loader2').hide()
		
		$('#settings-div').show(function(){
		
		$('#change-name').val(data.userName)
			
		$('#change-email').val(data.userEmail)
		
		if(data.posts_email_setting=='true') $('#posts-email-yes').attr('checked',true)
		else $('#posts-email-no').attr('checked',true)
		
		if(data.reply_email_setting=='true') $('#reply-email-yes').attr('checked',true)
		else $('#reply-email-no').attr('checked',true)
		
		if(data.notification_email_setting=='true') $('#notification-email-yes').attr('checked',true)
		else $('#notification-email-no').attr('checked',true)
		
		$('#select-timezone').val(data.timezone)
			
		})//show
	
},'json')//post
	
