<?php

function wettkampf_wreiten_wreiten1_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		output("`@`bBullenreiten`b`nAls Du den Bullen erblickst, bleibt Dir fast das Herz stehen. Hätte Dich denn nicht jemand warnen können, dass es bei den Menschen üblich ist, auch sieben Meter lange, männliche Landdrachen als Bullen zu bezeichnen?! Aber jetzt gibt es kein Zurück mehr ...");
	  
		$reiten=get_fertigkeit(reiten);
		$wreiten1sek=0;
		$wreiten1=0;	
		$wreiten0=get_module_pref("wreiten0", "wettkampf");
		$bestreiten1=get_module_pref("bestreiten1", "wettkampf");
		$bestreiten2=get_module_pref("bestreiten2", "wettkampf");
			
		//Proben
		$t1=probe($reiten, 30, 0.9, 99.1, true);
		$t2=probe($reiten, 20, 0.9, 99.1, true);
		$t3=probe($reiten, 10, 0.9, 99.1, true);
		$t4=probe($reiten, 0, 0.9, 99.1, true);
		$t5=probe($reiten, -10, 0.9, 99.1, true);
		$t6=probe($reiten, -20, 0.9, 99.1, true);
		$t7=probe($reiten, -35, 0.9, 99.1, true);
		$t8=probe($reiten, -50, 0.9, 99.1, true);
		$t9=probe($reiten, -70, 0.9, 99.1, true);
			
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
			if ($value[1] == "kritischer erfolg") $value[0]=135;
			else if ($value[1] == "kritischer misserfolg") $value[0]=0;
			$modvalue=$value[0];
			if ($modvalue > 0) $wreiten1sek+=$modvalue;
			else break;
		}	
	 	
		$wreiten1=round($wreiten1sek/240, 3);
		set_module_pref("wreiten1", $wreiten1, "wettkampf");
		if ($wreiten1>$bestreiten1) set_module_pref("bestreiten1", $wreiten1, "wettkampf");
			
		if ($wreiten1sek>480) output("`n`n`@Göttliche `^%s`@ Minuten auf diesem Ungetüm! Du erntest tosenden Applaus für diese Leistung! Man bittet Dich um Autogramme!", $wreiten1);
		else if ($wreiten1sek>390 && $wreiten1sek<=480) output("`n`n`@Unglaubliche `^%s`@ Minuten auf diesem Ungetüm! Du erntest tosenden Applaus für diese Leistung!", $wreiten1);
		else if ($wreiten1sek>320 && $wreiten1sek<=390) output("`n`n`^%s`@ Minuten auf diesem Ungetüm! Du erntest großen Applaus für diese Leistung!", $wreiten1);
		else if ($wreiten1sek>250 && $wreiten1sek<=320) output("`n`n`^%s`@ Minuten auf diesem Ungetüm! Du erntest Applaus für diese Leistung!", $wreiten1);
		else if ($wreiten1sek>180 && $wreiten1sek<=250) output("`n`n`^%s`@ Minuten auf diesem Ungetüm. Du erntest ein wenig Applaus für diese Leistung!", $wreiten1);
		else if ($wreiten1sek>120 && $wreiten1sek<=180) output("`n`n`^%s`@ Minuten auf diesem Ungetüm. Du erntest vereinzelt Applaus für diese Leistung!", $wreiten1);
		else if ($wreiten1sek>0 && $wreiten1sek<=120) output("`n`nLäppische `^%s`@ Minuten auf diesem Ungetüm. Dafür wirst Du hoffentlich keinen Applaus erwarten.", $wreiten1);
		else if ($wreiten1sek==0) output("`n`n`\$Warst Du überhaupt aufgestiegen? Jedenfalls rennst Du jetzt um Dein Leben! Für diese lächerliche Vorstellung buht man Dich aus.`@");
			
		$wreiten0p=round((18-$wreiten0) * 45);
		$wreiten1p=round($wreiten1 * 270);
		if ($wreiten0p > 500) $wreiten0p=500;
		if ($wreiten1p > 500) $wreiten1p=500;
		$wreiten2=$wreiten0p + $wreiten1p;
		set_module_pref("wreiten2", $wreiten2, "wettkampf");
		
		//Folgende Werte werden gespeichert, damit sich die Sortierung der Bestenlisten nicht ändert, wenn
		//jemand bspw. einen Level aufsteigt:
		set_module_pref("wreitenlevel", $session[user][level], "wettkampf");
		set_module_pref("wreitendk", $session[user][dragonkills], "wettkampf");
		set_module_pref("wreitenfw", $reiten, "wettkampf");
	
		if ($wreiten2>$bestreiten2){
			set_module_pref("bestreiten2", $wreiten2, "wettkampf");
			set_module_pref("bestreitenlevel", $session[user][level], "wettkampf");
			set_module_pref("bestreitendk", $session[user][dragonkills], "wettkampf");
			set_module_pref("bestreitenfw", $reiten, "wettkampf");
		}			
		addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wreiten&subop2=reiten");
		page_footer();
}
?>