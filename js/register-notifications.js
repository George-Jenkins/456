//This document isn't doing anything as for as I know

var pushNotification;

document.addEventListener("deviceready", function(){
	
	//get platform
     var platform = navigator.platform
	 
	 pushNotification = window.plugins.pushNotification;
	  
	 if (platform == 'android' || platform == 'Android' || platform == "amazon-fireos" ){
    pushNotification.register(
    successHandler,
    errorHandler,
    {
        "senderID":"21900408987",
        "ecb":"onNotification"
    });
} else if (platform == 'blackberry10' || platform == 'BlackBerry'){
    pushNotification.register(
    successHandler,
    errorHandler,
    {
        invokeTargetId : "replace_with_invoke_target_id",
        appId: "replace_with_app_id",
        ppgUrl:"replace_with_ppg_url", //remove for BES pushes
        ecb: "pushNotificationHandler",
        simChangeCallback: replace_with_simChange_callback,
        pushTransportReadyCallback: replace_with_pushTransportReady_callback,
        launchApplicationOnPush: true
    });
} else {
    pushNotification.register(
    tokenHandler,
    errorHandler,
    {
        "badge":"true",
        "sound":"true",
        "alert":"true",
        "ecb":"onNotificationAPN"
    });
} 


// result contains any message sent from the plugin call
function successHandler (result) {
   // alert('result = ' + result);
}

function tokenHandler (result) {
    // Your iOS push server needs to know the token before it can push to this device
    // here is where you might want to send it the token for later use.
    //alert('device token = ' + result);
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

// Android and Amazon Fire OS
function onNotification(e) {
    $("#app-status-ul").append('<li>EVENT -> RECEIVED:' + e.event + '</li>');

    switch( e.event )
    {
    case 'registered':
        if ( e.regid.length > 0 )
        {
            $("#app-status-ul").append('<li>REGISTERED -> REGID:' + e.regid + "</li>");
            // Your GCM push server needs to know the regID before it can push to this device
            // here is where you might want to send it the regID for later use.
            console.log("regID = " + e.regid);
        }
    break;

    case 'message':
        // if this flag is set, this notification happened while we were in the foreground.
        // you might want to play a sound to get the user's attention, throw up a dialog, etc.
        if ( e.foreground )
        {
            $("#app-status-ul").append('<li>--INLINE NOTIFICATION--' + '</li>');

            // on Android soundname is outside the payload.
            // On Amazon FireOS all custom attributes are contained within payload
            var soundfile = e.soundname || e.payload.sound;
            // if the notification contains a soundname, play it.
            var my_media = new Media("/android_asset/www/"+ soundfile);
            my_media.play();
        }
        else
        {  // otherwise we were launched because the user touched a notification in the notification tray.
            if ( e.coldstart )
            {
                $("#app-status-ul").append('<li>--COLDSTART NOTIFICATION--' + '</li>');
            }
            else
            {
                $("#app-status-ul").append('<li>--BACKGROUND NOTIFICATION--' + '</li>');
            }
        }

       $("#app-status-ul").append('<li>MESSAGE -> MSG: ' + e.payload.message + '</li>');
           //Only works for GCM
       $("#app-status-ul").append('<li>MESSAGE -> MSGCNT: ' + e.payload.msgcnt + '</li>');
       //Only works on Amazon Fire OS
       $status.append('<li>MESSAGE -> TIME: ' + e.payload.timeStamp + '</li>');
    break;

    case 'error':
        $("#app-status-ul").append('<li>ERROR -> MSG:' + e.msg + '</li>');
    break;

    default:
        $("#app-status-ul").append('<li>EVENT -> Unknown, an event was received and we do not know what it is</li>');
    break;
  }
}

// BlackBerry10
function pushNotificationHandler(pushpayload) {
    var contentType = pushpayload.headers["Content-Type"],
        id = pushpayload.id,
        data = pushpayload.data;//blob

    // If an acknowledgement of the push is required (that is, the push was sent as a confirmed push
    // - which is equivalent terminology to the push being sent with application level reliability),
    // then you must either accept the push or reject the push
    if (pushpayload.isAcknowledgeRequired) {
        // In our sample, we always accept the push, but situations might arise where an application
        // might want to reject the push (for example, after looking at the headers that came with the push
        // or the data of the push, we might decide that the push received did not match what we expected
        // and so we might want to reject it)
        pushpayload.acknowledge(true);
    }
};

},true);//document.addEventListener