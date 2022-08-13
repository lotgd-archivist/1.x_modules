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

function racevampir_getmoduleinfo(){
    $info = array(
        "name"=>"Rasse - Vampir",
        "version"=>"1.1",
        "author"=>"Oliver Wellinghoff",
        "category"=>"Races",
        "download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
        "settings"=>array(
            "Vampir - Rasseneinstellungen,title",
            "minedeathchance"=>"Prozentuale Chance für einen Vampir in der Mine zu sterben,range,0,100,1|25",
			"mindk"=>"Nach wievielen DKs wird die Rasse freigeschaltet?,int|35",
        ),
    );
    return $info;
}

function racevampir_install(){
	require_once("modules/racevampir/racevampir_install.php");
	$args = func_get_args();
	return call_user_func_array("racevampir_install_private",$args);
}

function racevampir_uninstall(){
    require_once("modules/racevampir/racevampir_uninstall.php");
	$args = func_get_args();
	return call_user_func_array("racevampir_uninstall_private",$args);
}

function racevampir_dohook($hookname,$args){
	global $session;
	$race="Vampir";
	include("modules/racevampir/racevampir_hooks_".$hookname.".php");
	return $args;
}

function racevampir_checkcity(){
    global $session;
	include("modules/racevampir/racevampir_checkcity.php");
	return true;
}

function racevampir_run(){
}
?>