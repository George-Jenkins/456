(function(){

$('#edit-birthday').click(function(){
	
	$('#edit-birthday').hide()
	$('#cancel-edit-birthday').show()
	
	$('#birthday-display').hide()
	$('#select-birthday-span').show()
	
})//click

$('#cancel-edit-birthday').click(function(){
	
	$('#edit-birthday').show()
	$('#cancel-edit-birthday').hide()
	
	$('#birthday-display').show()
	$('#select-birthday-span').hide()
	
})//click

$('#submit-birthday').click(function(){
	
	if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
	else postPath = '';
	
	var birthdayDay = $('#birthday-day').val()
	var birthdayMonth = $('#birthday-month').val()
	var birthdayYear = $('#birthday-year').val()
	var z = getZ()
	
	if(!birthdayDay || !birthdayMonth || !birthdayYear) return;
		
	$('#load-icon3').removeClass('hide')	
	
	$.post(postPath+'queries/submit-birthday.php',{z:z, birthdayDay:birthdayDay, birthdayMonth:birthdayMonth, birthdayYear:birthdayYear},function(data){
		
		$('#edit-birthday').show()
		$('#cancel-edit-birthday').hide()
		
		$('#select-birthday-span').hide()
		$('#birthday-display').html(data.birthday).show()
		$('#load-icon3').addClass('hide')	
		
	},'json')//post
	
})//click
	
})();