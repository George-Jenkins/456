(function(){
	
	$('#submit-group-location').click(function(){
		
		var groupState = $('#select-state').find('option:selected').val()
		var groupCity = $('#provide-city').val()
		if(groupCity == 'City' && !$('#provide-city').hasClass('clicked')) groupCity = '';
		
		if(!groupState) return;
		
		$('#location-loader').removeClass('hide')
		
		var z = getZ()
		var groupID = getGroupID()
		
		if(pathForPost) postPath = "http://ritzkey.com/login/group/";
		else postPath = "";
		
		$.post(postPath+'queries/edit-location.php',{z:z, groupID:groupID, groupState:groupState, groupCity:groupCity}, function(data){
			
		$('#location-loader').addClass('hide')	
		$('#edit-group-location').show()
		$('#cancel-edit-group-location').hide()
		$('#select-state-div').hide()
		$('#provide-city-div').hide()
		$('#submit-group-location-div').hide()
		$('#select-state').val('')//reset val
		$('#provide-city').removeClass('clicked').val('City')//reset val
		
		$('#group-location-display').html(data.sendLocation).show()
		
		},'json')//post
		
	})//click
	$('#edit-group-location').click(function(){
		
		$('#edit-group-location').hide()
		$('#cancel-edit-group-location').show()
		$('#select-state-div').show()
		$('#group-location-display').hide()
		
	})//click
	$('#cancel-edit-group-location').click(function(){
		
		$('#location-loader').addClass('hide')	
		$('#edit-group-location').show()
		$('#cancel-edit-group-location').hide()
		$('#select-state-div').hide()
		$('#provide-city-div').hide()
		$('#submit-group-location-div').hide()
		$('#group-location-display').show()
		$('#select-state').val('')//reset val
		$('#provide-city').removeClass('clicked').val('City')//reset val
		
	})//click
	$('#select-state').change(function(){
		
		$('#provide-city-div').show()
		$('#provide-city-div').css('display','inline-block')
		$('#select-state-div').css('display','inline-block')	
		$('#submit-group-location-div').show()
		$('#submit-group-location-div').css('display','inline-block')	
		
	})//change
	$('#provide-city').click(function(){
		
		$('#provide-city').addClass('clicked').val('')
		
	})//click
	$('#provide-city').blur(function(){
		
		//if($('#provide-city').val()=='') $('#provide-city').val('City')
		
	})//click
})();