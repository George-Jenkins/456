(function(){

document.addEventListener("deviceready", function(){
//cordova.plugins.notification.badge.set(3);

var z = getZ();	


if(!z) return;

var pushNotification;

// call this to get a new token each time. don't call it to reuse existing token.
//pushNotification.unregister(successHandler, errorHandler, options)

	//get platform
     var platform = navigator.platform
	 
	 pushNotification = window.plugins.pushNotification;
	  
	if (platform == 'ios' || platform == 'iPhone'){
    pushNotification.register(
    tokenHandler,
    errorHandler,
    {
        "badge":"true",
        "sound":"true",
        "alert":"true",
        "ecb":"onNotificationAPN"
    });
} else {
  
//pushNotification.unregister(successHandler, errorHandler)  
  
	pushNotification.register(
    successHandler,
    errorHandler,
    {
        "senderID":"429599383559",
        "ecb":"onNotification"
    });
}


// result contains any message sent from the plugin call
function successHandler (result) {
   // alert('result = ' + result);
}

function tokenHandler (result) {
	
	var device = 'ios';
    // Your iOS push server needs to know the token before it can push to this device
    // here is where you might want to send it the token for later use.
    if(z) $.post('http://ritzkey.com/queries/register-notification-key.php',{z:z, token:result, device:device},function(data){	
	},'json')
}

// result contains any error description text returned from the plugin call
function errorHandler (error) {
  //  alert('error = ' + error);
}

// iOS
function onNotificationAPN (event) {
	
    if ( event.alert )
    {
        navigator.notification.alert(event.alert);
    }

    if ( event.sound )
    {
        var snd = new Media(event.sound);
        snd.play();
    }

    if ( event.badge )
    {
		
        pushNotification.setApplicationIconBadgeNumber(successHandler, errorHandler, event.badge);
    }
}
/*
// Android and Amazon Fire OS
function onNotification(e) {
    
    switch( e.event )
    {
    case 'registered':
        if ( e.regid.length > 0 )
        {
            // Your GCM push server needs to know the regID before it can push to this device
            // here is where you might want to send it the regID for later use.
            //console.log("regID = " + e.regid);
			var device = 'android';
			if(z) $.post('http://ritzkey.com/queries/register-notification-key.php',{z:z, token:e.regid, device:device},function(data){
			},'json')
        }
    break;

    case 'message':
        // if this flag is set, this notification happened while we were in the foreground.
        // you might want to play a sound to get the user's attention, throw up a dialog, etc.
        if ( e.foreground )
        {
            //$("#app-status-ul").append('<li>--INLINE NOTIFICATION--' + '</li>');

            // on Android soundname is outside the payload.
            // On Amazon FireOS all custom attributes are contained within payload
            //var soundfile = e.soundname || e.payload.sound;
            // if the notification contains a soundname, play it.
            //var my_media = new Media("/android_asset/www/"+ soundfile);
            //my_media.play();
        }
        else
        {  // otherwise we were launched because the user touched a notification in the notification tray.
            if ( e.coldstart )
            {
                //$("#app-status-ul").append('<li>--COLDSTART NOTIFICATION--' + '</li>');
            }
            else
            {
                //$("#app-status-ul").append('<li>--BACKGROUND NOTIFICATION--' + '</li>');
            }
        }

       //$("#app-status-ul").append('<li>MESSAGE -> MSG: ' + e.payload.message + '</li>');
           //Only works for GCM
       //$("#app-status-ul").append('<li>MESSAGE -> MSGCNT: ' + e.payload.msgcnt + '</li>');
       //Only works on Amazon Fire OS
       //$status.append('<li>MESSAGE -> TIME: ' + e.payload.timeStamp + '</li>');
    break;

    case 'error':
        //$("#app-status-ul").append('<li>ERROR -> MSG:' + e.msg + '</li>');
    break;

    default:
       // $("#app-status-ul").append('<li>EVENT -> Unknown, an event was received and we do not know what it is</li>');
    break;
  }
}
*/



},true);//document.addEventListener


})()
