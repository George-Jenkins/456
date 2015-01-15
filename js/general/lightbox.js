$('html').append("<div id='dim-background'><div id='lightbox'></div><div class='close'></div></div>")
$('#dim-background').hide()


//pressing escape
	$(document).keyup(function(e){
	
	if($('#dim-background').hasClass('no-escape')) return;
	
	if (e.keyCode == 27) { 
	$('#dim-background').hide()
	}//if 
	})/*keyup*/

//click x
	$('.close').click(function(e){
	
	e.preventDefault()
	
	$('#dim-background').hide()
			
})//click dim background
