$(document).ready(function(){
	
	var z = getZ()
	var eventID = getEventID()
	
	$.post('queries/load-event.php',{z:z, eventID:eventID},function(data){
		
		if(data.error == 'wrong z') window.location = "/member-login.html";
		
		if(data.error == 'not event') window.location = "../profile/profile.html";
		
		if(data.creator){
		window.location = "event.html?"+eventID;//if this is creator send user to event.html
		return;
		}
		
		if(data.done){
			
		$('#event-name').html(data.eventName)
		$('#organizer-div').html(data.creatorInfo)
		$('#event-image').css('background-image','url('+data.eventImg+')')
		$('#event-description').html(data.description)
		$('#event-start-span').html(data.eventStart)
		$('#event-end-span').html(data.eventEnd)
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
			$('#publish-event-span').hide()
			$('#cancel-event-span').show()
			$('#publish-instructions').html('')
		}//if
		else{
			$('#publish-instructions').html("Click \"Invite entourages\" to invite your friends. When you're ready, click publish event to make your activity live.")
		}//else
		
		if(data.attending){
			$('#attend-button').attr('value','Attending!')
			$('#attend-event-div').show()
			$('#leave-event-button-span').show()
		}//if 
		
		}//if
		
	},'json')//post
	
})

