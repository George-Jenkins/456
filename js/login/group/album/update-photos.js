(function(){
	
$('#add-caption-div-cancel').hide()
	
$('#add-caption-div').click(function(){
	$('#add-caption-div').hide()
	$('#add-caption-div-cancel').show()
	$('#add-caption-area').css('text-align','right')//for some reason clicking makes this element lose right text align
	$('#edit-caption-area').removeClass('hide')
	$('#caption-container').hide()
})//click

$('#add-caption-div-cancel').click(function(){
	$('#add-caption-div').show()
	$('#add-caption-div-cancel').hide()
	$('#add-caption-area').css('text-align','right')//for some reason clicking makes this element lose right text align
	$('#edit-caption-area').addClass('hide')
	$('#caption-input').addClass('no-content').val('Caption')
	$('#caption-container').show()
})//click

$('#caption-input').focus(function(){
	if($('#caption-input').hasClass('no-content')) $('#caption-input').removeClass('no-content').val('')	
})
$('#caption-input').blur(function(){
	var currentContent = $('#caption-input').val()
	if(!currentContent) $('#caption-input').addClass('no-content').val('Caption')	
})

//submit caption
$('#submit-caption').click(function(){
	
	$('#submit-caption-loader').removeClass('hide')
	
	var postPath = getPostPath('http://ritzkey.com/login/group/')
	var imageSrc = $('#group-photo-load img').attr('src')
	var imageID = $('[id="'+imageSrc+'"]').attr('id2')
	var z = getZ()
	
	var captionInput = $('#caption-input').val()
	if($('#caption-input').hasClass('no-content') && captionInput=='Caption') captionInput = '';
	
	$.post(postPath+'queries/album/submit-caption.php',{z:z, captionInput:captionInput, imageID:imageID},function(data){
	$('[captionID="'+imageID+'"]').removeClass('hide').html(captionInput)
	//reset
	$('#add-caption-div').show()
	$('#add-caption-div-cancel').hide()
	$('#add-caption-area').css('text-align','right')//for some reason clicking makes this element lose right text align
	$('#edit-caption-area').addClass('hide')
	$('#caption-input').addClass('no-content').val('Caption')
	$('#caption-container').show()
	$('#submit-caption-loader').addClass('hide')
	},'json')//post
})//click
	
})();