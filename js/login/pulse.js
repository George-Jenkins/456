var path = pathToRoot()

var z = getZ();

//start functions	
checkForNotifications()
checkForReplies()

//repeat functions
setInterval(function(){

checkForNotifications()
checkForReplies()
checkLoggedIn()	
},1000)//setinterval	

//end repeat functions

//creat functions
function checkForNotifications(){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	
	
	$.post(postPath+'login/check-for-notifications.php',{z:z},function(data){
		
		if(data.notifications==true) $('.notifications-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.notifications-alert').html('')
	},'json')//post
	
}//function	
	
function checkForReplies(){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	
	
	$.post(postPath+'login/check-for-replies.php',{z:z},function(data){
		
		if(data.replies==true) $('.replies-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.replies-alert').html('')
		
	},'json')//post
	
}//function		

function checkLoggedIn(){
	
	//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	

var k = getK();
	
	$.post(postPath+'connect/handle.php',{z:z,k:k},function(data){
		
		if(data.error=='wrong z'){
		
		$('#dim-background').removeClass().show()
		$('#lightbox').removeClass().addClass('white-background').html("<p>Your aren't logged in. <a href='"+path+"member-login.html'>Click here.</a></p>");
		$('.close').show()
			
		} 
		
	},'json')//post
	
}//function
