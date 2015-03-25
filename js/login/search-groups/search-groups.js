(function(){

$('#search-groups').keyup(function(){

searchMethod = 'name';//this is so function stops if input is blank and user is searching by name
	
startSearch()

})//keyup

$('#filter-search-button').click(function(){

searchMethod = 'demographic';
		
startSearch()

})//keyup
		
$('#show-more').click(function(){
	showMore()
})//click
		

function startSearch(){
	
	var input = $('#search-groups').val()
		
	if(searchMethod == 'name' && !input){$('#loader1 img').hide(); return;}//if
	
	var z = getZ()	
	
	$('#min-age-filter').removeClass('red-background')
	$('#max-age-filter').removeClass('red-background')
	$('#filter-states').removeClass('red-background');
	
	//filter variables
	var sex = '';
	if($('#female-filter').is(':checked')) sex = 'female';
	if($('#male-filter').is(':checked')) sex = 'male';
	var minAge = $('#min-age-filter').val()
	var maxAge = $('#max-age-filter').val()
	if(minAge=='Min') var minAge = '';
	if(maxAge=='Max') var maxAge = '';
		if(minAge && minAge*0!=0){ $('#min-age-filter').addClass('red-background').val('Not number'); $('#loader1 img').hide()}//make sure Min age is a number
		if(maxAge && maxAge*0!=0){ $('#max-age-filter').addClass('red-background').val('Not number'); $('#loader1 img').hide()}//make sure Min age is a number
		if(minAge && minAge*0!=0 || maxAge && maxAge*0!=0) return;
		if(minAge) $('#min-age-filter').val(Math.floor(minAge))//round int down
		if(maxAge) $('#max-age-filter').val(Math.floor(maxAge))//round int down
	var city = $('#city-filter').val()
	if(city=='City') var city = '';
	var state = $('#filter-states').find('option:selected').val()
	if(city && !state){ $('#filter-states').addClass('red-background'); return;}
	//end filter variables
	
	$('#loader1 img').show()	
	
	setTimeout(function(){
		
	var inputFinal = $('#search-groups').val()
	
	if(inputFinal!=input) return
		
	var z = getZ()
		
	var loop = 'first'
	
	
	searchGroups(input, z, loop, sex, minAge, maxAge, state, city)
	
		
	},1000)//setTimeout	
}//function startSearch
		
function searchGroups(input, z, loop, sex, minAge, maxAge, state, city){

//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/search-groups/';
else postPath = '';	
	
	$.post(postPath+'queries/get-groups.php',{input:input, z:z, loop:loop, sex:sex, minAge:minAge, maxAge:maxAge, state:state, city:city},function(data){
	
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
	searchGroups(input, z, loop, sex, minAge, maxAge, state, city)
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

})();




