<?php

function wettkampf_wbogen_bogen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
		require_once("modules/wettkampf/wettkampf_lib.php");
		require_once("lib/fert.php");
		$wbogen0=get_module_pref("wbogen0", "wettkampf");
		$wbogen1=get_module_pref("wbogen1", "wettkampf");
		$wbogen2=get_module_pref("wbogen2", "wettkampf");
		$wbogen3=get_module_pref("wbogen3", "wettkampf");
		$bestbogen0=get_module_pref("bestbogen0", "wettkampf");
		$bestbogen1=get_module_pref("bestbogen1", "wettkampf");
		$bestbogen2=get_module_pref("bestbogen2", "wettkampf");
		$bestbogen3=get_module_pref("bestbogen3", "wettkampf");
		$bogen=get_fertigkeit(bogen);
		$modbogentext=set_modtext(bogen);	
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		
		output("`@`bWettbewerb: Bogenschießen`b`n");
		
	    if ($wbogen3==10000) output("`@Du näherst Dich einem elfischen Ehepaar, das gerade an einem Holztisch sitzt und Bewerbungen für den Wettbewerb annimmt. Als sie Dich erblicken, erhebt sich der Mann und und spricht Dich an: `#'Chara zum Gruße, mein Name ist Edranel! Ich nehme an, Ihr wollt Euch mit den anderen Bürgern im Bogenschießen messen. Die Teilnahme kostet `^%s`# Goldstücke.'`n`n", ($teilnahme*$session[user][level]));
	    if ($wbogen3!=10000) output("`@Edranel meint, dass Du nun auf das Ende des Wettbewerbs warten musst.`n`n ");
		output ("`@Dein momentaner Fertigkeitswert im Bogenschießen beträgt `^%s/100`@ Punkten! %s", $bogen, $modbogentext);
	    if ($wbogen3!=10000) output("`@`n`n`bDeine Ergebnisse`b");
	    if  ($wbogen0!=10000 && $wbogen0>0) output("`n`n`@Reiterschießen: `^%s/600`@ Punkten.", $wbogen0); 
	    if  ($wbogen0!=10000 && $wbogen0<=0) output("`n`n`@Reiterschießen: `\$Kein einziger Treffer`@.");
		if  ($wbogen0==$bestbogen0 && $wbogen0!=10000 && $wbogen0>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
	//Sieger
		$result = db_query(abfrage_wettbewerb(wbogen0, bogen, wbogen, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in dieser Disziplin!");
	
	//Rekord
		$result = db_query(abfrage_wettbewerb(bestbogen0, bogen, bestbogen, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wbogen0 == $sieger[data1]) output("`n`^`b--> Du hast in dieser Disziplin einen neuen Allzeitrekord aufgestellt!`b");
	    if  ($wbogen1!=10000 && $wbogen1>0) output("`n`n`@Blindschießen: `^%s/100 `@Punkten.", $wbogen1); 
	    if  ($wbogen1!=10000 && $wbogen1<=0) output("`n`n`@Blindschießen: `\$Nicht getroffen`@.");
		if  ($wbogen1==$bestbogen1 && $wbogen1!=10000 && $wbogen1>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
	//Sieger
		$result = db_query(abfrage_wettbewerb(wbogen1, bogen, wbogen, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in dieser Disziplin!");
	
	//Rekord
		$result = db_query(abfrage_wettbewerb(bestbogen1, bogen, bestbogen, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wbogen1 == $sieger[data1]) output("`n`^`b--> Du hast in dieser Disziplin einen neuen Allzeitrekord aufgestellt!`b");
	    if  ($wbogen2!=10000 && $wbogen2>0) output("`n`n`@Schnellschießen: `^%s/1000 `@Punkten.", $wbogen2); 
	    if  ($wbogen2!=10000 && $wbogen2<=0) output("`n`n`@Schnellschießen: `\$Kein einziger Treffer`@.");
		if  ($wbogen2==$bestbogen2 && $wbogen2!=10000 && $wbogen2>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
	
	//Sieger	
		$result = db_query(abfrage_wettbewerb(wbogen2, bogen, wbogen, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]) output("`n`2--> Du führst in dieser Disziplin!");
	
	//Rekord
		$result = db_query(abfrage_wettbewerb(bestbogen2, bogen, bestbogen, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wbogen2 == $sieger[data1]) output("`n`^`b--> Du hast in dieser Disziplin einen neuen Allzeitrekord aufgestellt!`b");
		if  ($wbogen3!=10000 && $wbogen3>0) output("`n`n`@Endergebnis: `^%s/1700`@ Punkten.", $wbogen3);
		if  ($wbogen3!=10000 && $wbogen3<=0) output("`n`n`\$Insgesamt hast Du nicht ein einziges Mal getroffen ...`@");	
		if  ($wbogen3==$bestbogen3 && $wbogen3!=10000 && $wbogen3>0){
			output("`2`n--> Das ist Dein persönlicher Rekord!");
		}
	
	//Sieger
		$result = db_query(abfrage_wettbewerb(wbogen3, bogen, wbogen, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid])output("`2`n--> Du führst in diesem Wettbewerb!");
	
	//Rekord
		$result = db_query(abfrage_wettbewerb(bestbogen3, bogen, bestbogen, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $sieger[data1]==$wbogen3)output("`^`n`b--> Du hast in diesem Wettbewerb einen neuen Allzeitrekord aufgestellt!`b");
		if  ($wbogen3==10000) output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wbogen&subop2=wbogen0'>`n`nIch möchte am Wettschießen teilnehmen.</a>", true);
	    output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Zurück.</a>", true);
	    if  ($wbogen3==10000) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wbogen&subop2=wbogen0");
	    addnav("","runmodule.php?module=wettkampf&op1=");
	    if  ($wbogen3==10000) addnav("Teilnehmen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wbogen&subop2=wbogen0");
	    addnav("Zurück","runmodule.php?module=wettkampf&op1=");
	page_footer();
}
?>