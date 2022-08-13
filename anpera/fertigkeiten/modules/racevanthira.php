<?php

// translator ready
// addnews ready

/*
*********************************************************
*	Diese Datei sollte aus fertigkeiten.zip stammen.	*
*														*
*	Achtung: Wer diese Dateien benutzt, verpflichtet	*
*	sich, alle Module, die er für das Fertigkeiten-		*
*	system entwickelt frei und öffentlich zugänglich	*
*	zu machen! Jegliche Veränderungen an diesen Dateien *
*	müssen ebenfalls veröffentlicht werden!				*
*														*
*	Näheres siehe: dokumentation.txt					*
*														*
*	Wir entwickeln für Euch - Ihr entwickelt für uns.	*
*														*
*	Jegliche Veränderungen an diesen Dateien 			*
*	müssen ebenfalls veröffentlicht werden - so sieht 	*
*	es die Lizenz vor, unter der LOTGD veröffentlicht	*
*	wurde!												*
*														*
*	Zuwiderhandlungen können empfindliche Strafen		*
*	nach sich ziehen!									*
*														*
*	Zudem bitten wir darum, dass Ihr uns eine kurze		*
*	Mail an folgende Adresse zukommen lasst, in der		*
*	Ihr	uns die Adresse des Servers nennt, auf dem das	*
*	Fertigkeitensystem verwendet wird:					*
*	cern AT quantentunnel.de							*
*	(Spamschutz " AT " durch "@" ersetzen)				*
*														*
*	Das komplette Fertigkeitensystem ist zuerst auf		*
*	http://www.green-dragon.info erschienen.			*
*														*
*********************************************************
*/

function racevanthira_getmoduleinfo(){
	$info = array(
		"name"=>"Rasse - Vanthira",
		"version"=>"1.2",
		"author"=>"Oliver Wellinghoff",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"settings"=>array(
			"Vanthira - Rasseneinstellungen,title",
			"mindk"=>"Nach wievielen DKs wird die Rasse freigeschaltet?,int|10",
			"minedeathchance"=>"Prozentuale Chance für einen Vanthira in der Mine zu sterben,range,0,100,1|70",
			"wiedergeburt"=>"Wieviele Gefallen zahlt ein Vanthira für die Wiedergeburt? ,range,70,90,5|70",
			"senden"=>"Prozentuale Chance nach einem Kampf senden zu wollen ,range,3,10,1|5",
			"sendendauer"=>"--> Wielange dauert der Vorteil? ,range,5,40,1|5",
			"sendenstärke"=>"--> Multiplikator für den Vorteil (auf Angriff) ,floatrange,1.05,1.50,0.01|1.05",
			"sehnen"=>"Prozentuale Chance nach einem Kampf Todessehnsucht zu bekommen ,range,3,10,1|3",
			"sehnenstärke"=>"--> Multiplikator für den Nachteil (auf beide Werte!) ,floatrange,0,0.5,0.05|0.25",
			"sehnendauer"=>"--> Wielange dauert der Nachteil? ,range,5,40,1|10",
		),
	);
	return $info;
}

function racevanthira_install(){
	require_once("modules/racevanthira/racevanthira_install.php");
	$args = func_get_args();
	return call_user_func_array("racevanthira_install_private",$args);
}

function racevanthira_uninstall(){
	 require_once("modules/racevanthira/racevanthira_uninstall.php");
	$args = func_get_args();
	return call_user_func_array("racevanthira_uninstall_private",$args);
}
	
function racevanthira_dohook($hookname,$args){
	global $session;
	$race="Vanthira";
	include("modules/racevanthira/racevanthira_hooks_".$hookname.".php");
	return $args;
}

function racevanthira_checkcity(){
	global $session;
	include("modules/racevanthira/racevanthira_checkcity.php");
	return true;
}
		
function racevanthira_run(){
}
?>