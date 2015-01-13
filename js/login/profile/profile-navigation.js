$(document).ready(function(){
	
$('#view-groups').click(function(){
		
	$('#view-groups').hide()//button
	$('#view-profile').show()//button
	$('body').animate({scrollTop:$('#view-profile').offset().top-70},300)
	
	$('#profile-container').hide()
	
	$('#entourages-div').toggle('slide')
	
})//click
	
$('#view-profile').click(function(){
	
	$('#view-groups').show()//button
	$('body').animate({scrollTop:$('#view-groups').offset().top-70},300)	
	$('#view-profile').hide()//button
	
	$('#entourages-div').hide()
	$('#profile-container').toggle("slide");
			
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
	
})