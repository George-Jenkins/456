(function(){

if(!mobileView) var slideDuration = 200;
else var slideDuration = 100;
	
$('#view-group-members').click(function(){
	
	$('#view-group-members').hide()//button
	$('#view-chat-wall').show()//button
	$('#chat-box-container').hide()
	
	waitScroll = true;//defined below. Prevents scroll when app
	var screenWidth = window.innerWidth
	
	//decide which slide effect to use depending on screen width
	if(screenWidth<=965){
		
	$('#member-list').toggle('slide', slideDuration, function(){
		
	$('#member-list').css('width','100%').css('height','100%')//I set it back to normal here when function is done
		
		enableScroll()//defined below. Enables scroll when app
		
	})
	
	$('#member-list').css('width',screenWidth)//had to do this because the slide effect caused pics to not be inline during slide
	
	}//if 
	else $('#member-list').slideDown()
	
	$('#member-list').css('display','inline-block')
	
	//if screen is too small hide group info div
	if(screenWidth<=965){
		$('#view-group-info').show()//button
		$('#group-info-div').hide()
	}//if 
})//click

$('#view-chat-wall').click(function(){
	
	$('#view-group-members').show()//button
	$('#view-chat-wall').hide()//button
	$('#member-list').hide()
	
	waitScroll = true;//defined below. Prevents scroll when app
	var screenWidth = window.innerWidth
	//decide which slide effect to use depending on screen width
	if(screenWidth<=965){
		$('#chat-wall').hide()//i'll hide chat wall so it doesn'slow down toggle on mobile devices. I'll show it after toggle
		$('#show-more').addClass('hide2')//I'll also hide show earlier button because it was still there when wall was hidden
		$('#chat-box-container').toggle('slide', slideDuration, function(){
			$('#show-more').removeClass('hide2')
			$('#chat-wall').show()
			enableScroll()//defined below. Enables scroll when app	
		})
	}//if
	else $('#chat-box-container').slideDown()
	
	$('#chat-box-container').css('display','inline-block')
	//if screen is too small hide group info div
	
	if(screenWidth<=965){
		$('#view-group-info').show()//button
		$('#group-info-div').hide()
	}//if 
	
})//click

$('#view-group-info').click(function(){

	$('#view-group-info').hide()//button
	
	waitScroll = true;//defined below. Prevents scroll when app
	var screenWidth = window.innerWidth
	//decide which slide effect to use depending on screen width
	if(screenWidth<=965) $('#group-info-div').toggle('slide',slideDuration,function(){
		enableScroll()//defined below. Enables scroll when app	
	})//slide
	else $('#group-info-div').slideDown()
	
	if(screenWidth<=965){
		$('#view-group-members').show()//button
		$('#member-list').hide()
		$('#view-chat-wall').show()//button
		$('#chat-box-container').hide()
	}//if 
})//click

var hideSmall = true; //this is confusing but this makes it so the function doesn't hide anything if the screen resizes after 
//it has resized already. It if hides elements every time if resizes it can hide all elements. This variable is used inside the 
//function hideSections()	
var hideMedium = true; //this variable is essentially for the same purpose only it's for when the screen is medium size

//show sections
hideSections()

//if view specific reply I want the wall to load showing the chat wall first
url = document.location.href
urlArray = url.split('&')
id = urlArray[1];
if(id) loadIfReply()

//handle what to show according to screen width
$(window).resize(function(){
	
hideSections()
	
})//resize

function hideSections(){
	var screenWidth = window.innerWidth
	
	if(screenWidth>965) hideSmall = true
	//handle chat box
	if(screenWidth<=965){
		if(hideSmall == true){
		$('#view-chat-wall').show()//button
		$('#chat-box-container').hide()
		
		$('#view-group-members').show()//button
		$('#member-list').hide()
		$('#group-info-div').show()
		}//if
	}//if
	
	if(screenWidth<=965) hideSmall = false
	
	//when screen widens begin to show divs
	if(screenWidth<=965 || screenWidth>1455) hideMedium = true;
	if(screenWidth>965){
		if(hideMedium == true){
		//group info
		$('#view-group-info').hide()//button
		$('#group-info-div').show()
		//chat wall
		$('#view-chat-wall').hide()//button
		$('#chat-box-container').show()
		//group members
		$('#view-group-members').show()//button
		$('#member-list').hide()
		//make inline
		$('#group-info-div').css('display','inline-block')
		$('#chat-box-container').css('display','inline-block')
		
		}//if
	}//if
	if(screenWidth>965 && screenWidth<=1455) hideMedium = false;
	
	if(screenWidth>1455){
		$('#member-list').show()
		$('#member-list').css('display','inline-block')
		$('#view-group-members').hide()//button
	}//if
	
}//function


function loadIfReply(){
	
	var screenWidth = window.innerWidth
	if(screenWidth<=965){
	$('#view-group-members').show()//button
	$('#view-chat-wall').hide()//button
	$('#member-list').hide()
	
	$('#chat-box-container').show()
	$('#chat-wall').show()
	
	$('#chat-box-container').css('display','inline-block')
	//if screen is too small hide group info div
	
		$('#view-group-info').show()//button
		$('#group-info-div').hide()
	}//if screenWidth<=965
}//function

//this prevents scroll while a div slides into window on app because scroll before div slides out hides the div. (Glitch)
var waitScroll = false;
$('body').on('touchstart touchmove',function(e){ 

if(waitScroll === true) e.preventDefault()

})//on	
//this enables scroll while a div slides into window on app
function enableScroll(){
	setTimeout(function(){
		waitScroll = false;
	},0.5)
}//function

})();