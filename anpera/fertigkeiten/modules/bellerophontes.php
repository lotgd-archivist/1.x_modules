<?php

//translator ready
//addnews ready
//alignment ready

/*

Bellerophontes' Turm, Version für logd ab 0.98

Bellerophontes' Turm birgt viele Überraschungen.
Wohl dem, der es schafft, ihn zu erreichen!
Wohl dem ... ?

Wetterabhängiges Ereignis für das Fertigkeitensystem

Benutzte Fertigkeit: 	- Schleichen
						- Klettern

Erdacht und umgesetzt von Oliver Wellinghoff.

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
