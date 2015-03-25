$('#upload-album').change(function(){
	
	$('#upload-feedback').removeClass('red').html('').hide()
	$('#group-photo-loader').removeClass('hide')

	var z = getZ()
	var groupID = getGroupID()
	$('#upload-album-z').val(z)
	$('#group-album-id').val(groupID)
	
	$('#upload-album-form').submit()//submit
	
	
	
	
})//change

function sendFeedback(result,caption,uploadedBy){
	
	$('#upload-album-z').val('')
	$('#group-album-id').val('')
	$('#upload-album').val('')
	
	$('#group-photo-loader').addClass('hide')
	
	if(result=='error'){$('#upload-feedback').addClass('red').html('Not an image').show(); return;}
	
	$('#upload-feedback').html('Done!').show(); 
	$('#load-photo-div').prepend(result)
	$('#caption-container').prepend(caption)
	$('#uploaded-by-container').prepend(uploadedBy)
	
	return;
	
}//function
	
//if on app let cordova framework handle image upload
if(mobileView){
	
$('#upload-album').click(function(e){

e.preventDefault()	
	
if(typeof lastImageDataBaseID==='undefined') lastImageDataBaseID = $('[showmoreid="0"]').attr('id2')//this will get the database id of the last image in the db. This will be changed below once the image has been added to the db	

navigator.camera.getPicture(onSuccess, onFail, { quality: 50,
    destinationType: Camera.DestinationType.FILE_URI,
	sourceType : Camera.PictureSourceType.PHOTOLIBRARY 
});

function onSuccess(imageData) {
	$('#upload-feedback').removeClass('red').html('').hide()
	$('#group-photo-loader').removeClass('hide')
	uploadImage(imageData)
	
}

function onFail(message) {
    //alert('Failed because: ' + message);
}

<!--upload image-->
function uploadImage(imageData){

var group_id = getGroupID();
var z = getZ();

var options = new FileUploadOptions();
options.fileKey = "upload-album";
options.fileName = imageData.substr(imageData.lastIndexOf('/') + 1);
options.mimeType = "image/jpeg";
var params = {z:z, 'group-id':group_id};

options.params = params;

var win = function (r) {//this is success callback for FileTranser

var x = 0;
var interval = setInterval(function(){
	
	var z = getZ()	
	
	var group_id = getGroupID();
	$.post('http://ritzkey.com/login/group/queries/album/check-image-change-mobile.php',{z:z,group_id:group_id,lastImageDataBaseID:lastImageDataBaseID},function(data){
		
		if(data.lastDBID){
			
		$('#load-photo-div').prepend(data.result)
		$('#caption-container').prepend(data.caption)
		$('#uploaded-by-container').prepend(data.uploadedBy)
		
		lastImageDataBaseID = data.lastDBID//reset lastImageDataBaseID to the latest id
		
			$('#group-photo-loader').addClass('hide')
			$('#upload-feedback').html('Done!').show(); 
			//clear z
			$('#upload-album-z').val('')
			clearInterval(interval);
			return;
		}//if
		
	},'json')//post
x++;
//if 16 seconds have passed asstume there was an error
if(x==15){
$('#group-photo-loader').addClass('hide')
$('#upload-feedback').addClass('red').html('Error').show()
//clear z
$('#upload-album-z').val('')
clearInterval(interval);	
return;
}		
},1000)//set interval

}

var fail = function (error) {
    alert("An error has occurred: Code = " + error.code);
}
	         
var ft = new FileTransfer();
ft.upload(imageData, encodeURI('http://ritzkey.com/login/group/queries/album/upload-photo-for-album.php'), win, fail, options);
	
}//uploadImage function
		
})//click
}//if	
