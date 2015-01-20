$('#settings-form').submit(function(e){
		
		e.preventDefault()

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/account/';
else postPath = '';

var path = pathToRoot()
		
		var z = getZ()
		
		$('#hiddenZ').val(z)
		
		$('#loader1').show()
		$('#done-msg').html('')
		
		var formData = $('form').serialize()
		
		$.ajax({
			
			type:'POST',
			url:postPath+'queries/settings.php',
			data:formData,
			dataType:'json',
			success: function(data){
				
				$('#hiddenZ').val('')
				
				if(data.error=='wrong z'){
					
					window.location = path+'member-login.html';
				}//if
				
				if(data.done=='done'){
					
					$('#loader1').hide()
					$('#done-msg').html('Saved')
					
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
