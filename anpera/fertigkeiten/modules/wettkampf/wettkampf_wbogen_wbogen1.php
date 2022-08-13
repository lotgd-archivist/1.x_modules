<?php

function wettkampf_wbogen_wbogen1_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
	require_once("lib/fert.php");
		output("`@`bBlindschie�en`b`nAls Edranel Dir die Augen verbunden hat, konzentrierst Du Dich nur noch auf Deinen Pfeil ... Erst als er unterwegs ist, nimmst Du wieder etwas anderes wahr.");
	
		$bogen=get_fertigkeit(bogen);
		
		//$mod ist spezifisch f�r diesen Wettbewerb, um eine gute Auslastung bis 100 Punkte zu erreichen
		$mod=1.24;
			
		//Probe
		$t=probe($bogen, -25, 0.01, 99, true);
		if ($t[ergebnis] == "kritischer erfolg") $t[wert]=100;
		else if ($t[ergebnis] == "kritischer misserfolg") $t[wert]=0;
		else if ($t[wert] < 0) $t[wert]=0;
		
		$wbogen1=round($t[wert]*$mod);
		if ($wbogen1 > 100) $wbogen1=100;
				
		//Gesamtergebnis
		if ($wbogen1>0){
			output("`n`nDu erzielst mit Deinem Schuss `^%s`@ %s!", $wbogen1, ($wbogen1==1?"Punkt":"Punkte"));
			if ($wbogen1==100) output("`n`nDu erntest tosenden Applaus f�r diese Leistung! Die Zuschauer st�rmen den Platz, um Deine grandiose Leistung geb�hrend zu feiern!");
			else if ($wbogen1>=90 && $wbogen1<100) output("`n`nDu erntest tosenden Applaus f�r diese Leistung! Man bittet Dich um Autogramme!");
			else if ($wbogen1>=80 && $wbogen1<90) output("`n`nDu erntest tosenden Applaus f�r diese Leistung!");
			else if ($wbogen1>=60 && $wbogen1<80) output("`n`nDu erntest gro�en Applaus f�r diese Leistung!");
			else if ($wbogen1>=40 && $wbogen1<60) output("`n`nDu erntest Applaus f�r diese Leistung!");
			else if ($wbogen1>=20 && $wbogen1<40) output("`n`nDu erntest ein wenig Applaus f�r diese Leistung!");
			else if ($wbogen1>=10 && $wbogen1<20) output("`n`nDu erntest vereinzelt Applaus f�r diese Leistung!");
			set_module_pref("wbogen1", $wbogen1, "wettkampf");
			$bestbogen1=get_module_pref("bestbogen1", "wettkampf");
			if ($wbogen1>$bestbogen1) set_module_pref("bestbogen1", $wbogen1, "wettkampf");
		}else{
			output("`n`n`\$Du hast leider nicht getroffen ...");
			output("`n`nEinige Leute buhen Dich daf�r aus!");
			set_module_pref("wbogen1", 0, "wettkampf");
		}
		addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wbogen&subop2=wbogen2");
	page_footer();
}
?>