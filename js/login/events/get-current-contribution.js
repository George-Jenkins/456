var z = getZ()
var eventID = getEventID()

getContributionsFunction = function(){
	
getContributionsInterval = setInterval(function(){
	
//this is path to post for apps
if(pathForPost) postPath = 'http://ritzkey.com/login/events/';
else postPath = '';		
	
	if(getContr==false) return;
	
	var getContr = false
	
	$.post(postPath+'queries/get-current-contribution.php',{z:z, eventID:eventID},function(data){
		
		getContr = true;
		
		if(data.done){
			
		$('#amount-contributed-span').html(data.contribution)
		
		}//if
		
	},'json')//post
	
},1000)

}//getContributionFunction

getContributionsFunction()