<?php

function wettkampf_wreiten_wreiten0_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		if ($session[user][gold]<$session[user][level]*$teilnahme){
			output("`@Hannes VI. runzelt die Stirn und scheint auf irgendetwas zu warten ... es könnte Geld sein.");
			addnav("Zurück","runmodule.php?module=wettkampf&op1=aufruf&subop1=wreiten&subop2=reiten");
		}else {
		output("`@`bWettreiten`b`nHannes VI. gibt das Startsignal und stellt ein kleines Gerät an, das er als 'Chronometer' bezeichnet und mit dem man sehr genau die Zeit nehmen kann. Da ist bestimmt Magie mit im Spiel.`n`n Aber das zählt jetzt nicht. Du siehst nur noch die Bahn vor Dir und denkst immer wieder an seine Worte zurück: `#'Reite wie Odin auf Sleipnir, dem achtbeinigen Ross, als wäre Dir der Fenriswolf dicht auf den Fersen!'`@");
		
		$reiten=get_fertigkeit(reiten);
		$bestreiten0=get_module_pref("bestreiten0", "wettkampf");
		$wreiten0sek=0;
		$session[user][gold]-=$session[user][level]*$teilnahme;
			 
			//Proben
			$t1=probe($reiten, 5, 0.9, 99.1, true);
			$t2=probe($reiten, 0, 0.9, 99.1, true);
			$t3=probe($reiten, -5, 0.9, 99.1, true);
			$t4=probe($reiten, 5, 0.9, 99.1, true);
			$t5=probe($reiten, 0, 0.9, 99.1, true);
			$t6=probe($reiten, -5, 0.9, 99.1, true);
			
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
	
			//Keine Runde kann in weniger als 85 Sekunden geschafft werden	
			while (list($key, $value) = each ($t)) {
				if ($value[1] == "kritischer erfolg") $value[0]=115;
				else if ($value[1] == "kritischer misserfolg") $value[0]=-150;
				$modvalue=e_rand(200,210)-$value[0];
				output ("`n`n`@Deine Zeit für die `^%s`@ Runde: `^%s`@ Sekunden.", ($key==6?"letzte":"$key."), $modvalue);
				$wreiten0sek+=$modvalue;
			}	
			
			$wreiten0=round($wreiten0sek/120, 3);
			set_module_pref("wreiten0", $wreiten0, "wettkampf");
			if ($wreiten0<$bestreiten0 || $bestreiten0==0) set_module_pref("bestreiten0", $wreiten0, "wettkampf");
										
			if ($wreiten0sek<700) output("`n`nGöttliche `^%s`@ Minuten! Du erntest tosenden Applaus für diese Leistung! Man bittet Dich um Autogramme!", $wreiten0);
			else if ($wreiten0sek<870 && $wreiten0sek>=700) output("`n`nRekordverdächtige `^%s`@ Minuten! Du erntest tosenden Applaus für diese Leistung!", $wreiten0);
			else if ($wreiten0sek<950 && $wreiten0sek>=870) output("`n`nNur `^%s`@ Minuten! Du erntest großen Applaus für diese Leistung!", $wreiten0);
			else if ($wreiten0sek<1050 && $wreiten0sek>=950) output("`n`nNur `^%s`@ Minuten! Du erntest Applaus für diese Leistung!", $wreiten0);
			else if ($wreiten0sek<1150 && $wreiten0sek>=1050) output("`n`n`^%s`@ Minuten. Du erntest ein wenig Applaus für diese Leistung!", $wreiten0);
			else if ($wreiten0sek<1300 && $wreiten0sek>=1150) output("`n`n`^%s`@ Minuten. Du erntest vereinzelt Applaus für diese Leistung!", $wreiten0);
			else if ($wreiten0sek<1500 && $wreiten0sek>=1300) output("`n`nDu hast insgesamt `^%s`@ Minuten gebraucht ...", $wreiten0);
			else if ($wreiten0sek>1500)output("`n`nSatte `^%s`@ Minuten! Der Fenriswolf? Ein altersschwacher Bernhardiner würde Dich noch überrunden! Du wirst völlig zurecht ausgebuht!", $wreiten0);
			
		addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wreiten&subop2=wreiten1");
		}
	page_footer();
}
?>