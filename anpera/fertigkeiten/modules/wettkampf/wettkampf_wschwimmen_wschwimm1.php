<?php

function wettkampf_wschwimmen_wschwimm1_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		output("`@`bLangzeittauchen im Schlammtümpel`b`nDu holst tieeeef Luft und hältst Dich unter Wasser an einer Stange fest ...");
	
		$schwimmen=get_fertigkeit(schwimmen);		
		$wschwimm1=0;	
		$wschwimm0=get_module_pref("wschwimm0", "wettkampf");
		$bestschwimm2=get_module_pref("bestschwimm2", "wettkampf");
		
		//Proben
		$t1=probe($schwimmen, 40, 0.9, 99.1, true);
		$t2=probe($schwimmen, 25, 0.9, 99.1, true);
		$t3=probe($schwimmen, 10, 0.9, 99.1, true);
		$t4=probe($schwimmen, 5, 0.9, 99.1, true);
		$t5=probe($schwimmen, 0, 0.9, 99.1, true);
		$t6=probe($schwimmen, -15, 0.9, 99.1, true);
		$t7=probe($schwimmen, -25, 0.9, 99.1, true);
		$t8=probe($schwimmen, -35, 0.9, 99.1, true);
		$t9=probe($schwimmen, -45, 0.9, 99.1, true);
			
		$t=array(
			"1" => array(
					$t1[wert],
					$t1[ergebnis]),
			"2" => array(
					$t2[wert],
					$t2[ergebnis]),
			"3" => array(
					$t3[wert],
					$t3[ergebnis]),				
			"4" => array(
					$t4[wert],
					$t4[ergebnis]),
			"5" => array(
					$t5[wert],
					$t5[ergebnis]),		
			"6" => array(
					$t6[wert],
					$t6[ergebnis]),
			"7" => array(
					$t7[wert],
					$t7[ergebnis]),
			"8" => array(
					$t8[wert],
					$t8[ergebnis]),
			"9" => array(
					$t9[wert],
					$t9[ergebnis]),		
		);
	
		while (list($key, $value) = each ($t)) {
			if ($value[1] == "kritischer erfolg") $value[0]=60;
			else if ($value[1] == "kritischer misserfolg") $value[0]=0;
			$modvalue=$value[0];
			if ($modvalue > 0) $wschwimm1sek+=$modvalue;
			else break;
		}	
	 	
		$wschwimm1=round($wschwimm1sek / 60, 3);
		set_module_pref("wschwimm1", $wschwimm1, "wettkampf");
		if ($wschwimm1>$bestschwimm1) set_module_pref("bestschwimm1", $wschwimm1, "wettkampf");
			
		if ($wschwimm1sek>500) output("`n`n`^%s`@ Minuten unter Wasser! Du erntest tosenden Applaus für diese Leistung! Man bittet Dich um Autogramme!", $wschwimm1);
		else if ($wschwimm1sek>420 && $wschwimm1sek<=500) output("`n`n`^%s`@ Minuten unter Wasser! Du erntest tosenden Applaus für diese Leistung!", $wschwimm1);
		else if ($wschwimm1sek>340 && $wschwimm1sek<=420) output("`n`n`^%s`@ Minuten unter Wasser! Du erntest großen Applaus für diese Leistung!", $wschwimm1);
		else if ($wschwimm1sek>280 && $wschwimm1sek<=340) output("`n`n`^%s`@ Minuten unter Wasser! Du erntest Applaus für diese Leistung!", $wschwimm1);
		else if ($wschwimm1sek>220 && $wschwimm1sek<=280) output("`n`n`^%s`@ Minuten unter Wasser. Du erntest ein wenig Applaus für diese Leistung!", $wschwimm1);
		else if ($wschwimm1sek>120 && $wschwimm1sek<=220) output("`n`n`^%s`@ Minuten unter Wasser. Du erntest vereinzelt Applaus für diese Leistung!", $wschwimm1);
		else if ($wschwimm1sek>0 && $wschwimm1sek<=120) output("`n`nLäppische `^%s`@ Minuten unter Wasser. Dafür wirst Du hoffentlich keinen Applaus erwarten.", $wschwimm1);
		else if ($wschwimm1sek==0) output("`n`nDu hast sofort Atemnot bekommen und bist gleich wieder aufgetaucht. Man buht Dich aus!");
		
		$wschwimm2=16.8-$wschwimm0+$wschwimm1;
		$wschwimm0p=round((16.8 - $wschwimm0) * 37);
		$wschwimm1p=round($wschwimm1 * 55);
		if ($wschwimm0p > 500) $wschwimm0p=500;
		if ($wschwimm1p > 500) $wschwimm1p=500;
		$wschwimm2=$wschwimm0p + $wschwimm1p;
		set_module_pref("wschwimm2", $wschwimm2, "wettkampf");
		
		//Folgende Werte werden gespeichert, damit sich die Sortierung der Bestenlisten nicht ändert, wenn
		//jemand bspw. einen Level aufsteigt:
		set_module_pref("wschwimmlevel", $session[user][level], "wettkampf");
		set_module_pref("wschwimmdk", $session[user][dragonkills], "wettkampf");
		set_module_pref("wschwimmfw", $schwimmen, "wettkampf");
	
		if ($wschwimm2>$bestschwimm2){
			set_module_pref("bestschwimm2", $wschwimm2, "wettkampf");
			set_module_pref("bestschwimmlevel", $session[user][level], "wettkampf");
			set_module_pref("bestschwimmdk", $session[user][dragonkills], "wettkampf");
			set_module_pref("bestschwimmfw", $schwimmen, "wettkampf");
		}
	
		addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschwimmen&subop2=schwimmen");
	page_footer();
}
?>