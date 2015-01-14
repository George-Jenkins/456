$(document).ready(function(){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';		
	
	$('#edit-going-out-link').click(function(){		
		
		//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
		
		$('#edit-going-out-link').hide()
		$('#edit-going-out-cancel').show()
		
		
		$('#going-out-text-area-div').show()
		$('body, html').animate({scrollTop:$('#going-out-div').offset().top},200)		
		
		$('#going-out-answer').hide()
	})//click edit going out
	
	//cancel
	$('#edit-going-out-cancel').click(function(){
		$('#edit-going-out-link').show()
		$('#edit-going-out-cancel').hide()
		$('#going-out-textarea').val('')
		$('#going-out-text-area-div').hide()
	})//click
	
	$('#submit-going-out').click(function(){
				
		var input = $('#going-out-textarea').val()
		
		var i = localStorage.getItem('i');
		var k = localStorage.getItem('k');
		var z = sjcl.decrypt(k,i);
		
		$.post(postPath+'queries/submit-going-out-info.php',{input:input,z:z},function(data){
			
		if(data.error=="wrong z"){
		window.location = "../../member-login.html";
		return;
		}//if
			
			$('#going-out-textarea').val('')
			$('#going-out-text-area-div').hide()
			$('#edit-going-out-link').show()
			$('#edit-going-out-cancel').hide()
		
			$('#going-out-answer').html(data.input).show()		
	
		},'json')//post
		
	})//click submit
	
})