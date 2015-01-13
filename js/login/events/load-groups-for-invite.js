$(document).ready(function(){
	
	var z = getZ()
	var eventID = getEventID()
	
	$.post('queries/load-groups-for-invite.php',{z:z, eventID:eventID},function(data){
		
		$('#group-display-list').html(data.groupInfo);
		
	},'json')//post
	
})