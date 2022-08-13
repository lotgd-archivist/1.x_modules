<?php

function wettkampf_wschwimmen_schwimmen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
		require_once("modules/wettkampf/wettkampf_lib.php");	
		require_once("lib/fert.php");
		$wschwimm0=get_module_pref("wschwimm0", "wettkampf");
		$wschwimm1=get_module_pref("wschwimm1", "wettkampf");
		$wschwimm2=get_module_pref("wschwimm2", "wettkampf");
		$bestschwimm0=get_module_pref("bestschwimm0", "wettkampf");
		$bestschwimm1=get_module_pref("bestschwimm1", "wettkampf");
		$bestschwimm2=get_module_pref("bestschwimm2", "wettkampf");
		$schwimmen=get_fertigkeit(schwimmen);
		$modschwimmentext=set_modtext(schwimmen);
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		$wschwimm0p=round((16.8 - $wschwimm0) * 37);
	    $wschwimm1p=round($wschwimm1 * 55);
	    if ($wschwimm0p > 500) $wschwimm0p=500;
		if ($wschwimm1p > 500) $wschwimm1p=500;
			
		output("`@`bWettbewerb: Schwimmen und Tauchen im Schlammtümpel`b`n");
		
	    if ($wschwimm2==10000) output("`@Schon von weitem siehst Du einige Bürger um den Sieg schwimmen. Ein Troll schlägt rhythmisch auf eine Trommel und ein anderer notiert die Zahl der Schläge - offenbar nehmen sie die Zeit. Die Teilnahme an diesem Wettbewerb kostet `^%s`@ Goldstücke.`n`n", ($teilnahme*$session[user][level]));
	    if ($wschwimm2!=10000) output("`@Chro'ghran meint, dass Du nun auf das Ende des Wettbewerbs warten musst.`n`n ");
		output ("`@Dein momentaner Fertigkeitswert im Schwimmen und Tauchen beträgt `^%s/100`@ Punkten! %s", $schwimmen, $modschwimmentext);
	    if ($wschwimm2!=10000) output("`@`n`n`bDeine Ergebnisse`b");
		if  ($wschwimm0!=10000 && $wschwimm0>0) output("`n`n`@Schlammbahnenschwimmen: `^%s`@ Minuten, was `^%s entspricht.", $wschwimm0, ($wschwimm0p==1?"$wschwimm0p`@ Punkt":"$wschwimm0p `@Punkten")); 
		if  ($wschwimm0==$bestschwimm0 && $wschwimm0!=10000 && $wschwimm0>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
	
	//Sieger
		$result = db_query(abfrage_wettbewerb(wschwimm0, schwimmen, wschwimm, 10000, 500, false, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in dieser Disziplin!");
	//Rekord
		$result = db_query(abfrage_wettbewerb(bestschwimm0, schwimmen, bestschwimm, 10000, 0, false, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wschwimm0 == $sieger[data1]) output("`n`^`b--> Du hast in dieser Disziplin einen neuen Allzeitrekord aufgestellt!`b");
	    if  ($wschwimm1!=10000 && $wschwimm1>0) output("`n`n`@Langzeittauchen im Schlammtümpel: `^%s `@Minuten, was `^%s entspricht.", $wschwimm1, ($wschwimm1p==1?"$wschwimm1p`@ Punkt":"$wschwimm1p `@Punkten")); 
	    if  ($wschwimm1!=10000 && $wschwimm1<=0) output("`n`n`@Langzeittauchen im Schlammtümpel: `\$Aus sofortiger Atemnot versagt`@.");
		if  ($wschwimm1==$bestschwimm1 && $wschwimm1!=10000 && $wschwimm1>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
	//Sieger
		$result = db_query(abfrage_wettbewerb(wschwimm1, schwimmen, wschwimm, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in dieser Disziplin!");
	
	//Rekord
		$result = db_query(abfrage_wettbewerb(bestschwimm1, schwimmen, bestschwimm, 10000, 10000, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wschwimm1 == $sieger[data1]) output("`n`^`b--> Du hast in dieser Disziplin einen neuen Allzeitrekord aufgestellt!`b");
	    if  ($wschwimm2!=10000 && $wschwimm2>0) output("`n`n`@Gesamtergebnis: `^%s/1000`@.", $wschwimm2); 
		if  ($wschwimm2==$bestschwimm2 && $wschwimm2!=10000 && $wschwimm2>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
	//Sieger
		$result = db_query(abfrage_wettbewerb(wschwimm2, schwimmen, wschwimm, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in diesem Wettbewerb!");
	//Rekord
		$result = db_query(abfrage_wettbewerb(bestschwimm2, schwimmen, bestschwimm, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wschwimm2 == $sieger[data1]) output("`n`^`b--> Du hast in diesem Wettbewerb einen neuen Allzeitrekord aufgestellt!`b");
		if  ($wschwimm2==10000) output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschwimmen&subop2=wschwimm0'>`n`nIch möchte am Wettschwimmen und -tauchen teilnehmen.</a>", true);
	    output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Zurück.</a>", true);
	    if  ($wschwimm2==10000) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschwimmen&subop2=wschwimm0");
	    addnav("","runmodule.php?module=wettkampf&op1=");
	    if  ($wschwimm2==10000) addnav("Teilnehmen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschwimmen&subop2=wschwimm0");
	    addnav("Zurück","runmodule.php?module=wettkampf&op1=");
	page_footer();
}
?>