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
//remove click delay. This may not be need bc I have the fastclick plugin but it seemed to make chat wall buttons faster
function NoClickDelay(el) {
	this.element = el;
	if( window.Touch ) this.element.addEventListener('touchstart', this, false);
}

NoClickDelay.prototype = {
	handleEvent: function(e) {
		switch(e.type) {
			case 'touchstart': this.onTouchStart(e); break;
			case 'touchmove': this.onTouchMove(e); break;
			case 'touchend': this.onTouchEnd(e); break;
		}
	},

	onTouchStart: function(e) {
		e.preventDefault();
		this.moved = false;

		this.element.addEventListener('touchmove', this, false);
		this.element.addEventListener('touchend', this, false);
	},

	onTouchMove: function(e) {
		this.moved = true;
	},

	onTouchEnd: function(e) {
		this.element.removeEventListener('touchmove', this, false);
		this.element.removeEventListener('touchend', this, false);

		if( !this.moved ) {
			// Place your code here or use the click simulation below
			var theTarget = document.elementFromPoint(e.changedTouches[0].clientX, e.changedTouches[0].clientY);
			if(theTarget.nodeType == 3) theTarget = theTarget.parentNode;

			var theEvent = document.createEvent('MouseEvents');
			theEvent.initEvent('click', true, true);
			theTarget.dispatchEvent(theEvent);
		}
	}
};

function getPostPath(path){
	if(pathForPost) postPath = path;
	else postPath = '';
	return postPath;
} 

function getDocumentHeight(){
	return Math.max(
        document.body.scrollHeight, document.documentElement.scrollHeight,
        document.body.offsetHeight, document.documentElement.offsetHeight,
        document.body.clientHeight, document.documentElement.clientHeight
	)
}

function detectInactivity(){//for now this is called in pulse.js
	var inactive = false
	detectInactivityTimout = setTimeout(function(){
	clearTimeout(detectInactivityTimout)//clear this timeOut
	inactive = true
	//clear all intervals
	if(typeof notificationsAndRepliesInterval!=='undefined') clearTimeout(notificationsAndRepliesInterval)
	if(typeof chatWallInterval!=='undefined') clearTimeout(chatWallInterval)
	if(typeof getContributionsInterval!=='undefined') clearTimeout(getContributionsInterval)
	//show still their message
	var content = $('#lightbox').html()
	if(content!="<p>Still there? <span onClick='startIntervals()' class='functionLink buttonLink'>Yes</span></p>"){
	$('#dim-background').removeClass().show()
	$('#lightbox').removeClass().addClass('white-background').html("<p>Still there? <span onClick='startIntervals()' class='functionLink buttonLink'>Yes</span></p>");
	$('.close').hide()
	}//if
	},600000)	
}//function
$(document).on('touchstart touchmove keyup click',function(e){//clear timeout if user is active
	clearTimeout(detectInactivityTimout)//clear this timeOut
	detectInactivity()//start timeout again
})//on	

function startIntervals(){//starts detectInactivity() again
	$('#dim-background').hide()
	$('#lightbox').removeClass().addClass('white-background').html('')
	if(typeof notificationsAndRepliesFunction!=='undefined') notificationsAndRepliesFunction()
	if(typeof chatWallIntervalFunction!=='undefined') chatWallIntervalFunction()
	if(typeof getContributionsFunction!=='undefined') getContributionsFunction()
	clearTimeout(detectInactivityTimout)//clear timeOut
	detectInactivity()//start timeout again
}//function


//this is used to provide path to post requests
//this is used to provide path to post requests
var pathForPost;
var mobileView;
//pathForPost = true;
mobileView = true;


