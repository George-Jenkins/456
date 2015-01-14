$(document).ready(function(){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
	
	var z = getZ()
	var eventID = getEventID()
	
	$.post(postPath+'queries/load-groups-for-invite.php',{z:z, eventID:eventID},function(data){
		
		$('#group-display-list').html(data.groupInfo);
		
	},'json')//post
	
})