<?php

function pdvdiebstahl_hooks_dohook_pdvanfang_private($args=false){
	global $session;
		$dieb=get_module_setting("dieb");
		$immun=get_module_pref("diebstahlsimmun", "pdvdiebstahl");
		
		$zufall=e_rand(1,30);
		
		if ($zufall <= 3 && $session[user][name] == $dieb){
		 	$meldung=get_module_pref("meldung", "pdvdiebstahl");
			if ($meldung < 3){
			 	switch($meldung){
					case "0": $text=translate_inline("`%Mm ... `5".$dieb."`% schleicht hier ungewöhnlich aufmerksam umher ..."); break;										
					case "1": $text=translate_inline("`%Man könnte fast meinen, `5".$dieb."`% führt etwas im Schilde ..."); break;
					case "2": $text=translate_inline("`%Warum schaut `5".$dieb."`% eigentlich immer so genau hin, wenn jemand bezahlt?"); break;
				}
				system_commentary(wettkampf, $text, $schema=false);
				$meldung++;
				set_module_pref("meldung", $meldung, "pdvdiebstahl");
			}
		}
		
		//Diebe vergewissern sich erst, ob Ihr Opfer auch etwas dabei hat (Beim Zahlen beobachten etc.).
		//Der Fairness halber kann man nur einmal pro Tag einem Dieb begegnen werden, sonst würde das Überhand nehmen.
		
		if ($immun == 0){
			$diebid=get_module_setting("diebid");
			$bestohlen=get_module_pref("bestohlen");
			
			if ($dieb != "" && $session[user][gold] != 0 && $diebid != $session[user][acctid] && $bestohlen == 0){
					
			$probe=get_module_setting("probe");
			$gegenprobe=probe(50);
			$abgleich=$probe-$gegenprobe[wert];
			
			if ($abgleich < 0){
				$grundstrafe=get_module_setting("strafe");
				$erwischt=get_module_pref("erwischt","pdvdiebstahl", "$diebid");
				if ($erwischt>15) $erwischtwert=15;
				if ($erwischt > 1) $erwischtwertneu=$erwischtwert+1;
				else $erwischtwertneu=1;
				
				$strafe=$grundstrafe*$erwischtwertneu;
				$erwischtneu=$erwischt+1;
				set_module_pref("erwischt", $erwischtneu, "pdvdiebstahl", "$diebid");
				
				if ($erwischtneu == 1){
					$text1=translate_inline("Zum Glück erinnert sich niemand an Dich, weshalb Deine Strafe mild ausfällt.");
					$text2=translate_inline("wurde beim Taschendiebstahl erwischt!");
				}
				if ($erwischtneu > 1 && $erwischtneu <= 4){
					$text1=translate_inline("Das war nicht Dein erstes Mal, weshalb Du höher bestraft wirst und eine Belehrung erhältst.");
					$text2=translate_inline("wurde wieder beim Taschendiebstahl erwischt!");
				}
				if ($erwischtneu > 4 && $erwischtneu <= 6){
					$text1=translate_inline("Du giltst inzwischen als bekannter Kleinkrimineller, weswegen Du eine saftige Strafe erhältst und Dich in der näheren Zukunft etwas unauffälliger geben solltest.");
					$text2=translate_inline("wurde schon wieder beim Taschendiebstahl erwischt und erhielt dafür eine saftige Geldstrafe!");
				} 
				if ($erwischtneu > 6 && $erwischtneu <= 8){
					$text1=translate_inline("Inzwischen bist Du ein alter Bekannter bei der Stadtwache. Du kennst das ja, eine hohe Strafe zahlen und weiterklauen. Aber es wird immer schwieriger, weil sie ein Auge auf Dich geworfen haben.");
					$text2=translate_inline("wurde schon wieder beim Taschendiebstahl erwischt und erhielt dafür eine saftige Geldstrafe!");
				}
				if ($erwischtneu > 8 && $erwischtneu <= 10){
					$text1=translate_inline("Das war ja nicht anders zu erwarten - bei Deinem zweifelhaften Bekanntheitsgrad im Dorf! Hoffentlich kannst Du Dir die Strafe noch leisten.");
					$text2=translate_inline("wurde schon wieder beim Taschendiebstahl erwischt! Die Stadtwache hat eine öffentliche Warnung ausgesprochen!");
				}
				if ($erwischtneu > 10){
					$text1=translate_inline("Inzwischen meiden Dich die Leute, wenn sie Dir begegnen. So langsam solltest Du eine Weile Gras über Deine Diebstähle wachsen lassen. Man kennt Dein Gesicht zu gut.");
					$text2=translate_inline("wurde schon wieder beim Taschendiebstahl erwischt! Überall spricht man davon!");
				}
		
				//Mail an erwischten Dieb
				$mailmessage1 = array("`\$Du bist bei Deiner Diebestour über den Platz der Völker auf frischer Tat ertappt worden, als Du `^%s`\$´s Taschen erleichtern wolltest. %s`n`nDu musst `^%s`\$ Goldstücke Strafe zahlen - die Vollstreckung ist bereits über die Bank abgewickelt worden.", $session[user][name], $text1, $strafe);
				systemmail($diebid,array("`\$Du wurdest erwischt!"),$mailmessage1);
				addnews_for_user($diebid, "`\$%s`4 %s", $dieb, $text2, $diebid);
				
				$sql = "SELECT goldinbank FROM accounts WHERE acctid='$diebid'";
				$result = db_query($sql) or die(db_error(LINK));
				
				$g1 = db_fetch_assoc($result);
				$gminus=$g1[goldinbank]-$strafe;
				
				$sql = "UPDATE accounts SET goldinbank='$gminus' WHERE acctid='$diebid'";
				db_query($sql);
				
				//Mail an glückliches Opfer
				$mailmessage1 = array("`@Beim Schlendern über den Platz der Völker hast Du plötzlich eine Hand an Deinen Taschen gefühlt und reflexartig nach ihr gegriffen. Es war die Hand von `\$%s`@! Für diesen niederträchtigen Diebstahlversuch hat die Stadtwache eine Geldstrafe verhängt.", $dieb);
				systemmail($session[user][acctid],array("`\$Du hast einen Dieb gestellt!"),$mailmessage1);
				set_module_pref("bestohlen",1);
			}else{
				$menge=round($abgleich*(e_rand(10,20)/10)*$session[user][level]);
				
				//Maximum bei 2000 GS
				if ($menge>2000) $menge=2000;
				if ($abgleich==0)$menge=15;
				$uebrig=$session[user][gold]-$menge;
				if ($uebrig<0){
					$uebrig=0;
					$menge=$session[user][gold];
				}
			
				$session[user][gold]=$uebrig;
				$sql = "SELECT gold FROM accounts WHERE acctid='$diebid'";
				$result = db_query($sql) or die(db_error(LINK));
				
				$g1 = db_fetch_assoc($result);
				$gplus=$g1[gold]+$menge;
				
				$sql = "UPDATE accounts SET gold='$gplus' WHERE acctid='$diebid'";
				db_query($sql);
				
				//Mail an bestohlenes Opfer
				$mailmessage1 = array("`@Du bist beim Schlendern über den Platz der Völker bestohlen worden! Der Dieb erbeutete `\$%s Goldstücke`@ - nur leider hast Du keinen Anhaltspunkt, wer es gewesen sein könnte. Vielleicht solltest Du Dich mit anderen Dorfbewohnern darüber unterhalten, denn so mancher Dieb ist als solcher bekannt ...", $menge);
				systemmail($session[user][acctid],array("`\$Du wurdest bestohlen!"),$mailmessage1);
				
				//Mail an glücklichen Dieb
				$mailmessage1 = array("`@Brilliant, einfach brilliant wie Du `^%s`@ nachgeschlichen bist! Als Du %s Taschen leertest, gelang es Dir, `^%s Goldstücke`@ einzusacken.`n`nGlückwunsch und weiter so!", $session[user][name], ($session[user][sex]?"ihre":"seine"), $menge);
				systemmail($diebid,array("`@Du warst auf Deiner Diebestour erfolgreich!"),$mailmessage1);
				set_module_pref("bestohlen",1);
				
				$erwischt=get_module_pref("erwischt","pdvdiebstahl", "$diebid");
				$erwischtneu=$erwischt+1;
				set_module_pref("erwischt", $erwischtneu, "pdvdiebstahl", "$diebid");
			}
				set_module_setting("diebid", "");
				set_module_setting("dieb", "");
				set_module_pref("meldung", 0, "pdvdiebstahl", $diebid);
			}
		}
	return $args;
}
?>
