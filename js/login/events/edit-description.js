$(document).ready(function(){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';		
	
	$('#save-description').click(function(){
	
	$('#loader2').show()
	
	var z = getZ()
	var description = $('#edit-description-textarea').val()
	var eventID = getEventID()
	
	$.post(postPath+'queries/submit-description.php',{z:z, eventID:eventID, description:description},function(data){
		$('#loader2').hide()
		
		$('#event-description').html(data.description)
		//hide textarea
		$('#edit-description-div').hide()
		$('#edit-description-textarea').val('')
		//show actual description
		$('#event-description').show()
		//handle links
		$('#edit-description-td').show()
		$('#cancel-description-td').hide()
		$('#save-description-td').hide()
	},'json')//post
	})//click
	
	$('#edit-description-td').click(function(){
		
		var currentDescription = $('#event-description').html()
		currentDescription = currentDescription.trim()
		//show textarea
		$('#edit-description-div').show()
		$('#edit-description-textarea').val(currentDescription.replace(/<br\s*\/?>/g, ''))
    
		//hide actual description
		$('#event-description').hide()
		//handle links
		$('#edit-description-td').hide()
		$('#cancel-description-td').show()
		$('#save-description-td').show()
	})//click
	
	$('#cancel-description-span').click(function(){
		
		var currentDescription = $('#event-description').html()
		//hide textarea
		$('#edit-description-div').hide()
		$('#edit-description-textarea').val('')
		//show actual description
		$('#event-description').show()
		//handle links
		$('#edit-description-td').show()
		$('#cancel-description-td').hide()
		$('#save-description-td').hide()
		$('#loader2').hide()
	})//click
})