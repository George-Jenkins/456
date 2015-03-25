<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<script src='../js/jquery.js'></script>
<script src="tweets_json.php?count=1&screen_name=NiaConley"></script>
<script>
$(document).ready(function(){
	
			$.getJSON('http://abstracthealth.com/get-tweets/tweets_json.php?count=1&screen_name=NiaConley',function(data){
		
		if(!data) alert();
			$('#tweetlist').html(data.created_at[0]);	
				})//function
				

	})
</script>

<div id='tweetlist'></div>

</body>
</html>