$(document).ready(function(){
	
	var z = getZ();

	//profile pic
	$.post('queries/load-profile/load-profile.php',{z:z},function(data){
		
		if(data){
		
		//load profile pic
		$('#profile-pic-div').css('background-image','url('+data.profile_pic+')');
	
		//load profile pic
		$('body').addClass('profile-background').css('background-image','url('+data.profile_background+')');
	
		if(data.path){
			$('#cover-photo-div').css('background-image','url('+data.path+')').show()
			$('#add-cover-span').hide()
			$('#delete-cover-span').show()
			$('#profile-title').html('Profile')
			
		}
		else
		{
			$('#profile-title').html(data.name)
		}
		//load name in profile
		$('#cover-photo-name').html(data.name)
			
		$('#home-town-info').html(data.hometown)
		$('#going-out-answer').html(data.going_out)
		
		if(data.list){	
		
			$('#entourages-list').html(data.list)
		}//if
		
	}//if data
	
	},'json')//post
	
	
})//ready