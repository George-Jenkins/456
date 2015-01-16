//on scroll
$(window).scroll(function(){
	
	var windowHeight = $(window).scrollTop()
	var actionPoint = $('#fadeInPoint').offset().top ;
	var screenWidth = window.innerWidth
	if(windowHeight>=actionPoint && screenWidth>570){
		$('#section1-container').hide()
		}//if
	else{
		$('#section1-container').fadeIn()
		}
})//function

