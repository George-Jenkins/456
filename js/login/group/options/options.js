$(document).ready(function(){
	
	//handle clicks that show things
	$('#remove-entourage-link').click(function(){
		$('#load-group-members-div').slideDown()
		
		$('#remove-entourage-link').hide()
		$('#cancel-remove-entourage-link').show()
		
	})//click
	$('#cancel-remove-entourage-link').click(function(){
		$('#load-group-members-div').slideUp()
		
		$('#remove-entourage-link').show()
		$('#cancel-remove-entourage-link').hide()
		$('.group-div-list').removeClass('blue-background')
		$('.delete-div').hide()
	})//click
	
	var z = getZ()
	var group = getGroupID()
	
	$('#return-link').attr('href','group.html?'+group);
	
	if(!group) window.location = "../profile/profile.html";
	
	//load group members in 
	$.post('queries/options/load-options.php',{z:z, group:group},function(data){
		
		if(data.error=='wrong group') window.location = "../profile/profile.html";
		
		$('#load-group-members-div').html("<div class='text-bold'><p>Select member<p></div>"+data.groupMember).hide();
		
	},'json')//post
	
	
})
//handing group members
function selectMember(y){
	$('.group-div-list').removeClass('blue-background')
	$('#member-div'+y).addClass('blue-background')
	$('.delete-div').hide()
	$('#delete-div'+y).show()
}
function noRemove(y){
	$('#delete-div'+y).hide()
	$('#member-div'+y).removeClass('blue-background')
}
function yesRemove(y,id){
	var z = getZ()
	var group = getGroupID()
	$.post('queries/options/remove-member.php',{z:z,group:group,id:id},function(data){
		
	})//post
	$('#member-div'+y).fadeOut()
}
//end handing group members
function deleteOption(){
	$('#delete-options-div').show()
}

function noDelete(){
	$('#delete-options-div').hide()
}
function yesDelete(){
	
	var z = getZ()
	var group = getGroupID()
	
	$('#deleted-table').show()
	
	$.post('queries/options/delete-group.php',{z:z,group:group},function(data){
		
		$('#delete-msg').html('Deleted!')
	},'json')//post
	
	$('#deleted-table').show()
	$('#delete-warning').hide()
	$('#delete-table').hide()
	$('#remove-member-div').hide()
	$('#remove-entourage-link').hide()
	$('#cancel-remove-entourage-link').hide()
	$('.option-title').hide()
}