(function(){

//get states
if(pathForPost) postPath = 'http://ritzkey.com/login/search-groups/';
else postPath = '';

var z = getZ()
$.post(postPath+'queries/get-states.php', {z:z}, function(data){
	$('#filter-states').append(data.states)
},'json')//post

//handle filter
$('#by-demographic-button').click(function(){
	$('#by-demographic-button').hide()
	$('#by-name-button').show()
	$('#filters-div').show()
	$('#name-search-span').hide()
	
	//reset search field
	$('#search-groups').val('')
})//click

$('#by-name-button').click(function(){
	$('#by-demographic-button').show()
	$('#by-name-button').hide()
	$('#filters-div').hide()
	$('#name-search-span').show()
	//reset fields
	$('#male-filter').attr('checked',false)
	$('#female-filter').attr('checked',false)
	$('#min-age-filter').val('Min')
	$('#max-age-filter').val('Max')
	$('#city-filter').val('City')
	$('#filter-states').val('')
	
	$('#min-age-filter').removeClass('red')//this class may be added on error in search-group.js
	$('#max-age-filter').removeClass('red')//this class may be added on error in search-group.js
	$('#filter-states').removeClass('red-background');//this class may be added on error in search-group.js
})//click		

$('#female-filter').click(function(e){
	$('#male-filter').attr('checked',false)
})//click

$('#male-filter').click(function(){
	$('#female-filter').attr('checked',false)
})//click

$('#min-age-filter').click(function(){
	var minAgeValue = $('#min-age-filter').val()
	if(minAgeValue=='Min' || minAgeValue=='Not number') $('#min-age-filter').removeClass('red-background').val('')
})//click

$('#max-age-filter').click(function(){
	var maxAgeValue = $('#max-age-filter').val()
	if(maxAgeValue=='Max' || maxAgeValue=='Not number') $('#max-age-filter').removeClass('red-background').val('')
})//click

$('#city-filter').click(function(){
	var cityFilter = $('#city-filter').val()
	if(cityFilter=='City') $('#city-filter').val('')
})//click

$('#filter-states').click(function(){
	$('#filter-states').removeClass('red-background');//this class may be added on error in search-group.js
})

$('#min-age-filter, #max-age-filter, #city-filter').blur(function(){
	var minAgeValue = $('#min-age-filter').val()
	if(minAgeValue=='') $('#min-age-filter').val('Min')
	var maxAgeValue = $('#max-age-filter').val()
	if(maxAgeValue=='') $('#max-age-filter').val('Max')
	var cityFilter = $('#city-filter').val()
	if(cityFilter=='') $('#city-filter').val('City')
})//click
	
})();