$('#leave-button').click(function(){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
		
		var z = getZ()
		var eventID = getEventID()
	
		$('#loader7').show()
		
		$.post(postPath+'queries/leave-event.php',{z:z, eventID:eventID},function(data){
			
			$('#loader7').hide()
			
			if(data.error == 'wrong z') window.location = "/member-login.html";
			
			if(data.error == 'wrong event') window.location = "../profile/profile.html";
			
			
			if(data.done){
				
				$('#attend-button').show()
				$('#submit-contribution').hide()
				$('#attend-button').attr('value','Attend Event');
				$('#leave-event-button-span').hide()
				$('#contribution-by-user-span').html('$0')
			}//if
			
		},'json')//post
		
	})//click 
