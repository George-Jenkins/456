$('#menu').show()//I hid menu when less than 400px because until load it would stay in center of page. Mainly an issue on group page
$(window).resize(function(){
	$('#menu').show()
})

$('#menu-dropdown-icon').click(function(e){
		e.preventDefault()
		
	if(!$('#mobile-menu').hasClass('clicked')){ 
	
	$('#mobile-menu').show()
	
	$('#mobile-menu').addClass('clicked');
	
	}//if clicked == false
	else{
		
	$('#mobile-menu').hide()
	
	$('#initial-dropdown').show()
	$('#hidden-items').hide()
	
	$('#mobile-menu').removeClass('clicked');
	}//else
	
})//click

//hide dropdown when user clicks dropdown menu link. This is because when a user click the back button the dropdown will still be shown
$('#mobile-menu a').click(function(){
	$('#initial-dropdown').show()
	$('#hidden-items').hide()
	$('#mobile-menu').hide()
})//click

//screen width change
	$(window).resize(function(){
	var width = $(window.innerWidth)? window.innerWidth : screen.width	

	if(width>=901){
		$('#mobile-menu').hide()
		$('#mobile-menu').removeClass('clicked');
		}
	})//window resize
	
//the more button
$('body').click(function(e){
	
	var targetID = $(e.target).attr('id')
	
	if(targetID == 'more-td') e.preventDefault()
	
	if(targetID=='more-dropdown') return;
	
	if(targetID == 'more-td' && !$('#more-td').hasClass('clicked')){
		 $('#more-dropdown-container').show()
		 $('#more-td').addClass('clicked')
		 e.preventDefault()
	}//if
	else if(targetID == 'more-td' && $('#more-td').hasClass('clicked') || targetID != 'more-td'){
		$('#more-dropdown-container').hide()
		$('#more-td').removeClass('clicked')
	}//if
})//click


//the mobile more dropdown
$('#more-menu').click(function(){
	$('#initial-dropdown').hide("slide", { direction: "left" },function(){
	$('#hidden-items').show("slide", { direction: "right" });	
	})
	
})//click
$('#back-menu').click(function(){
	$('#hidden-items').hide("slide", { direction: "right" },function(){
	$('#initial-dropdown').show("slide", { direction: "left" });	
	})
	
})//click


//back menu (on bottom of page)	
if(mobileView){
$('#container').css('margin-bottom',40)
$('#bottom-menu').show()
$('#back-button').click(function(){
	window.history.back()
})//click
$('#forward-button').click(function(){
	window.history.forward()
})//click
}//if mobileView

//iphone is having an issue where when user focuses on inout field fixed elements are ruined.
$('textarea, input[type=password], input[type=text]').focus(function(){
	
	system = navigator.platform;
	
	if(system === 'iPad' || system === 'iPhone' || system === 'iPod'){
		
		$('#menu').css('position','absolute').css('top',0);
		$('#bottom-menu').addClass('hide2')
		
	}//if	
})	
$('textarea, input[type=password], input[type=text]').blur(function(){
	
	system = navigator.platform;
	
	if(system === 'iPad' || system === 'iPhone' || system === 'iPod'){
		
	$('#menu').css('position','fixed').css('top',0);
	$('#bottom-menu').removeClass('hide2')
	//scroll page alittle just to set it back. (Had to be done with iphone)
	var offSet = $(window).scrollTop()
	$('body').animate({scrollTop:offSet+1},1)
		
	}//if
})		

