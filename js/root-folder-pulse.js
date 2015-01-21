//this document is really just for mobile apps
document.addEventListener('deviceready', function () {

//cordova.plugins.notification.badge.promptForPermission();//ask for permission
var path = pathToRoot()

var z = getZ(); //must say if because script won't run if not logged in

//these variables allow functions to start
goNotifications = true
goCheckReplies = true
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
		
		goCheckReplies = true
		
		numberOfReplies = data.numberOfReplies//this is for mobile notifications
		unreadPosts = data.unreadPosts //this is for mobile notifications too
	},'json')//post
	
}//function		

}, false);