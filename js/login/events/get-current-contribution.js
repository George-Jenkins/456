$(document).ready(function(){
	
	var z = getZ()
	var eventID = getEventID()
	
	setInterval(function(){
	
	if(getContr==false) return;
	
	var getContr = false
	
	$.post('queries/get-current-contribution.php',{z:z, eventID:eventID},function(data){
		
		getContr = true;
		
		if(data.done){
			
		$('#amount-contributed-span').html(data.contribution)
		
		}//if
		
	},'json')//post
	
	},1000)
})

