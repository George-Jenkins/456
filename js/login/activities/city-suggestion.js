(function(){

var defaultText = 'City, State';

$('#search-area-input').val(defaultText)

$('#search-area-input').focus(function(){

var currentVal = $('#search-area-input').val()

if(currentVal==defaultText) $('#search-area-input').val('')
	
})//focus

$('#search-area-input').blur(function(){

var currentVal = $('#search-area-input').val()

if(!currentVal) $('#search-area-input').val(defaultText)
	
})//focus

//get suggestions
$('#search-area-input').on('keyup',function(){
	
var currentVal = $('#search-area-input').val()	

$.post('queries/city-suggestions.php',{currentVal:currentVal},function(data){

if(data.suggestion) $('#city-suggestions-div').html(data.suggestion).removeClass('hide')
else $('#city-suggestions-div').html('').addClass('hide')
//if input is empty clear suggestions
if(!currentVal){
	
$('#city-suggestions-div').html('').addClass('hide')

}//if
	
},'json')

})//keyup

//handle hovering over suggestions
highlightHover = function(id){
$('.each-suggestion').each(function(){
if(!$(this).hasClass('clicked')) $(this).css('background-color','transparent')
})
$('#'+id).css('background-color','#0FF')
}

highlightClick = function(id){
$('.each-suggestion').css('background-color','transparent').removeClass('clicked')
$('#'+id).css('background-color','#0FF').addClass('clicked')
//add suggestion to input box
var suggestionText = $('#'+id).html()
$('#search-area-input').val(suggestionText)	
//hide suggestions
$('#city-suggestions-div').html('').addClass('hide')
}//function

$('#city-suggestions-div').mouseout(function(){
$('.each-suggestion').each(function(){
if(!$(this).hasClass('clicked')) $(this).css('background-color','transparent')
})//each
})//mouseout

})();