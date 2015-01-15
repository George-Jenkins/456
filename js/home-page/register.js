//this is path to post for apps
	if(pathForPost) postPath = 'http://ritzkey.com/';
	else postPath = '';
	
	//remove any red once someone types in the field
	$('#register-name').keyup(function(){
		$('#td-name').removeClass('red').val('')
	})
	$('#register-password').keyup(function(){
		$('#td-password').removeClass('red').val('')
	})
	$('#register-email').keyup(function(){
		$('#td-email').removeClass('red').val('')
	})
	$('#rep-email').keyup(function(){
		$('#td-rep-email').removeClass('red').val('')
	})
	//hide error message for gender select
	$('#male-gender').click(function(){
	$('.red-x').hide()
	})
	//hide error message for gender select
	$('#female-gender').click(function(){
	$('.red-x').hide()
	})
	
	//submit form
	$('#registration-form').submit(function(e){
	
	//clear error messages
	$('.error-message').html('').hide()
	
	e.preventDefault()
	
	var name = $('#register-name').val()
	var password = $('#register-password').val()
	var email = $('#register-email').val()
	var rep_email = $('#rep-email').val()
	if($('#male-gender').is(':checked')) gender = 'male';
	if($('#female-gender').is(':checked')) gender = 'female';

	var errors=false;
	
	if(!name && !password && !email && !rep_email && !gender ) return;
	
	if(!name){
		 $('#td-name').addClass('red').val('');
		 errors = true;
	}
	
	if(!password){
		 $('#td-password').addClass('red').val('');
		errors = true;
	}
	
	if(password && password.length<6){
			$('#td-password').addClass('red');
			$('#password-message').html('Password at least 6 characters').fadeIn()
			errors = true;
	}//if
	
	if(!email){ 
		$('#td-email').addClass('red').val('')	 
		errors = true;
	}
	
	if(email && !email.match(/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i)){
		$('#td-email').addClass('red').val('');
		$('#email-message').html("Email doesn't look valid").fadeIn()
		errors = true;
	}//if
	
	if(!rep_email){
		$('#td-rep-email').addClass('red').val('');
		errors = true;
	}
	
	if(email && rep_email && email!=rep_email && email.match(/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i)){
		$('#td-rep-email').addClass('red')
		$('#rep-email-message').html("Email addresses don't match").fadeIn()
		errors = true;
	}//if
	
	if(!$('#male-gender').is(':checked') && !$('#female-gender').is(':checked')){
		$('.red-x').show()
		errors = true;
	}//if
	
	//process info
	if(errors==false){
		
		$('#dim-background').addClass('no-escape').show()
		$('#lightbox').html('').removeClass().addClass('processing');
		$('.close').hide()
		
	var formData = $('form').serialize()
	
	$.ajax({
		
		type:'POST',
		url:postPath+'queries/register.php',
		data:formData,
		dataType:'json',
		success:function(data){
			
			$('#lightbox').hide()
			
			if(data.error==false){
				$('#dim-background').removeClass().show()
				$('#lightbox').removeClass().addClass('white-background').html(data.msg).show()
				$('.close').show()
			}//if
			
			if(data.error==true){
				$('#dim-background').removeClass().show()
				$('#lightbox').removeClass().addClass('red-background').html(data.msg).show()
				$('.close').show()
			}//if
			
			},//success
		error:function(textStatus, XMLHttpRequest, errorThrown){
				$('#dim-background').removeClass().show()
				$('#lightbox').removeClass().addClass('red-background').html("Sorry. There was an error. Please try again.");
				$('.close').show()
			}
			
		})//ajax
		
	}//if errors = false
	
	})