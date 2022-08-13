<?php
require_once("lib/addnews.php");
require_once("lib/commentary.php");
require_once("lib/systemmail.php");

function kneipenerweiterung_getmoduleinfo(){
    $info = array(
        "name"=>"Kneipenerweiterungen",
        "version"=>"1.2",
        "author"=>"Oliver Wellinghoff<br>Nico Lachmann",
		"category"=>"Inn",
		"download"=>"http://dragonprime.net/users/Harassim/kneipenerweiterung.zip",
		"requires"=>array("mod_rp"=>"Moderate Roleplay von Michael Jandke",
			"drinks"=>"Exotic Drinks von John J. Collins Heavily modified by JT Traub"),
		"prefs"=>array(
			"Ale umsonst - Einstellungen ,title",
			"ausgegeben"=>"Heute schon Ales ausgegeben? ,bool|0",
			"getrunken"=>"Heute schon ein freies Ale getrunken? ,bool|0",
			"Nachricht"=>"Aktuelle Boardnachricht des Benutzers|",
			"Tage"=>"Wieviele Spieltage noch bis die Nachricht abgelaufen ist? ,int|0"
		),
		"settings"=>array(
			"Ale umsonst - Einstellungen,title",
			"bezahlt"=>"Wie viele sind gerade verfügbar? ,int|0",
			"preis_mod" =>"Multiplikator für den Preis von Nachrichten am Schwarzen Brett ,int|6"
		),
	);
    return $info;
}

function kneipenerweiterung_install(){
	module_addhook("newday");
	module_addhook("newday-runonce");
	module_addhook("ale");
	module_addhook("inn");
	module_addhook("inn-desc");
	module_addhook("header-inn");
	return true;
}	

function kneipenerweiterung_uninstall(){
    return true;
}

function kneipenerweiterung_dohook($hookname,$args){
	require_once("modules/kneipenerweiterung/kneipenerweiterung_hooks.php");
	$args = func_get_args();
	return call_user_func_array("kneipenerweiterung_hooks_dohook_private",$args);
}
	
function kneipenerweiterung_run(){
	global $session;
	$from = "runmodule.php?module=kneipenerweiterung";
	$ops=$_GET[op];
	
	switch($ops){
		case "ausgeben": case "ausgegeben": case "trinken": case "auswirkung":
			require_once("modules/kneipenerweiterung/kneipenerweiterung_ale.php");
			$args = $ops;
			return call_user_func_array("kneipenerweiterung_ale_run_private",$args);
		break;
		case "schwarzesbrett":
			require_once("modules/kneipenerweiterung/kneipenerweiterung_brett.php");
			$args = $_GET[act];
			return call_user_func_array("kneipenerweiterung_brett_run_private",$args);
		break;
    }
}
?>