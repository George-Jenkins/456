$(document).ready(function(){

	$('#create-group-mission').submit(function(e){
	
	e.preventDefault()
	
	//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../../member-login.html";
	
	$('#feedback').html("<div class='small-processing' style='left:50%;margin-top:-15px;margin-left:-15px'></div>").show() 
	
	var group_name = $('#group-name').val()
	var group_mission = $('#group-mission').val()
	
	var z = getZ();

	if(!group_name) return;

		$.post('queries/submit-group.php',{z:z, group_name:group_name, group_mission:group_mission},function(data){
			
			if(data.error=="wrong z"){
				window.location = "../../../member-login.html";
				return;
			}//if
			
			if(data.error){
				
				$('#feedback').addClass('red').html('<div style="position:absolute;margin-top:-7px">You already created a group with that name</div>').show()
				return
			}//if
			
			if(data.error==false){
				
				$('#feedback').removeClass('red').hide()
				
				$('#group-name').val('')
				$('#group-mission').val('')
				
				window.location = '../../group/group?'+data.groupID;
				
			}//if
		
		},'json')//post

	})//submit
})