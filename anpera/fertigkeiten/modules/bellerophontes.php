<?php

//translator ready
//addnews ready
//alignment ready

/*

Bellerophontes' Turm, Version f�r logd ab 0.98

Bellerophontes' Turm birgt viele �berraschungen.
Wohl dem, der es schafft, ihn zu erreichen!
Wohl dem ... ?

Wetterabh�ngiges Ereignis f�r das Fertigkeitensystem

Benutzte Fertigkeit: 	- Schleichen
						- Klettern

Erdacht und umgesetzt von Oliver Wellinghoff.

*********************************************************
*	Diese Datei sollte aus fertigkeiten.zip stammen.	*
*														*
*	Achtung: Wer diese Dateien benutzt, verpflichtet	*
*	sich, alle Module, die er f�r das Fertigkeiten-		*
*	system entwickelt frei und �ffentlich zug�nglich	*
*	zu machen! Jegliche Ver�nderungen an diesen Dateien *
*	m�ssen ebenfalls ver�ffentlicht werden!				*
*														*
*	N�heres siehe: dokumentation.txt					*
*														*
*	Wir entwickeln f�r Euch - Ihr entwickelt f�r uns.	*
*														*
*	Jegliche Ver�nderungen an diesen Dateien 			*
*	m�ssen ebenfalls ver�ffentlicht werden - so sieht 	*
*	es die Lizenz vor, unter der LOTGD ver�ffentlicht	*
*	wurde!												*
*														*
*	Zuwiderhandlungen k�nnen empfindliche Strafen		*
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

function bellerophontes_getmoduleinfo(){
    $info = array(
        "name"=>"Bellerophontes' Turm",
        "version"=>"2.1",
        "author"=>"Oliver Wellinghoff",
		"category"=>"Fertigkeiten - Wald",
        "requires"=>array("fertigkeiten"=>"1.0|Fertigkeitensystem von Oliver Wellinghoff und Michael Jandke",
			"weather"=>"2.0|By Talisman, part of the core download"),
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
    );
    return $info;
}

function bellerophontes_install(){
    module_addeventhook("forest", "return 100;");
    module_addhook("validatesettings");
    return true;
}

function bellerophontes_uninstall(){
    return true;
}

function bellerophontes_runevent($type){
    global $session;
	$op = httpget('op');

    $from = "forest.php?";
    $session['user']['specialinc'] = "module:bellerophontes";

	$typ=$op;
	if ($op == "") $typ="search";
	include("modules/bellerophontes/bellerophontes_".$typ.".php");
}
 
function bellerophontes_run(){
}
?>
