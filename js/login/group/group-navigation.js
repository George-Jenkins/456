$(document).ready(function(){
	
$('#view-group-members').click(function(){
	
	$('#view-group-members').hide()//button
	$('#view-chat-wall').show()//button
	$('#chat-box-container').hide()
	
	var screenWidth = window.innerWidth
	//decide which slide effect to use depending on screen width
	if(screenWidth<=965){
		$('#member-list').toggle('slide', function(){
		$('#member-list').css('width','100%')//I set it back to normal here when function is done
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
	
	var screenWidth = window.innerWidth
	//decide which slide effect to use depending on screen width
	if(screenWidth<=965) $('#chat-box-container').toggle('slide')
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
	
	var screenWidth = window.innerWidth
	//decide which slide effect to use depending on screen width
	if(screenWidth<=965) $('#group-info-div').toggle('slide')
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

hideSections()

//handle what to show according to screen width
$(window).resize(function(){
	
hideSections()
	
})//resize

function hideSections(){
	var screenWidth = window.innerWidth
	
	/*
	if(screenWidth>965 && screenWidth<=1455){
		
		$('#member-list').hide()
		$('#member-list').css('position','relative')
		$('#view-group-members').show()//button
	}//if
	*/
	
	if(screenWidth>965) hideSmall = true
	//handle chat box
	if(screenWidth<=965){
		if(hideSmall == true){
		$('#view-chat-wall').show()//button
		$('#chat-box-container').hide()
		
		$('#view-group-members').show()//button
		$('#member-list').hide()
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

})//ready