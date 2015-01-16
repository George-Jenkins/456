//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
	
	var z = getZ()
	var eventID = getEventID()
	
	$.post(postPath+'queries/load-groups-for-invite.php',{z:z, eventID:eventID},function(data){
		
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
		
if(pathForPost) data.groupInfo = data.groupInfo.replace(/background-image:url\(/g,'background-image:url('+postPath)		
		
		$('#group-display-list').html(data.groupInfo);
		
	},'json')//post
