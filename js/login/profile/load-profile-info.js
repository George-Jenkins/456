//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';		
	
	var z = getZ();

	//profile pic
	$.post(postPath+'queries/load-profile/load-profile.php',{z:z},function(data){
		
		
	//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';		
		
		if(data){
		
		
		
	}//if data
	
	},'json')//post
	