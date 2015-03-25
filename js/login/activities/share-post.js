(function(){

//this is path to post for apps
if(pathForPost) postPath = ''//getPostPath('http://ritzkey.com/login/activities/');
	
	var z = getZ()
	
	$.post(postPath+'queries/load-groups-to-share.php',{z:z},function(data){
		
//this is path to post for apps
if(pathForPost) postPath = ''//getPostPath('http://ritzkey.com/login/activities/');
else postPath = '';	
		
if(pathForPost) data.groupInfo = data.groupInfo.replace(/background-image:url\(/g,'background-image:url('+postPath)		
		
		$('#group-display-list').html(data.groupInfo);
		
	},'json')//post

	
	
})();



function sharePost(x2,x,network){

//get current scroll top
	currentScrollTop = $(window).scrollTop()
	$('#activities-dark-background').removeClass('hide')
	$('#activities-pic-canvas').removeClass('hide')
	$('#share-post-div').removeClass('hide')
	$('body, html').scrollTop(0)
	
	if(network=='twitter'){
	var userName = $('#tweet-container'+x2+x+' .username').html()
	var message = $('#tweet-container'+x2+x+' .tweet-message').html()
	var imageSrc = $('#tweet-container'+x2+x+' .twitter-images').attr('src')
	var onClickImageSrc = $('#tweet-container'+x2+x+' .twitter-images').attr('onClick')
	}//if
	if(network=='instagram'){
	var userName = $('#instagram-container'+x2+x+' .username').html()
	var message = $('#instagram-container'+x2+x+' .instagram-message').html()
	var imageSrc = $('#instagram-container'+x2+x+' .instagram-images').attr('src')
	var onClickImageSrc = $('#instagram-container'+x2+x+' .instagram-images').attr('onClick')
	}//if	
	if(network=='facebook'){
	var userName = $('#facebook-container'+x2+x+' .username').html()
	var message = $('#facebook-container'+x2+x+' .facebook-message').html()
	var imageSrc = $('#facebook-container'+x2+x+' .facebook-images').attr('src')
	var onClickImageSrc = $('#facebook-container'+x2+x+' .facebook-images').attr('onClick')
	}//if
	shareHtml = "[div class='username']"+userName+"[/div][div]"+message+"[/div]";
	if(typeof imageSrc!=='undefined') shareHtml+="[div][img] src='"+imageSrc+"' onClick="+onClickImageSrc+" [/img][/div]";//if there is an image add it
	
}//function

function selectGroup(x){
	
	if($('#checkMark'+x).hasClass('clicked')) $('#checkMark'+x).removeClass('clicked').addClass('hide')
	else $('#checkMark'+x).addClass('clicked').removeClass('hide')
	
}//if

//submit post to share
$('#done-button').click(function(){
	
	$('#share-feedback-message').html('').addClass('hide')
	
	var groupArray = new Array()//create array for group
	$('.check-group').each(function(){
		
		if(!$(this).hasClass('hide')){
			var group = $(this).attr('group');
			//add group to an array
			groupArray.push(group)			
			
		}//if 
		
	})//each
	
	if(groupArray=='') return;
	
	$('#share-loader').removeClass('hide')
	
	var z = getZ()
	var postPath;
	if(pathForPost) postPath = 'http://ritzkey.com/'; 
	else postPath = pathToRoot()
	
	$.post(postPath+'login/group/queries/chat-wall.php',{z:z,groupArray:groupArray,shareHtml:shareHtml},function(data){
		
	$('#share-loader').addClass('hide')
	$('.check-group').removeClass('clicked').addClass('hide')
	$('#share-feedback-message').html('Success!').removeClass('hide')	
		
	},'json')
	
})//click