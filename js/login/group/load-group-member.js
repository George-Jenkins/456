$(document).ready(function(){

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
	
	$.post('queries/load-group.php',{z:z, group:group},function(data){
		
		if(data.userType=='creator'){
		window.location = "group.html?"+group;
		return	
		}
		if(data.userType=='not member'){
		window.location = "group-view.html?"+group;
		return	
		}
		
		$('#group-image-div').css('background-image','url('+data.img1+')').show()
		
		$('#created-by').html('Created by '+data.creatorName)
		
		$('body').addClass('group-background').css('background-image','url('+data.img2+')')
		
		$('#group-name').html(data.groupName)
		
		var title = $('title').html()//if title is blank it's because user is on group-view.html or group-member.html
		if(!title) $('title').html(data.groupName)
		else $('title').html(title+' | '+data.groupName)
		
		$('#group-mission').html(data.mission)
		
		$.when(
			$('#members').html(data.members)
		).then(function(){
			//var membersHeight = $('#members')[0].scrollHeight
			//if(membersHeight>240) $('#members').css('overflow-y','scroll')
		})//when then
		
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
})