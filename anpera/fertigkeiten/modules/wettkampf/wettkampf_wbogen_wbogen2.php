<?php

function wettkampf_wbogen_wbogen2_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
	 	output("`@`bSchnellschießen`b`nEdranel gibt Dir einen Köcher mit zehn Pfeilen und beginnt dann zu zählen: `#'3, 2, 1 ... Los!' `@Nun bleiben Dir genau acht Sekunden Zeit!`@`n`n");
	
		$bogen=get_fertigkeit(bogen);	
		$wbogen2=0;	
		
		//$mod ist spezifisch für diesen Wettbewerb, um eine gute Auslastung bis 600 Punkte zu erreichen
		$mod=1.8;
		
		//Proben	
		$t1=probe($bogen, 10, 0.9, 99.1, true);
		$t2=probe($bogen, 7, 0.9, 99.1, true);
		$t3=probe($bogen, 5, 0.9, 99.1, true);
		$t4=probe($bogen, 3, 0.9, 99.1, true);
		$t5=probe($bogen, 0, 0.9, 99.1, true);
		$t6=probe($bogen, 0, 0.9, 99.1, true);
		$t7=probe($bogen, -3, 0.9, 99.1, true);
		$t8=probe($bogen, -5, 0.9, 99.1, true);
		$t9=probe($bogen, -7, 0.9, 99.1, true);
		$t10=probe($bogen, -10, 0.9, 99.1, true);
			
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
			"10" => array(
					$t10[wert],
					$t10[ergebnis]),
		);
			
		while (list($key, $value) = each ($t)) {
			if ($value[1] == "kritischer erfolg") $value[0]=100;
			else if ($value[1] == "kritischer misserfolg") $value[0]=0;
			else if ($value[0] < 0) $value[0]=0;
			$modvalue=floor($value[0]*$mod);
			if ($modvalue > 100) $modvalue=100;
			output ("`@Dein `^%s.`@ Schuss: %s`@`n`n", ($key), ($modvalue==0?"`\$Daneben`@.":($modvalue==100?"`bVolltreffer!`b":"`^$modvalue`@.")));
			$wbogen2+=$modvalue;
		}	
		
		//Gesamtergebnis
		if ($wbogen2>0){
			output("`nDein Gesamtergebnis: `^%s`@ Punkte!", $wbogen2);
			if ($wbogen2>=900) output("`n`nDu erntest tosenden Applaus für diese Leistung! Man bittet Dich um Autogramme!");
			else if ($wbogen2>=750 && $wbogen2<900) output("`n`nDu erntest tosenden Applaus für diese Leistung!");
			else if ($wbogen2>=600 && $wbogen2<750) output("`n`nDu erntest großen Applaus für diese Leistung!");
			else if ($wbogen2>=450 && $wbogen2<600) output("`n`nDu erntest Applaus für diese Leistung!");
			else if ($wbogen2>=250 && $wbogen2<450) output("`n`nDu erntest ein wenig Applaus für diese Leistung!");
			else if ($wbogen2>=50 && $wbogen2<250) output("`n`nDu erntest vereinzelt Applaus für diese Leistung!");
			else if ($wbogen2<20) output("`n`nDu hörst vereinzeltes Gelächter ...");
			set_module_pref("wbogen2", $wbogen2, "wettkampf");
			$wbogen2=get_module_pref("wbogen2", "wettkampf");
			$bestbogen2=get_module_pref("bestbogen2", "wettkampf");
			if ($wbogen2>$bestbogen2) set_module_pref("bestbogen2", $wbogen2, "wettkampf"); 
		}else{
			output("`n`\$Du hast leider kein einziges Mal getroffen ...");
			output("`n`nEinige Leute buhen Dich dafür aus!");
			set_module_pref("wbogen2", 0, "wettkampf");
		}
		
		$wbogen0=get_module_pref("wbogen0", "wettkampf");
		$wbogen1=get_module_pref("wbogen1", "wettkampf");
		$wbogen2=get_module_pref("wbogen2", "wettkampf");
		$wbogen3=$wbogen0+$wbogen1+$wbogen2;
		set_module_pref("wbogen3", $wbogen3, "wettkampf");
		$bestbogen3=get_module_pref("bestbogen3", "wettkampf");
		
		//Folgende Werte werden gespeichert, damit sich die Sortierung der Bestenlisten nicht ändert, wenn
		//jemand bspw. einen Level aufsteigt:
		set_module_pref("wbogenlevel", $session[user][level], "wettkampf");
		set_module_pref("wbogendk", $session[user][dragonkills], "wettkampf");
		set_module_pref("wbogenfw", $bogen, "wettkampf");
	
		if ($wbogen3>$bestbogen3){
			set_module_pref("bestbogen3", $wbogen3, "wettkampf");
			set_module_pref("bestbogenlevel", $session[user][level], "wettkampf");
			set_module_pref("bestbogendk", $session[user][dragonkills], "wettkampf");
			set_module_pref("bestbogenfw", $bogen, "wettkampf");
		}
	
	 	addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wbogen&subop2=bogen");
	page_footer();
}
?>