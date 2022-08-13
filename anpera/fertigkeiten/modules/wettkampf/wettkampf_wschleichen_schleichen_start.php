<?php

function wettkampf_wschleichen_schleichen_start_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	
		require_once("modules/wettkampf/wettkampf_wschleichen_lib.php");
		
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		if ($session['user']['gold'] < $session['user']['level']*$teilnahme){
			output("`@`bSchleichen und Verstecken`b`n`n");
			output("`@Kalyth lächelt Dich an. `#'Kein Geld dabei? Vielleicht sollte ich Dein Leben in Zahlung nehmen ...'`@");
			addnav("Zurück","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen");
		} else {
			output("`@`bSchleichen und Verstecken`b`n`n");
			$versucheoffen   = get_module_pref("schleichenversuch", "wettkampf");
			//OW: Kostenabzug nur beim ersten Mal
			if ($versucheoffen == 2){
				output("`@Kalyth nimmt deine `^%s`@ Goldstücke als Bezahlung entgegen und macht sich eine kurze Notiz in ihre Unterlagen.`@`n`n", $session[user][level]*$teilnahme);
				$session['user']['gold']-=$session['user']['level']*$teilnahme;
			}
			// Ergebnis erstmal auf 0 zurücksetzen
			$zeit=0;
			$spieler=2;
			$irog=7;
			$tha=9;
			// Schleicharena initialisieren
			// Spezialfall Sektor 8 - zufälliger Bonus für Schleichen/Entdecken
			$spec = e_rand(10,25);
			$arena = array (
				1 => array ( "n" => 0, "o" => 2, "s" => 4, "w" => 0, "wege" => 2 ,'verstecken' => 30,  'schleichen' => 15,    'entdecken' => -15,   'desc' => ", in die nordwestlichste Ecke des Platzes, wo sich der obere Marktbereich befindet.", 'wache' => "im unteren Marktbereich"),
				2 => array ( "n" => 0, "o" => 3, "s" => 5, "w" => 1, "wege" => 3 ,'verstecken' => 30,  'schleichen' => 15,    'entdecken' => -15,   'desc' => ", wo sich das große Eingangstor befindet",  'wache' => "am Tor"),
				3 => array ( "n" => 0, "o" => 0, "s" => 6, "w" => 2, "wege" => 2 ,'verstecken' => 20,  'schleichen' => 10,    'entdecken' => -10,   'desc' => ", in die nordöstlichste Ecke des Platzes, wo sich das Küchenhaus der Echsen befindet." , 'wache' => "am Küchenhaus der Echsen"),
				4 => array ( "n" => 1, "o" => 5, "s" => 7, "w" => 0, "wege" => 3 ,'verstecken' => 20,  'schleichen' => 10,    'entdecken' => -10,   'desc' => ", wo sich der untere Marktbereich befindet",   'wache' => "im unteren Marktbereich"),
				5 => array ( "n" => 2, "o" => 6, "s" => 8, "w" => 4, "wege" => 4 ,'verstecken' => -99, 'schleichen' => -10,   'entdecken' => 10,    'desc' => ", in die Mitte des Platzes, wo sich die Statue der großen Vermittlerin befindet.",  'wache' => "an der Statue der großen Vermittlerin`6" ),
				6 => array ( "n" => 3, "o" => 0, "s" => 9, "w" => 5, "wege" => 3 ,'verstecken' => 20,  'schleichen' => 10,    'entdecken' => -10,   'desc' => ", wo sich der Schlammtümpel der Trolle befindet" ,   'wache' => "am Schlammtümpel"),
				7 => array ( "n" => 4, "o" => 8, "s" => 0, "w" => 0, "wege" => 2 ,'verstecken' => -25, 'schleichen' => -15,   'entdecken' => 15,    'desc' => ", in die südwestlichste Ecke des Platzes, wo sich die Schießanlage der Elfen befindet.",  'wache' => "an der Schießanlage der Elfen"),
				8 => array ( "n" => 5, "o" => 9, "s" => 0, "w" => 7, "wege" => 3 ,'verstecken' => 30,  'schleichen' => $spec, 'entdecken' => -$spec,'desc' => ", wo sich die große Bühne der Vanthira befindet",   'wache' => "an der Bühne der Vanthira"),
				9 => array ( "n" => 6, "o" => 0, "s" => 0, "w" => 8, "wege" => 2 ,'verstecken' => -25, 'schleichen' => -15,   'entdecken' => 15,    'desc' => ", in die südöstlichste Ecke des Platzes, wo der Reitwettbewerb der Menschen abgehalten wird.",   'wache' => "an der Reitbahn der Menschen")
			);
			set_module_pref("schleichen_wk_data", createstring($arena), "wettkampf");
			set_module_pref("schleichenzeit", $zeit, "wettkampf");
			set_module_pref("schleichenversteckt", 0, "wettkampf");
			set_module_pref("schleichengegenstand1", 0, "wettkampf");
			set_module_pref("schleichengegenstand2", 0, "wettkampf");
			set_module_pref("schleichenprobespieler", 0, "wettkampf");
			set_module_pref("schleichenortspieler", $spieler, "wettkampf");
			set_module_pref("schleichenortirog", $irog, "wettkampf");
			set_module_pref("schleichenorttha", $tha, "wettkampf");
			
			output("Schließlich führt sie Dich zum Startpunkt. `n`n`#'Die Wächter heißen `3Irog`# und `6Tha`#. Nimm Dich besonders vor `6Tha`# in acht!'`@`n`n");
			output("`@Nun stehst Du am nördlichen Eingangstor des Platzes und mischt Dich unauffällig unter die hereinströmenden Besucher. Kalyth hat Dir noch gesagt, dass sich die Gegenstände jeweils in der südöstlichen und der südwestlichen Ecke befinden. Jetzt geht's aber los! Wenn Du einmal erwischt wirst, hast Du noch einen zweiten Versuch.`n`n");
		
			erstelle_navpoints($spieler, &$arena, false);
		}
		page_footer();
}
?>