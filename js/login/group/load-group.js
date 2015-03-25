//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	

	//setting title blank it it is empty. Otherwise it will show default value for a second (Not sure if this works that well though)
	var title = $('title').html()//if title is blank it's because user is on group-view.html or group-member.html
	if(!title) $('title').html('')

	var z = getZ();
	
	//get group code
	var domain = document.location.href
	var array = domain.split('?')
	var firstItem = array[1]
	if(firstItem){
		var firstItem = firstItem.split('&')
		var group = firstItem[0]
	}//if
	
	$('#group-options-link').attr('href','options.html?'+group);
	
	$.post(postPath+'queries/load-group.php',{z:z, group:group},function(data){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	
		
		if(data.userType=='member'){
		window.location = "group-member.html?"+group;
		return	
		}
		if(data.userType=='not member'){
		window.location = "group-view.html?"+group;
		return	
		}
		
		$('#group-image-div').css('background-image','url('+postPath+data.img1+')').show()
		
		$('#created-by').html('Created by '+data.creatorName)
		
		$('body').css('background-image','url('+postPath+data.img2+')')
		
		if(!mobileView) $('body').css('background-attachment','fixed')
		
		$('#group-name').html(data.groupName)
		
		var title = $('title').html()//if title is blank it's because user is on group-view.html or group-member.html
		if(!title) $('title').html(data.groupName)
		else $('title').html(title+' | '+data.groupName)
		
		$('#group-mission').html(data.mission)
		
		if(postPath) data.members = data.members.replace(/background-image:url\(/g,'background-image:url('+postPath)
		$('#members').html(data.members)
		
		$('#select-state').append(data.states)
		$('#group-location-display').html(data.groupLocation)
		$('#upload-photo-for-album').attr('href','group-photos.html?'+group)
		
		//this handles events
		$('#list-events-div').html(data.events)
		$('#number-of-events').html(data.numberOfEvents)
		
		if(data.pending_approval==true){
			$('#awaiting-approval').show()
		}//if
		else{
			$('#ask-to-join-button').show()
		}		
	},'json')//post
