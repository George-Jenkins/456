//this document is really just for mobile apps

var path = pathToRoot()

var z = getZ();

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
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	

//this stops function if post isn't done
if(goNotifications == false) return
goNotifications = false
	
	$.post(postPath+'login/check-for-notifications.php',{z:z},function(data){
		
		goNotifications = true
		
		numberOfNotifications = data.numberOfNotifications//this is for mobile notifications
		
	},'json')//post
	
}//function	
	
function checkForReplies(){

var path = pathToRoot()

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	

//this stops function if post isn't done
if(goCheckReplies == false) return
goCheckReplies = false
	
	$.post(postPath+'login/check-for-replies.php',{z:z},function(data){
		
		goCheckReplies = true
		
		numberOfReplies = data.numberOfReplies//this is for mobile notifications
		unreadPosts = data.unreadPosts //this is for mobile notifications too
	},'json')//post
	
}//function		

