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
        "senderID":"replace_with_sender_id",
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
	alert('ok')
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
	  
},true);