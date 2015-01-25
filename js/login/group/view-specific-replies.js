function viewSpecificPost(){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	
			
	url = document.location.href
	urlArray = url.split('&')
	id = urlArray[1];
	
	sessionStorage.setItem('specificReplies','true');
	
	if(id){
		
		$('#chat-send').hide()
		
	var z = getZ()
	//set checked to true
	$.post(postPath+'queries/update-checked.php',{id:id, z:z},function(data){
		
		if(data.done){
			
		}//if
		
	},'json')//post
	
	
	}//if
	
}//function
