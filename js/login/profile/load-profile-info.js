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
		$('body').css('background-image','url('+postPath+data.profile_background+')');
		
		if(!mobileView) $('body').css('background-attachment','fixed')
		
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
		
		//load dates in birthday field
		for(x=1; x<=31; x++){//day
		if(x<10) var zero = 0;
		else zero = '';	
			$('#birthday-day').append("<option value="+zero+x+">"+x+"</option>")
		}//for
		
		for(x=1; x<=12; x++){//month
		if(x<10) var zero = 0;
		else zero = '';
			$('#birthday-month').append("<option value="+zero+x+">"+x+"</option>")
		}//for
		var date = new Date()
		var year = date.getFullYear()
		
		for(x=year; x>=1920; x--){//year
			$('#birthday-year').append("<option value="+x+">"+x+"</option>")
		}//for
		
		$('#birthday-display').html(data.birthday)
		
		$('#going-out-answer').html(data.going_out)
		
		if(data.list){	
			if(postPath) data.list = data.list.replace(/background-image:url\(/g,'background-image:url('+postPath)//if postPath provide external path
			$('#entourages-list').html(data.list)
		}//if
		
	}//if data
	
	},'json')//post
	