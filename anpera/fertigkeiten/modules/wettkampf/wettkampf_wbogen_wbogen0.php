<?php

function wettkampf_wbogen_wbogen0_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		if ($session[user][gold]<$session[user][level]*$teilnahme){
			output("`@Es gibt einige Dinge, die die Elfen bei den Zwergen gelernt haben, z.B. erst sicherzustellen, dass der Kunde zahlungskräftig ist. Und Du gehörst offenbar nicht dazu!");
			addnav("Zurück","runmodule.php?module=wettkampf&op1=aufruf&subop1=wbogen&subop2=bogen");
		}else {
			$bogen=get_fertigkeit(bogen);
			$reiten=get_fertigkeit(reiten);
			$session[user][gold]-=$session[user][level]*$teilnahme;
			$rbogen=floor($bogen*0.7+$reiten*0.3);
			output("`@`bReiterschießen`b`nDu steigst auf das Pferd, das Ghena Dir an die Hand gegeben hat und reitest auf die drei Zielscheiben zu, die so angeordnet sind, dass Du zweimal nach vorne, zweimal zur Seite und zweimal nach hinten schießen musst. Ein Volltreffer bringt 100 Punkte.`n`n");
	
			//Proben
			$wbogen0=0;	
			$t1=probe($rbogen, 7, 0.9, 99.1, true);
			$t2=probe($rbogen, 5, 0.9, 99.1, true);
			$t3=probe($rbogen, 0, 0.9, 99.1, true);
			$t4=probe($rbogen, 0, 0.9, 99.1, true);
			$t5=probe($rbogen, -5, 0.9, 99.1, true);
			$t6=probe($rbogen, -7, 0.9, 99.1, true);
	
			//$mod ist spezifisch für diesen Wettbewerb, um eine gute Auslastung bis 600 Punkte zu erreichen
			$mod=1.8;
		
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
			);
				
			while (list($key, $value) = each ($t)) {
				if ($value[1] == "kritischer erfolg") $value[0]=100;
				else if ($value[1] == "kritischer misserfolg") $value[0]=0;
				else if ($value[0] < 0) $value[0]=0;
				$modvalue=floor($value[0]*$mod);
				if ($modvalue > 100) $modvalue=100;
				output ("`@Dein `^%s.`@ Schuss: %s`@`n`n", ($key), ($modvalue==0?"`\$Daneben`@.":($modvalue==100?"`bVolltreffer!`b":($modvalue==1?"`^$modvalue`@ Punkt.":"`^$modvalue`@ Punkte."))));
				$wbogen0+=$modvalue;
			}	
			
			//Gesamtergebnis
			if ($wbogen0 == 0){
		 		output("`n`\$Du hast leider kein einziges Mal getroffen ...");
		 		output("`n`nEinige Leute buhen Dich dafür aus!");
		 		set_module_pref("wbogen0", 0, "wettkampf");
			}else{
				output("`n`@Dein Gesamtergebnis: `^%s`@ %s.", $wbogen0, ($wbogen0==1?"Punkt":"Punkte"));	
				if ($wbogen0>=550) output("`n`nDu erntest tosenden Applaus für diese Leistung! Man bittet Dich um Autogramme!");
				else if ($wbogen0>=450 && $wbogen0<550) output("`n`nDu erntest tosenden Applaus für diese Leistung!");
				else if ($wbogen0>=370 && $wbogen0<450) output("`n`nDu erntest großen Applaus für diese Leistung!");
				else if ($wbogen0>=300 && $wbogen0<370) output("`n`nDu erntest Applaus für diese Leistung!");
				else if ($wbogen0>=150 && $wbogen0<300) output("`n`nDu erntest ein wenig Applaus für diese Leistung!");
				else if ($wbogen0>=70 && $wbogen0<150) output("`n`nDu erntest vereinzelt Applaus für diese Leistung!");
				else if ($wbogen0<20) output("`n`nDu hörst vereinzeltes Gelächter ...");
				set_module_pref("wbogen0", $wbogen0, "wettkampf");
				$bestbogen0=get_module_pref("bestbogen0", "wettkampf");
				if ($wbogen0>$bestbogen0) set_module_pref("bestbogen0", $wbogen0, "wettkampf");
			}
			addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wbogen&subop2=wbogen1");
		}
	page_footer();
}
?>