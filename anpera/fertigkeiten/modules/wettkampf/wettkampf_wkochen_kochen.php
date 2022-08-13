<?php

function wettkampf_wkochen_kochen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
	require_once("lib/fert.php");
		require_once("modules/wettkampf/wettkampf_lib.php");
		$wkochen=get_module_pref("wkochen", "wettkampf");
		$bestkochen=get_module_pref("bestkochen", "wettkampf");
		$kochen=get_fertigkeit(kochen);
		$modkochentext=set_modtext(kochen);	
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		$speise=get_module_pref("letztespeise", "wettkampf");
		
		output("`@`bWettbewerb: Kochen`b`n");
		
	    if ($wkochen==10000) output("`@Vor dem Lehmhaus, in dem Du sonst Deine Kochstunden nimmst, haben die Echsen einen langen Tisch aufgebaut, auf dem sich die Beitr�ge der bisherigen Teilnehmer befinden. Da das Fest der V�lker ohne Blutvergie�en verlaufen soll und aus R�cksicht gegen�ber den Elfen, sind nur vegetarische Beitr�ge zugelassen. Es duftet ganz und gar wunderbar! Die Teilnahme an diesem Wettbewerb kostet `^%s`@ Goldst�cke.`n`n", ($teilnahme*$session[user][level]));
	    if ($wkochen!=10000) output("`@Ag'nsra meint, dass Du nun auf das Ende des Wettbewerbs warten musst.`n`n ");
		output ("`@Dein momentaner Fertigkeitswert im Kochen betr�gt `^%s/100`@ Punkten! %s", $kochen, $modkochentext);
	    if ($wkochen!=10000) output("`@`n`n`bDein Ergebnis`b");
		if ($wkochen!=10000 && $wkochen>0) output("`n`@Du hast der Jury `^%s`@ pr�sentiert, wof�r Du eine Wertung von `^%s/125`@ Punkten bekommen hast!", $speise, $wkochen); 
		if ($wkochen!=10000 && $wkochen<=0) output("`n`@Du hast der Jury nur `^%s`@ pr�sentiert und bist deshalb `\$disqualifiziert`@ worden.", $speise);
		if ($wkochen==$bestkochen && $wkochen!=10000 && $wkochen>0){
			output("`2`n--> Das ist Dein pers�nlicher Rekord!");
		}
	
		//Sieger
		$result = db_query(abfrage_wettbewerb(wkochen, kochen, wkochen, 10000, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid]){
			output("`n`2--> Du f�hrst in diesem Wettbewerb!");
			set_module_setting("siegspeise", $speise, "wettkampf"); 
		}
		//Rekord
		$result = db_query(abfrage_wettbewerb(bestkochen, kochen, bestkochen, 0, 0, true, 1)) or die(db_error(LINK));
		$sieger = db_fetch_assoc($result);
		if ($sieger[acctid]==$session[user][acctid] && $wkochen == $sieger[data1]){
			output("`n`^`b--> Du hast in diesem Wettbewerb einen neuen Allzeitrekord aufgestellt!`b");
			set_module_setting("bestespeise", $speise, "wettkampf");
		}
		if  ($wkochen==10000) output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen'>`n`nIch m�chte am Wettkochen teilnehmen.</a>", true);
	    output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Zur�ck.</a>", true);
	    if  ($wkochen==10000) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen");
	    addnav("","runmodule.php?module=wettkampf&op1=");
	    if  ($wkochen==10000) addnav("Teilnehmen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen");
	    addnav("Zur�ck","runmodule.php?module=wettkampf&op1=");
	page_footer();
}
?>