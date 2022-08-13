<?php

function wettkampf_aschleichen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
	
switch($op){
	//Ausbildung Schleichen und Verstecken   **********************************************************
	case "aus-schleichen":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$text=translate_inline("Als sie sich setzt, eine junge Elfe, bemerkst Du mit Sorge, dass sie sich in der blankpolierten Klingel nicht spiegelt ...`n");
		if ($session[user][race]==Vampir) $text=translate_inline("Als sie sich setzt, erkennst Du in der Frau, die wie eine junge Elfe aussieht, erleichtert Deinesgleichen ...`n");
	
		output("`@`bAusbildung: Schleichen und Verstecken`b`n");
		output("`@Etwas unauff�lliger gelegen, am Rande des Platzes unter B�umen, erblickst Du ein kleines Haus aus Bruchstein, das an eine Gruft erinnert. �ber eine Treppe, die nach unten f�hrt erreichst Du einen mit rotem Samt verkleideten Raum, in dem einige Tische und St�hle aus edlen H�lzern stehen. Du setzt Dich und dr�ckst auf eine Klingel aus Gold, die vor Dir steht. In demselben Moment ber�hrt Dich jemand an der Schulter: `#'Willkommen ... mein Name ist Kalyth, was ist Euer Begehr?'`@");
	
		welche_steigerungen(schleichen);
	break;
	case "schleichen0":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(schleichen, gespr�ch, $gems);
	break;
	case "schleichen1":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(schleichen, normal, $gems);
	break;
	case "schleichen2":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(schleichen, intensiv, $gems);
	break;
}
	page_footer();
}
?>