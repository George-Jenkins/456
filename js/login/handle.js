$(document).ready(function(){
		
	if(!getI()) window.location = '/member-login.html';
		
	var k = getK();
	var z = getZ()
	
	var path = pathToRoot()
		
	$.post(path+'connect/handle.php', {z:z,k:k}, function(data){
		
		if(data.error=='wrong z') window.location = '/member-login.html';
			
	},'json')//post
	

	
})//ready