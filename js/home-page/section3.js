$('#sign-up-scroll').click(function(a){
	
	a.preventDefault()
	$('body, html').animate({scrollTop:$('#section1').offset().top},200);
})//function


var windowHeight = $(window).scrollTop()
	var actionPoint = $('#section2').offset().top - 55;
	if(windowHeight>=actionPoint){
		$('#section3-container').fadeIn('slow')
		}//if
	else{
		$('#section3-container').hide()
		}

//on scroll
$(window).scroll(function(){
	
	var windowHeight = $(window).scrollTop()
	var actionPoint = $('#section2').offset().top - 55;
	if(windowHeight>=actionPoint){
		$('#section3-container').fadeIn()
		}//if
	else{
		$('#section3-container').hide()
		}
})//function
