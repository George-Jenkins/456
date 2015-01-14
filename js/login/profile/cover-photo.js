$(document).ready(function(){

//this is path to post for apps
if(pathForPost){ 
postPath = 'http://ritzkey.com/login/profile/';
var action = $('#cover-photo-form').attr('action')
$('#cover-photo-form').attr('action',postPath+action)
}//if

//show where cover photo is on hover
$('#what-is-cover').click(function(){
	
	$('#cover-photo-div').show()
	$('#hide-example-cover').show()
	$('#what-is-cover').hide()
	
	$('#profile-title').html('Profile')
	$('body').animate({scrollTop:0},200)
})//show sample cover

$('#hide-example-cover').click(function(){
	
	$('#cover-photo-div').hide()
	$('#hide-example-cover').hide()
	$('#what-is-cover').show()
	
	var name = localStorage.getItem('loginName');
	$('#profile-title').html(name)
	
})//hide sample cover

//detect change in value and submit
$('#cover-photo-file').change(function(){
	
	//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
	
	var z = getZ()
	$('#cover-img-z').val(z)
	
	$('#cover-photo-form').submit()
	$('#load-icon2').show()
	 $('#add-cover-feedback').removeClass('red').html('').hide()
	 $('#cover-img-z').val('')
})//change


//delete cover
$('#delete-cover-span').click(function(){
	
	var z = getZ();
	
	$('#load-icon2').show()
	
	$.post('queries/delete-cover-img.php',{z:z},function(data){
	
		if(data){
		
			$('#add-cover-span').show()
			$('#what-is-cover').show()
			$('#hide-example-cover').hide()
			$('#delete-cover-span').hide()
			$('#load-icon2').hide()
			$('#cover-photo-div').css('background-image','url(pics/nightclub2-1.jpg)').hide()
			var name = localStorage.getItem('loginName');
			$('#profile-title').html(name)
		}//if
	})//post
})//click

})//ready


function coverPhoto(feedback){
	
	if(feedback=="wrong z"){
		
		window.location = "../../../member-login.html";
		return;
	}//if
	
	if(feedback=='error'){
		$('#load-icon2').hide()
		 $('#add-cover-feedback').addClass('red').html('Not an image').show()
		 return;
	}//if
	
	$('#cover-photo-div').css('background-image','url('+feedback+')').show()
	
	//hide the what's this stuff
	$('#add-cover-span').hide()
	$('#delete-cover-span').show()
	$('#load-icon2').hide()
	$('#cover-photo-file').val('')
	$('#profile-title').html('Profile')
}//function