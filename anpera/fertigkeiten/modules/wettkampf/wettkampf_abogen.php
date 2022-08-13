<?php

function wettkampf_abogen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
				
//Ausbildung Bogenschießen   **********************************************************
switch($op){
	case "aus-bogen":
		require_once("modules/wettkampf/wettkampf_lib.php");
		output("`@`bAusbildung: Bogenschießen`b`n");
		output("`@Du näherst Dich einem elfischen Ehepaar, das gerade an einem Holztisch sitzt und angebrochene Übungspfeile aussortiert. Als sie Dich erblicken, erhebt sich die Frau und und spricht Dich an: `#'Chara zum Gruße, ich nehme an, Du möchtest im Bogenschießen ausgebildet werden. Mein Name ist Ghena und das ist mein Mann Edranel. Ich kann Dir das Schießen vom Pferd beibringen, während er sich auf das schnelle und das Schießen mit verbundenen Augen spezialisiert hat.'");
		
		welche_steigerungen(bogen);
	break;
	case "bogen0":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $subop;
		steigerung(bogen, gespräch, $gems);
	break;
	case "bogen1":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $subop;
		steigerung(bogen, normal, $gems);
	break;
	case "bogen2":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $subop;
		steigerung(bogen, intensiv, $gems);
	break;
}
	page_footer();
}
?>