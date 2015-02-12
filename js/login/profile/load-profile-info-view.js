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


//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';	
		
		if(data.creator){ 
		window.location = "profile.html";//redirect to profile if user is creator
		return;
		}//if
		
		if(data){
		
		//load profile pic
		$('#profile-pic-div').css('background-image','url('+postPath+data.profile_pic+')');
	
		//load body image
		$('body').css('background-image','url('+postPath+data.profile_background+')');
		var platform = navigator.platform;
		if(!mobileView || mobileView && platform=='iPhone') $('body').css('background-attachment','fixed')
	
		if(data.cover_path){
			$('#cover-photo-div').css('background-image','url('+postPath+data.cover_path+')').show()
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
			if(postPath) data.list = data.list.replace(/background-image:url\(/g,'background-image:url('+postPath);
			$('#entourages-list').html(data.list)
		}//if
		
	}//if data
	
	},'json')//post
