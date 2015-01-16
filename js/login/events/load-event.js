//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
	
	var z = getZ()
	var eventID = getEventID()
	
	$.post(postPath+'queries/load-event.php',{z:z, eventID:eventID},function(data){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
		
		if(data.error == 'wrong z') window.location = "/member-login.html";
		
		if(data.error == 'not event') window.location = "../profile/profile.html";
		
		if(!data.creator){ 
		window.location = "event-view.html?"+eventID;//if this isn't creator send user to event.html
		return;
		}
		if(data.done){
			
		$('#event-name').html(data.eventName)
		
		if(postPath) data.creatorInfo = data.creatorInfo.replace(/background-image:url\(/g,'background-image:url('+postPath);
		$('#organizer-div').html(data.creatorInfo)
		
		$('#event-image').css('background-image','url('+postPath+data.eventImg+')')
		$('#event-description').html(data.description)
		$('#event-start-span').html(data.eventStart)
		$('#event-end-span').html(data.eventEnd)
		
		if(postPath && data.attendees) data.attendees = data.attendees.replace(/background-image:url\(/g,'background-image:url('+postPath);
		if(data.attendees) $('#list-attendees').html(data.attendees)
		
		if(data.invite==true) $('#invite-entourage-span').show()
		
		if(data.eventPrice!='$0'){
			$('#price-span').html(data.eventPrice)
			$('#amount-contributed-span').html(data.amountContr)
			$('#min-contribution-span').html(data.minContr)
			$('#contribution-by-user-span').html('$'+data.userContribution)
			$('#collection-method').html(data.collectionMethod)
			
			//show the divs that partain to price
			$('#description-div, #collection-method-hr, #collection-method-div, #price-div, #amount-contributed-div, #min-contribution-div, #contribution-by-user-div').show()
		}//if
		
		if(data.active=='true'){
			$('#attend-event-div').show()
			$('#cancel-event-span').show()
			$('#publish-instructions').html('')
		}//if
		else{
			$('#publish-event-span').show()
			$('#publish-instructions').html("Click \"Invite entourages\" to invite your friends. When you're ready, click publish event to make your activity live.")
		}//else
		
		if(data.attending){
			$('#attend-button').attr('value','Attending!')
			$('#attend-event-div').show()
			$('#leave-event-button-span').show()
		}//if 
		
		}//if
		
	},'json')//post
