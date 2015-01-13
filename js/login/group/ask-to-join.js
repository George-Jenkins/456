$(document).ready(function(){
	
	$('#ask-to-join-button').click(function(){
		
		if(!getZ()) window.location = "/member-login.html";
		
		$('#awaiting-approval').show()
		$('#ask-to-join-button').hide()
		
		var group = getGroupID()
		var z = getZ()
		
		$.post('queries/ask-to-join.php',{z:z, group:group},function(data){
			
			if(data.error=='wrong z') window.location = "/member-login.html";
			
		},'json')//post
		
	})//click
	
})