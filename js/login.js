$(document).ready(function(){
	
	var url = document.location.href
	var urlArray = url.split('?')
	var inviteCode = urlArray[1]
	if(inviteCode){//clean invite
		inviteCode = inviteCode.split('&')[0]
	}//if
	$('#inviteCode').val(inviteCode)
	
	$('#form').submit(function(e){
	
	e.preventDefault()
	
	var email = $('#email').val()	
	var password = $('#password').val()
	var inviteCode = $('#inviteCode').val()
	
	if(!email || !password) return;
	
	$('#message').html("<div class='small-processing' style='margin-top:0'></div>").show()
	
	$.post('queries/login.php',{email:email, password:password, inviteCode:inviteCode}, function(data){
		
		if(data.error=='email'){
			$('#message').addClass('red').html("Wrong email address").show()
			return;
		}//if
		
		if(data.error=='password'){
			$('#message').addClass('red').html("Wrong password").show()
			return;
		}//if	
		
		if(data.error=='login'){
			
			localStorage.setItem('loginName',data.name)
			
			//start sessions
			localStorage.setItem('k',data.k);
			var k = localStorage.getItem('k')
			
			var i = sjcl.encrypt(k,data.i);
			localStorage.setItem('i',i);	

			window.location = "login/profile/profile.html";
		}//if
			
	},'json')
	
	})//submit
	
})