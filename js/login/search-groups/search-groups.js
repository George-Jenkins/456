$('#search-groups').keyup(function(){
		
	var input = $('#search-groups').val()
		
	if(!input){
		$('#loader1 img').hide()
		return;
	}//if
	var z = getZ()
		
	$('#loader1 img').show()
		
	setTimeout(function(){
		
	var inputFinal = $('#search-groups').val()
	
	if(inputFinal!=input) return
		
	var z = getZ()
		
	var loop = 'first'
	searchGroups(input,z,loop)
	
		
	},1000)//setTimeout
})//keyup
		
$('#show-more').click(function(){
	showMore()
})//click
		
		
function searchGroups(input,z,loop){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/search-groups/';
else postPath = '';	
	
	$.post(postPath+'queries/get-groups.php',{input:input, z:z, loop:loop},function(data){
	
	if(data.results!='No results') var loadingImg = "<img src='../../pics/ajax-loader2.gif' id='loader2'/>";
	else var loadingImg = "";
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/search-groups/';
else postPath = '';		
	
	if(pathForPost && data.results) data.results = data.results.replace(/background-image:url\(/g,'background-image:url('+postPath);
	
	if(loop=='first') $('#results-div').html(data.results+loadingImg)
	if(loop=='second'){
		
		 $('#results-div').append(data.results)	
		 $('#loader2').hide()
	}//if
	$('#loader1 img').hide()
		
	//handle the show more
	showNextNum = 10;
	start = 0;
	showMore(loop)
	
	//I put the function inside itself to get the second loop. When I did when/then it sometimes didn't load the second loop
	if(loop == 'first'){
	loop = 'second'
	searchGroups(input,z,loop)
	}//if
	
	},'json')//post	
}//function
		
		
//this function shows hidden results
function showMore(loop){
		
	for(x=start;x<=showNextNum;x++){
			
	$('#results-div'+x).show()
		
	if($('#results-div'+((showNextNum*1)+1)).length && loop=='second') $('#show-more').show()
	else $('#show-more').hide()
		
	if(!loop && !$('#results-div'+((showNextNum*1)+1)).length) $('#show-more').hide()//if loop is undefined it means button was clicked and not that
	//the page was loaded. 
			
	}//for
		
	showNextNum = ((showNextNum*1)+10);//increase limit
	start = ((start*1)+10);//increase start
		
}//function 		
