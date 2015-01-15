//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';	
	
	$('#submit-hometown').click(function(){
		
		//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
		
		var state = $('#select-states').find('option:selected').val()
		var city = $('#type-city').val()
		
		var z = getZ();
		
		$('#city-suggestion-box').html('').hide()
		
		if(!state) return;
		
		
		$.post(postPath+'queries/submit-hometown.php',{state:state,city:city,z:z},function(data){
			
		if(data.error=="wrong z"){
			window.location = "../../member-login.html";
			return;
		}//if
			
		$('#home-town-info').html(data.input).show()
			
		$('#select-state-div').hide()
		$('#select-city-div').hide()
		$('#hometown-link').show()
		$('#hometown-cancel').hide()
		$('#home-town-info').show()
		
		$('#city-suggestion-box').html('').hide()
		$('#type-city').val('')
		$('#select-states').val('')
		
			
		},'json')//post
		
	})//click