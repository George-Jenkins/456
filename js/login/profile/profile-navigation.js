(function(){

if(!mobileView) var slideDuration = 200;
else var slideDuration = 100;

$('#view-groups').click(function(){
		
	$('#view-groups').hide()//button
	$('#view-profile').show()//button
	
	waitScroll = true;//defined below. Prevents scroll when app
	
	$('body').animate({scrollTop:$('#view-profile').offset().top-70},300)
	
	$('#entourages-div').toggle('slide', slideDuration, function(){
		
		enableScroll()
		
	})//toggle
	
	$('#entourages-div').css('display','inline-block').css('margin-top','0')//without this the div is a little lower than it should be after it slides
	
	$('#profile-container').hide()
	
})//click
	
$('#view-profile').click(function(){
	
	$('#view-groups').show()//button
	$('body').animate({scrollTop:$('#view-groups').offset().top-70},300)	
	$('#view-profile').hide()//button
	
	waitScroll = true;//defined below. Prevents scroll when app
	
	$('#profile-container').toggle("slide", slideDuration, function(){
		enableScroll()
	});//toggle
	
	$('#entourages-div').hide()
			
})//click
	
var hide = true;//this is confusing but this makes it so the function doesn't hide anything if the screen resizes after 
//it has resized once already. It if hides elements every time if resizes it can hide all elements. This variable is used inside the 
//function hideSections()	
	
hideSections()	
	
//handle
$(window).resize(function(){
	
hideSections()	
	
})//resize
	
function hideSections(){
	var screenWidth = window.innerWidth
	
	//hide buttons when screen is big enough to show everything
	if(screenWidth>1206){
		$('#view-groups').hide()//button
		$('#view-profile').hide()//button
	}//if
	//show all divs when screen is big enough to show everything
	if(screenWidth>1206){
		$('#profile-container').show()
		$('#entourages-div').show()
		$('#profile-container').css('display','inline-block')
		$('#entourages-div').css('display','inline-block').css('margin-top','0')
	}//if
	
	
	if(screenWidth>1206) hide = true //this is so if the screen goes from small to big the device has permission to hide elements again onces the screen gets smaller
	
	if(screenWidth<=1206){
		
		if(hide == true){
		$('#view-groups').show()//button
		$('#entourages-div').hide()
		}//if
	}//if
	
	if(screenWidth<=1206) hide = false  //this is so device knows it has already hidden elements
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