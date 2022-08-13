<?php

function wettkampf_wreiten_reiten_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
		require_once("modules/wettkampf/wettkampf_lib.php");
		require_once("lib/fert.php");
		$wreiten0=get_module_pref("wreiten0", "wettkampf");
		$wreiten1=get_module_pref("wreiten1", "wettkampf");
		$wreiten2=get_module_pref("wreiten2", "wettkampf");
		$bestreiten0=get_module_pref("bestreiten0", "wettkampf");
		$bestreiten1=get_module_pref("bestreiten1", "wettkampf");
		$bestreiten2=get_module_pref("bestreiten2", "wettkampf");
		$wreiten0p=round((18-$wreiten0) * 45);
		$wreiten1p=round($wreiten1 * 270);
		if ($wreiten0p > 500) $wreiten0p=500;
		if ($wreiten1p > 500) $wreiten1p=500;
		
		$reiten=get_fertigkeit(reiten);
		$modreitentext=set_modtext(reiten);	
			
		output("`@`bWettbewerb: Reiten`b`n");
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
	    if ($wreiten2==10000) output("`@Du schaust zur Tribüne hinauf: Die Zuschauerreihen sind gut gefüllt - alle Leute scheinen nur auf Deine Reitkünste zu warten! Die Teilnahme an diesem Wettbewerb kostet `^%s`@ Goldstücke.`n`n", ($teilnahme*$session[user][level]));
	    if ($wreiten2!=10000) output("`@Hannes IV. meint, dass Du nun auf das Ende des Wettbewerbs warten musst.`n`n ");
		output ("`@Dein momentaner Fertigkeitswert im Reiten beträgt `^%s/100`@ Punkten! %s", $reiten, $modreitentext);
	    if ($wreiten2!=10000) output("`@`n`n`bDeine Ergebnisse`b");
	    if  ($wreiten0!=10000 && $wreiten0>0) output("`n`n`@Wettreiten: `^%s`@ Minuten, was `^%s/500`@ Punkten entspricht.", $wreiten0, $wreiten0p); 
		if  ($wreiten0==$bestreiten0 && $wreiten0!=10000 && $wreiten0>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
		//Sieger
		$result = db_query(abfrage_wettbewerb(wreiten0, reiten, wreiten, 10000, 10000, false, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in dieser Disziplin!");
	
		//Rekord
		$result = db_query(abfrage_wettbewerb(bestreiten0, reiten, bestreiten, 0, 10000, false, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wreiten0 == $sieger[data1]) output("`n`^`b--> Du hast in dieser Disziplin einen neuen Allzeitrekord aufgestellt!`b");
		if  ($wreiten1!=10000 && $wreiten1>0) output("`n`n`@Bullenreiten: `^%s `@Minuten, was `^%s/500`@ Punkten entspricht.", $wreiten1, $wreiten1p); 
		if  ($wreiten0!=10000 && $wreiten1==0) output("`n`n`@Bullenreiten: `\$Gestürzt.`@");
		if  ($wreiten1==$bestreiten1 && $wreiten1!=10000 && $wreiten1>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
		//Sieger
		$result = db_query(abfrage_wettbewerb(wreiten1, reiten, wreiten, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in dieser Disziplin!");
	
		//Rekord
		$result = db_query(abfrage_wettbewerb(bestreiten1, reiten, bestreiten, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wreiten1 == $sieger[data1]) output("`n`^`b--> Du hast in dieser Disziplin einen neuen Allzeitrekord aufgestellt!`b");
		if  ($wreiten2!=10000) output("`n`n`@Gesamtergebnis: `^%s/1000`@ Punkten.", $wreiten2); 
		if  ($wreiten2==$bestreiten2 && $wreiten2!=10000 && $wreiten2>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
		//Sieger
		$result = db_query(abfrage_wettbewerb(wreiten2, reiten, wreiten, 10000, 10000, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in diesem Wettbewerb!");
		//Rekord
		$result = db_query(abfrage_wettbewerb(bestreiten2, reiten, bestreiten, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wreiten2 == $sieger[data1]) output("`n`^`b--> Du hast in diesem Wettbewerb einen neuen Allzeitrekord aufgestellt!`b");
		if  ($wreiten2==10000) output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wreiten&subop2=wreiten0'>`n`nIch möchte am Reitwettbewerb teilnehmen.</a>", true);
		output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Zurück.</a>", true);
		if  ($wreiten2==10000) addnav("","runmodule.php?module&op1=aufruf&subop1=wreiten&subop2=wreiten0");
		addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wreiten&subop2=wreiten0");
		if  ($wreiten2==10000) addnav("Teilnehmen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wreiten&subop2=wreiten0");
		addnav("Zurück","runmodule.php?module=wettkampf&op1=");
	page_footer();
}
?>