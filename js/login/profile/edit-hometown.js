$('#hometown-link').click(function(){
		
		$('#select-state-div').show()
		$('#hometown-link').hide()
		$('#hometown-cancel').show()
		$('#home-town-info').hide()
	})//click
	$('#hometown-cancel').click(function(){
		
		$('#select-state-div').hide()
		$('#select-city-div').hide()
		$('#hometown-link').show()
		$('#hometown-cancel').hide()
		$('#home-town-info').show()
		
		$('#city-suggestion-box').html('').hide()
		$('#type-city').val('')
		$('#select-states').val('')
	})//hometown cancel

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';	
	
	var z = getZ()
	
	//add states to dropdown
	$.post(postPath+'queries/get-states.php',{z:z},function(data){
		
		if(data){
		$('#select-states').append(data.states)
		}
	},'json')//post


		//get cities for state
		$('#select-states').change(function(){
			
			sessionStorage.setItem('currentNumber',0);
			
			$('#city-suggestion-box').html('').hide()
			$('#type-city').val('')
			$('#select-city-div').show()
			
		})//change
		
	//search for cities
	$('#type-city').keyup(function(e){
		
		if(e.keyCode == 40 || e.keyCode == 38) return;
		
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';			
		
		//reset downNumber (this is need for later)
		sessionStorage.setItem('downNumber',0);
		sessionStorage.setItem('scrollDownNumber',4);
		sessionStorage.removeItem('upNumber')
		
		 sessionStorage.setItem('currentNumber',0);//reset
		
		var input = $('#type-city').val()
		
		if(!input){
			$('#city-suggestion-box').html('').hide()
		}
		
		var original = input;
		
		var z = getZ()
		var state = $('#select-states').find('option:selected').val()
		$.post(postPath+'queries/get-cities.php',{z:z,input:input,state:state},function(data){
			
			var current_input = $('#type-city').val()
			
			if(current_input!=input) return;
			
			if(data) $('#city-suggestion-box').html(data).show()
			else{
				$('#city-suggestion-box').html('').show()
			}//ese
			
			if(!data){
				 $('#city-suggestion-box').html('').hide()
			}//if
		})//post
		
	})//keyup


//handle what happens when user presses down
sessionStorage.setItem('currentNumber',0);

$('body').keydown(function(e){
	
	var input = $('#type-city').val()
	var div_content = $('#city-suggestion-box').html()
	
	//if down key
	if(input && div_content && e.keyCode == 40){
	
	var x = sessionStorage.getItem('currentNumber');
	
	if(sessionStorage.getItem('goingDown')){
		
		sessionStorage.setItem('currentNumber',(x*1)+2)
		
		var x = sessionStorage.getItem('currentNumber');
		
		sessionStorage.removeItem('goingDown')
		
	}//if

//handle what happens when one was clicked
	if(sessionStorage.getItem('currentClicked')){
			
			sessionStorage.setItem('currentNumber',(x*1)+1)
			var x = sessionStorage.getItem('currentNumber')
			sessionStorage.removeItem('currentClicked')
			}//if

	var html = $('#city-num'+x).html()
		if(html){
		
		$('.city-div').removeClass('city-div-select')
		$('#city-num'+x).addClass('city-div-select')
		
		//set when the menu should scroll down	
		var container = document.getElementById('city-suggestion-box');
		var rowToScrollTo = document.getElementById('city-num'+x);
		container.scrollTop = rowToScrollTo.offsetTop-40;
		
		//set wich item will be selected next 	
		sessionStorage.setItem('currentNumber',(x*1)+1);
	}//if down key
	}//if html
})//keydown	

sessionStorage.removeItem('goingDown')
$('body').keydown(function(e){
	
	var input = $('#type-city').val()
	var div_content = $('#city-suggestion-box').html()
	
	//if up key
	if(input && div_content && e.keyCode == 38){
		
		if(!sessionStorage.getItem('goingDown')) var x = sessionStorage.getItem('currentNumber')-2;
		else var x = sessionStorage.getItem('currentNumber')
		
		if(sessionStorage.getItem('currentClicked')){
			
			var x = sessionStorage.getItem('currentNumber')-1
			sessionStorage.removeItem('currentClicked')
			}//if
			
		var html = $('#city-num'+x).html()
		if(html){
		
		$('.city-div').removeClass('city-div-select')
		$('#city-num'+x).addClass('city-div-select')
		
		//set when the menu should scroll down	
		var container = document.getElementById('city-suggestion-box');
		var rowToScrollTo = document.getElementById('city-num'+x);
		container.scrollTop = rowToScrollTo.offsetTop;
		
		sessionStorage.setItem('currentNumber',(x*1)-1)
		
		sessionStorage.setItem('goingDown','down')
		}//if html
		
	}//if up key
	
})//keydown	

		
//click background to close box
$('body').click(function(e){
	
	if($(e.target).attr('class')!='city-div city-div-select'){
		$('#city-suggestion-box').html('').hide()
	}//if
		
		
})//click body

//handle selecting city by clicking enter
$('body').keyup(function(e){
	
	if(e.keyCode==13){
		
		if($('.city-div').hasClass('city-div-select')){
			
			var city = $('.city-div-select').html()
			
			$('#type-city').val(city)
			}//if
		}//if
})//keyup
	
//select city
function getCity(city,x){
	
	$('.city-div').removeClass('city-div-select')
	$('#city-num'+x).addClass('city-div-select')
	
	$('#type-city').val(city)
	
	//reset currentNumber
	sessionStorage.setItem('currentNumber',x)
	sessionStorage.setItem('currentClicked',x)
}
