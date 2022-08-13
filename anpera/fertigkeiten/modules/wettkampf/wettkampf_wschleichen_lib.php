<?php
	function erstelle_navpoints($spieler, &$arena, $versteckt){
		switch ($arena[$spieler]['wege']) {
			case 2 : $wege = "zwei"; break;
			case 3 : $wege = "drei"; break;
			case 4 : $wege = "vier"; break;
		}
		output("`@`nDu kannst dein Glück in %s verschiedenen Richtungen probieren:`n`n", $wege, true);
		if ($arena[$spieler]['n']!=0) {
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=%s'>Nach Norden gehen</a>`@%s`n`n", $arena[$spieler]['n'], $arena[($arena[$spieler]['n'])]['desc'], true);
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=".$arena[$spieler]['n']);
			addnav("Norden","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=".$arena[$spieler]['n']);
		}
		if ($arena[$spieler]['s']!=0) {
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=%s'>Nach Süden</a>`@%s`n`n", $arena[$spieler]['s'], $arena[($arena[$spieler]['s'])]['desc'], true);
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=".$arena[$spieler]['s']);
			addnav("Süden","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=".$arena[$spieler]['s']);
		}
		if ($arena[$spieler]['w']!=0) {
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=%s'>Nach Westen gehen</a>`@%s`n`n", $arena[$spieler]['w'], $arena[($arena[$spieler]['w'])]['desc'], true);
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=".$arena[$spieler]['w']);
			addnav("Westen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=".$arena[$spieler]['w']);
		}
		if ($arena[$spieler]['o']!=0) {
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=%s'>Nach Osten gehen</a>`@%s`n`n", $arena[$spieler]['o'], $arena[($arena[$spieler]['o'])]['desc'], true);
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=".$arena[$spieler]['o']);
			addnav("Osten","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=bewegen&ziel=".$arena[$spieler]['o']);
		}
		if (($arena[$spieler]['verstecken']!=-99) && (!$versteckt)) {
			output("`@Oder willst Du lieber einen Moment hier bleiben und <a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=verstecken'>versuchen Dich zu verstecken</a>?`n", true);	
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=verstecken");
			addnav("Verstecken","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=verstecken");
		}
		if (($arena[$spieler]['verstecken']!=-99) && ($versteckt)) {
			output("`@Oder willst Du lieber noch einen Moment <a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=verstecken'>in Deinem Versteck bleiben</a>?`n", true);	
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=verstecken");
			addnav("Verstecken","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=verstecken");
		}
		return true;
	}
	
	// überprüft ob man von $spieler Ort zu $ziel Ort direkt schauen kann
	function verbindung_existiert($spieler, $ziel, &$arena) {
		$result = false;
		if ( ($arena[$spieler]['n'] == $ziel) || ($arena[$spieler]['o'] == $ziel) || ($arena[$spieler]['s'] == $ziel) || ($arena[$spieler]['w'] == $ziel) )
		{   
			$result = true;
		}
		return $result;
	}
	
	function bewege_wache($aktuellersektor, &$arena) {
		$anz_wege = $arena[$aktuellersektor]['wege'];
		$auswahl = e_rand(0,$anz_wege);
		if ($auswahl != 0)  
		{   // Wache entscheidet sich nicht zu warten... 
			$temp = $arena[$aktuellersektor];
			foreach ($temp as $i => $wert) {
				if ($i!='n' && $i!='o' && $i!='s' && $i!='w') { unset($temp[$i]); }
				elseif ($wert==0) { unset($temp[$i]); }
			}
			$temp = array_values($temp);
			// Jetzt haben alle Indizes Werte von 0..n mit n=(Anzahl der Ausgänge - 1)
			$neuersektor = $temp[$auswahl-1];
		} else {
			// Wache entscheidet sich zu warten... 
			$neuersektor = $aktuellersektor;
		}
		return $neuersektor;
	}
?>