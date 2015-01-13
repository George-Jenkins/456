$(document).ready(function(){
	
//on scroll
$(window).scroll(function(){
	
	var windowHeight = $(window).scrollTop()
	var actionPoint = $('#fadeInPoint').offset().top ;
	if(windowHeight>=actionPoint){
		$('#section1-container').hide()
		}//if
	else{
		$('#section1-container').fadeIn()
		}
})//function


})//ready