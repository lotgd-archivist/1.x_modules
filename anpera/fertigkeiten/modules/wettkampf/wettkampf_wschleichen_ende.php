<?php

function wettkampf_wschleichen_ende_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		$subop = $_GET['result'];
		$schleichen = get_fertigkeit(schleichen);
		switch ($subop) {
			case "gewonnen" :
				$schleichenzeit = get_module_pref("schleichenzeit", "wettkampf");
				$zeit = round($schleichenzeit/60, 3);
				output("`@Kalyth holt ein Buch hervor und vermerkt darin die Zeit, die Du gebraucht hast: `^%s`@ Minuten.`n`n", $zeit);
				set_module_pref("wschleichen0", $zeit, "wettkampf");
				$alter_rekord = get_module_pref("bestschleichen0", "wettkampf");
				
				//Folgende Werte werden gespeichert, damit sich die Sortierung der Bestenlisten nicht ändert, wenn
				//jemand bspw. einen Level aufsteigt:
				set_module_pref("wschleichenlevel", $session[user][level], "wettkampf");
				set_module_pref("wschleichendk", $session[user][dragonkills], "wettkampf");
				set_module_pref("wschleichenfw", $schleichen, "wettkampf");
							
				if ($zeit<$alter_rekord){
					set_module_pref("bestschleichen0", $zeit, "wettkampf");
					set_module_pref("bestschleichenlevel", $session[user][level], "wettkampf");
					set_module_pref("bestschleichendk", $session[user][dragonkills], "wettkampf");
					set_module_pref("bestschleichenfw", $schleichen, "wettkampf");
				}
				set_module_pref("schleichengegenstand1", 0, "wettkampf");
				set_module_pref("schleichengegenstand2", 0, "wettkampf");
				output("<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen'>Zurück zum Platz der Völker</a>", true);
				addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen");
				addnav("Zurück","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen");
			break;
			case "tot" :
				$versucheoffen = get_module_pref("schleichenversuch", "wettkampf");
				if ($versucheoffen==2){
					output("`@Damit hast Du Deinen ersten Versuch verbraucht. Beim zweiten muss es klappen, sonst bekommst Du keine Wertung.`n`n"); 
					output("<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_start'>Auf zum zweiten Versuch!</a>", true);
					addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_start");
					addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_start");
					set_module_pref("schleichenversuch",1, "wettkampf");
					set_module_pref("wschleichen0",10000, "wettkampf");
				} else {
					output("`\$Das war's, Du hast auch Deinen zweiten Versuch aufgebraucht und wirst disqualifiziert.`@`n`n");
					output("<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen'>Weiter.</a>", true);
					addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen");
					addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen");
					set_module_pref("schleichenversuch", 0, "wettkampf");
					set_module_pref("wschleichen0", 9999, "wettkampf"); 
					//Folgende Werte werden gespeichert, damit sich die Sortierung der Bestenlisten nicht ändert, wenn
					//jemand bspw. einen Level aufsteigt:
					$schleichen = get_fertigkeit(schleichen);
					set_module_pref("wschleichenlevel", $session[user][level], "wettkampf");
					set_module_pref("wschleichendk", $session[user][dragonkills], "wettkampf");
					set_module_pref("wschleichenfw", $schleichen, "wettkampf");
				}
			break;
		}
		page_footer();
}
?>