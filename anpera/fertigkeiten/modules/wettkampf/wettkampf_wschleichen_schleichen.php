<?php

function wettkampf_wschleichen_schleichen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
		
	//Wettbewerb: Schleichen und Verstecken   **********************************************************
	//Von Oliver Wellinghoff, stark �berarbeitet von Nico Lachmann
		require_once("modules/wettkampf/wettkampf_lib.php");
		require_once("lib/fert.php");
		// Startseite f�r Schleichwettbewerb, auf der sich der Spieler einschreiben kann,
		// falls er noch nicht am Wettbewerb teilgenommen hat (er hat einen zweiten Versuch,
		// falls der erste mi�lingt)
		output("`@`bWettbewerb: Schleichen und Verstecken `b`n`n");
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
						
		$schleichen		 = get_fertigkeit(schleichen);
		$modschleichentext=set_modtext(schleichen);	
		
		$wschleichen0    = get_module_pref("wschleichen0", "wettkampf");
		$bestschleichen0 = get_module_pref("bestschleichen0", "wettkampf");
		$versucheoffen   = get_module_pref("schleichenversuch", "wettkampf");

		if (($versucheoffen>0) && ($wschleichen0==10000) ) {
			output("`@Kalyth erkl�rt Dir den Ablauf. Du musst so schnell wie m�glich an zwei Gegenst�nde gelangen, die auf dem Festplatz verteilt sind und sie zum Startpunkt zur�ckbringen. Sie zu nehmen, wird kein Problem darstellen - dabei nicht von einem der W�chter entdeckt zu werden, hingegen schon. Die Teilnahme an diesem Wettbewerb kostet `^%s`@ Goldst�cke.`n`n", ($teilnahme*$session['user']['level']), true );
			output ("`@Dein momentaner Fertigkeitswert im Schleichen und Verstecken betr�gt `^%s/95`@ Punkten%s!", $schleichen, $modschleichentext);
			addnav("Teilnehmen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_start");
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_start'>`n`nIch m�chte am Wettbewerb im Schleichen und Verstecken teilnehmen.</a>", true);
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_start");
		} else {
			output("`@Kalyth meint, dass Du nun auf das Ende des Wettbewerbs warten musst.`n`n ");
			output ("`@Dein momentaner Fertigkeitswert im Schleichen und Verstecken betr�gt `^%s/95`@ Punkten%s!", $schleichen, $modschleichentext);
			output("`@`n`n`bDein Ergebnis`b");
			if  ($wschleichen0!=9999) {
				output("`n`n`@Schleichen und Verstecken: `^%s`@ Minuten.", $wschleichen0); 
				if  ($wschleichen0==$bestschleichen0) {
					output("`2`n--> Das ist Dein pers�nlicher Rekord!"); 
				}
				$result = db_query(abfrage_wettbewerb(wschleichen0, schleichen, wschleichen, 10000, 9999, false, 1)) or die(db_error(LINK));
				$sieger = db_fetch_assoc($result);
				if ($sieger['acctid']==$session['user']['acctid']) {
					output("`n`2--> Du f�hrst in diesem Wettbewerb!");
				}
				$result = db_query(abfrage_wettbewerb(bestschleichen0, schleichen, bestschleichen, 10000, 10000, false, 1)) or die(db_error(LINK));
				$sieger = db_fetch_assoc($result);
				if ($sieger['acctid']==$session['user']['acctid'] && $wschleichen0 == $sieger['data1']) {
					output("`n`^`b--> Du hast in diesem Wettbewerb einen neuen Allzeitrekord aufgestellt!`b");
				}
			} else { 
				output("`n`n`@Schleichen und Verstecken: `\$Du wurdest zweimal entdeckt und bist deshalb disqualifiziert worden`@.");
			}
		}
		addnav("Zur�ck","runmodule.php?module=wettkampf&op1=");
		output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Zur�ck.</a>", true);
		addnav("","runmodule.php?module=wettkampf&op1=");
		page_footer();
}
?>