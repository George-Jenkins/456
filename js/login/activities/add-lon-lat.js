//this function gets the longitude and latitude of a city 

var z = getZ()
var currentID;
var nextID;
var city;
var state;
var street;

getLocation = function(lastID){
	
	$.post('queries/get-location.php',{z:z,lastID:lastID},function(data){
		
		currentID = data.currentID
		city = data.city
		state = data.state
		street = data.street
		
		var HOST_URL = "http://open.mapquestapi.com";
		var SAMPLE_POST = HOST_URL + '/geocoding/v1/address?key=Fmjtd%7Cluu821uy2g%2C72%3Do5-94bsd6&street='+street+'&city='+city+'&state='+state+'&callback=renderGeocode';
	alert(street)
		var lastScript = document.getElementById('gettingLocation');
		if(lastScript) lastScript.remove()
   		var script = document.createElement('script');
    	script.type = 'text/javascript';
    	var newURL = SAMPLE_POST//.replace('Fmjtd%7Cluu821uy2g%2C72%3Do5-94bsd6', APP_KEY);
    	script.src = newURL;
		script.id='gettingLocation'
    	document.body.appendChild(script);
		
	},'json')//post
	
}//function

function startAdding(){
	
	getLocation(0)
	
}//function

startAdding()


//this is the call back when getting longitude and latitude. Here is where longitude and latitude are sent
function renderGeocode(response) {
    var i = 0;
    var j = 0;
	
	var location = response.results[0].locations[0];
	
	latitude = location.latLng.lat;
	longitude = location.latLng.lng;
	

	$.post('queries/get-location.php',{z:z,currentID:currentID,latitude:latitude,longitude:longitude},function(data){
		
	if(data.nextID) getLocation(currentID)
	else alert('done')
	
	},'json')//post
	
	
}

