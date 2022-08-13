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
		
			if ($g1==$id || $g2==$id || $g3==$id || $g4==$id || $g5==$id || $g6==$id || $g7==$id || $g8==$id) output("`@`n`bDein gutes Abschneiden beim letzten Fest der Völker hat folgende Auswirkungen:`b");
			
			if ($g1==$id){
				output("`@`nDurch %s erhältst Du `^1`@ Waldkampf zusätzlich!", $g1name);
				$session['user']['turns']+=1;
			}
			if ($g2==$id){
				output("`@`nDurch %s erhältst Du `^10`@ Gefallen bei `\$Ramius`@!", $g2name);
				$session['user']['deathpower']+=10;
			}
			if ($g3==$id){
				$expbonus=round($session['user']['experience']*0.01);
				if ($expbonus==0) $expbonus=5;
				output("`@`nDurch %s erhöht sich Deine Erfahrung um `^%s`@ Punkte!", $g3name, $expbonus);
				$session['user']['experience']+=$expbonus;
			}
			if ($g4==$id){
				$beutel=e_rand(1,10);
		
				if ($beutel==1||$beutel==2){
					output("`@`nDer sprechende Beutel begrüßt Dich heute mit einem lauten `#'Auf in den Tag!'`@ Derart angespornt erhältst Du einen zusätzlichen Waldkampf.");
					$session['user']['turns']+=1;
				}else if ($beutel==3){
					output("`@`nDer sprechende Beutel verbreitet heute ungeheuer schlechte Laune, weil er schlecht geschlafen hat. Derart genervt verlierst Du einen Waldkampf.");
					$session['user']['turns']-=1;
				}else if ($beutel==4||$beutel==5){
					output("`@`nDer sprechende Beutel ist heute besonders gut gelaunt und schmeichelt Dir so sehr, dass Du Dich tatsächlich schöner fühlst - und schöner wirst!");
					$session['user']['charm']+=1;
				}else if ($beutel==6){
					output("`@`nDer sprechende Beutel ist heute besonders schlecht gelaunt und macht ständig makabere Witze über Dein Aussehen, so dass Du Dich tatsächlich hässlicher fühlst - und hässlicher wirst!");
					$session['user']['charm']-=1;
				}else if ($beutel==7){
					$zufall=e_rand(20,80);
					$gold=$session[user][level]*$zufall;
					$session['user']['gold']+=$gold;
					output("`@`nDer sprechende Beutel erwacht heute mit großer Übelkeit. Erst als er sich übergeben hat, geht es ihm besser. Du zählst sein Erbrochenes: `^%s`@ Goldstücke!", $gold);
				}else if ($beutel==8){
					output("`@`nDer sprechende Beutel hat sich heute morgen nicht geöffnet. Er spricht nicht! Seltsam ... Du machst Dir Sorgen um ihn.");
				}else if ($beutel==9||$beutel==10){
					$zufall=e_rand(1,5);
					if ($zufall==1)$text=translate_inline("'Als junger Beutel habe ich immer davon geträumt, jemandem wie Dir zu gehören. Du kannst Dir nicht vorstellen, wie glücklich ich bin.'");
					else if ($zufall==2)$text=translate_inline("'Vier oder vielleicht fünf Besitzer ist es her, da war ich im Krieg. Schau mal genau hin, dann kannst Du meine Narben sehen - von innen vernäht, versteht sich.'");
					else if ($zufall==3)$text=translate_inline("'Es gab da mal einen Jutesack, in den ich mich verliebt hatte, das muss man sich mal vorstellen ... Aber ich war jung und unbedarft, hach, damals ...'");
					else if ($zufall==4)$text=translate_inline("'Mein vorvorvorvorvorvorvorletzter Besitzer, ein kleiner Strauchdieb, hat immer Dietriche in mir aufbewahrt. Das war kein Spaß - die Dinger pieksen wie die Hölle! Aber Goldstücke sind auch nicht das Wahre, viel zu schwer ...'");
					else if ($zufall==5)$text=translate_inline("'Vor Jahren mussten sie mir die Kordel herausziehen, weil sie verrottet war ... ich lag vier Tage lang völlig erledigt auf einem Tisch, bis sie eine neue für mich gefunden hatten.'");
					
					output("`@`nDer sprechende Beutel erzählt Dir ein wenig aus seiner Lebensgeschichte: `#%s`@", $text);
				}
			}
			if ($g5==$id){
				output("`@`nDu schaust Dir den seltsamen Schlüssel an und liest die Eingravierung: `#'`iNur der Aufmerksame findet zu sich selbst zurück`i'`@. Was das wohl zu bedeuten hat?");
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
				output("`@`nDurch %s erhältst Du heute `^%s`@ %s in der Fähigkeit `^%s`@ zusätzlich!", $g6name, $menge, $text, $specialty_name);
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
				output("`@`nDurch %s erhältst Du heute `^%s`@ %s in der Fähigkeit `^%s`@ zusätzlich!", $g7name, $menge, $text, $specialty_name);
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
				output("`@`nDurch %s erhältst Du heute `^%s`@ %s in der Fähigkeit `^%s`@ zusätzlich!", $g8name, $menge, $text, $specialty_name);
			}
	return $args;
?>
