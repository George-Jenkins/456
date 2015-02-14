$('#attend-button').click(function(){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
		
		if($('#attend-button').attr('value')=='Attending!') $('#leave-event-button-span').hide()
		
		var price = $('#price-span').html() 
		
		if(price){
			
			 $('#contribute-money-span').show()
			 $('#submit-contribution').show()
			 $('#attend-button').hide()
			 
			
		}//if price
		else{
			$('#loader8').removeClass('hide')
			
			var z = getZ()
			var eventID = getEventID()
		
			$.post(postPath+'queries/attend-event.php',{z:z, eventID:eventID},function(data){
				
			$('#loader8').removeClass('hide')
			$('#attend-button').attr('value','Attending!')
			$('#leave-event-button-span').show()
			
			},'json')
			
		}
	})//click attend button
	
	$('#submit-contribution').click(function(){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
		
		var contribution = $('#contribute-money').val()
		
		if(!contribution) return;
		
		$('#loader3').removeClass('hide')
		
		if(contribution*0!=0){
			$('#contribute-money').addClass('red').val('Numbers only')
			$('#loader3').addClass('hide')
			return;
		}//if
		
		$('#leave-event-button-span').hide()
		
		var z = getZ()
		var eventID = getEventID()
		
		$.post(postPath+'queries/attend-event.php',{z:z, eventID:eventID, contribution:contribution},function(data){
			
			$('#loader3').addClass('hide')
			
			var path = pathToRoot();
			
			if(data.error == 'wrong z') window.location = path+"member-login.html";
			
			if(data.error == 'wrong event') window.location = "../profile/profile.html";
			
			if(data.expired){
				$('#dim-background').removeClass().show()
				$('#lightbox').removeClass().addClass('white-background').html("<p>This event has expired.</p>");
				$('.close').show()
			}
			
			if(data.error == 'not enough') $('#contribute-money').addClass('red').val('Not enough')
			
			if(data.done){
				
				$('#attend-button').attr('value','Attending!')
			
			 $('#contribute-money-span').hide()
			 $('#contribute-money').val('')
			 $('#submit-contribution').hide()
			 $('#attend-button').show()
			 $('#contribution-by-user-span').html('$'+contribution)
			 $('#leave-event-button-span').show()
			 
			 //tell them that they have to give money to person
			$('#dim-background').removeClass().show()
			$('#lightbox').removeClass().addClass('white-background').html("<p>You are now attending but just so you know RitzKey.com currently isn't collecting money online so you'll physically have to make sure your money gets to the activity organizer</p>");
			$('.close').show()
			
			}//if
			
		},'json')//post
		
	})//click 
	
	//if input wasn't a number
	$('#contribute-money').click(function(){
		if($('#contribute-money').hasClass('red')) $('#contribute-money').removeClass('red').val('')
	})//click
	
	$('#cancel-contribute-price').click(function(){
			
			$('#loader3').addClass('hide')
			
			 $('#contribute-money-span').hide()
			 $('#contribute-money').val('')
			 
			 $('#submit-contribution').hide()
			 $('#attend-button').show()
			if($('#attend-button').attr('value')=='Attending!') $('#leave-event-button-span').show()//only show if user is attending
			
			
	})//click cancel-contribute-price
