<?php

function wettkampf_akochen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	
//Ausbildung Kochen und Pflanzenkunde   **********************************************************
switch($op){
	case "aus-kochen":
		require_once("modules/wettkampf/wettkampf_lib.php");
		output("`@`bAusbildung: Kochen und Pflanzenkunde`b`n");
		output("`@Gleich links vom Eingang des Platzes haben die Echsen eine Lehmhütte errichtet, aus der es vorzüglich duftet. Als Du eintrittst, wirst Du von einer etwas fülligeren Echse begrüßt: `#'Sssslassarrr zum Gruße ... Mein Name ist Ag'nsra, und ich nehme an, Ihr seid gekommen, um Eure Koch- und Backkünste zu verbessern, nicht wahr?'`@");
	
		welche_steigerungen(kochen);
	break;
	case "kochen0":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(kochen, gespräch, $gems);
	break;
	case "kochen1":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(kochen, normal, $gems);
	break;
	case "kochen2":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(kochen, intensiv, $gems);
	break;
}
page_footer();
}
?>