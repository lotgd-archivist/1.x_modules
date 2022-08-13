<?php

function wettkampf_aklettern_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");

switch($op){
	//Ausbildung Klettern   **********************************************************
	case "aus-klettern":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$text=translate_inline("`#'Kommen wir gleich zum Grund Eures Erscheinens ...'");
		if ($session[user][race]==Elf) $text=translate_inline("`#'Bei Zrarek, ein Elf, der Klettern lernen will! Dass ich das noch erleben darf ...'`@");
		
		output("`@`bAusbildung: Klettern`b`n");
		output("`@In der N�he des Schlammt�mpels f�hrt ein Weg einen kleinen H�gel hinauf, an dem sich ein l�ngliches Bruchsteinhaus befindet. Als Du eintrittst, erblickst Du den Zwerg Regon, der gerade jemandem die Grundz�ge des Schachtkletterns erkl�rt, eine Disziplin, in der jeder Zwerg geschult wird. Du wartest einen Moment, bis Du an der Reihe bist. Der Zwerg kommt mit schnellen Schritten auf Dich zu und sagt: %s", $text);
	
		welche_steigerungen(klettern);
	break;
	case "klettern0":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(klettern, gespr�ch, $gems);
	break;
	case "klettern1":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(klettern, normal, $gems);
	break;
	case "klettern2":
		require_once("modules/wettkampf/wettkampf_lib.php");
		$gems = $_GET['subop'];
		steigerung(klettern, intensiv, $gems);
	break;
	}
	page_footer();
}
?>