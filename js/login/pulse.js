var path = pathToRoot()

var z = getZ();

//these variables allow functions to start
goNotifications = true
goCheckReplies = true
goCheckLoggedIn = true

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

var path = pathToRoot()

var email = localStorage.getItem('userEmail')//I'll use email instead of z just so the app can get notifications if user not logged in
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	

//this stops function if post isn't done
if(goNotifications == false) return
goNotifications = false
	
	$.post(postPath+'login/check-for-notifications.php',{z:z},function(data){
		
		if(data.notifications==true) $('.notifications-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.notifications-alert').html('')
		
		goNotifications = true
		
	},'json')//post
	
}//function	
	
function checkForReplies(){

var path = pathToRoot()

var email = localStorage.getItem('userEmail')//I'll use email instead of z just so the app can get replies if user not logged in

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	

//this stops function if post isn't done
if(goCheckReplies == false) return
goCheckReplies = false
	
	$.post(postPath+'login/check-for-replies.php',{z:z},function(data){
		
		if(data.replies==true) $('.replies-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.replies-alert').html('')
		
		goCheckReplies = true
		
	},'json')//post
	
}//function		

function checkLoggedIn(){

var path = pathToRoot()
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	

//this stops function if post isn't done
if(goCheckLoggedIn == false) return
goCheckLoggedIn = false

var k = getK();
	
	$.post(postPath+'connect/handle.php',{z:z,k:k},function(data){
		
		if(data.error=='wrong z'){
		
		var url = document.location.href
		var page = url.split('login/')[1]
		sessionStorage.setItem('toLastPage','login/'+page);
		
		$('#dim-background').removeClass().show()
		$('#lightbox').removeClass().addClass('white-background').html("<p>Your aren't logged in. <a href='"+path+"member-login.html'>Click here.</a></p>");
		} 
		else goCheckLoggedIn = true
		
		
	},'json')//post
	
}//function
