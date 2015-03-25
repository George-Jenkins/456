(function(){

//submit post
$('#submit-post').click(function(){
	
	var z = getZ()
	var groupID = getGroupID()

	
	var message = $('#message-box').val()
	if($('#message-box').hasClass('no-comment')) message = '';
	
	if(!message) return;
	
	$('#submit-message-loader').removeClass('hide')
	
	var postPath = getPostPath('http://ritzkey.com/login/group/')
	
	$.post(postPath+'queries/chat-wall.php', {group:groupID, groupIDPhoto:groupIDForPost, message:message, z:z}, function(data){
		
		$('#submit-message-loader').addClass('hide')
		
	},'json')//post
	
})//submit


$('#chat-send').focus(function(){
	if($('#chat-send').hasClass('no-comment')) $('#chat-send').removeClass('no-comment').val('')
})//click

//for now this won't work	
$('#chat-send').blur(function(){
	if(!$('#chat-send').val()) $('#chat-send').addClass('no-comment').val('Comment')
})//click	
	
})();