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
	$('#notInvolvedStyle').remove()//notInvolvedStyle is the name if the style tag that hides the notInvolved class. It's set below
	$('.post-div').removeClass('slide')//I'll remove class "slide" in case some posts were loaded when "my conver.." was selected 
	$('.reply-div').removeClass('slide')//I'll remove class "slide" in case some posts were loaded when "my conver.." was selected
	
	})//when then
	
})//click

$('#my-posts').click(function(){
	
	loadWallFromSpecificReply()
	
	$('#all-posts').removeClass('underline')
	$('#my-posts').addClass('underline')
	$('html').prepend("<style id='notInvolvedStyle'>.notInvolved{display:none}</style>")
	
})//click

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
		
	var message = $('#message').val()
		
	var z = getZ();
		
	if(!message) return;
		
	$('#message').val('')

	$('#submit-loader').show()	

while(x<=8){
		
	$.post(postPath+'queries/chat-wall.php', {message:message, z:z, group:group}, function(data){
		
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












