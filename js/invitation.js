//add timezone
var tz = jstz.determine(); 
tz.name();
$('#time-zone').val(tz.name())

$('#form').submit(function(e){
	
e.preventDefault()
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/';
else postPath = '';	
	
	var name = $('#name').val()
	var email = $('#email').val().trim()
	var rep_email = $('#rep-email').val().trim()
	var password = $('#password').val()
	if($('#male-gender').is(':checked')) var gender='male';
	if($('#female-gender').is(':checked')) var gender='female';
	
	if(!name && !email && !rep_email && !name && !password) return;
	
	//create function for removing red
	function removeRed(field){
	$("#"+field+"").click(function(){
	$("#"+field+"").removeClass('red-background')
	})//click
	}//function
	
	errors = false
	
	//validate form
	if(!name){
		$('#name').addClass('red-background')
		removeRed('name')
		errors = true
	}//if
	if(!email){
		$('#email').addClass('red-background')
		removeRed('email')
		errors = true
	}//if
	if(email && !email.match(/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i)){
		$('#email-error').addClass('red').html("Doesn't look like a real email")
		errors = true
	}//if
	else $('#email-error').removeClass('red').html("Email")
	
	if(!rep_email){
		$('#rep-email').addClass('red-background')
		removeRed('rep-email')
		errors = true
	}//if
	if(rep_email && rep_email.toLowerCase!=email.toLowerCase){
		$('#rep-email-error').addClass('red').html("Emails don't match")
		errors = true
	}//if
	else $('#rep-email-error').removeClass('red').html("Repeat Email")
	
	if(!password){
		$('#password').addClass('red-background')
		removeRed('password')
		errors = true
	}//if
	if(password && password.length<6){
		$('#password-error').addClass('red').html('Password must be at least 6 characters')
		errors = true
	}//if
	else $('#password-error').removeClass('red').html('Password')
	
	if(!gender){
		$('.no-gend').show()
		$('#male-gender').click(function(){
			$('.no-gend').hide()
		})
		$('#female-gender').click(function(){
			$('.no-gend').hide()
		})
		errors = true
	}//if
	
	if(errors==true) $('#error-span').html('Please correct errors').show()
	
	//end validate form
	
	if(errors==false){
		
		$('.small-processing').show()
		var formData = $('form').serialize()
		
		$('#error-span').html('').hide()
		
		$.ajax({
			
			url:postPath+'queries/register-from-invitation.php',
			type:'POST',
			data:formData,
			dataType:'json',
			success:function(data){
				
				$('.small-processing').hide()
				
				if(data.error===true){
					
					$('#error-span').html(data.msg).show()
				}//if
				
				if(data.error===false){
					
				$('#dim-background').removeClass().show()
				$('#lightbox').removeClass().addClass('white-background').html(data.msg).show()
				$('.close').show()
				}//if
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				
			}
			
		})//ajax
		
	}//if no errors
	
	})//click
