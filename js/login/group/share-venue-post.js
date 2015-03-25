//show images
function highLightImage(image){
	//get current scroll top
	currentScrollTop = $(window).scrollTop()
	$('#activities-dark-background').removeClass('hide')
	$('#activities-pic-canvas').removeClass('hide')
	$('#load-activity-image').removeClass('hide')
	$('#load-activity-image').html("<img src='"+image+"'/>")
	$('body, html').scrollTop(0)
}//function
$('#close-view-canvas').click(function(){
	$('#activities-dark-background').addClass('hide')
	$('#activities-pic-canvas').addClass('hide')
	$('#load-activity-image').addClass('hide')
	$('#share-post-div').addClass('hide')//has instructions for sharing post
	$('.check-group').removeClass('clicked').addClass('hide')//rehide all checkmarks
	$('#share-feedback-message').html('').addClass('hide')//clear feedback message
	$('#load-activity-image').html("")
	//return to current position
	$('body, html').scrollTop(currentScrollTop)
})