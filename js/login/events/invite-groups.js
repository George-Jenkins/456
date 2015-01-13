$(document).ready(function(){
	
	$('#invite-entourage-span').click(function(){
		$('#dark-background-for-event').show()
		$('#select-groups-container').slideDown()
	})//click
	
	$('#done-adding-groups-button').click(function(){
		
		var groupString = '';
		//this goes through all the checkmarks to see which were selected
		$('.check-group').each(function(){
			if($(this).hasClass('shown')){
								
				var id = $(this).attr('id')	
				var group = id.replace(/check-mark/g,'')
				groupString += group+'---'
			}//if
		})//
		
		$('#loader6').show()
		
		var z = getZ()
		var eventID = getEventID()
		
		$.post('queries/invite-groups.php',{z:z, eventID:eventID, groupString:groupString},function(data){
			
			doneInviting()
			
		},'json')//post
		
	})//click
	
function doneInviting(){
	$('#loader6').hide()
	$('#dark-background-for-event').hide()
	$('#select-groups-container').hide()
}
	
})//document ready

function selectGroup(x){
	if(!$('#check-mark'+x).hasClass('shown')) $('#check-mark'+x).addClass('shown').show()
	else $('#check-mark'+x).removeClass('shown').hide()
}//function