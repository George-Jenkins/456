$('#form').submit(function(e){
	
	e.preventDefault()
	
	$('#message').html('')
	
	var z = getZ()
	$('[name="z"]').val(z)
	
	var name = $('[name="name"]').val()
	
	if(name){
		
		var formData = $('form').serialize()
		
		$.ajax({
		
		type:'POST',
		url:'queries/add-venue.php',
		data:formData,
		dataType:'json',
		success:function(data){
		
		if(data.error=='Already added') $('#message').html('Already added')
		if(data.error===false) $('#message').html('Done')
		$('[name="z"]').val('')
		
		document.getElementById('form').reset()
			
		},	
		error:function(textStatus, errorThrown, XMLHttpRequest){
			
		},	
		})
		
	}//if
})