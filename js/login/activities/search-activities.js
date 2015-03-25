var doneSearching = 0;//this will add up to three when all are done done
	
$('#search-activities').click(function(){

if($('#deals-radio').is(':checked')) lookingFor = 'deals';
if($('#events-radio').is(':checked')) lookingFor = 'events';
var searchArea = $('#search-area-input').val()
if(searchArea=='City, State') searchArea = '';
var maxDistance = $('#max-distance').val()
var postTimeLimit = $('#post-time-limit').val()

if(!lookingFor || !searchArea) return

//hide suggestions
$('#city-suggestions-div').html('').addClass('hide')

//break string into city and state
var searchAreaArray = searchArea.split(',')
var City = searchAreaArray[0];
var State = searchAreaArray[1];

$('#feedback-message').html('')
$('#twitter-icon-search, #twitter-loader').removeClass('hide');
$('#instagram-icon-search, #instagram-loader').removeClass('hide');
$('#facebook-icon-search, #facebook-loader').removeClass('hide');
$('#show-options').addClass('hide')//hide the showing buttons incase they were previously showing

//this function gets the longitude and latitude of a city 
getLongLat()
function getLongLat() {
	var lastScript = document.getElementById('getLongLat')
	if(lastScript) lastScript.remove()
	var HOST_URL = "http://open.mapquestapi.com";
	var SAMPLE_POST = HOST_URL + '/geocoding/v1/address?key=Fmjtd%7Cluu821uy2g%2C72%3Do5-94bsd6&location='+City+','+State+'&callback=renderGeocode';

    var script = document.createElement('script');
    script.type = 'text/javascript';
    var newURL = SAMPLE_POST//.replace('Fmjtd%7Cluu821uy2g%2C72%3Do5-94bsd6', APP_KEY);
    script.src = newURL;
	script.id = 'getLongLat';
    document.body.appendChild(script);
	
};
	
})//click

//this is the call back when getting longitude and latitude. Here is where longitude and latitude are sent
function renderGeocode(response) {
	
    var i = 0;
    var j = 0;
	
	var location = response.results[0].locations[0];
	
	if(typeof location==='undefined'){//if location isn't found it equals undefined
		$('#feedback-message').html('No results')
		$('#twitter-icon-search, #twitter-loader').removeClass('hide');
		$('#instagram-icon-search, #instagram-loader').removeClass('hide');
		$('#facebook-icon-search, #facebook-loader').removeClass('hide');
		$('#show-options').addClass('hide')//hide the showing buttons incase they were previously showing
		return;
	}//if
	
	latitude = location.latLng.lat;
	longitude = location.latLng.lng;

var lookingFor = '';

if($('#deals-radio').is(':checked')) lookingFor = 'deals';
if($('#events-radio').is(':checked')) lookingFor = 'events';
var searchArea = $('#search-area-input').val()
if(searchArea=='City, State') searchArea = '';
var maxDistance = $('#max-distance').val()
var postTimeLimit = $('#post-time-limit').val()

if(!lookingFor || !searchArea) return

//break string into city and state
var searchAreaArray = searchArea.split(',')
var City = searchAreaArray[0];
var State = searchAreaArray[1];

var z = getZ()
	
$('#searching-social-media').removeClass('hide');//show searching social media div

var postPath = ''//getPostPath('http://ritzkey.com/login/activities/');

//twitter
var twitterResults = '';
while(x<2){
$.post(postPath+'queries/search-twitter.php',{z:z, lookingFor:lookingFor, City:City, State:State, maxDistance:maxDistance,
latitude:latitude, longitude:longitude, postTimeLimit:postTimeLimit},function(data){

//show container
if(data.results){
//$('#twitter-results-container').removeClass('hide')
$('#show-twitter-span').removeClass('hide')
}
else{
$('#twitter-results-container').addClass('hide')
$('#show-twitter-span').addClass('hide')
}
//hide twitter icon and loader
$('#twitter-icon-search, #twitter-loader').addClass('hide');
//show results	
twitterResults+=data.results ;
$('#twitter-results').html(twitterResults)
//increase doneSearching
doneSearching++;
if(doneSearching==3){
$('#searching-social-media').addClass('hide');//hide that text
doneSearching=0;
$('#show-options').removeClass('hide')
showContainers()
}
	
},'json')//post
x++;
}//while


//instagram
$.post(postPath+'queries/search-instagram.php',{z:z, lookingFor:lookingFor, City:City, State:State, maxDistance:maxDistance,
latitude:latitude, longitude:longitude, postTimeLimit:postTimeLimit},function(data){

//show container
if(data.results){
//$('#instagram-results-container').removeClass('hide')
$('#show-instagram-span').removeClass('hide')
}
else{
$('#instagram-results-container').addClass('hide')
$('#show-instagram-span').addClass('hide')
}
//hide instagram icon and loader
$('#instagram-icon-search, #instagram-loader').addClass('hide');
//show results	
$('#instagram-results').html(data.results)

//increase doneSearching
doneSearching++;
if(doneSearching==3){
$('#searching-social-media').addClass('hide');//hide that text
doneSearching = 0;
$('#show-options').removeClass('hide')
showContainers()
}//if done
	
},'json')//post

//facebook
$.post(postPath+'queries/search-facebook.php',{z:z, lookingFor:lookingFor, City:City, State:State, maxDistance:maxDistance,
latitude:latitude, longitude:longitude, postTimeLimit:postTimeLimit},function(data){

//show container
if(data.results){
//$('#facebook-results-container').removeClass('hide')
$('#show-facebook-span').removeClass('hide')
}
else{
$('#facebook-results-container').addClass('hide')
$('#show-facebook-span').addClass('hide')
}
//hide facebook icon and loader
$('#facebook-icon-search, #facebook-loader').addClass('hide');
//show results	
$('#facebook-results').html(data.results)
//increase doneSearching
doneSearching++;
if(doneSearching==3){
$('#searching-social-media').addClass('hide');//hide that text
doneSearching=0;
$('#show-options').removeClass('hide')
showContainers()
}
	
},'json')//post

}//function renderGeocode

