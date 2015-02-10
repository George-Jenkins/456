
	//publish event
	$('#publish-event-button').click(function(){
		
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';		
		
		$('#loader4').show()
		
		var z = getZ()
		var eventID = getEventID()
		
		$.post(postPath+'queries/publish-event.php',{z:z, eventID:eventID},function(data){
			
			$('#loader4').hide()
			
			if(data.error == 'wrong z') window.location = "/member-login.html";
			
			if(data.error == 'not creator') window.location = "../profile/profile.html";
			
			if(data.done){
				
				$('#attend-event-div').show()
				$('#publish-event-span').hide()
				$('#cancel-event-span').show()
				$('#publish-instructions').html('')
				
			}//if
			
		},'json')//post
		
	})//click
	
	//cancel event
	$('#cancel-event-button').click(function(){
		
		$('#cancel-event-table').css('display','inline-block').show()
		$('#cancel-event-button').hide()
	})//cancel
	$('#no-cancel-event').click(function(){
		$('#cancel-event-table').hide()
		$('#cancel-event-button').show()
	})//click
	
	$('#yes-cancel-event').click(function(){
		
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';			
		
	$('#loader5').show()
		
		var z = getZ()
		var eventID = getEventID()
		
		$.post(postPath+'queries/cancel-event.php',{z:z, eventID:eventID},function(data){
			
			$('#loader5').hide()
			
			if(data.error == 'wrong z') window.location = "/member-login.html";
			
			if(data.error == 'not creator') window.location = "../profile/profile.html";
			
			if(data.done){
				
				window.location = "view-events.html";
				
			}//if
			
		},'json')//post	
		
	})//click


