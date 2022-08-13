<?php

//translator ready
//mail ready
//addnews ready

/*
Der Platz der V�lker - Modul: T�towierer (f�r LoGD ab 0.98)

Petra hat mir nicht gefallen ...

**********************************************************
*    Diese Datei sollte aus fertigkeiten.zip stammen.    *
*                                                        *
*    N�heres siehe: dokumentation.txt                    *
*                                                        *
*    Achtung: Wer diese Dateien benutzt, verpflichtet    *
*    sich, alle Module, die er f�r das Fertigkeiten-     *
*    system entwickelt frei und �ffentlich zug�nglich    *
*    zu machen!                                          *
*                                                        *
*    Wir entwickeln f�r Euch - Ihr entwickelt f�r uns.   *
*                                                        *
*    Zudem bitten wir darum, dass Ihr uns eine kurze     *    
*    Mail an folgende Adresse zukommen lasst, in der     *
*    Ihr uns die Adresse des Servers nennt, auf dem das  *
*    Fertigkeitensystem verwendet wird:                  *
*    cern AT quantentunnel.de                            *
*    (Spamschutz " AT " durch "@" ersetzen)              *
*                                                        *
*    Das komplette Fertigkeitensystem ist zuerst auf     *
*    http://www.green-dragon.info erschienen.            *
*                                                        *
**********************************************************/

require_once("lib/sanitize.php");

function pdvtaet_getmoduleinfo(){
	$info = array(
			"name"=>"PdV - T�towierer",
			"version"=>"1.0",
			"author"=>"Oliver Wellinghoff",
			"category"=>"Der Platz der Voelker",
			"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
			"requires"=>array("wettkampf"=>"|Platz der V�lker von Oliver Wellinghoff"),
		"settings"=>array(
			"PdV - T�towierer - Einstellungen,title",
			"chance"=>"Wie gro� ist die Chance dass dieser Stand w�hrend des Festes erscheint (in Prozent)?,range,0,100,1|100",
			"appear"=>"Ist der Stand gerade anwesend (nur w�hrend Fest)?,bool|0",
		),
		"prefs"=>array(
			"PdV - T�towierer - Einstellungen,title",
			"heilung"=>"Heilt gerade eine T�towierung? ,viewonly|0",
			"koerper"=>"T�towierungen ,textarea|1",
		)
	);
return $info;
}

function pdvtaet_install(){
	module_addhook("pdvst�nde");
	module_addhook("newday");
	module_addhook("biostat");
	return true;
}    

function pdvtaet_uninstall(){
	return true;
}

function pdvtaet_dohook($hookname,$args){
	require_once("modules/pdvtaet/pdvtaet_hooks.php");
	$args = func_get_args();
	return call_user_func_array("pdvtaet_hooks_dohook_private",$args);
}
		
function pdvtaet_run(){
	$op=httpget("op1");
	if ($op == "") $op="main";
	
	$args = func_get_args();
	require_once("modules/pdvtaet/pdvtaet_".$op.".php");
	return call_user_func_array("pdvtaet_".$op."_run_private",$args);
}
 
?> 