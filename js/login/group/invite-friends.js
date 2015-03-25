(function(){
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	
	
	var group = getGroupID()
	var z = getZ()
	
	//create invite code
	$.post(postPath+'queries/create-invite.php',{z:z,group:group},function(data){
		
		//load link in text form
	$('#invite-code').val('RitzKey.com/invitation.php?ic='+data.code)
		
	},'json')//post
	//end create invite code
	
	//start handling options
	$('#invite-members-title').click(function(){
		
		$('#invite-members-title').hide()
		$('#invite-members-cancel').show()
		$('#members').slideUp()
		$('#invite-members-instructions').slideDown()
	})//click
	
	$('#invite-members-cancel').click(function(){
		
		$('#invite-members-title').show()
		$('#invite-members-cancel').hide()
		$('#members').slideDown()
		$('#invite-members-instructions').slideUp()
		
	})//click
	
	
	$('#invite-code').click(function(){
		$(this).select(); 
	})
})();