$(document).ready(function(){
	
var z = getZ();
	
var loop = 'first';
		
getReplies().done(function(){

showHidden(loop)

loop = 'second';
	
getReplies().done(function(){

showHidden(loop)	

})


	
})//get replies
	
function getReplies(){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/replies/';
else postPath = '';		
	
	var deferred = $.Deferred()
	
	//get replies
	$.post(postPath+'queries/load-replies.php',{z:z, loop:loop},function(data){
	
	//hide loader right away if there are no replies.
	if(data.replies=='Currently no replies') $('#loader1').hide()	 
		
	if(loop=='first') $('#replies-container').prepend(data.replies)
	else if(data.replies!='Currently no replies') $('#replies-container').append(data.replies)
		
	deferred.resolve()	
	
	},'json')//post
	
	return deferred.promise()
	
}//function

})//ready

showNextStart = 10//this is the limit for the showHidden loop
function showHidden(loop){

	if(loop=='second') $('#loader1').hide()

	//handle showing next hidden comments
	var limit = showNextStart
	
	for(x=0;x<=limit;x++){
		$('#hiding-div'+x).removeClass('hide')
		if($('#post-div'+x).length){
		scrollHeight = $('#post-div'+x)[0].scrollHeight
		if(scrollHeight>68) $('#show-more'+x).removeClass('hide')
		
		}//if length
	}//for
	
	//hide #show-earlier-div if necessary
	var next = (showNextStart*1) + 1;
	
	if(!$('#hiding-div'+next).length) $('#show-earlier-div').hide()
	else if(loop!='first') $('#show-earlier-div').show()//show only if it isn't the first loop.

	if(loop!='first') showNextStart = ((showNextStart*1)+10)//increase limit only if it isn't the first loop.
	
	
}//function showHidden

function showMore(x){
	$('#post-div'+x).css('max-height','none')
	$('#show-more'+x).hide()
}//function showMore