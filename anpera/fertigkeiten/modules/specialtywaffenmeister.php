<?php
/*
Specialty: Waffenmeister - soll Thief als Basiskampfskill ablösen, da dieser jetzt mit Mindestanforderung im Schleichen
basiert auf specialtythiefskills
Letzte Änderung am 20.04.05 von Michael Jandke

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

function specialtywaffenmeister_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Waffenmeister",
		"author" => "Michael Jandke",
		"version" => "0.95",
		"download" => "http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"category" => "Specialties",
		"settings"=> array(
			"Specialty - Waffenmeister Einstellungen,title",
			"mindk"=>"Ab welchem DK ist die Waffenmeisterkunst verfügbar?,int|0",
		),
		"prefs" => array(
			"Specialty - Waffenmeister Spielereinstellungen,title",
			"skill"=>"Stufen als Waffenmeister,int|0",
			"uses"=>"Wieviele Anwendungen als Waffenmeister,int|0",
		),
	);
	return $info;
}

function specialtywaffenmeister_install(){
	require_once("modules/specialtywaffenmeister/specialtywaffenmeister_install.php");
	$args = func_get_args();
	return call_user_func_array("specialtywaffenmeister_install_private",$args);
}
	
function specialtywaffenmeister_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='WM'";
	db_query($sql);
	return true;
}

function specialtywaffenmeister_dohook($hookname,$args){
	global $session,$resline;

	$spec = "WM";
	$name = "Waffenmeister";
	$ccode = "`3";
	include("modules/specialtywaffenmeister/specialtywaffenmeister_hooks_".$hookname.".php");
	return $args;
}

function specialtywaffenmeister_run(){
}
?>
