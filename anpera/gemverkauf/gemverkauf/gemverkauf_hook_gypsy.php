<?php
function gemverkauf_dohook_gypsy_private($args){
	global $session;
			$maxuser=get_module_setting("maxuser", "gemverkauf");
			$mengeuser=get_module_pref("menge", "gemverkauf");
			output("`n`n`!'Oder möchtest Du Edelsteine kaufen? Ein Moment, ich schaue gleich mal nach ...' "
				."`5Sie kramt in einer Truhe und verkündet bald darauf: ");
			$menge=get_module_setting("menge", "gemverkauf");
			$max=get_module_setting("max", "gemverkauf");
			if ($menge == 0) output("`!'Ich habe leider keine mehr da. ", $menge);
			else if ($menge > 0) output("`!'Schau, `^%s`! habe ich noch da. ", $menge);
						
			if ($menge < $max && $mengeuser < $maxuser) output("Aber ich kaufe auch gerne welche!'");
			else if ($menge < $max && $mengeuser == $maxuser) output("Von Dir kaufe ich aber keine weiteren mehr - "
				."nicht heute. Es kommen noch andere Leute, denen ich versprochen habe, welche abzukaufen, "
				."die will ich nicht enttäuschen.'");
			else if ($menge < $max && $session['user']['level'] == 15) output("Von Dir kaufe ich aber keine weiteren mehr - "
				."nicht heute. Stell Dich erstmal wieder Deinem Schicksal, es wird Zeit!'");
			else output("Weitere kaufe ich aber nicht an - diese hier muss ich erstmal wieder loswerden ...'");
			
			//Eine Zeile z.T. übernommen aus der 0.97er Erweiterung (Autor mir nicht bekannt)
			$costs=array(1=>3500-10*$menge, 7300-20*$menge, 11000-32*$menge, 1180-4*$menge);
			
			if ($menge > 0 || $menge < $max && $mengeuser < $maxuser && $session['user']['gems'] > 0) addnav("Edelsteinhandel");
			if ($menge > 0) addnav(array("`^1`0 Edelstein kaufen (%s)",$costs[1]), "runmodule.php?module=gemverkauf&op=kauf&menge=".$menge."&subop=1");
			if ($menge > 1) addnav(array("`^2`0 Edelsteine kaufen (%s)",$costs[2]), "runmodule.php?module=gemverkauf&op=kauf&menge=".$menge."&subop=2");
			if ($menge > 2) addnav(array("`^3`0 Edelsteine kaufen (%s)",$costs[3]), "runmodule.php?module=gemverkauf&op=kauf&menge=".$menge."&subop=3");

			if ($menge < $max && $mengeuser < $maxuser && $session['user']['gems'] > 0) addnav(array("Edelstein verkaufen (%s)",$costs[4]), "runmodule.php?module=gemverkauf&op=verkauf&menge=".$menge."");
			
			return $args;
}
?>