//handle which results to hide
$('[name="select-show-box"]').change(function(e){
	
var network = $(this).attr('value')

$('#twitter-results-container').addClass('hide')
$('#instagram-results-container').addClass('hide')
$('#facebook-results-container').addClass('hide')
$('#'+network+'-results-container').removeClass('hide')
})//change

function showContainers(){
var twitterResults = $('#twitter-results').html()
var instagramResults = $('#instagram-results').html()
var facebookResults = $('#facebook-results').html()
if(twitterResults){
	$('#twitter-results-container').removeClass('hide');	
	$('#show-twitter-box').click()
}
else if(instagramResults){
	$('#instagram-results-container').removeClass('hide');	
	$('#show-instagram-box').click()
}
else if(facebookResults){
	$('#facebook-results-container').removeClass('hide');
	$('#show-facebook-box').click()
}
else $('#feedback-message').html('No results')
}//function


//show more tweets
function showMoreTweets(venueNumber,numberOfTweets){

//hide all containers after first
$('.not-first-tweet').addClass('hide')
//show the show more buttons
$('.more-tweets').removeClass('hide')

for(var x=0;x<numberOfTweets;x++){

$('#tweet-container'+venueNumber+x).removeClass('hide')

}//for

$('#showMoreTweets'+venueNumber+numberOfTweets).addClass('hide')
	
}//function

//show more instagram
function showMoreInstagram(venueNumber,numberOfInstagram){

//hide all containers after first
$('.not-first-instagram').addClass('hide')
//show the show more buttons
$('.more-instagram').removeClass('hide')

for(var x=0;x<numberOfInstagram;x++){

$('#instagram-container'+venueNumber+x).removeClass('hide')

}//for

$('#showMoreInstagram'+venueNumber+numberOfInstagram).addClass('hide')
	
}//function

//show more facebook
function showMoreFacebook(venueNumber,numberOfFacebook){

//hide all containers after first
$('.not-first-facebook').addClass('hide')
//show the show more buttons
$('.more-facebook').removeClass('hide')

for(var x=0;x<numberOfFacebook;x++){

$('#facebook-container'+venueNumber+x).removeClass('hide')

}//for

$('#showMoreFacebook'+venueNumber+numberOfFacebook).addClass('hide')
	
}//function

//show images
function highLightImage(image){
	//get current scroll top
	currentScrollTop = $(window).scrollTop()
	$('#activities-dark-background').removeClass('hide')
	$('#activities-pic-canvas').removeClass('hide')
	$('#load-activity-image').removeClass('hide')
	$('#load-activity-image').html("<img src='"+image+"'/>")
	$('body, html').scrollTop(0)
}//function
$('#close-canvas').click(function(){
	$('#activities-dark-background').addClass('hide')
	$('#activities-pic-canvas').addClass('hide')
	$('#load-activity-image').addClass('hide')
	$('#share-post-div').addClass('hide')//has instructions for sharing post
	$('.check-group').removeClass('clicked').addClass('hide')//rehide all checkmarks
	$('#share-feedback-message').html('').addClass('hide')//clear feedback message
	$('#load-activity-image').html("")
	//return to current position
	$('body, html').scrollTop(currentScrollTop)
})

