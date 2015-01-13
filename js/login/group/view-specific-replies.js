function viewSpecificPost(){
			
	url = document.location.href
	urlArray = url.split('&')
	id = urlArray[1];
	
	sessionStorage.setItem('specificReplies','true');
	
	if(id){
		
		$('#chat-send').hide()
		
	var z = getZ()
	//set checked to true
	$.post('queries/update-checked.php',{id:id, z:z},function(data){
		
		if(data.done){
			
		}//if
		
	},'json')//post
	
	
	}//if
	
}//function
