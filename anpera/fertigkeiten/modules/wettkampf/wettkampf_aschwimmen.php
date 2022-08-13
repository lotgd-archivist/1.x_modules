<?php

function wettkampf_aschwimmen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
				
//Ausbildung Bogenschießen   **********************************************************
switch($op){
	//Ausbildung Schwimmen und Tauchen   **********************************************************
	case "aus-schwimmen": 
		require_once("modules/wettkampf/wettkampf_lib.php");	
		output("`@`bAusbildung: Schwimmen und Tauchen im Schlammtümpel`b`n");
		output("`@Auf der anderen Seite des Platzes haben die Trolle mit Hilfe des nahen Flusses, der durch den Bürgergarten fließt einen Schlammtümpel angelegt. Unter einem kleinen, hölzernen Unterstand sitzt ein männlicher Troll und raucht eine Pfeife, von der ein scharfer Geruch ausgeht. Als Du näherkommst, spricht er Dich mit tiefer Stimme an: `#'Crogh-Uuuhl'achra, mein Name ist Chro'ghran! Nehmt Platz und raucht ein wenig mit mir ... Oder seid Ihr gekommen, um bei mir zu lernen?'`@ Nun, damit könnte er recht haben ...");
		
		welche_steigerungen(schwimmen);
	break;
	case "schwimmen0": 
		require_once("modules/wettkampf/wettkampf_lib.php");	
		$gems = $_GET['subop'];
		steigerung(schwimmen, gespräch, $gems);
	break;
	case "schwimmen1": 
		require_once("modules/wettkampf/wettkampf_lib.php");	
		$gems = $_GET['subop'];
		steigerung(schwimmen, normal, $gems);
	break;
	case "schwimmen2": 
		require_once("modules/wettkampf/wettkampf_lib.php");	
		$gems = $_GET['subop'];
		steigerung(schwimmen, intensiv, $gems);
	break;
}
	page_footer();
}
?>