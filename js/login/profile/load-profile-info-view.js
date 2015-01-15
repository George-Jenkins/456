//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';	
	
	var z = getZ();
	
	//get members id
	var url = window.location.href
	var urlArray = url.split('?')[1]
	var cleanID = urlArray.split('&')[0]
	
	
	//profile pic
	$.post(postPath+'queries/load-profile/load-profile.php',{z:z, cleanID:cleanID},function(data){
		
		if(data.creator){ 
		window.location = "profile.html";//redirect to profile if user is creator
		return;
		}//if
		
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
			//load name in profile
			$('#cover-photo-name').html(data.name)
		}
		else
		{
			$('#profile-title').html(data.name)
		}
		$('title').html(data.name)
			
		$('#home-town-info').html(data.hometown)
		$('#going-out-answer').html(data.going_out)
		
		if(data.list){	
		
			$('#entourages-list').html(data.list)
		}//if
		
	}//if data
	
	},'json')//post
