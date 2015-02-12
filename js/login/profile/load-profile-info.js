//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';		
	
	var z = getZ();

	//profile pic
	$.post(postPath+'queries/load-profile/load-profile.php',{z:z},function(data){
		
		
	//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/profile/';
else postPath = '';		
		
		if(data){
		
		//load profile pic
		$('#profile-pic-div').css('background-image','url('+postPath+data.profile_pic+')');
	
		//load body image
		$('#page-body').css('background-image','url('+postPath+data.profile_background+')');
	
		if(data.cover_path){
			$('#cover-photo-div').css('background-image','url('+postPath+data.cover_path+')').show()
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
			if(postPath) data.list = data.list.replace(/background-image:url\(/g,'background-image:url('+postPath)//if postPath provide external path
			$('#entourages-list').html(data.list)
		}//if
		
	}//if data
	
	},'json')//post
	