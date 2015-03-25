(function(){

if(!mobileView) $('body').css('background-attachment','fixed')
	
var z = getZ()
var groupID = getGroupID()


if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';

for(var x=0;x<2;x++){

var limit = 12;

$.post(postPath+'queries/album/load-group-photos.php',{z:z, groupID:groupID, loop:x, limit:limit}, function(data){
	
	if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
	else postPath = '';
	
	$('#group-photo').attr('style','background-image:url('+postPath+data.groupImage+')')
	$('#group-name-title').html(data.groupName+' album')
	$('title').html(data.groupName+' album')
	if(data.groupMember===true) $('#add-photos-button-div').removeClass('hide')
	else $('#message-area, #message-area-hr').hide()//hide message area
	$('#link-to-group').attr('href',data.linkToGroup)
	
	//load images
	if(data.images){//if there are any images

	if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
	else postPath = '';
	
	var images = data.images;
	images = images.replace(/background-image:url\(/g,'background-image:url('+postPath)
	$('#load-photo-div').append(images)
	$('#uploaded-by-container').append(data.uploadedByDivs)
	$('#caption-container').append(data.captionDivs)
	setgroupPhotoContainerHeight()
	
	//handle show more button
	if(data.loop==1){ //do this only on second loop
	$('#show-more-photos').show()
	var numberOfLoops = data.loops//if limit is equal to or higher than this the show more button is hidden
	var loopLimit = limit*2//this limit sets how many images are shown when clicking show more
	var startLimit = limit
	$('#show-more-photos').click(function(){
		for(var x=limit;x<=loopLimit;x++){
			$('[showMoreID="'+x+'"]').show()
			if(x>=numberOfLoops) $('#show-more-photos').hide()//hide if the limit if all images are shown
		}//for
		//increase limit
		loopLimit+=limit;
		startLimit+=limit;
	})//click
	}//if data.loop
	
	}//if data.images
	
	if(data.loop==1) $('#photos-loading-img').hide()//hide on second loop which is equal to x
	
},'json')//post

}//for

$(window).resize(function(){

setgroupPhotoContainerHeight()
	
})//window

//make load-photos-container the right height
function setgroupPhotoContainerHeight(){
var Height = $('#relative-div').css('height').split('px')
$('#load-photos-container').css('max-height',Height[0]-100)	
if(mobileView) $('#load-photos-container').css('margin-bottom',40)
//make display-photo-background-div (dim background) the right height 
initialHeight = $('#group-photo-container').css('height')//the height of display-photo-background-div will get set to initial when canvas closes or changes
$('#display-photo-background-div').css('height',initialHeight)

}//setgroupPhotoContainerHeight


//close canvas
$('#close-canvas').click(function(){
$('#display-photo-background-div').addClass('hide')
$('#group-photo-canvas-container').addClass('hide')

//reset delete options
$('#delete-button-div').css('display','inline-block')
$('#you-sure-span').addClass('hide')	
$('#delete-img-loader').addClass('hide')

//clear chat wall
clearChatWall()
//reset scrollTop
$(window).scrollTop(windowOffset)

resetUploadedBy()
resetCaptionEdit()
$('#delete-button-div').css('display','inline-block')
$('#you-sure-span').addClass('hide')

//reset editing options
$('#delete-area').addClass('hide')
$('#add-caption-area').addClass('hide')
	
})//click close


$('#right-gallery-arrow').click(function(){//click to next picture

showNext('right')

})//click

$('#left-gallery-arrow').click(function(){//click to previous picture

showNext('left')

})//click

//click to next picture
function showNext(direction){

if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';		

for(var x=0;x<2;x++){//do this twice so that once the new image is set the appropriate arrow will be hidden if the next image doesn't exist
	
var currentImage = $('#group-photo-load').html()
var currentImageSrc = $(currentImage).attr('src')

switch(direction){//get thumbnail by id (which is the image path) then get the id of the one next to it
	case 'left': var nextImage = $("[id='"+currentImageSrc+"']").prev().attr('id'); break;
	case 'right': var nextImage = $("[id='"+currentImageSrc+"']").next('div').attr('id'); break;
}//switch

if(nextImage && x==0){ 
var currentImageHeight = $('[src="'+currentImageSrc+'"]').css('height')//to avoid no height while next image loads get the height of current image before changing
$('#group-photo-load').css('min-height',currentImageHeight)
$('#group-photo-load').html('<img src="'+postPath+nextImage+'" />'); //note* only on first loop so the image isn't set to next one
$('[src="'+nextImage+'"]').on('load',function(){
$('#group-photo-load').css('min-height',0)//undo min height
})//on
}//if
$('#right-gallery-arrow').removeClass('hide')
$('#left-gallery-arrow').removeClass('hide')

if(!nextImage) $('#'+direction+'-gallery-arrow').addClass('hide')

}//for

clearChatWall()

//get comments
var currentImage = $('#group-photo-load').html()
var currentImageSrc = $(currentImage).attr('src')
var id2 = $('[id="'+currentImageSrc+'"]').attr('id2');
var groupID = getGroupID()
var groupIDPhoto = groupID+':imageID-'+id2

var loadTimeout = setTimeout(function(){
loadWallFunction(groupIDPhoto)
clearTimout(loadTimeout)
},1000)

resetUploadedBy()	
resetCaptionEdit()
//reset delete options
$('#delete-button-div').css('display','inline-block')
$('#you-sure-span').addClass('hide')

//show caption
$('[captionID="'+id2+'"]').removeClass('hide')
//show uploaded by
//show uploaded by 
$('[uploadedByID="'+id2+'"]').removeClass('hide')
//reset editing options
$('#delete-area').addClass('hide')
$('#add-caption-area').addClass('hide')

//determine if user can delete photo
var deletable = $('[id="'+currentImageSrc+'"]').attr('deletable');
if(deletable=='true') $('#delete-area').removeClass('hide')
//determine if user edit caption
var editCaption = $('[id="'+currentImageSrc+'"]').attr('editCaption')	
if(editCaption=='true') $('#add-caption-area').removeClass('hide')
	
}//function show next


//handle deleting images
$('#delete-button-div').click(function(){
	$('#delete-button-div').css('display','none')
	$('#you-sure-span').removeClass('hide')	
})//click
$('#no-delete').click(function(){
	$('#delete-button-div').css('display','inline-block')
	$('#you-sure-span').addClass('hide')	
})//click
$('#yes-delete').click(function(){
	$('#delete-img-loader').removeClass('hide')
	var imagePath = $('#group-photo-load img').attr('src')//get src
	var imagePathArray = imagePath.split('/')//get image from src
	var image = imagePathArray[imagePathArray.length-1]
	var rowID = $('[id="'+imagePath+'"]').attr('id2')//get table id
	var z = getZ()
	var groupID = getGroupID()
	var postPath = getPostPath('http://ritzkey.com/login/group/')
	$.post(postPath+'queries/album/delete-photo.php',{z:z, imagePath:imagePath, rowID:rowID, groupID:groupID},function(data){
		//reset delete options
		$('#delete-button-div').css('display','inline-block')
		$('#you-sure-span').addClass('hide')	
		$('#delete-img-loader').addClass('hide')
		//hide canvas and remove image
		$('#display-photo-background-div, #group-photo-canvas-container').addClass('hide')
		var currentImage = $('#group-photo-load img').attr('src')
		$('[id="'+currentImage+'"]').remove()
		clearChatWall()
		resetCaptionEdit()
	},'json')//post
	
//reset editing options
$('#delete-area').addClass('hide')
$('#add-caption-area').addClass('hide')

resetUploadedBy()
	
})//click yes delete

function clearChatWall(){
$('#chat-wall-load').html("<img src='../../pics/ajax-loader2.gif' id='wall-loading-img' style='height:40px;width:auto'/>")
if($('#show-more').length) $('#show-more').remove()
//clear interval	
clearInterval(chatWallInterval)	
}//function


function resetCaptionEdit(){
//reset edit caption
$('#add-caption-div').show()
$('#add-caption-div-cancel').hide()
$('#add-caption-area').css('text-align','right')//for some reason clicking makes this element lose right text align
$('#edit-caption-area').addClass('hide')
$('#caption-input').addClass('no-content').val('Caption')
$('#caption-container').show()
$('#caption-input').addClass('no-content').val('Caption')
$('.caption').addClass('hide')//reset displayed caption	
}//function


function resetUploadedBy(){	
$('.uploaded-by').addClass('hide')
}//function

})();



function showImage(image){

if(pathForPost) postPath = 'http://ritzkey.com/login/group/';
else postPath = '';		
	
$('#display-photo-background-div').removeClass('hide')

$('#group-photo-load').html('<img src="'+postPath+image+'" />')

//Hide canvas until image loads
$('[src="'+image+'"]').on('load',function(){
$('#group-photo-canvas-container').removeClass('hide')	
})

//determine if arrows should be shown
var currentImage = $('#group-photo-load').html()//get current image from the currentImage attr.
var currentImageSrc = $(currentImage).attr('src')

var id2 = $('[id="'+currentImageSrc+'"]').attr('id2');

//show caption
$('[captionID="'+id2+'"]').removeClass('hide')

//show uploaded by 
$('[uploadedByID="'+id2+'"]').removeClass('hide')

//determine if user can delete photo
var deletable = $('[id="'+currentImageSrc+'"]').attr('deletable');
if(deletable=='true') $('#delete-area').removeClass('hide')

//determine if user edit caption
var editCaption = $('[id="'+currentImageSrc+'"]').attr('editCaption')	
if(editCaption=='true') $('#add-caption-area').removeClass('hide')
	
//get comments
var groupID = getGroupID()
var groupIDPhoto = groupID+':imageID-'+id2

var loadTimeout = setTimeout(function(){
loadWallFunction(groupIDPhoto)
clearTimout(loadTimeout)
},1000)

for(x=0;x<2;x++){

switch(x){
	case 0:direction='right'; break;
	case 1: direction='left';break;
}//switch
	
switch(direction){//get thumbnail by id (which is the image path) then get the id of the one next to it
	case 'left': var nextImage = $("[id='"+currentImageSrc+"']").prev().attr('id'); break;
	case 'right': var nextImage = $("[id='"+currentImageSrc+"']").next().attr('id'); break;
}//switch
$('#'+direction+'-gallery-arrow').removeClass('hide')//remove hide in case it was added earlier
if(!nextImage) $('#'+direction+'-gallery-arrow').addClass('hide')//add hide if no adjacent image
}//for	

windowOffset = $(window).scrollTop()//get scrollTop for when user closes canvas
$(window).scrollTop(0)

}//function showImage
