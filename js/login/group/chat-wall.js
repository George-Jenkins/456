var messageBox = $('#message')

var clicked = false

$(messageBox).click(function(){
	
	if(clicked == false) $(messageBox).val('')

	clicked = true
})//click

function showMoreLink(x){
	$('#message-span'+x).css('max-height','none').show()
	$('#etc-div'+x).addClass('clicked').hide()
}

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	
	
function loadWallFromSpecificReply(){
	
	if(!$('#all-posts').hasClass('underline') && !$('#my-posts').hasClass('underline')){//if these don't have class underline don't reload the chat wall
		
	$('#chat-wall').html("<img src='../../pics/ajax-loader2.gif' id='wall-loading-img' style='height:40px;width:auto'/>")
	var group = getGroupID()
	var z = getZ()
	var loop = 'first';
	$.when(
		loadWall(loop)//start loading chat wall
	).then(function(){
		var loop = 'second';
		loadWall(loop)//start loading chat wall
	})//when then
	
	}//if
	
}//function
	
//handle view filter 
$('#all-posts').click(function(){
		
	$.when(
	
	loadWallFromSpecificReply()
	
	).then(function(){
		
	$('#all-posts').addClass('underline')
	$('#my-posts').removeClass('underline')
	$('.post-div').removeClass('slide')//I'll remove class "slide" in case some posts were loaded when "my conver.." was selected 
	$('.reply-div').removeClass('slide')//I'll remove class "slide" in case some posts were loaded when "my conver.." was selected
	$('#notInvolvedStyle').remove()//notInvolvedStyle is the name if the style tag that hides the notInvolved class. It's set below
	$('.notInvolved').show()
	})//when then
	
})//click

$('#my-posts').click(function(){
	
	loadWallFromSpecificReply()
	
	$('#all-posts').removeClass('underline')
	$('#my-posts').addClass('underline')
	$('html').prepend("<style id='notInvolvedStyle'>.notInvolved{display:none}</style>")
	$('.notInvolved').hide()//I added this because if a post slides down hiding it with css won't work
	
})//click

function showReples(x){
	
	$('.replyID'+x).show()
	$('.replyID'+x).removeClass('hide')//I'll also remove class hide so the wall knows it can slide down other comments with replyIDx class
	$('#show-replies'+x).hide()
	
}//function

//this allows user to submit post and is set after user clicks textbox
$('#message').click(function(){
	clickedTextBox = true;
})
	
$('#chat-form').submit(function(e){
	
e.preventDefault()

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	

if(!clickedTextBox) return			

x=0;

//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
		
	//get group from url
	var url = window.location.href;
	var urlArray = url.split('?');
	var firstItem = urlArray[1];
	//clean item
	var firstItem = firstItem.split('&')
	var group = firstItem[0];
	
	var groupIDPhoto = '';
	if($('#group-photo-load img').length){
	var image = $('#group-photo-load img').attr('src')
	var imageID2 = $('[id="'+image+'"]').attr('id2')
	var groupIDPhoto = group+':imageID-'+imageID2//combine groupID and postID for group_id 
	}//if
	
	var message = $('#message').val()
		
	var z = getZ();
		
	if(!message) return;
		
	$('#message').val('')

	$('#submit-loader').removeClass('hide')

while(x<=8){
		
	$.post(postPath+'queries/chat-wall.php', {message:message, z:z, group:group, groupIDPhoto:groupIDPhoto}, function(data){
		
		//redirect if wrong z
		if(data.error=='wrong z'){
			window.location = "../../member-login.html";
			return;
		}//if
		
		if(data.error=='false') success = 'true';
		
		},'json')//post
		
		x++;
		//break if it was a success
		if(success == 'true') break;
		
		}//while
	})//submit	












