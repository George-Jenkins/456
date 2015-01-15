//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
	
	$('#form').submit(function(e){
		
		e.preventDefault()
		
		$('#error-msg').html('').hide()
		$('.form-span').removeClass('red')
		$('#event-price-span').html('Price of event')
		$('#min-contr-span').html('Minimum contribution')
		eventPriceChecked = ''
		minAmountChecked = '';
		whoCanInvite = '';
		$('#loader').hide()
		
		errors = '';
		
		var eventName = $('#event-name').val()
		var startDate = $('#start-date').val()
		var endDate = $('#end-date').val()
		var eventDescription = $('#event-description').val()
		var eventPrice = $('#event-price').val()
			if($('#yes-raise-money').is(':checked')) eventPriceChecked = 'yes';
			if($('#no-raise-money').is(':checked')) eventPriceChecked = 'no';
		var minAmount = $('#min-amount').val()
			if($('#yes-min').is(':checked')) minAmountChecked = 'yes';
			if($('#no-min').is(':checked')) minAmountChecked = 'no';
		var collectionMethod = $('#collection-method').val()
			if($('#only-creator-invite').is(':checked')) whoCanInvite = 'false';
			if($('#friend-can-invite').is(':checked')) whoCanInvite = 'true';
		
		if(!eventName && !eventDescription && !eventPrice && !minAmount) return;
		
		//validate
		if(!eventName){
			$('#event-name-span').addClass('red')
			errors = true;
		}//if
		if(!startDate){
			$('#start-date-span').addClass('red')
			errors = true;	
		}
		if(!endDate){
			$('#end-date-span').addClass('red')
			errors = true;	
		}
		if(!eventDescription){
			$('#description-span').addClass('red')
			errors = true;
		}//if
		
		if(eventPriceChecked == 'yes' && !eventPrice){
			$('#event-price-span').addClass('red')
			errors = true;
		}//if
		
		if(!eventPriceChecked){
			$('#combine-money-span').addClass('red')
			errors = true;
		}//if
		
		if(eventPrice && (eventPrice*0)!=0){
			$('#event-price-span').addClass('red').html('Must be a number')
			errors = true;
		}//if
		
		if(minAmountChecked == 'yes' && !minAmount){
			$('#min-contr-span').addClass('red')
			errors = true;
		}//if
		
		if(eventPriceChecked == 'yes' && !minAmountChecked){
			$('#min-amount-span').addClass('red')
			errors = true
		}//if
		
		if(minAmount && (minAmount*0)!=0){
			$('#min-contr-span').addClass('red').html('Must be a number')
			errors = true;
		}//if
		if(eventPriceChecked=='yes' && !collectionMethod){
			$('#collection-method-span').addClass('red')
			errors = true;
		}//if
		if(!whoCanInvite){
			$('#who-can-invite-span').addClass('red')
			errors = true;
		}//if
		
		if(errors){
			$('#error-msg').html('Please correct errors.').show()
			
		}//if
		else{
			$('#loader').show()
			var z = getZ()
			$('#z').val(z)
			
			var formData = $('form').serialize()
			
			$.ajax({
				
			url:postPath+'queries/add-event.php',
			type:'POST',
			dataType:'json',
			data:formData,	
			cache:false,		
			success:function(data){
				if(data.error=='wrong z'){
					
					window.location = '/index.html';
				}//if
				
				if(data.done){
					window.location = "event.html?"+data.groupID;
				}//if
					
				},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				
			}
			})//ajax
			
		}//else
		
	})//submit
