<?php

function wettkampf_abogen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
				
//Ausbildung Bogenschie�en   **********************************************************
switch($op){
	case "aus-bogen":
		require_once("modules/wettkampf/wettkampf_lib.php");
		output("`@`bAusbildung: Bogenschie�en`b`n");
		output("`@Du n�herst Dich einem elfischen Ehepaar, das gerade an einem Holztisch sitzt und angebrochene �bungspfeile aussortiert. Als sie Dich erblicken, erhebt sich die Frau und und spricht Dich an: `#'Chara zum Gru�e, ich nehme an, Du m�chtest im Bogenschie�en ausgebildet werden. Mein Name ist Ghena und das ist mein Mann Edranel. Ich kann Dir das Schie�en vom Pferd beibringen, w�hrend er sich auf das schnelle und das Schie�en mit verbundenen Augen spezialisiert hat.'");
		
		welche_steigerungen(bogen);
	break;
	case "bogen0":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $subop;
		steigerung(bogen, gespr�ch, $gems);
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