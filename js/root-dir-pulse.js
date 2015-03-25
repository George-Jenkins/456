var path = pathToRoot()

var z = getZ();

if(mobileView){

//these variables allow functions to start
platform = navigator.platform;
badge = 0;
goNotifications = true
goCheckReplies = true

//start functions	
checkForNotifications().done(function(){

	checkForReplies().done(function(){

		//set badge
		if(mobileView && platform=='iPhone') cordova.plugins.notification.badge.set(badge);
		badge = 0;//reset badge
		
	})//checkForReplies done
	
})//checkForNotifications done

//repeat functions
notificationsAndRepliesFunction = function(){
notificationsAndRepliesInterval = setInterval(function(){

checkForNotifications().done(function(){

	checkForReplies().done(function(){

		//set badge
		if(mobileView && platform=='iPhone') cordova.plugins.notification.badge.set(badge);
		badge = 0;//reset badge
		
	})//checkForReplies done
	
})//checkForNotifications done

},1000)//setinterval	
}//notificationsAndRepliesFunction
notificationsAndRepliesFunction()
//pause if user is inactivity
detectInactivity(notificationsAndRepliesInterval,notificationsAndRepliesFunction)
//end repeat functions

//create functions
function checkForNotifications(){

var path = pathToRoot()

var email = localStorage.getItem('userEmail')//I'll use email instead of z just so the app can get notifications if user not logged in

var deferred = new $.Deferred()
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	

//this stops function if post isn't done
if(goNotifications == false) return
goNotifications = false
	
	$.post(postPath+'login/check-for-notifications.php',{email:email},function(data){
		
		if(data.notifications==true) $('.notifications-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.notifications-alert').html('')
		
		badge += data.numberOfNotifications; //this for mobile
		
		goNotifications = true
		
deferred.resolve()

	},'json')//post
	
return deferred.promise();
	
}//function	
	
function checkForReplies(){

var path = pathToRoot()

var email = localStorage.getItem('userEmail')//I'll use email instead of z just so the app can get notifications if user not logged in

var deferred = new $.Deferred()

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	

//this stops function if post isn't done
if(goCheckReplies == false) return
goCheckReplies = false
	
	$.post(postPath+'login/check-for-replies.php',{email:email},function(data){
		
		if(data.replies==true) $('.replies-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.replies-alert').html('')
		
		badge += data.numberOfReplies + data.numberOfPosts;
		
		goCheckReplies = true
		
deferred.resolve()
		
	},'json')//post
	
return deferred.promise()		

}//function		

}//if mobileView

//detect user inactivity
if(mobileView) detectInactivity()