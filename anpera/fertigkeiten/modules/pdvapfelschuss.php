<?php

//translator ready
//mail ready
//addnews ready

/*
Der Platz der V�lker - Modul: Apfelschuss (f�r LoGD ab 0.98)

Wer trifft den Apfel - und wer den Kopf des anderen Spielers?

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

function pdvapfelschuss_getmoduleinfo(){
    $info = array(
        "name"=>"PdV - Apfelschuss",
        "version"=>"1.0",
        "author"=>"Oliver Wellinghoff",
		"category"=>"Der Platz der Voelker",
        "download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("wettkampf"=>"|Platz der V�lker von Oliver Wellinghoff"),
		"settings"=>array(
			"PdV - Apfelschuss - Einstellungen,title",
			"chance"=>"Wie gro� ist die Chance dass dieser Stand w�hrend des Festes erscheint (in Prozent)?,range,0,100,1|100",
			"appear"=>"Ist der Stand gerade anwesend (nur w�hrend Fest)?,bool|0",
			"preis"=>"Wie viel kostet die Teilnahme (= Preis f�r den Schuss und Entlohnung f�r das Opfer. Bei einem Treffer bekommt der Sch�tze das doppelte zur�ck.)? |250",
			"schuetze"=>"Wer hat sich gerade als Sch�tze gemeldet? ,viewonly |0",
			"ziel"=>"Worauf zielt der Sch�tze (1=Apfel 2=Kopf)? ,viewonly|0",
			"fw"=>"Mit welchem Fertigkeitswert? ,viewonly|0",
		),
		"prefs"=>array(
			"PdV - Apfelschuss - Einstellungen,title",
			"teilnahme"=>"Hat der Spieler heute schon einmal als Opfer oder Sch�tze teilgenommen (es z�hlt der Servertag!)? ,bool|0",
		)
    );
    return $info;
}

function pdvapfelschuss_install(){
    module_addhook("pdvst�nde");
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