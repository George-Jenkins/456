//redirect to profile if logged in when on app
if(mobileView && getZ()) window.location = 'login/profile/profile.html';

//if(!mobileView) $('#app-links').show()//.css('display','inline-block')

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

