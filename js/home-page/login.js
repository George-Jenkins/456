	var url = document.location.href
	var urlArray = url.split('?')
	var inviteCode = urlArray[1]
	if(inviteCode){//clean invite
		inviteCode = inviteCode.split('&')[0]
	}//if
	$('#inviteCodeMenu').val(inviteCode)	
	
	
	
	$('#login-link').click(function(e){
	
	e.preventDefault()
	
	loginFunction()
	
	})//click	
	
	
	
	////////////SUBMIT//////////(same this as click only it's submit) 
	
	
	$('#login-form').submit(function(e){
	
	e.preventDefault()
	
	loginFunction()
	
	})//click	

	
function loginFunction(){
	
	//this is path to post for apps
	if(pathForPost) postPath = 'http://ritzkey.com/';
	else postPath = '';
	
	var email = $('#login-email').val()	
	var password = $('#login-password').val()
	var inviteCode = $('#inviteCodeMenu').val()
	var platform = '';
	if(mobileView) platform = 'mobile';
	
	if(!email || !password) return;
	
	$('#dim-background').removeClass().addClass('no-escape').show()
	$('#lightbox').removeClass().addClass('processing').show();
	$('.close').hide()
	
	$.post(postPath+'queries/login.php',{email:email, password:password, inviteCode:inviteCode, platform:platform}, function(data){
		
		if(data.error=='email'){
			$('#email-span').html("<span style='color:#f00'>Wrong email</span>")
			$('#dim-background').removeClass().hide()
			$('#lightbox').removeClass().hide()
			return;
		}//if
		
		if(data.error=='password'){
			$('#email-span').html("Email")
			$('#password-span').html("<div style='color:#f00;position:absolute;margin-left:-4px;'>Wrong password <a href='retrieve-login.html'>(Forgot?)</a></div>")
			$('#dim-background').removeClass().hide()
			$('#lightbox').removeClass().hide()
			return;
		}//if	
		
		if(data.error=='login'){
			$('#password-span').html("Password <a href='retrieve-login.html'>(Forgot?)</a>")
			
			//set email localStorage
			localStorage.removeItem('userEmail');
			localStorage.setItem('userEmail',email);
			
			//set localstorage
			localStorage.setItem('loginName',data.name);
			
			//start sessions
			localStorage.setItem('k',data.k);
			var k = localStorage.getItem('k')
			
			var i = sjcl.encrypt(k,data.i);
			localStorage.setItem('i',i);	
			
			var lastPage;
			lastPage = sessionStorage.getItem('toLastPage')//get last page user was on before logout
			
			if(lastPage){
				window.location = lastPage;
				sessionStorage.removeItem('toLastPage')
			}//if
			else window.location = "login/profile/profile.html";
			
		}//if
			
	},'json')//post
	
}//login functin