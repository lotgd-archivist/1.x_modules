<?php
function gemverkauf_getmoduleinfo(){
	$info = array(
		"name"=>"Edelsteine (An- und Verkauf)",
		"version"=>"1.05",
		"author"=>"Oliver Wellinghoff",
		"category"=>"General",
		"download"=>"http://dragonprime.net/users/Harassim/gemverkauf.zip",
		"settings"=>array(
			"Edelsteine (An- und Verkauf) - Einstellungen ,title",
			"menge"=>"Wie viele sind im Angebot? ,int |10",
			"max"=>"Bis zu welcher Menge wird angekauft? ,int |50",
			"maxuser"=>"Wie oft kann ein Spieler maximal pro Tag verkaufen? ,int |3",
		),
		"prefs"=>array(
			"Edelsteine (An- und Verkauf) - Einstellungen ,title",
			"menge"=>"Wie oft heute verkauft? ,int |0",
		)
	);
    return $info;
}

function gemverkauf_install(){
	module_addhook("gypsy");
	module_addhook("newday");
	return true;
}	

function gemverkauf_uninstall(){
    return true;
}

function gemverkauf_dohook($hookname,$args){
	require_once("modules/gemverkauf/gemverkauf_hooks.php");
	$args = func_get_args();
	return call_user_func_array("gemverkauf_dohook_private",$args);
}

function gemverkauf_run(){
	$op=$_GET[op];
	
	$args=false;	
	require_once("modules/gemverkauf/gemverkauf_".$op.".php");
	return call_user_func_array("gemverkauf_".$op."_run_private",$args);
}
?>