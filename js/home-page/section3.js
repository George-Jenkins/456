$('#sign-up-scroll').click(function(a){
	
	a.preventDefault()
	$('body, html').animate({scrollTop:$('#section1').offset().top},200);
})//function

var screenWidth = window.innerWidth

if(screenWidth>570){

var windowHeight = $(window).scrollTop()
	var actionPoint = $('#section2').offset().top - 55;
	if(windowHeight>=actionPoint){
		$('#section3-container').fadeIn('slow')
		}//if
	else{
		$('#section3-container').hide()
		}
		
}//if screenWidth>570
else
{
	$('#section3-container').show()
}
//on scroll
$(window).scroll(function(){
	
	screenWidth = window.innerWidth
	
	if(screenWidth>570){
	
	var windowHeight = $(window).scrollTop()
	var actionPoint = $('#section2').offset().top - 55;
	if(windowHeight>=actionPoint){
		$('#section3-container').fadeIn()
		}//if
	else{
		$('#section3-container').hide()
		}
	}//if screenWidth>570
})//function


//window resize
$(window).resize(function(){

screenWidth = window.innerWidth
	
if(screenWidth<=570){
	$('#section3-container').show()
}
})//resize
