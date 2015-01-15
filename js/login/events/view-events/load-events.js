//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
	
	var z = getZ()
	
	$.post(postPath+'queries/view-events/load-events.php',{z:z},function(data){
		
		$('#loader1').hide()
		
		$('#load-events-div').html(data.Events).show()
		
	},'json')
