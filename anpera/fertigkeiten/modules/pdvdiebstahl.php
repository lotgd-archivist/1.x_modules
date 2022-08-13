<?php

//translator ready
//mail ready
//addnews ready
//alignment ready

/*
Der Platz der V�lker - Modul: Diebstahl(f�r LoGD ab 0.98)

F�gt die M�glichkeit hinzu, anderen Spielern die Taschen zu leeren.

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

require_once("lib/systemmail.php");
require_once("lib/fert.php");

function pdvdiebstahl_getmoduleinfo(){
    $info = array(
        "name"=>"PdV - Diebstahl",
        "version"=>"1.0",
        "author"=>"Oliver Wellinghoff",
		"category"=>"Der Platz der Voelker",
        "download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("wettkampf"=>"Platz der V�lker von Oliver Wellinghoff",
					"mod_rp"=>"Moderate Roleplay von Michael Jandke"),
		"settings"=>array(
            "Der Platz der V�lker - Diebstahl - Einstellungen,title",
			"strafe"=>"Wie hoch ist die niedrigste Geldstrafe (steigert sich bis *15!)? |100",
			"dieb"=>"Wer ist gerade auf Diebestour (name)? |",
			"diebid"=>"Wer ist gerade auf Diebestour (acctid)?",
			"probe"=>"Mit welchem Probenergebnis? |",
			"Spendenpunkte,title",
			"immun_kosten"=>"Wie viele Spendenpunkte kostet die Immunit�t gegen Taschendiebe (verf�llt bei eigenem Diebstahlsversuch / 0 = deaktiviert)? |300",
			),
		"prefs"=>array(
			"erwischt"=>"Bekanntheitsgrad (steigt mit jedem Verbrechen) |0",
			"geklaut"=>"Heute schon geklaut? ,bool|0",
			"bestohlen"=>"Heute schon selbst bestohlen worden (der Versuch z�hlt auch)? ,bool|0",
			"meldung"=>"Wie oft verd�chtig verhalten? ,viewonly|0",
			"diebstahlsimmun"=>"Immunit�t gegen Diebstahl gekauft? ,bool ,viewonly|0"
        )
    );
    return $info;
}

//Achtung: "Erwischt" ist irref�hrend. Gemeint ist, dass jemand �berhaupt ein Verbrechen begangen hat

function pdvdiebstahl_install(){
    module_addhook("pdvnavsonstiges");
	module_addhook("newday");
	module_addhook("pdvanfang");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("footer-hof");
	return true;
}	

function pdvdiebstahl_uninstall(){
    return true;
}

function pdvdiebstahl_dohook($hookname, $args){
	require_once("modules/pdvdiebstahl/pdvdiebstahl_hooks.php");
	$args = func_get_args();
	return call_user_func_array("pdvdiebstahl_dohook_private",$args);
}
	
function pdvdiebstahl_run(){
	global $session;
	
	$op=$_GET[op1];
	if($op=="") $op="main";
	$args = func_get_args();
	require_once("modules/pdvdiebstahl/pdvdiebstahl_".$op.".php");
	return call_user_func_array("pdvdiebstahl_".$op."_run_private",$args);
}
?>