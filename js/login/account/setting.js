$('#settings-form').submit(function(e){
		
		e.preventDefault()

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/account/';
else postPath = '';

var path = pathToRoot()
		
		var z = getZ()
		
		$('#hiddenZ').val(z)
		
		$('#done-msg').html('').hide()
		$('#loader1').removeClass('hide')
		
		
		var formData = $('form').serialize()
		
		$.ajax({
			
			type:'POST',
			url:postPath+'queries/settings.php',
			data:formData,
			dataType:'json',
			success: function(data){
				
				$('#loader1').addClass('hide')
				
				$('#hiddenZ').val('')
				
				if(data.error=='wrong z'){
					
					window.location = path+'member-login.html';
				}//if
				
				if(data.error=='email taken'){
					$('#done-msg').html('Email in use').show()
				}
				
				if(data.done=='done'){
					
					
					$('#done-msg').html('Saved').show()
					
					//set email localStorage
					var email = $('#change-email').val()
					localStorage.setItem('userEmail',email);
					
				}//if
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				
			}
		})//ajax
		
	})//submit
	
	//handle deleting
	$('#yes-delete-account').click(function(){
		
		var z = getZ()
		
		$.post(postPath+'queries/delete-account.php',{z:z},function(data){
			window.location = "/index.html";
		},'json')//post
	})//click
	
	$('#delete-account-text').click(function(){
		
		$('#delete-table').show()
	})//click
	
	$('#no-delete-account').click(function(){
		
		$('#delete-table').hide()
	})//click
