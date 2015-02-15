(function(){

if(!mobileView) var slideDuration = 200;
else var slideDuration = 100;

$('#view-attendees').click(function(){
	
	$('#view-attendees').addClass('focus').hide()//button
	$('#view-event').show()//button
	
	$('#event-info-container').hide()
	
	waitScroll = true;//defined below. Prevents scroll when app
	
	$('#list-attendees-div').toggle('slide', slideDuration, function(){
		
		enableScroll()
		
	})//toggle
	
})//click

$('#view-event').click(function(){
	
	$('#view-attendees').show()//button
	$('#view-event').addClass('focus').hide()//button
	
	$('#list-attendees-div').hide()
	
	waitScroll = true;//defined below. Prevents scroll when app
	
	$('#event-info-container').toggle('slide', slideDuration, function(){
		
		enableScroll()
		
	})//toggle
	
})//click	
	
var hide = true;//this is confusing but this makes it so the function doesn't hide anything if the screen resizes after 
//it has resized once already. It if hides elements every time if resizes it can hide all elements. This variable is used inside the 
//function hideElements()

hideElements()		

$(window).resize(function(){
	
	hideElements()
	
})//resize
	
//determine what is hidden depending on screen size	
function hideElements(){
	
	var screenWidth = window.innerWidth
	if(screenWidth>1120) hide = true //this is so if the screen goes from small to big the device has permission to hide elements again onces the screen gets smaller
	
	if(screenWidth<=1120){
		if(hide == true){
		$('#view-attendees').show()//button
		$('#view-event').hide()//button
		$('#list-attendees-div').hide()
		}//if
	}//if
	else{
		$('#view-attendees').hide()
		$('#view-event').hide()//button
		$('#list-attendees-div').hide()
		$('#event-info-container').show()
		$('#list-attendees-div').css('display','inline-block')
		$('#event-info-container').css('display','inline-block')
	}
	if(screenWidth<=1120) hide = false //this is so device knows it has already hidden elements
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