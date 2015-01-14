function getI(){
	
	var i = localStorage.getItem('i')
	return i
}

function getK(){
	
	var k = localStorage.getItem('k')
	return k
}

function getZ(){
	
	var i = localStorage.getItem('i')
	var k = localStorage.getItem('k');
	var z = sjcl.decrypt(k,i);
	return z;
}

function pathToRoot(){

	var url = window.location.href
	var removeTLD = url.split('.com')[1]
	var numberOfSlashes = removeTLD.match(/\//g).length
	var loops = numberOfSlashes-1;
	//create path
	var path = '';
	for(x=1;x<=loops;x++){
		
		path +="../"; 
	}//while
	return path	
}

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