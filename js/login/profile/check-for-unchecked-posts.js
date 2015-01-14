$(document).ready(function(){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';		
	
	setInterval(function(){
	
	var z = getZ()
	
	$.post(postPath+'queries/check-for-unchecked-posts.php',{z:z},function(data){
		
		for(x=0;x<=data.numberOfLoops;x++){
			//get group id
			var group_id = 'group_id'+x;
			var groupID = data[group_id];
			
			//see if unchecked
			var unchecked_loop = 'unchecked'+x;
			var unchecked = data[unchecked_loop];
			
			if(unchecked==true) $('#groupID'+groupID).html('<img src="../../pics/new-message-icon.png"> ')
			else $('#groupID'+groupID).html('')
			
		}//for
		
	},'json')//post
	
	},1000)//setInterval
	
})//ready