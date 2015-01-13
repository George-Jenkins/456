$(document).ready(function(){
	
	//show and cancel edit box
	$('#group-mission-edit-link').click(function(){
		
		$('#group-mission-edit-div').hide()
		$('#group-mission-cancel-edit-div').show()
		$('#mission-text-area-div').show()
		$('#group-mission').hide()
	})//click
	
	$('#group-mission-cancel-edit-link').click(function(){
		
		$('#group-mission-edit-div').show()
		$('#group-mission-cancel-edit-div').hide()
		$('#mission-text-area-div').hide()
		$('#group-mission').show()
		
		$('#mission-text-area').val('')
		$('#load-icon2').hide()
	})//click
	
	//end show and cancel edit box
	
	//submit group mission
	$('#submit-mission').click(function(){
		
		//redirect to login if not logged in
		if(!localStorage.getItem('i')) window.location = "../profile/profile";
		
	var mission = $('#mission-text-area').val()
	
	if(!mission) return;
	
	$('#load-icon2').show()
	
	var z = getZ()
	
	//grap group id from form
	var group = $('#group-background-id').val()
	
		$.post('queries/submit-mission.php',{mission:mission, group:group, z:z},function(data){
			
			if(data.error=='wrong z'){
				
				window.location = "../../member-login.html";
			}//if
			
			if(data.error=='no group'){
				
				window.location = "../profile/profile";
			}//if
			
			if(data.done=='done'){
				
				$('#group-mission-edit-div').show()
				$('#group-mission-cancel-edit-div').hide()
				$('#mission-text-area-div').hide()
				$('#group-mission').show()
		
				$('#mission-text-area').val('')
				$('#load-icon2').hide()
				$('#group-mission').html(data.mission)
			}//if
			
		},'json')//post
	
	})//click
	
})//ready

