<?php

function wettkampf_amusik_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	
switch($op){
	//Ausbildung Musik und Gesang   **********************************************************
	case "aus-musik": 
		require_once("modules/wettkampf/wettkampf_lib.php");
		output("`@`bAusbildung: Musik und Gesang`b`n");
		output("`@Auf der gegenüberliegenden Seite des Eingangstores, am südlichen Ende des Platzes, befindet sich die große Bühne der Vanthira. Im Moment ist sie leer und Du brauchst eine Weile, bis Du jemanden gefunden hast: Eine schlanke, bleichhäutige Frau mit langen, weißen Haaren, die von einem starken Silberschimmer durchzogen sind, der sich nicht nach dem Lichteinfall richtet. Sie stellt sich mit Ra'esha vor und sagt: `#'Ein solch schöner Tag und ich verbringe ihn nicht in der Unterwelt ... aber wie ich sehe, wird meine Hilfe gebraucht, was kann ich für Euch tun?'`@");
	
		welche_steigerungen(musik);
	break;
	case "musik0": 
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(musik, gespräch, $gems);
	break;
	case "musik1": 
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(musik, normal, $gems);
	break;
	case "musik2": 
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(musik, intensiv, $gems);
	break;
	
}
	page_footer();
}
?>