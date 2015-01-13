$(document).ready(function(){
	
$('#search-groups').keyup(function(){
		
	var input = $('#search-groups').val()
		
	if(!input){
		$('#loader1 img').hide()
		return;
	}//if
	var z = getZ()
		
	$('#loader1 img').show()
		
	setTimeout(function(){
		
	var input = $('#search-groups').val()
	var z = getZ()
		
	var loop = 'first'
	searchGroups(input,z,loop)
	
		
	},1000)//setTimeout
})//keyup
		
$('#show-more').click(function(){
	showMore()
})//click
		
		
function searchGroups(input,z,loop){
	$.post('queries/get-groups.php',{input:input, z:z, loop:loop},function(data){
			
	var finalInput = $('#search-groups').val()
			
	if(input!=finalInput) return;
	
	if(data.results!='No results') var loadingImg = "<img src='../../pics/ajax-loader2.gif' id='loader2'/>";
	else var loadingImg = "";
	
	if(loop=='first') $('#results-div').html(data.results+loadingImg)
	if(loop=='second'){
		 $('#results-div').append(data.results)	
		 $('#loader2').hide()
	}//if
	$('#loader1 img').hide()
		
	//handle the show more
	showNextNum = 20;
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
			
	var start = showNextNum
		
	for(x=0;x<=start;x++){
			
	$('#results-div'+x).show()
		
	if($('#results-div'+((showNextNum*1)+1)).length && loop=='second') $('#show-more').show()
	else $('#show-more').hide()
		
	if(!loop && !$('#results-div'+((showNextNum*1)+1)).length) $('#show-more').hide()//if loop is undefined it means button was clicked and not that
	//the page was loaded. 
			
	}//for
		
	showNextNum = ((showNextNum*1)+20);//increase limit
		
}//function 		
		
	
})