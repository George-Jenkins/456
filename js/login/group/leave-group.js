$(document).ready(function(){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	
	
	$('#leave-group-button').click(function(){
		
		$('#leave-group-options').show()
		$('#leave-group-button').hide()
	})//click
	
	$('#no-leave').click(function(){
		
		$('#leave-group-options').hide()
		$('#leave-group-button').show()
	})//click
	
	$('#yes-leave').click(function(){
		
		var z = getZ()
		var group = getGroupID()
		
		$.post(postPath+'queries/leave-group/leave-group.php',{z:z, group:group},function(data){
			
			if(data.error == 'wrong z') window.location = "/index.html";
			
			if(data.done){
				
				$('#leave-group-options').hide()
				window.location = "../profile/profile.html";
			}//if
			
		},'json')//post
		
	})//click
})