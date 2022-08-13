<?php

//translator ready
//mail ready
//addnews ready

/*
Der Platz der Völker - Modul: Apfelschuss (für LoGD ab 0.98)

Wer trifft den Apfel - und wer den Kopf des anderen Spielers?

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

function pdvapfelschuss_getmoduleinfo(){
    $info = array(
        "name"=>"PdV - Apfelschuss",
        "version"=>"1.0",
        "author"=>"Oliver Wellinghoff",
		"category"=>"Der Platz der Voelker",
        "download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("wettkampf"=>"|Platz der Völker von Oliver Wellinghoff"),
		"settings"=>array(
			"PdV - Apfelschuss - Einstellungen,title",
			"chance"=>"Wie groß ist die Chance dass dieser Stand während des Festes erscheint (in Prozent)?,range,0,100,1|100",
			"appear"=>"Ist der Stand gerade anwesend (nur während Fest)?,bool|0",
			"preis"=>"Wie viel kostet die Teilnahme (= Preis für den Schuss und Entlohnung für das Opfer. Bei einem Treffer bekommt der Schütze das doppelte zurück.)? |250",
			"schuetze"=>"Wer hat sich gerade als Schütze gemeldet? ,viewonly |0",
			"ziel"=>"Worauf zielt der Schütze (1=Apfel 2=Kopf)? ,viewonly|0",
			"fw"=>"Mit welchem Fertigkeitswert? ,viewonly|0",
		),
		"prefs"=>array(
			"PdV - Apfelschuss - Einstellungen,title",
			"teilnahme"=>"Hat der Spieler heute schon einmal als Opfer oder Schütze teilgenommen (es zählt der Servertag!)? ,bool|0",
		)
    );
    return $info;
}

function pdvapfelschuss_install(){
    module_addhook("pdvstände");
    module_addhook("newday-runonce");
    module_addhook("newday");
    return true;
}	

function pdvapfelschuss_uninstall(){
    return true;
}

function pdvapfelschuss_dohook($hookname,$args){
	require_once("modules/pdvapfelschuss/pdvapfelschuss_hooks.php");
	$args = func_get_args();
	return call_user_func_array("pdvapfelschuss_hooks_dohook_private",$args);
}
		
function pdvapfelschuss_run(){
	$op=httpget("op1");
	if ($op == "") $op="main";
	
	$args = func_get_args();
	require_once("modules/pdvapfelschuss/pdvapfelschuss_".$op.".php");
	return call_user_func_array("pdvapfelschuss_".$op."_run_private",$args);
}

?>