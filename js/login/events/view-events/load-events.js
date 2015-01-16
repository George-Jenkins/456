//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
	
	var z = getZ()
	
	$.post(postPath+'queries/view-events/load-events.php',{z:z},function(data){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';	
		
		$('#loader1').hide()
		
		if(pathForPost) data.Events = data.Events.replace(/background-image:url\(/g,'background-image:url('+postPath)
		
		$('#load-events-div').html(data.Events).show()
		
	},'json')
