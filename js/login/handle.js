var path = pathToRoot()

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;

if(!getI()) window.location = postPath+'member-login.html';

var k = getK();
var z = getZ()
	
$.post(postPath+'connect/handle.php', {z:z,k:k}, function(data){
	
var path = pathToRoot()	
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = path;	

if(data.error=='wrong z'){
	
	var url = window.location.href
	var page = url.split('login/')[1]
	sessionStorage.setItem('toLastPage','login/'+page);
	
	window.location = postPath+'member-login.html';
}//if
},'json')//post
