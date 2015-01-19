var pushNotification = window.plugins.pushNotification;
pushNotification.registerDevice({alert:true, badge:true, sound:true}, function(status) {
    app.myLog.value+=JSON.stringify(['registerDevice status: ', status])+"\n";
    app.storeToken(status.deviceToken);
});