<?php

function wettkampf_aschwimmen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
				
//Ausbildung Bogenschie�en   **********************************************************
switch($op){
	//Ausbildung Schwimmen und Tauchen   **********************************************************
	case "aus-schwimmen": 
		require_once("modules/wettkampf/wettkampf_lib.php");	
		output("`@`bAusbildung: Schwimmen und Tauchen im Schlammt�mpel`b`n");
		output("`@Auf der anderen Seite des Platzes haben die Trolle mit Hilfe des nahen Flusses, der durch den B�rgergarten flie�t einen Schlammt�mpel angelegt. Unter einem kleinen, h�lzernen Unterstand sitzt ein m�nnlicher Troll und raucht eine Pfeife, von der ein scharfer Geruch ausgeht. Als Du n�herkommst, spricht er Dich mit tiefer Stimme an: `#'Crogh-Uuuhl'achra, mein Name ist Chro'ghran! Nehmt Platz und raucht ein wenig mit mir ... Oder seid Ihr gekommen, um bei mir zu lernen?'`@ Nun, damit k�nnte er recht haben ...");
		
		welche_steigerungen(schwimmen);
	break;
	case "schwimmen0": 
		require_once("modules/wettkampf/wettkampf_lib.php");	
		$gems = $_GET['subop'];
		steigerung(schwimmen, gespr�ch, $gems);
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