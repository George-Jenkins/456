$(document).ready(function(){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	
	
		//get group from url
		var url = window.location.href;
		var urlArray = url.split('?');
		var firstItem = urlArray[1];
		//clean item
		if(firstItem){
		var firstItem = firstItem.split('&')
		var group = firstItem[0];
		}//if
		
		//add group to forms
		$('#group-pic-id').val(group)
		$('#group-background-id').val(group)
		
		sessionStorage.removeItem('startPulse')

//this is too see if user is viewing specific reply
url = document.location.href
urlArray = url.split('&')
id = urlArray[1];


if(id){
	var loop = id;
	loadWall(loop)
	$('#all-posts').removeClass('underline')
}//if id
else//
{
	var loop = 'first';
	$.when(
		loadWall(loop)//start loading chat wall
	).then(function(){
		var loop = 'second';
		loadWall(loop)
	})//when then
}//else


})//ready

//this function starts loading the wall
function loadWall(loop){

var z = getZ();
var group = getGroupID()
$.post(postPath+'queries/chat-wall-load.php',{group:group, z:z, loop:loop},function(data){

$.when(

	loadPosts()

).then(function(){
	
	loadReplies()
})

function loadPosts(){		
	if(data){
				
	if(data.error=='no group'){
		window.location = "../profile/profile.html";
		return;
	}
	
	//it's prepended on first and appended on second. This is basically so that the loading gif can stay at the bottom
	if(loop=='first') $('#chat-wall').prepend(data.post)			
	else $('#chat-wall').append(data.post)
				
	numberOfPosts = data.limit;			
				
	if(data.limit>=20 && loop=='first') $('#chat-wall').after("<div id='show-more' class='hide' onClick='show_more()'><a href='' onClick='return false'>Show earlier</a></div>")
	
	if(loop=='second'){
		$('#show-more').removeClass('hide')//show the show mow button on second posts. This is so the user can't click show more before the rest have loaded	
		//hide loading gif
		$('#wall-loading-img').hide()
	}//if
	//this function is defined in view-specific-replies.js
	viewSpecificPost()
				
	}//if
}//function loadPosts		
function loadReplies(){
	
	lastID = data.lastID//this passed into startPulse()
	firstID = data.firstID//this passed into startPulse()
	
	//replies
	if(data){
				
		for(x=0;x<data.replyNum;x++){
					
		reply = 'reply'+x;
		replyPost = data[reply];
				
		replyID = 'replyID'+x;
		post = data[replyID];
				
		$('#post'+post).append(replyPost);
		}//for
				
	}//if
		
	if(loop=='first' || loop=='second') $('#text-box').removeClass('hide')//if loop is an id keep it hidden
			
	//this tell pulse script to start
	startPulse(firstID,lastID)
		
}//function load replies				
		
},'json')//post		

//show the "show more" link if necessary
	for(x=0; x<=starting_show_next; x++){
		if($('.getPostScrollHeight'+x).length){
		var scrollHeight = $('.getPostScrollHeight'+x)[0].scrollHeight
		if(scrollHeight>105) $('.gotPostScrollHeight'+x).show()
		}//if
		if($('.getReplyScrollHeight'+x).length){
		var scrollHeight = $('.getReplyScrollHeight'+x)[0].scrollHeight
		if(scrollHeight>105) $('.gotReplyScrollHeight'+x).show()	
		}//if		
	}//for

}//function loadWall


///handle show more button (expan the post)
var starting_show_next = 40;

//load more posts
function show_more(){
	
	var limit = sessionStorage.getItem('numberOfPosts');
	
	for(x=0; x<=starting_show_next; x++){
		
		$('.post-div'+x).removeClass('hide')
		
		if(x==numberOfPosts) $('#show-more').hide();
		
			//show the "show more" link if necessary
			if($('.getPostScrollHeight'+x).length){
			var scrollHeight = $('.getPostScrollHeight'+x)[0].scrollHeight
			if(scrollHeight>105) $('.gotPostScrollHeight'+x).show()
			}//if
			if($('.getReplyScrollHeight'+x).length){
				var scrollHeight = $('.getReplyScrollHeight'+x)[0].scrollHeight
				if(scrollHeight>105) $('.gotReplyScrollHeight'+x).show()	
			}//if
					
	}//for
	
	//increase the limit
		starting_show_next = ((starting_show_next)*1)+20;
	
}//function