<?php

function wettkampf_areiten_run_private($op, $subop=false){
	global $session;
	
	page_header("Der Platz der V�lker");
	
	switch($op){
	//Ausbildung Reiten und Kutschefahren   **********************************************************
	case "aus-reiten":
		require_once("modules/wettkampf/wettkampf_lib.php");
		output("`@`bAusbildung: Reiten und Kutschefahren`b`n");
		output("`@Im s�d�stlichsten Bereich des Platzes, hinter dem Schlammt�mpel, befindet sich die gro�e Reitanlage der Menschen: Eine ovale Bahn f�r das Wettrennen, die eine Wiese umschlie�t, auf der das Bullenreiten stattfindet. Als Du bis auf wenige Meter an das kleine Haus neben dem Eingang herangegangen bist, kommt jemand heraus, um Dich zu begr��en; ein hagerer, sehr vornehm, aber dezent gekleideter Mensch, der sich sogleich vorstellt: `#'Seid gegr��t! Mein Name ist Hannes VII., Sohn des Hannes VI., der der gro�en Vermittlerin bei der Planung zur Seite stand, und Ur-Enkel des gro�en Hannes IV., der ihr ein loyaler Diener war. Was kann ich f�r Euch tun?'`@");
	
		welche_steigerungen(reiten);
	break;
	case "reiten0":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(reiten, gespr�ch, $gems);
	break;
	case "reiten1":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(reiten, normal, $gems);
	break;
	case "reiten2":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(reiten, intensiv, $gems);
	break;
}
	page_footer();
}
?>