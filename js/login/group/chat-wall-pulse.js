function startPulse(firstID,lastID){/*I need the firstID and lastID when posts load so that I know how many times to loop to see what's been deleted*/

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	

	//put lastID in a session so it can be changed later
	sessionStorage.setItem('lastID',lastID);
	
	var stopPostPulse;
	
	setInterval(function(){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';		
		
	//stop if posts hasn't finished
	if(stopPostPulse==true) return;
	stopPostPulse = true;//this gets set to false only when post is done	
		
	//get group from url
	var url = window.location.href;
	var urlArray = url.split('?');
	var firstItem = urlArray[1];
	//clean item
	var firstItem = firstItem.split('&')
	var group = firstItem[0];
		
		var Go = sessionStorage.getItem('go')
		if(Go === 'false') return;
		
		var z = getZ();
		
		$.post(postPath+'queries/chat-wall-pulse.php',{z:z, group:group, postPath:postPath}, function(data){	
			
			stopPostPulse = false;
			
			//load posts
			for(x=0;x<=data.numberOfLoops;x++){
				
				//get id
				var idLoop = 'id'+x;
				var id = data[idLoop]
				
				var updateLoop = 'update'+x
				var content = data[updateLoop]
				
				//this sessions helps prevent old posts from showing multiple times
				var oldPostID = sessionStorage.getItem('post'+id);
				
				if(!$('#post'+id).length && !oldPostID){
					 $('#chat-wall').prepend(content)
					
					//update lastID in a session. This is important for checking for deleted posts. 
					var lastIDSession = sessionStorage.getItem('lastID');
					if(data.lastID > lastIDSession) sessionStorage.setItem('lastID',data.lastID);//only update it if it's greater so that the id number doesn't go down
				}//if
				
				//set a session for the post to help prevent old posts from showing multiple times
				sessionStorage.setItem('post'+id,id)
				
				//function to slide down post
				function slideDownPost(){
					
					//stop if deleting
					if($('#chat-wall').hasClass('deleting')) return;
					//stop if deleted
					if($('#post'+id).hasClass('deleted')) return;
					//stop if hidden
					if($('#post'+id).hasClass('hide')) return;	
						
					$('#post'+id).slideDown(1000, function(){
					if(data.mine) $('#submit-loader').addClass('hide')//this is shown in chat-wall.js
					})
					
				}//function
				
					//handle what slides down if "my conversations" is selected
					if($('#my-posts').hasClass('underline')){
						
						if(!$('#post'+id).hasClass('notInvolved')) slideDownPost()
					}//if
					//handle what happens if the user is looking at specific replies. For now I'll use "all posts" and "my coversations" not being underlined as an indication
					else if(!$('#all-posts').hasClass('underline') && !$('#my-posts').hasClass('underline')){}//else if
						
					else slideDownPost()
			
			//handle removing any comments that were deleted
			var deletedLoop = 'deletedPost'+x;
			var deletedPost = data[deletedLoop];
			if(deletedPost=='delete') $('#post'+x).slideUp()	
			
			var idLoop = 'id'+x;
			var id = data[idLoop];
			var scrollHeight = $('#message-span'+id)[0].scrollHeight
			if(scrollHeight>105 && !$('#etc-div'+id).hasClass('clicked')) $('#etc-div'+id).show()
			
			}//for
			
			
	
	
			},'json')//post
	
	},1500)

var stopReplyPulse;

//handle replies
setInterval(function(){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	

	//stop if posts hasn't finished
	if(stopReplyPulse==true) return;
	stopReplyPulse = true;//this gets set to false only when post is done	
	
	//get group from url
	var url = window.location.href;
	var urlArray = url.split('?');
	var firstItem = urlArray[1];
	//clean item
	var firstItem = firstItem.split('&')
	var group = firstItem[0];
	
	var Go = sessionStorage.getItem('go')
	if(Go === 'false') return;
	
	var i = localStorage.getItem('i');
	var k = localStorage.getItem('k');
	var z = sjcl.decrypt(k,i);
	
		$.post(postPath+'queries/chat-wall-replies-pulse.php',{z:z, group:group, postPath:postPath}, function(data){
			
			stopReplyPulse = false;
			
			if(data){
				for(x=0;x<data.limit;x++){
					ID = 'ID'+x;
					
					//get the id of the reply
					reply_update = 'reply_update'+x;
					replyID = data[reply_update];
					
					post_id = 'postID'+x
					postID = data[post_id];
					
					last_id = 'lastID'+x;
					lastID = data[last_id];
					
					//this is basically so that a reply is added on to the last reply
					
				//this sessions helps prevent old posts from showing multiple times
				var oldPostID = sessionStorage.getItem('post'+replyID);
					
					if(!$('#post'+ID).length && !oldPostID){ 
					
					$('#post'+postID).append(replyID)
					
					//update lastID in a session. This is important for checking for deleted posts. 
					var lastIDSession = sessionStorage.getItem('lastID');
					if(data.lastID > lastIDSession) sessionStorage.setItem('lastID',data.lastID);//only update it if it's greater so that the id number doesn't go down
					}//if !$('#post'+ID).length && !oldPostID
					
				//set a session for the post to help prevent old posts from showing multiple times
				sessionStorage.setItem('post'+replyID,replyID)
					
				}//for
				
				//load replies
				for(x=0;x<=data.numberOfLoops;x++){
				var idLoop = 'id'+x;
				var id = data[idLoop]
				
				post_id = 'postID'+x
				postID = data[post_id];
				
				originalPostData = 'originalPostID'+x
				originalPostID = data[originalPostData];
				
				//this function slides the post down to show it
				function slidePostDown(){
					//stop if deleting
					if($('#chat-wall').hasClass('deleting')) return;
					//stop if deleted
					if($('#post'+id).hasClass('deleted')) return;
					//stop if hidden
					if($('#post'+id).hasClass('hide')) return;
					//stop if the user hasn't click show replies yet for this post only if user didn't type this reply
					if($('.replyID'+originalPostID).hasClass('hide') && !$('#post'+id).hasClass('my-post')){
					$('#post'+id).removeClass('slide').addClass('hide').addClass('replyID'+originalPostID)//remove slide but add originalPostID and hide so it's just like the replies loaded when wall loaded	
					return
					}//if
					$('#post'+id).slideDown(1000)//slideDown
				
				}//function
				//this function scrolls the page to the reply if it is the users reply
				function scrollToPost(){
					if($('#post'+id).hasClass('my-post')){
					$('body, html').animate({scrollTop:$('#post'+id).offset().top-110},300)
					$('#post'+id).removeClass('my-post')
					}//if
				}//function
				
					//handle what slides down if "my conversations" is selected
					if($('#my-posts').hasClass('underline')){
						
						if(!$('#post'+id).hasClass('notInvolved')) $.when(slidePostDown()).then(scrollToPost())
						
					}//if
					//handle what happens if the user is looking at specific replies. For now I'll use "all posts" and "my coversations" not being underlined as an indication
					else if(!$('#all-posts').hasClass('underline') && !$('#my-posts').hasClass('underline')){
						//get the id of the post the reply is added to
						originalPostData = 'originalPostID'+x
						originalPostID = data[originalPostData];
						if(viewSpecificPost()==originalPostID) $.when(slidePostDown()).then(function(){
							
							scrollToPost()
						})
						
						}//else if	
					else $.when(slidePostDown()).then(function(){
							
						scrollToPost()
						})
				}//for
				
				//decide whether to show the show more button
				for(x=data.numberOfLoops;x>=0;x--){
					var idLoop = 'id'+x;
					var id = data[idLoop];
					var scrollHeight = $('#message-span'+id)[0].scrollHeight
					if(scrollHeight>105 && !$('#etc-div'+id).hasClass('clicked')) $('#etc-div'+id).show()
				}//for
				
			}//if data
	
		},'json')//post
		},1500)	
	
	
var stopDeletePulse;
//check for deleted posts
setInterval(function(){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	
	
	//stop if posts hasn't finished
	if(stopDeletePulse==true) return;
	stopDeletePulse = true;//this gets set to false only when post is done	
	
	var group = getGroupID()
	var z = getZ()
	var lastID = sessionStorage.getItem('lastID')
	
	$.post(postPath+'queries/check-for-deleted.php',{z:z, group:group, firstID:firstID, lastID:lastID},function(data){
		
		stopDeletePulse = false
		
		for(x=firstID;x<=lastID;x++){
			var deleteLoop = 'deletedPost'+x;
			var deletedID = data[deleteLoop];
			
			if(deletedID == 'deleted') $('#post'+x).addClass('deleted').slideUp(1000, function(){
			$('#post'+x).remove()	
			})/*the class deleted tells the slide down function no to slide it down again*/
			
		}//for
		
	},'json')//post
	
},1000)//setInterval
		
}//start pulse function

