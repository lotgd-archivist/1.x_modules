<?php

function wettkampf_wklettern_klettern_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		require_once("modules/wettkampf/wettkampf_lib.php");
		$wklettern0=get_module_pref("wklettern0", "wettkampf");
		$bestklettern0=get_module_pref("bestklettern0", "wettkampf");
			
		$klettern=get_fertigkeit(klettern);
		$modkletterntext=set_modtext(klettern);	
		
		output("`@`bWettbewerb: Klettern im Tiefenschacht`b`n");
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		if ($wklettern0==10000) output("`@Oben auf dem Hügel haben sich bereits einige Kletterer versammelt, die in den Tiefenschacht hinabsteigen wollen. Kein gefahrloses Unterfangen, reichen solche Schächte doch mehrere Meilen senkrecht in den Boden. Die Teilnahme an diesem Wettbewerb kostet `^%s`@ Goldstücke.`n`n", ($teilnahme*$session[user][level]));
		if ($wklettern0!=10000) output("`@Regon meint, dass Du nun auf das Ende des Wettbewerbs warten musst.`n`n ");
		output ("`@Dein momentaner Fertigkeitswert im Klettern beträgt `^%s/100`@ Punkten! %s", $klettern, $modkletterntext);
		if ($wklettern0!=10000) output("`@`n`n`bDein Ergebnis`b");
		$wklettern0p=$wklettern0;
		if  ($wklettern0!=10000 && $wklettern0>0) output("`n`n`@Klettern im Tiefenschacht: `^%s`@ Meter.", $wklettern0); 
		if  ($wklettern0!=10000 && $wklettern0==0) output("`n`n`@Klettern im Tiefenschacht: Weil Du abgerutscht bist, wurdest Du `\$disqualifiziert`@.");
		if  ($wklettern0==$bestklettern0 && $wklettern0!=10000 && $wklettern0!=0){
		output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
		//Sieger
		$result = db_query(abfrage_wettbewerb(wklettern0, klettern, wklettern, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst im Kletternwettbewerb!");
		//Rekord
		$result = db_query(abfrage_wettbewerb(bestklettern0, klettern, bestklettern, 0, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wklettern0 == $sieger[data1]) output("`n`^`b--> Du hast in diesem Wettbewerb einen neuen Allzeitrekord aufgestellt!`b");
		if  ($wklettern0==10000) output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern0'>`n`nIch möchte am Wettbewerb im Klettern teilnehmen.</a>", true);
		output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Zurück.</a>", true);
		if  ($wklettern0==10000) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern0");
		addnav("","runmodule.php?module=wettkampf&op1=");
		if  ($wklettern0==10000) addnav("Teilnehmen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern0");
		addnav("Zurück","runmodule.php?module=wettkampf&op1=");
	page_footer();
}
?>