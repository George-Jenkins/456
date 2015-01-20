function submitReply(id,time){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';	
	
	//redirect if no z/i
	if(!localStorage.getItem('i')) window.location = "../../member-login.html";
	
	//get group from url
	var url = window.location.href;
	var urlArray = url.split('?');
	var firstItem = urlArray[1];
	//clean item
	var firstItem = firstItem.split('&')
	var group = firstItem[0];
	
	var z = getZ();
	
	var reply = $('#message'+id).val()
	
	if(!reply) return;
	
	$('#message'+id).val('')
	
	$('#replyLoader'+id).show()
	
	x=0;
	while(x<=8){
	
	$.post(postPath+'queries/chat-wall-reply.php',{reply:reply,id:id,time:time,z:z,group:group},function(data){
		
		//redirect if wrong z
			if(data.error=='wrong z'){
				window.location = "../../member-login.html";
				return;
			}//if
		
			$('#replyLoader'+id).hide()
			$('#text-box'+id).hide()
			$('#cancel-reply'+id).hide()
			$('#reply'+id).show()
		
			$('#text-box'+id).slideUp()
			
			if(data.error=='false') success = 'true';
			
	},'json')//reply
	x++;
	//break if reply was a success
	if(success == 'true') break;
	}//while
	
}//submit reply function

//set this to null when page loads
var lastMessageID;
//click reply
function replyBox(id,time){
		
	$('#cancel-reply'+id).show()
	$('#reply'+id).hide()
	
	$('#text-box'+id).show(function(){
		$('body, html').animate({scrollTop:$('#text-box'+id).offset().top-110},300)
	})

	
	//hide the last reply divs
	$('#cancel-reply'+lastMessageID).hide()
	if(lastMessageID==id) $('#cancel-reply'+lastMessageID).show()//incase the same one is pressed again (This will likely confuse you)
	$('#reply'+lastMessageID).show()
	if(lastMessageID==id) $('#reply'+lastMessageID).hide()//incase the same one is pressed again (This will likely confuse you)
	$('#text-box'+lastMessageID).hide()
	if(lastMessageID==id) $('#text-box'+lastMessageID).show()//incase the same one is pressed again (This will likely confuse you)
	
	$('#replyLoader'+lastMessageID).hide()//hide loader
	//set last id to this one. The textarea with this id will be destroyed the next time 'reply' is clicked
	lastMessageID = id;
	
	//make sure it's blank
	$('#message'+id).val('')

}//function

//cancel reply
function cancelReply(id){
	$('#text-box'+id).hide()
	$('#cancel-reply'+id).hide()
	$('#reply'+id).show()
	
	$('#message'+id).val('')
	
}//function

//close reply box when someone clicks somewhere
$("body").click(function(e){
	
	//stop this if what is clicked isn't body
	var clickID = $(e.target).attr('id')
	if(clickID) return;
	if($(e.target).closest('a').length) return;
	
	//if the box has content stop
	var message = $('#message'+lastMessageID).val()
	if(message) return;
	
	$('#text-box'+lastMessageID).hide()
	$('#cancel-reply'+lastMessageID).hide()
	$('#reply'+lastMessageID).show()
	
	$('#message'+lastMessageID).val('')
})