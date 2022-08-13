<?php

function wettkampf_werte_run_private($op){
	global $session;
	page_header("Der Platz der Vˆlker");
	//Fertigkeiten und Mods aufrufen
		require_once("lib/fert.php");
		require_once("modules/wettkampf/wettkampf_lib.php");
		
		$array=get_fertigkeiten_array();
		
		$bogen=$array["bogen"];
		$schwimmen=$array["schwimmen"];
		$kochen=$array["kochen"];
		$klettern=$array["klettern"];
		$musik=$array["musik"];
		$reiten=$array["reiten"];
		$schleichen=$array["schleichen"];
		
		$modbogentext=set_modtext(bogen);
		$modschwimmentext=set_modtext(schwimmen);
		$modkochentext=set_modtext(kochen);
		$modkletterntext=set_modtext(klettern);
		$modmusiktext=set_modtext(musik);
		$modreitentext=set_modtext(reiten);
		$modschleichentext=set_modtext(schleichen);
	
	//Wettkampfergebnisse aufrufen
		$wbogen0=get_module_pref("wbogen0");
		$wbogen1=get_module_pref("wbogen1");
		$wbogen2=get_module_pref("wbogen2");
		$wbogen3=get_module_pref("wbogen3");
		$bestbogen0=get_module_pref("bestbogen0");
		$bestbogen1=get_module_pref("bestbogen1");
		$bestbogen2=get_module_pref("bestbogen2");
		$bestbogen3=get_module_pref("bestbogen3");
		
		$wschwimm0=get_module_pref("wschwimm0");
		$wschwimm1=get_module_pref("wschwimm1");
		$wschwimm2=get_module_pref("wschwimm2");
		$bestschwimm0=get_module_pref("bestschwimm0");
		$bestschwimm1=get_module_pref("bestschwimm1");
		$bestschwimm2=get_module_pref("bestschwimm2");
		
		$wmusik0=get_module_pref("wmusik0");
		$wmusik1=get_module_pref("wmusik1");
		$wmusik2=get_module_pref("wmusik2");
		$bestmusik0=get_module_pref("bestmusik0");
		$bestmusik1=get_module_pref("bestmusik1");
		$bestmusik2=get_module_pref("bestmusik2");
		
		$wreiten0=get_module_pref("wreiten0");
		$wreiten1=get_module_pref("wreiten1");
		$wreiten2=get_module_pref("wreiten2");
		$bestreiten0=get_module_pref("bestreiten0");
		$bestreiten1=get_module_pref("bestreiten1");
		$bestreiten2=get_module_pref("bestreiten2");
		
		$wkochen=get_module_pref("wkochen");
		$bestkochen=get_module_pref("bestkochen");
		$bestespeise=get_module_pref("bestespeise");
		
		$wklettern0=get_module_pref("wklettern0");
		$bestklettern0=get_module_pref("bestklettern0");
		
		$wschleichen0=get_module_pref("wschleichen0");
		$bestschleichen0=get_module_pref("bestschleichen0");
	
		//Werte
		output ("`@`bI. Deine Fertigkeitswerte`b`n`n");
		output("`@Bogenschieﬂen: `^%s/100%s`@`n", $bogen, $modbogentext);
		if ($bogen<25) output("`2--> Rang: %s.", ($session[user][sex]?"Anf‰ngerin":"Anf‰nger"));
		else if ($bogen>=25 && $bogen <50) output("`2--> Rang: %s.", ($session[user][sex]?"Sch¸lerin":"Sch¸ler"));
		else if ($bogen>=50 && $bogen < 75) output("`2--> Rang: %s.", ($session[user][sex]?"Fortgeschrittene":"Fortgeschrittener"));
		else if ($bogen>=75 && $bogen <= 95) output("`2--> Rang: %s.", ($session[user][sex]?"Meisterin":"Meister"));
		else if ($bogen>95) output("`2--> Rang: %s.", ($session[user][sex]?"Groﬂmeisterin":"Groﬂmeister"));
		
		output("`@`n`nKlettern: `^%s/100%s`@`n", $klettern, $modkletterntext);
		if ($klettern<25) output("`2--> Rang: %s.", ($session[user][sex]?"Anf‰ngerin":"Anf‰nger"));
		else if ($klettern>=25 && $klettern <50) output("`2--> Rang: %s.", ($session[user][sex]?"Sch¸lerin":"Sch¸ler"));
		else if ($klettern>=50 && $klettern < 75) output("`2--> Rang: %s.", ($session[user][sex]?"Fortgeschrittene":"Fortgeschrittener"));
		else if ($klettern>=75 && $klettern <= 95) output("`2--> Rang: %s.", ($session[user][sex]?"Meisterin":"Meister"));
		else if ($klettern>95) output("`2--> Rang: %s.", ($session[user][sex]?"Groﬂmeisterin":"Groﬂmeister"));
		
		output("`n`n`@Kochen und Pflanzenkunde: `^%s/100%s`@`n", $kochen, $modkochentext);
		if ($bestespeise!="")output("`2--> Als bislang beste Speise hast Du der Jury `i%s`i pr‰sentiert.`n", $bestespeise);
		if ($kochen<25) output("`2--> Rang: %s.", ($session[user][sex]?"Anf‰ngerin":"Anf‰nger"));
		else if ($kochen>=25 && $kochen <50) output("`2--> Rang: %s.", ($session[user][sex]?"Sch¸lerin":"Sch¸ler"));
		else if ($kochen>=50 && $kochen < 75) output("`2--> Rang: %s.", ($session[user][sex]?"Fortgeschrittene":"Fortgeschrittener"));
		else if ($kochen>=75 && $kochen <= 95) output("`2--> Rang: %s.", ($session[user][sex]?"Meisterin":"Meister"));
		else if ($kochen>95) output("`2--> Rang: %s.", ($session[user][sex]?"Groﬂmeisterin":"Groﬂmeister"));
		
		output("`@`n`nMusik und Gesang: `^%s/100%s`@`n", $musik, $modmusiktext);
		if ($musik<25) output("`2--> Rang: %s.", ($session[user][sex]?"Anf‰ngerin":"Anf‰nger"));
		else if ($musik>=25 && $musik <50) output("`2--> Rang: %s.", ($session[user][sex]?"Sch¸lerin":"Sch¸ler"));
		else if ($musik>=50 && $musik < 75) output("`2--> Rang: %s.", ($session[user][sex]?"Fortgeschrittene":"Fortgeschrittener"));
		else if ($musik>=75 && $musik <= 95) output("`2--> Rang: %s.", ($session[user][sex]?"Meisterin":"Meister"));
		else if ($musik>95) output("`2--> Rang: %s.", ($session[user][sex]?"Groﬂmeisterin":"Groﬂmeister"));
		
		output("`@`n`nReiten und Kutschefahren: `^%s/100%s`@`n", $reiten, $modreitentext);
		if ($reiten<25) output("`2--> Rang: %s.", ($session[user][sex]?"Anf‰ngerin":"Anf‰nger"));
		else if ($reiten>=25 && $reiten <50) output("`2--> Rang: %s.", ($session[user][sex]?"Sch¸lerin":"Sch¸ler"));
		else if ($reiten>=50 && $reiten < 75) output("`2--> Rang: %s.", ($session[user][sex]?"Fortgeschrittene":"Fortgeschrittener"));
		else if ($reiten>=75 && $reiten <= 95) output("`2--> Rang: %s.", ($session[user][sex]?"Meisterin":"Meister"));
		else if ($reiten>95) output("`2--> Rang: %s.", ($session[user][sex]?"Groﬂmeisterin":"Groﬂmeister"));
		
		output("`@`n`nSchleichen und Verstecken: `^%s/100%s`@`n", $schleichen, $modschleichentext);
		if ($schleichen<25) output("`2--> Rang: %s.", ($session[user][sex]?"Anf‰ngerin":"Anf‰nger"));
		else if ($schleichen>=25 && $schleichen <50) output("`2--> Rang: %s.", ($session[user][sex]?"Sch¸lerin":"Sch¸ler"));
		else if ($schleichen>=50 && $schleichen < 75) output("`2--> Rang: %s.", ($session[user][sex]?"Fortgeschrittene":"Fortgeschrittener"));
		else if ($schleichen>=75 && $schleichen <= 95) output("`2--> Rang: %s.", ($session[user][sex]?"Meisterin":"Meister"));
		else if ($schleichen>95) output("`2--> Rang: %s.", ($session[user][sex]?"Groﬂmeisterin":"Groﬂmeister"));
		
		output("`n`n`@Schwimmen und Tauchen: `^%s/100%s`@`n", $schwimmen, $modschwimmentext);
		if ($schwimmen<25) output("`2--> Rang: %s.", ($session[user][sex]?"Anf‰ngerin":"Anf‰nger"));
		else if ($schwimmen>=25 && $schwimmen <50) output("`2--> Rang: %s.", ($session[user][sex]?"Sch¸lerin":"Sch¸ler"));
		else if ($schwimmen>=50 && $schwimmen < 75) output("`2--> Rang: %s.", ($session[user][sex]?"Fortgeschrittene":"Fortgeschrittener"));
		else if ($schwimmen>=75 && $schwimmen <= 95) output("`2--> Rang: %s.", ($session[user][sex]?"Meisterin":"Meister"));
		else if ($schwimmen>95) output("`2--> Rang: %s.", ($session[user][sex]?"Groﬂmeisterin":"Groﬂmeister"));
		
		output("`n`n`i`2`bAnmerkungen:`b`n Der Startwert betr‰gt 5, kann jedoch durch einen Rassenmalus (-5) auf den Minimalwert 0 gesetzt sein.`n Der Maximalwert von 95 kann nur mit einem Rassenbonus (+5) ¸berschritten werden.`n Achtung: W‰ge bei Deinen Steigerungen gut ab, welchen zuk¸nftigen Nutzen Du Dir von der jeweiligen Fertigkeit versprichst.`i");
		addnav("Zur¸ck","runmodule.php?module=wettkampf&op1=");
	
		//Ergebnisse
		$fest=get_module_setting("fest");
		$festtext=translate_inline("bei diesem");
		$festtext2=translate_inline("Du f¸hrst bei diesem Wettbewerb!"); 
		$standardwhere = "(locked=0)";
		if ($fest==0){
			$festtext=translate_inline("beim letzten");
			$festtext2=translate_inline("Du hast diesen Wettbewerb gewonnen!");
		}
		$siegerbogen=sieger(bogen);
		$siegerklettern=sieger(klettern);
		$siegerkochen=sieger(kochen);
		$siegerreiten=sieger(reiten);
		$siegerschleichen=sieger(schleichen);
		$siegerschwimmen=sieger(schwimmen);
		$siegermusik=sieger(musik);
	
		if ($wbogen3 != 10000 || $wschwimm2 != 10000 || $wkochen != 10000 || $wklettern0 != 10000 || $wschleichen0 != 10000 || $wreiten2 != 10000 || $wmusik2 != -1) output ("`@`b`n`n`nII. Deine Ergebnisse %s Fest der Vˆlker`b`n`n", $festtext);
		if ($wbogen3!=10000) output("`@Bogenschieﬂen: %s.`n", ($wbogen3==0?"`\$Kein einziger Treffer`2":($wbogen3==1?"`^$wbogen3`2 Punkt":"`^$wbogen3`2 Punkte")));
		if ($siegerbogen[acctid]==$session[user][acctid])output("`2--> %s`n", $festtext2);
	
		if ($wklettern0!=10000) output("`@`nKlettern: %s.`n", ($wklettern0==0?"`\$Disqualifiziert`2":"`^$wklettern0`2 Meter"));
		if ($siegerklettern[acctid]==$session[user][acctid])output("`2--> %s`n", $festtext2);
	
		if ($wkochen!=10000) output("`@`nKochen: %s.`n", ($wkochen==0?"`\$Disqualifiziert`2":($wkochen==1?"`^$wkochen `2Punkt":"`^$wkochen `2Punkte")));
		if ($siegerkochen[acctid]==$session[user][acctid])output("`2--> %s`n", $festtext2);
	
		if ($wmusik2!=-1) output("`@`nMusik und Gesang: %s.`n", ($wmusik2==0?"`\$Disqualifiziert`2":($wmusik2==1?"`^$wmusik2`2 Punkt":"`^$wmusik2`2 Punkte")));
		if ($siegermusik[acctid]==$session[user][acctid])output("`2--> %s`n", $festtext2);
			
		if ($wreiten2!=10000) output("`@`nReiten und Kutschefahren: %s.`n", ($wreiten2==0?"`\$Null Punkte`2":($wreiten2==1?"`^$wreiten2`2 Punkt":"`^$wreiten2`2 Punkte")));
		if ($siegerreiten[acctid]==$session[user][acctid])output("`2--> %s`n", $festtext2);
		
		if ($wschleichen0!=10000) output("`@`nSchleichen und Verstecken: %s.`n", ($wschleichen0==9999?"`\$Disqualifiziert`2":($wschleichen0==1?"`^$wschleichen0`2 Minute":"`^$wschleichen0`2 Minuten")));
		if ($siegerschleichen[acctid]==$session[user][acctid])output("`2--> %s`n", $festtext2);
		
		if ($wschwimm2!=10000) output("`@`nSchwimmen und Tauchen: `^%s`2 Punkte.`n", $wschwimm2);
		if ($siegerschwimmen[acctid]==$session[user][acctid])output("`2--> %s`n", $festtext2);
	page_footer();
}
?>