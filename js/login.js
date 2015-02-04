	var url = document.location.href
	var urlArray = url.split('?')
	var inviteCode = urlArray[1]
	if(inviteCode){//clean invite
		inviteCode = inviteCode.split('&')[0]
	}//if
	$('#inviteCode').val(inviteCode)
	
	var priorEmail = localStorage.getItem('userEmail')
	if(priorEmail) $('#email').val(priorEmail)
	
	$('#form').submit(function(e){
	
	e.preventDefault()
	
	//this is path to post for apps
	if(pathForPost) postPath = 'http://ritzkey.com/';
	else postPath = '';
	
	var email = $('#email').val()	
	var password = $('#password').val()
	var inviteCode = $('#inviteCode').val()
	
	if(!email || !password) return;
	
	$('#message').html("<div class='small-processing' style='margin-top:0'></div>").show()
	
	$.post(postPath+'queries/login.php',{email:email, password:password, inviteCode:inviteCode}, function(data){
		
		if(data.error=='email'){
			$('#message').addClass('red').html("Wrong email address").show()
			return;
		}//if
		
		if(data.error=='password'){
			$('#message').addClass('red').html("Wrong password").show()
			return;
		}//if	
		
		if(data.error=='login'){
			alert('1')
			//set email localStorage
			localStorage.setItem('userEmail',email);
			if(mobileView) localStorage.setItem('registerDevice','true');//register-notifications.js will use this
			alert('2')
			localStorage.setItem('loginName',data.name)
			
			//start sessions
			localStorage.setItem('k',data.k);
			var k = localStorage.getItem('k')
			
			var i  = sjcl.encrypt(k,data.i);
			localStorage.setItem('i',i);	
			var z = getZ()
			
			var lastPage;
			lastPage = sessionStorage.getItem('toLastPage')//get last page user was on before logout
			sessionStorage.removeItem('toLastPage')
			alert('3')
			if(lastPage){
				alert('4')
				window.location = lastPage;
			}//if
			else window.location = "login/profile/profile.html";
			
			$('#message').hide()
			
		}//if
			
	},'json')
	
	})//submit
	