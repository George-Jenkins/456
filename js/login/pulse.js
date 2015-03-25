var path = pathToRoot()

var z = getZ();

//these variables allow functions to start
platform = navigator.platform;
badge = 0;
goNotifications = true
goCheckReplies = true
goCheckLoggedIn = true


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

checkLoggedIn()	


},1000)//setinterval	
}//notificationsAndRepliesFunction
notificationsAndRepliesFunction()
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
		
		var path = pathToRoot()
		
		//make sure there is nothing in .notifications-alert first
		var notifContent = $('.notifications-alert').html()
		
		if(data.notifications==true && !notifContent) $('.notifications-alert').html('<img src="'+path+'pics/new-message-icon.png">')
		if(data.notifications!=true) $('.notifications-alert').html('')
		
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
		
		var path = pathToRoot()
		
		//make sure nothing is in .replies-alert first
		var replyContent = $('.replies-alert').html()
		
		if(data.replies==true && !replyContent) $('.replies-alert').html('<img src="'+path+'pics/new-message-icon.png">')
		if(data.replies!=true) $('.replies-alert').html('')
		
		badge += data.numberOfReplies + data.numberOfPosts;
		
		goCheckReplies = true
		
deferred.resolve()
		
	},'json')//post
	
return deferred.promise()		

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

//detect user inactivity
if(mobileView) detectInactivity()
