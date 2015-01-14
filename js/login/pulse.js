$(document).ready(function(){
	
var url = window.location.href
	var removeTLD = url.split('.com')[1]
	var numberOfSlashes = removeTLD.match(/\//g).length
	var loops = numberOfSlashes-1;
	//create path
	var path = '';
	for(x=1;x<=loops;x++){
		
		path +="../"; 
	}//while	

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	
	
var z = getZ();

//start functions	
checkForNotifications()
checkForReplies()

//repeat functions
setInterval(function(){

checkForNotifications()
checkForReplies()
	
},1000)//setinterval	

//end repeat functions

//creat functions
function checkForNotifications(){
	
	$.post(postPath+'login/check-for-notifications.php',{z:z},function(data){
		
		if(data.notifications==true) $('.notifications-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.notifications-alert').html('')
	},'json')//post
	
}//function	
	
function checkForReplies(){
	
	$.post(postPath+'login/check-for-replies.php',{z:z},function(data){
		
		if(data.replies==true) $('.replies-alert').html('<img src="'+path+'/pics/new-message-icon.png">')
		else $('.replies-alert').html('')
		
	},'json')//post
	
}//function		


})