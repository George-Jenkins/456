$(document).ready(function(){
	
	$('#group-activities-span').click(function(){
		
		$('#group-activities-span').hide()
		$('#hide-activities-span').show()
		
		$('#list-events-div').slideDown()
		$('#group-info-span').slideUp()
		
	})//click
	
	$('#hide-activities-span').click(function(){
		
		$('#group-activities-span').show()
		$('#hide-activities-span').hide()
		
		$('#list-events-div').slideUp()
		$('#group-info-span').slideDown()
		
	})//click
	
})