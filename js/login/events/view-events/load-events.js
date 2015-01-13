$(document).ready(function(){
	
	var z = getZ()
	
	$.post('queries/view-events/load-events.php',{z:z},function(data){
		
		$('#loader1').hide()
		
		$('#load-events-div').html(data.Events).show()
		
	},'json')
	
	
	
})