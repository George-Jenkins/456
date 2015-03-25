<?php

function searchMatch($post){
	
$searchMatch = false;
 
if(substr_count(strtolower($post),'$')>0 ||
substr_count(strtolower($post),'deal')>0 ||
substr_count(strtolower($post),'half off')>0 ||
substr_count(strtolower($post),'half price')>0 ||
substr_count(strtolower($post),'1/2 price')>0 ||
substr_count(strtolower($post),'1/2 off')>0 ||
substr_count(strtolower($post),'limited time')>0 ||
substr_count(strtolower($post),'offer')>0 ||
substr_count(strtolower($post),'open bar')>0 ||
substr_count(strtolower($post),'specials')>0 ||
substr_count(strtolower($post),'on sale')>0 ||
substr_count(strtolower($post),'free')>0
){

$searchMatch = true;

}//if


if(substr_count(strtolower($post),'event')>0 ||
substr_count(strtolower($post),'party')>0 ||
substr_count(strtolower($post),'night!')>0 ||
substr_count(strtolower($post),'tomorrow!')>0 ||
substr_count(strtolower($post),'spring break')>0 ||
substr_count(strtolower($post),'holiday')>0 ||
substr_count(strtolower($post),'celebrat')>0 ||
substr_count(strtolower($post),'event')>0 ||
substr_count(strtolower($post),'ticket')>0 ||
substr_count(strtolower($post),'tix')>0 
){

$searchMatch = true;

}//if


return $searchMatch;

}//function

?>