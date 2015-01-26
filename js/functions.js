function getI(){
	
	var i = localStorage.getItem('i')
	return i
}

function getK(){
	
	var k = localStorage.getItem('k')
	return k
}

function getZ(){
	if(localStorage.getItem('i')){
	var i = localStorage.getItem('i')
	var k = localStorage.getItem('k');
	var z = sjcl.decrypt(k,i);
	return z;
	}//if
	else return '';//I still want to return something if user isn't logged in so that when I check for app notifications the script doesn't stop on the pulse pages 
					
}

function pathToRoot(){
	
	path = document.location.href
	//if in login folder
	if(path.indexOf('/login')>0){
		path = path.split('/login')[1]
		path = path.split('?')[0]//clean it
		position = path.match(/\//g).length;
		x=1;
		truePosition = '';
		while(x<=position){
			truePosition += '../';
			x++
		}//while
		return truePosition
	}//if
	else return '';
}//function

function getGroupID(){
	
	var domain = document.location.href
	var array = domain.split('?')
	var firstItem = array[1]
	if(firstItem){
		var firstItem = firstItem.split('&')
		var group = firstItem[0]
	}//if
	return group
}

function getEventID(){
	url = document.location.href
	urlArray = url.split('?')[1]
	if(urlArray){
		urlArray = urlArray.split('&')[0]
	}//if
	return urlArray
}

//this is used to provide path to post requests
var pathForPost = true;
var mobileView;
mobileView = true;

if(mobileView) new NoClickDelay('body')


