$(document).ready(function(){
	
	$('#form').submit(function(e){
		
		e.preventDefault()
		
		var password = $('#password').val()
		var code = $('#code').val()
		var email = $('#email').val()
		
		if(!password) return;
		
		if(!email || !code){
			$('#message').addClass('red').html('An error occurred. Please try following the address in your email again.').show();
		return;
		}//if
		
		if(password.length<6){
			$('#message').addClass('red').html('Password must be at least 6 characters').show();
			return;
		}//if
		
		$('#message').removeClass('red').html('<div class="small-processing" style="position:absolute"></div>').show()
		
		$.post('queries/retrieve-login/reset-password.php',{password:password, code:code, email:email}, function(data){
			
			if(data.error===true){
				
				$('#message').addClass('red').html("<div style='width:250px;position:absolute;margin-top:-15px'>"+data.msg+"</div>").show()
			}//if
			
			if(data.error===false){
				
				$('#message').removeClass('red').html(data.msg).show()
			}//if
			
		},'json')//post
			
	})//submit
	
})//document ready