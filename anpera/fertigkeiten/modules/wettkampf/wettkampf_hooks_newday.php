<?php

	global $session;
			
			$steigerung=get_module_setting("steigerung", "fertigkeiten");
			set_module_pref("usersteigerung", $steigerung, "fertigkeiten");
			
			$g1=get_module_setting("bgegenstand1", "wettkampf");
			$g2=get_module_setting("bgegenstand2", "wettkampf");
			$g3=get_module_setting("bgegenstand3", "wettkampf");
			$g4=get_module_setting("bgegenstand4", "wettkampf");
			$g5=get_module_setting("bgegenstand5", "wettkampf");
			$g6=get_module_setting("bgegenstand6", "wettkampf");
			$g7=get_module_setting("bgegenstand7", "wettkampf");
			$g8=get_module_setting("bgegenstand8", "wettkampf");
			
			$g1name=get_module_setting("gegenstand1", "wettkampf");
			$g2name=get_module_setting("gegenstand2", "wettkampf");
			$g3name=get_module_setting("gegenstand3", "wettkampf");
			$g6name=get_module_setting("gegenstand6", "wettkampf");
			$g7name=get_module_setting("gegenstand7", "wettkampf");
			$g8name=get_module_setting("gegenstand8", "wettkampf");
			
			$id=$session[user][acctid];
		
			if ($g1==$id || $g2==$id || $g3==$id || $g4==$id || $g5==$id || $g6==$id || $g7==$id || $g8==$id) output("`@`n`bDein gutes Abschneiden beim letzten Fest der V�lker hat folgende Auswirkungen:`b");
			
			if ($g1==$id){
				output("`@`nDurch %s erh�ltst Du `^1`@ Waldkampf zus�tzlich!", $g1name);
				$session['user']['turns']+=1;
			}
			if ($g2==$id){
				output("`@`nDurch %s erh�ltst Du `^10`@ Gefallen bei `\$Ramius`@!", $g2name);
				$session['user']['deathpower']+=10;
			}
			if ($g3==$id){
				$expbonus=round($session['user']['experience']*0.01);
				if ($expbonus==0) $expbonus=5;
				output("`@`nDurch %s erh�ht sich Deine Erfahrung um `^%s`@ Punkte!", $g3name, $expbonus);
				$session['user']['experience']+=$expbonus;
			}
			if ($g4==$id){
				$beutel=e_rand(1,10);
		
				if ($beutel==1||$beutel==2){
					output("`@`nDer sprechende Beutel begr��t Dich heute mit einem lauten `#'Auf in den Tag!'`@ Derart angespornt erh�ltst Du einen zus�tzlichen Waldkampf.");
					$session['user']['turns']+=1;
				}else if ($beutel==3){
					output("`@`nDer sprechende Beutel verbreitet heute ungeheuer schlechte Laune, weil er schlecht geschlafen hat. Derart genervt verlierst Du einen Waldkampf.");
					$session['user']['turns']-=1;
				}else if ($beutel==4||$beutel==5){
					output("`@`nDer sprechende Beutel ist heute besonders gut gelaunt und schmeichelt Dir so sehr, dass Du Dich tats�chlich sch�ner f�hlst - und sch�ner wirst!");
					$session['user']['charm']+=1;
				}else if ($beutel==6){
					output("`@`nDer sprechende Beutel ist heute besonders schlecht gelaunt und macht st�ndig makabere Witze �ber Dein Aussehen, so dass Du Dich tats�chlich h�sslicher f�hlst - und h�sslicher wirst!");
					$session['user']['charm']-=1;
				}else if ($beutel==7){
					$zufall=e_rand(20,80);
					$gold=$session[user][level]*$zufall;
					$session['user']['gold']+=$gold;
					output("`@`nDer sprechende Beutel erwacht heute mit gro�er �belkeit. Erst als er sich �bergeben hat, geht es ihm besser. Du z�hlst sein Erbrochenes: `^%s`@ Goldst�cke!", $gold);
				}else if ($beutel==8){
					output("`@`nDer sprechende Beutel hat sich heute morgen nicht ge�ffnet. Er spricht nicht! Seltsam ... Du machst Dir Sorgen um ihn.");
				}else if ($beutel==9||$beutel==10){
					$zufall=e_rand(1,5);
					if ($zufall==1)$text=translate_inline("'Als junger Beutel habe ich immer davon getr�umt, jemandem wie Dir zu geh�ren. Du kannst Dir nicht vorstellen, wie gl�cklich ich bin.'");
					else if ($zufall==2)$text=translate_inline("'Vier oder vielleicht f�nf Besitzer ist es her, da war ich im Krieg. Schau mal genau hin, dann kannst Du meine Narben sehen - von innen vern�ht, versteht sich.'");
					else if ($zufall==3)$text=translate_inline("'Es gab da mal einen Jutesack, in den ich mich verliebt hatte, das muss man sich mal vorstellen ... Aber ich war jung und unbedarft, hach, damals ...'");
					else if ($zufall==4)$text=translate_inline("'Mein vorvorvorvorvorvorvorletzter Besitzer, ein kleiner Strauchdieb, hat immer Dietriche in mir aufbewahrt. Das war kein Spa� - die Dinger pieksen wie die H�lle! Aber Goldst�cke sind auch nicht das Wahre, viel zu schwer ...'");
					else if ($zufall==5)$text=translate_inline("'Vor Jahren mussten sie mir die Kordel herausziehen, weil sie verrottet war ... ich lag vier Tage lang v�llig erledigt auf einem Tisch, bis sie eine neue f�r mich gefunden hatten.'");
					
					output("`@`nDer sprechende Beutel erz�hlt Dir ein wenig aus seiner Lebensgeschichte: `#%s`@", $text);
				}
			}
			if ($g5==$id){
				output("`@`nDu schaust Dir den seltsamen Schl�ssel an und liest die Eingravierung: `#'`iNur der Aufmerksame findet zu sich selbst zur�ck`i'`@. Was das wohl zu bedeuten hat?");
			}
			if ($g6==$id){
				$specialty_file_name=get_module_setting("special_file_gegenstand6", "wettkampf");
				$specialty_name=get_module_setting("special_gegenstand6", "wettkampf");
				$menge=e_rand(1,2);
				$text=translate_inline("Anwendung");
				if ($menge==2)$text=translate_inline("Anwendungen");
				$uses=get_module_pref("uses", $specialty_file_name);
				$usesnew=$uses+$menge;
				set_module_pref("uses", $usesnew, $specialty_file_name);
				output("`@`nDurch %s erh�ltst Du heute `^%s`@ %s in der F�higkeit `^%s`@ zus�tzlich!", $g6name, $menge, $text, $specialty_name);
			}
			if ($g7==$id){
				$specialty_file_name=get_module_setting("special_file_gegenstand7", "wettkampf");
				$specialty_name=get_module_setting("special_gegenstand7", "wettkampf");
				$menge=e_rand(1,2);
				$text=translate_inline("Anwendung");
				if ($menge==2)$text=translate_inline("Anwendungen");
				$uses=get_module_pref("uses", $specialty_file_name);
				$usesnew=$uses+$menge;
				set_module_pref("uses", $usesnew, $specialty_file_name);
				output("`@`nDurch %s erh�ltst Du heute `^%s`@ %s in der F�higkeit `^%s`@ zus�tzlich!", $g7name, $menge, $text, $specialty_name);
			}
			if ($g8==$id){
				$specialty_file_name=get_module_setting("special_file_gegenstand8", "wettkampf");
				$specialty_name=get_module_setting("special_gegenstand8", "wettkampf");
				$menge=e_rand(1,2);
				$text=translate_inline("Anwendung");
				if ($menge==2)$text=translate_inline("Anwendungen");
				$uses=get_module_pref("uses", $specialty_file_name);
				$usesnew=$uses+$menge;
				set_module_pref("uses", $usesnew, $specialty_file_name);
				output("`@`nDurch %s erh�ltst Du heute `^%s`@ %s in der F�higkeit `^%s`@ zus�tzlich!", $g8name, $menge, $text, $specialty_name);
			}
	return $args;
?>
