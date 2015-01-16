var k = getK();
var z = getZ()
	
var path = pathToRoot()
		
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;
		
	
if(!getI()) window.location = postPath+'member-login.html';
	
$.post(postPath+'connect/handle.php', {z:z,k:k}, function(data){
	
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	
		
if(data.error=='wrong z') window.location = postPath+'member-login.html';
			
},'json')//post
