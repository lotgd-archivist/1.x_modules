<?php

function wettkampf_wmusik_musik_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
		require_once("modules/wettkampf/wettkampf_lib.php");
		require_once("lib/fert.php");		
		$wmusik0=get_module_pref("wmusik0", "wettkampf");	// Zuschauerzahl
		$wmusik1=get_module_pref("wmusik1", "wettkampf");	// Stimmungspunkte
		$wmusik2=get_module_pref("wmusik2", "wettkampf");	// Gesamtpunktzahl
		
		$bestmusik0=get_module_pref("bestmusik0", "wettkampf");
		$bestmusik1=get_module_pref("bestmusik1", "wettkampf");
		$bestmusik2=get_module_pref("bestmusik2", "wettkampf");
		$wmusik0p=round($wmusik0 * 1.17);
		if ($wmusik0p > 500) $wmusik0p=500;
					
		$musik=get_fertigkeit(musik);
		$modmusiktext=set_modtext(musik);	
		
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
				
		output("`@`bWettbewerb: Musik und Gesang`b`n");
		if ($wmusik2==-1) output("`@Du gehst direkt vom Haupttor des Platzes an der Statue vorbei zur Bühne der Vanthira, die Du schon von weitem laut und deutlich hören kannst. Der derzeitige Intepret wird gefeiert wie ein Halbgott! Und Du könntest es ihm gleichtun ... Die Teilnahme an diesem Wettbewerb kostet `^%s`@ Goldstücke.`n`n", ($teilnahme*$session[user][level]));
		if ($wmusik0!=-1) output("`@Ra'esha meint, dass Du nun auf das Ende des Wettbewerbs warten musst.`n`n ");
		output ("`@Dein momentaner Fertigkeitswert im Musizieren und Singen beträgt `^%s/100`@ Punkten! %s", $musik, $modmusiktext);
		
		if ($wmusik0!=-1) output("`@`n`n`bDeine Ergebnisse`b");
		
		if  ($wmusik0!=-1) output("`n`n`@Dein Publikum am Ende der Darbietung: %s`@", ($wmusik0==0?"`\$Du hast absolut jedes empfindungsfähige Wesen vergrault, das gekommen war ...":"`^$wmusik0`@ Zuschauer, was `^$wmusik0p/500`@ Punkten entspricht."));
		if  ($wmusik0==$bestmusik0 && $wmusik0!=-1 && $wmusik0!=0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
		//Sieger
		$result = db_query(abfrage_wettbewerb(wmusik0, musik, wmusik, -1, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in dieser Disziplin!");
		//Rekord
		$result = db_query(abfrage_wettbewerb(bestmusik0, musik, bestmusik, 0, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wmusik0 == $sieger[data1]) output("`n`^`b--> Du hast in dieser Disziplin einen neuen Allzeitrekord aufgestellt!`b");
		
		if  ($wmusik1!=-1) output("`n`n`@Seine Stimmung zu diesem Zeitpunkt: %s`@", ($wmusik1==0?"`\$Du konntest dem wütenden Mob gerade noch entkommen ...":"`^$wmusik1/500`@ Punkten."));
		if  ($wmusik1==$bestmusik1 && $wmusik1!=-1 && $wmusik1!=0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
		//Sieger
		$result = db_query(abfrage_wettbewerb(wmusik1, musik, wmusik, -1, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in dieser Disziplin!");
		//Rekord
		$result = db_query(abfrage_wettbewerb(bestmusik1, musik, bestmusik, 0, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wmusik1 == $sieger[data1]) output("`n`^`b--> Du hast in dieser Disziplin einen neuen Allzeitrekord aufgestellt!`b");
		
		if  ($wmusik2!=-1) output("`n`n`@Deine Gesamtergebnis: %s", ($wmusik2==0?"`\$Die Vanthira vergeben nur sehr ungerne das Prädikat 'Disqualifiziert', aber ... sie vergeben es`@.":"`^$wmusik2/1000`@ Punkten."));
		if  ($wmusik2==$bestmusik2 && $wmusik2!=-1 && $wmusik2!=0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
		//Sieger
		$result = db_query(abfrage_wettbewerb(wmusik2, musik, wmusik, -1, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in diesem Wettbewerb!");
		//Rekord
		$result = db_query(abfrage_wettbewerb(bestmusik2, musik, bestmusik, 0, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wmusik2 == $sieger[data1]) output("`n`^`b--> Du hast in diesem Wettbewerb einen neuen Allzeitrekord aufgestellt!`b");
			
		if  ($wmusik0==-1) output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=anfang'>`n`nIch möchte am Wettbewerb im Musizieren und Singen teilnehmen.</a>", true);
		output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Zurück.</a>", true);
		if  ($wmusik0==-1) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=anfang");
		addnav("","runmodule.php?module=wettkampf&op1=");
		if  ($wmusik0==-1) addnav("Teilnehmen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=anfang");
		addnav("Zurück","runmodule.php?module=wettkampf&op1=");
	page_footer();
}

?>