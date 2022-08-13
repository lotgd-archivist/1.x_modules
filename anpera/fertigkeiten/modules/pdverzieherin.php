<?php

//translator ready
//mail ready
//addnews ready

/*
Der Platz der V�lker - Modul: Erzieherin (f�r LoGD ab 0.98)

Wer attraktiv sein will, muss zahlen ...

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

function pdverzieherin_getmoduleinfo(){
    $info = array(
        "name"=>"PdV - Erzieherin",
        "version"=>"1.0",
        "author"=>"Oliver Wellinghoff",
		"category"=>"Der Platz der Voelker",
        "download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("wettkampf"=>"|Platz der V�lker von Oliver Wellinghoff"),
		"settings"=>array(
			"PdV - Erzieherin - Einstellungen,title",
			"chance"=>"Wie gro� ist die Chance dass dieser Stand w�hrend des Festes erscheint (in Prozent)?,range,0,100,1|100",
			"appear"=>"Ist der Stand gerade anwesend (nur w�hrend Fest)?,bool|0",
			"preis"=>"Wie viele Edelsteine kostet eine Aufwertung (ab 150 Charme das doppelte!)? ,range,1,5,1 |1",
		),
		"prefs"=>array(
			"PdV - Erzieherin - Einstellungen,title",
			"teilnahme"=>"Wie oft war der Spieler heute schon hier (maximal 3 Mal)? ,viewonly|0",
		)
    );
    return $info;
}

function pdverzieherin_install(){
    output("`n`c`b`^ACHTUNG: Die Erzieherin steigert Charme nur bis 250! Bei Cedrick kann man "
    	."dar�ber hinauskommen. Dort sollte der Preis auf (Erzieherinpreis*2)+1 eingestellt werden!`0`b`c`n");
	module_addhook("pdvst�nde");
    module_addhook("newday");
    return true;
}	

function pdverzieherin_uninstall(){
    return true;
}

function pdverzieherin_dohook($hookname,$args){
	require_once("modules/pdverzieherin/pdverzieherin_hooks.php");
	$args = func_get_args();
	return call_user_func_array("pdverzieherin_hooks_dohook_private",$args);
}
		
function pdverzieherin_run(){
	$op=httpget("op1");
	if ($op == "") $op="main";
	
	$args = func_get_args();
	require_once("modules/pdverzieherin/pdverzieherin_".$op.".php");
	return call_user_func_array("pdverzieherin_".$op."_run_private",$args);
}

?>