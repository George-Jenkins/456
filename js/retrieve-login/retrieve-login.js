$(document).ready(function(){
	
	$('form').submit(function(e){
	
	e.preventDefault()
	
	var email = $('#email').val()
	
	if(!email) return;
	
	$('#message').html('<div class="small-processing" style="position:absolute"></div>').show()
	
	$.post('queries/retrieve-login/retrieve-login.php',{email:email},function(data){
		
	if(data.error===false){
		$('#dim-background').removeClass().show()
		$('#lightbox').removeClass().addClass('white-background').html(data.msg).show()
		$('.close').show()
		$('#message').hide()
		}//if
		
	if(data.error===true){
		$('#dim-background').removeClass().hide()
		$('#lightbox').removeClass().hide()
		$('.close').hide()
		$('#message').addClass('red').html(data.msg).show()
		}//if
		
	},'json')//post
	})//submit
})