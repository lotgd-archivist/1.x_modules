<?php

function wettkampf_akochen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
	
//Ausbildung Kochen und Pflanzenkunde   **********************************************************
switch($op){
	case "aus-kochen":
		require_once("modules/wettkampf/wettkampf_lib.php");
		output("`@`bAusbildung: Kochen und Pflanzenkunde`b`n");
		output("`@Gleich links vom Eingang des Platzes haben die Echsen eine Lehmh�tte errichtet, aus der es vorz�glich duftet. Als Du eintrittst, wirst Du von einer etwas f�lligeren Echse begr��t: `#'Sssslassarrr zum Gru�e ... Mein Name ist Ag'nsra, und ich nehme an, Ihr seid gekommen, um Eure Koch- und Backk�nste zu verbessern, nicht wahr?'`@");
	
		welche_steigerungen(kochen);
	break;
	case "kochen0":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(kochen, gespr�ch, $gems);
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