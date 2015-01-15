//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/notifications/';
else postPath = '';		
	
	var z = getZ()
	
	$.post(postPath+'queries/get-notifications.php',{z:z},function(data){
			
			$('#notifications-div').html(data.message)
		
	},'json')//post


function seenNotice(id){
	
	var z = getZ()
	
	$('#notice'+id).hide()
	$('#doneMessage'+id).html('Seen')
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/notifications/';
else postPath = '';		
	
	$.post(postPath+'queries/remove-notification.php',{z:z, id:id},function(data){
		
	},'json')//post
	
}//function

function approveMember(id,group){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/notifications/';
else postPath = '';		

	var z = getZ()

	$.post(postPath+'queries/approve-member.php',{z:z,approve_id:id,decision:'approve',groupID:group},function(data){
		
		if(data.error==false){
			 $('#approve-disapprove-table'+id).hide()
			 $('#approve-disapprove-decision-table'+id).show()
			 $('#approve-disapprove-msg'+id).html(data.msg) 
		}
	},'json')//post
	
}//function

function disapproveMember(id,group){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/notifications/';
else postPath = '';		
	
	var z = getZ()
	
	$.post(postPath+'queries/approve-member.php',{z:z,approve_id:id,decision:'disapprove',groupID:group},function(data){
		
		if(data.error==false){
			 $('#approve-disapprove-table'+id).hide()
			 $('#approve-disapprove-decision-table'+id).show()
			 $('#approve-disapprove-msg'+id).html(data.msg) 
		}
	},'json')//post
}//function