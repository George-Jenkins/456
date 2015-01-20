function yes_delete(id,time){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	
	
	//to stop wall from updating while deleting
	$('#chat-wall').addClass('deleting')
	
	//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
	
		var z = getZ();
	
	$('#post'+id).slideUp(function(){
	$('#post'+id).remove()	
	})/*the class deleted tells the slide down function no to slide it down again*/
	
	
	$.post(postPath+'queries/delete-post.php',{z:z,id:id,time:time},function(data){
		
		//redirect if wrong z
			if(data.error=='wrong z'){
				window.location = "../../member-login.html";
				return;
			}//if
		
		if(data.done=='done'){
			
			$('#chat-wall').removeClass('deleting')
		}//if	
	},'json')//post
	
}//yes_delete function

function no_delete(id){
	
	$('#delete-yes-no'+id).hide()
	$('#delete-show'+id).show()
	
}//no_delete function

function show_delete(id){
	
	$('#delete-yes-no'+id).show()
	$('#delete-show'+id).hide()
	
}//show_delete

