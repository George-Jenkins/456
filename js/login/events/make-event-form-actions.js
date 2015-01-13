$(document).ready(function(){
	
	//handle date picker
	$('#start-date').datepicker({minDate:0})
	$('#end-date').datepicker({minDate:0})
	
	$('#yes-raise-money').click(function(){
		
		$('#price-of-event').show()
		$('#is-there-min').show()
		$('#collection-method-tr').show()
		
		$('#combine-money-span').removeClass('red')
		$('body, html').animate({scrollTop:$('#price-of-event').offset().top},300)
	})//click
	
	$('#no-raise-money').click(function(){
		
		$('#price-of-event').hide()
		$('#is-there-min').hide()
		$('#min-amount-td').hide()
		$('#collection-method-tr').hide()
		
		$('#event-price').val('')
		$('#min-amount').val('')
		$('#collection-method').val('')
		$('#yes-min').attr('checked',false)
		$('#no-min').attr('checked',false)
		$('#event-price-span').removeClass('red').html('Price of event')
		$('#min-contr-span').removeClass('red').html('Minimum contribution')
		$('#combine-money-span').removeClass('red')
		$('#min-amount-span').removeClass('red')
		$('#collection-method-span').removeClass('red')
	})//click
	
	$('#yes-min').click(function(){
		
		$('#min-amount-td').show()
		$('#min-amount-span').removeClass('red')
		$('body, html').animate({scrollTop:$('#min-amount-td').offset().top},300)
	})//click
	
	$('#no-min').click(function(){
		
		$('#min-amount-td').hide()
		
		$('#min-amount').val('')
		$('#min-amount-span').removeClass('red')
		$('#min-contr-span').removeClass('red').html('Minimum contribution')
	})//click
	
	$('input[name="who-can-invite"]').click(function(){
		$('#who-can-invite-span').removeClass('red')
	})//click
	
	//add options to start and end times
	var startHour;
	for(hour=1;hour<=12;hour++){
		startHour += "<option value='"+hour+"'>"+hour+"</option>"; 
	}//for
	$('#start-hour').html(startHour);
	
	var startMinute;
	for(minute=0;minute<=59;minute++){
		if(minute<10) minute = 0+""+minute;
		startMinute += "<option value='"+minute+"'>"+minute+"</option>"; 
	}//for
	$('#start-minute').html(startMinute);
	$('#start-am-pm').html("<option value='am'>am</option><option value='pm'>pm</option>")
	
	var endHour;
	for(hour=1;hour<=12;hour++){
		endHour += "<option value='"+hour+"'>"+hour+"</option>"; 
	}//for
	$('#end-hour').html(startHour);
	
	var endMinute;
	for(minute=0;minute<=59;minute++){
		if(minute<10) minute = 0+""+minute;
		endMinute += "<option value='"+minute+"'>"+minute+"</option>"; 
	}//for
	$('#end-minute').html(endMinute);
	$('#end-am-pm').html("<option value='am'>am</option><option value='pm'>pm</option>")
})