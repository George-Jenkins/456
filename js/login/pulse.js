var path = pathToRoot()

var z = getZ();

//these variables allow functions to start
goNotifications = true
goCheckReplies = true
goCheckLoggedIn = true
//these variables are used when updating badge number
var badgeNumber=0;
var previousNumber=0;
//start functions	
checkForNotifications()
checkForReplies()

//repeat functions
setInterval(function(){

checkForNotifications()
checkForReplies()

if(navigator.notification){//this is true if this is an app
cordova.plugins.backgroundMode.enable();//enable js running in background
//handle badge number
previousNumber = badgeNumber//this number is badge number before it is updated
badgeNumber = (numberOfNotifications*1) + (numberOfReplies*1) + (unreadPosts*1)//this is where badge number will be determined
cordova.plugins.notification.badge.set(badgeNumber);//this is where badge number will be set
//(for now I'll have no vibration) if(badgeNumber>previousNumber) navigator.notification.vibrate(1000);//vibrate if badge number increases
}//if navigator.notification

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
	
	$.post(postPath+'login/check-for-notifications.php',{email:email},function(data){
		
		if(data.notifications==true) $('.notifications-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.notifications-alert').html('')
		
		goNotifications = true
		
		numberOfNotifications = data.numberOfNotifications//this is for mobile notifications
		
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
	
	$.post(postPath+'login/check-for-replies.php',{email:email},function(data){
		
		if(data.replies==true) $('.replies-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.replies-alert').html('')
		
		goCheckReplies = true
		
		numberOfReplies = data.numberOfReplies//this is for mobile notifications
		unreadPosts = data.unreadPosts //this is for mobile notifications too
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
