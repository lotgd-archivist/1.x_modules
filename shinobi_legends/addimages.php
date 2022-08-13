<?php

function addimages_getmoduleinfo(){
	$info = array(
		"name"=>"Add Images Handler only",
		"version"=>"2.0",
		"author"=>"`2Oliver Brendel ",
		"category"=>"Images",
		"download"=>"",
		"Settings"=>array(
			"Add Images Handler,title",
			"This module is only a dummy to host image handling centrally!,note",
			),
		"prefs"=>array(
			"Add Images Module User Preferences,title",
			"user_addimages"=>"Display Ingame Images?,bool|1",
		),
	);
	return $info;
}

function addimages_install(){
	return true;
}

function addimages_uninstall(){
	return true;
}

function addimages_dohook($hookname,$args){
	return $args;
}

?>
