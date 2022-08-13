<?php

//translator ready
//mail ready
//addnews ready

/*
Der Platz der Völker - Modul: Missionar (für LoGD ab 0.98)

Vanthira, der andere Rassen "missionieren" will

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

function pdvmissionar_getmoduleinfo(){
    $info = array(
        "name"=>"PdV - Missionar",
        "version"=>"1.2",
        "author"=>"Oliver Wellinghoff",
		"category"=>"Der Platz der Voelker",
        "download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("wettkampf"=>"|Platz der Völker von Oliver Wellinghoff",
			"racevanthira"=>"|Rasse 'Vanthira' von Oliver Wellinghoff"),
		"settings"=>array(
			"PdV - Missionar - Einstellungen,title",
			"chance"=>"Wie groß ist die Chance dass er während des Festes erscheint (in Prozent)?,range,0,100,1|75",
			"appear"=>"Ist der Stand gerade anwesend (nur während Fest)?,bool|0", // man kann ihn hiermit zur Not "herzwingen"
		),
    );
    return $info;
}

function pdvmissionar_install(){
    module_addhook("pdvstände");
    module_addhook("newday-runonce");
	return true;
}	

function pdvmissionar_uninstall(){
    return true;
}

function pdvmissionar_dohook($hookname,$args){
	require_once("modules/pdvmissionar/pdvmissionar_hooks.php");
	$args = func_get_args();
	return call_user_func_array("pdvmissionar_hooks_dohook_private",$args);
}
		
function pdvmissionar_run(){
	$op=httpget("op1");
	if ($op == "") $op="main";
	
	$args = func_get_args();
	require_once("modules/pdvmissionar/pdvmissionar_".$op.".php");
	return call_user_func_array("pdvmissionar_".$op."_run_private",$args);
}

?>