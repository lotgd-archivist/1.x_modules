<?php

function wettkampf_wschwimmen_wschwimm0_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		if ($session[user][gold]<$session[user][level]*$teilnahme){
			output("`@Der Troll schaut Dich grimmig an. `#'Hier macht Ihr nur mit, wenn Ihr Euren Geldbeutel leert - und genug dabei herauskommt.'`@");
			addnav("Zurück","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschwimmen&subop2=schwimmen");
		}else {
	 		output("`@`bSchlammbahnenschwimmen`b`nDu wartest auf das Signal zum Start. Plötzlich stößt einer der Trolle einen markerschütternden Schrei aus. Jetzt geht's wohl los! Du springst in das schlammige Wasser, das mit jeder der etwa 100 Fuß langen Bahnen zäher zu werden scheint ...`n");
	 		$session[user][gold]-=$session[user][level]*$teilnahme;
	 		
			$bestschwimm0=get_module_pref("bestschwimm0", "wettkampf");
			$schwimmen=get_fertigkeit(schwimmen);	
			$wschwimm0=0;	
			
			//Proben
			//Jede Bahn wird anstrengender als die vorherige
			$t1=probe($schwimmen, 10, 0.9, 99.1, true);
			$t2=probe($schwimmen, 7, 0.9, 99.1, true);
			$t3=probe($schwimmen, 3, 0.9, 99.1, true);
			$t4=probe($schwimmen, 0, 0.9, 99.1, true);
				
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
			);
	
			//Keine Bahn kann in weniger als 25 Sekunden geschafft werden	
			while (list($key, $value) = each ($t)) {
				if ($value[1] == "kritischer erfolg") $value[0]=110;
				else if ($value[1] == "kritischer misserfolg") $value[0]=-150;
				$modvalue=135-$value[0];
				output ("`n`@Deine Zeit für die `^%s`@ Bahn: `^%s`@ Sekunden.`n", ($key==4?"letzte":"$key."), $modvalue);
				$wschwimm0sek+=$modvalue;
			}	
		
			$wschwimm0=round($wschwimm0sek / 60, 3);
			set_module_pref("wschwimm0", $wschwimm0, "wettkampf");
			if ($wschwimm0<$bestschwimm0) set_module_pref("bestschwimm0", $wschwimm0, "wettkampf");
			 	
			if ($wschwimm0sek<140) output("`n`nAbsolut alles überragende `^%s`@ Minuten! Du erntest tosenden Applaus für diese Leistung! Du wirst auf Händen getragen!", $wschwimm0);	
			else if ($wschwimm0sek<220 && $wschwimm0sek>=140) output("`n`nGöttliche `^%s`@ Minuten! Du erntest tosenden Applaus für diese Leistung! Man bittet Dich um Autogramme!", $wschwimm0);
			else if ($wschwimm0sek<320 && $wschwimm0sek>=220) output("`n`nRekordverdächtige `^%s`@ Minuten! Du erntest tosenden Applaus für diese Leistung!", $wschwimm0);
			else if ($wschwimm0sek<390 && $wschwimm0sek>=320) output("`n`nNur `^%s`@ Minuten! Du erntest großen Applaus für diese Leistung!", $wschwimm0);
			else if ($wschwimm0sek<440 && $wschwimm0sek>=390) output("`n`nNur `^%s`@ Minuten! Du erntest Applaus für diese Leistung!", $wschwimm0);
			else if ($wschwimm0sek<490 && $wschwimm0sek>=440) output("`n`n`^%s`@ Minuten. Du erntest ein wenig Applaus für diese Leistung!", $wschwimm0);
			else if ($wschwimm0sek<540 && $wschwimm0sek>=490) output("`n`n`^%s`@ Minuten. Du erntest vereinzelt Applaus für diese Leistung!", $wschwimm0);
			else if ($wschwimm0sek<=640 && $wschwimm0sek>=540) output("`n`nDu hast insgesamt `^%s`@ Minuten gebraucht ...", $wschwimm0);
			else if ($wschwimm0sek>640)output("`n`n`\$Satte `^%s`\$ Minuten! Du bist geschwommen wie ein Stein und wirst zurecht dafür ausgebuht!`@", $wschwimm0);
			
			addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschwimmen&subop2=wschwimm1");	
	}
	page_footer();
}
?>