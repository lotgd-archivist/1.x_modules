<?php

function wettkampf_wschleichen_schleichen_wettkampf_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		require_once("modules/wettkampf/wettkampf_wschleichen_lib.php");
		$subop = $_GET['subop'];
		$spieler = get_module_pref("schleichenortspieler", "wettkampf");
		$zeit = get_module_pref("schleichenzeit", "wettkampf");
		$irog = get_module_pref("schleichenortirog", "wettkampf");
		$tha = get_module_pref("schleichenorttha", "wettkampf"); 
		$arena = createarray(get_module_pref("schleichen_wk_data", "wettkampf"));
		$imversteck =get_module_pref("schleichenversteckt", "wettkampf");
		$erwischt = false; $versteck =""; $thatext=""; $irogtext=""; $wechseltext=""; $wechseltext2=""; $ende=false; $tipptha="";
		if ($subop == "verstecken") { 
			if ($imversteck){
				output("`@Du verbringst eine Weile in Deinem Versteck und hoffst, dass die Luft dadurch reiner wird ...");
			}else{
				$mod = $arena[$spieler]['verstecken'];
				$fw  = get_fertigkeit("schleichen"); // verstecken und schleichen Fertigkeit
				$versteckenprobe = probe($fw, $mod);
				$wert = $versteckenprobe['wert'];
				// output("DEBUG FW %s MOD %s PROBE %s",$fw,$mod,$wert);
				if ($wert>=0) { 
					if ($wert>=0 && $wert <=15){ output("`@Du hast ein brauchbares Versteck gefunden. Hoffentlich ist es gut genug ...");} 
						if ($wert>15 && $wert <=30){ output("`@Du hast ein gutes Versteck gefunden. Hoffentlich ist es gut genug ...");} 
						if ($wert>30 && $wert <=50){ output("`@Du hast ein sehr gutes Versteck gefunden. Hier wird man Dich kaum finden können ...");} 
						if ($wert>50){ output("`@Du hast ein absolut brilliantes Versteck gefunden. Hier wird Dich bestimmt niemand finden."); }
					set_module_pref("schleichenprobespieler", $wert, "wettkampf");
					set_module_pref("schleichenversteckt", 1, "wettkampf");
				}else{
					output("`@Du versuchst ein passendes Versteck zu finden, gerätst dabei aber so sehr in Hektik, dass es Dir nicht gelingt.");
					set_module_pref("schleichenprobespieler", -1, "wettkampf"); // im diesem Fall werden die Proben sowieso nicht verrechnet
					set_module_pref("schleichenversteckt", 0, "wettkampf"); 
				}
			}
			// Verstecken braucht feste Zeit von 1 Minute
			output("<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=weiter'>`n`nWeiter.</a>", true);
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=weiter");
			addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen_wettkampf&subop=weiter");
		} else {
			if ($subop=="bewegen" || $subop=="weiter") {
				// Daten aus der DB holen und abgleichen
				if ($subop=="bewegen") {
					// Versteckmodus aufheben
					$imversteck = 0;
					set_module_pref("schleichenversteckt", $imversteck, "wettkampf");
					// Spieler erreicht Zielgebiet
					$ziel = $_GET['ziel'];
					if (verbindung_existiert($spieler, $ziel, &$arena)) {
						$spieler = $ziel;
						set_module_pref("schleichenortspieler", $spieler, "wettkampf");
					} else { output("DEBUG: Fehlerhafte Raumverbindung/Navigation"); }
				}
				// Wächter bewegen sich jedes Mal (auch beim Versteckversuch)
				// output("DEBUG : THA war im Sektor %s IROG im Sektor %s`n",$tha,$irog);
				$tha=bewege_wache($tha, &$arena);
				$irog=bewege_wache($irog, &$arena);
				set_module_pref("schleichenortirog",$irog, "wettkampf");
				set_module_pref("schleichenorttha",$tha, "wettkampf"); 
				// output("DEBUG : THA jetzt im Sektor %s IROG im Sektor %s`n",$tha,$irog);
				// Schleichenprobe zur Bestimmung der Geschwindigkeit des Spielers beim Herumschleichen (Zeit)
				// und wie gut er sich vor den Wächtern verbergen kann (wenn er versteckt ist wird
				if (!$imversteck) {
					$mod = $arena[$spieler]['schleichen'];
					$fw  = get_fertigkeit("schleichen"); 
					$probe = probe($fw, $mod);
					$schleichenwert = $probe['wert'];
					// gezählt werden die Sekunden (minimal 60 Sekunden pro Sektor)
					$zeit += (160-$schleichenwert);
				}else{
				// stattdessen die hinterlegte Probe "schleichenprobespieler" aus den prefs verwendet
				// und die Zeit um eine Minute erhöht. ( 60 Sekunden ) 
					$zeit += 60;
					$schleichenwert = get_module_pref("schleichenprobespieler", "wettkampf");
				}
				set_module_pref("schleichenzeit", $zeit, "wettkampf");
				
				// Wenn Tha im Sektor ist, überprüfen, ob er den Spieler erwischt hat.
				// Falls er im angrenzenden Gebiet ist, bekommt er einen Probenwurf mit modifikator $arena[$spieler]['entdecken']
				// der, falls er gelingt, ihm erlaubt, sofort zu dem Spielersektor zu gehen und dort erneut zu suchen
				If ($spieler==$tha || verbindung_existiert($spieler, $tha, &$arena) ) {
					if (verbindung_existiert($spieler, $tha, &$arena)) {
						// Fall 1 : im angrenzenden Sektor
						$fwtha = get_module_setting("fwtha", "wettkampf"); $mod = $arena[$spieler]['entdecken']; $probe = probe($fwtha, $mod);
						$thawert = $probe['wert']; $diff = $schleichenwert - $thawert;
						// output("DEBUG Tha probe %s Schleich/Versteckwert %s Dif %s`n`n", $thawert, $schleichenwert, $diff);
						// Konnte Spieler nicht sehen über die Grenze hinweg
						if ($thawert<0 || $diff>=0) {
							$thatext = translate_inline("`6Dir wird mulmig zumute, als Du Tha erblickst, der gerade ".$arena[$tha]['wache']." nach Dir sucht. Er scheint gerade nach Dir zu fragen ... Schnell weiter, noch hat er Dich nicht gesehen!`n`n");
						} else {
							// Hat Spieler entdeckt und wechselt den Sektor
							$wechseltext = "Du hörst leise Schritte, kannst aber ihre genaue Richtung nicht ausmachen! Das kann nur Tha sein, der sich eben noch ".$arena[$tha]['wache']." aufgehalten hatte. ";
							$wechseltext2 = "seinem Instinkt folgend zurückgekehrt ist und ";
							$tha = $spieler;
							set_module_pref("schleichenorttha",$tha, "wettkampf");
						}
					}
					// Tha im gleichen Sektor
					if ($spieler==$tha) {
						$fwtha = get_module_setting("fwtha", "wettkampf"); $mod = 0; $probe = probe($fwtha, $mod);
						$thawert = $probe['wert'];
						// Vergleich : 0 = identisch, <0 Tha Sieger, >0 Spieler Sieger
						$diff = $schleichenwert - $thawert;
						// output("DEBUG Tha probe %s Schleich/Versteckwert %s Dif %s`n`n", $thawert, $schleichenwert, $diff);
						// beide Proben mies (<0) oder Spieler besser bzw gleichgut - NICHT ENDECKT
						if ($thawert<0 || $diff>=0) {
							$thatext=translate_inline("`6Du bekommst Panik, als Du Tha erblickst, der ".$wechseltext2." hier gerade mit dem wachen Blick eines Jägers nach Dir sucht. Er sieht direkt in Deine Richtung! Jetzt bloß die Luft anhalten ...`n`n`@"); 
						// Tha hat gut gesucht und war überlegen
						} elseif ($diff<0) {
							$erwischt=true;
							output("`6%sPlötzlich reißt Dich etwas von hinten zu Boden. Tha sitzt auf Deinem Rücken und bricht Dir fast das Genick, als er Deinen Kopf mit einem Krachen herumdreht. Wie gelähmt starrst Du auf seine blitzenden Reißzähne ...`n`n",$wechseltext);
						}
					}
				}
				// dito mit Irog
				If ($erwischt==false) {
					If ($spieler==$irog || verbindung_existiert($spieler,$irog, &$arena) ) {
						if (verbindung_existiert($spieler, $irog, &$arena)) {
							$fwtha = get_module_setting("fwirog", "wettkampf"); $mod = $arena[$spieler]['entdecken']; $probe = probe($fwirog, $mod);
							$irogwert = $probe['wert']; $diff = $schleichenwert - $irogwert;
							// output("DEBUG irog probe %s Schleich/Versteckwert %s`n`n", $irogwert, $schleichenwert);
							if ($irogwert<0 || $diff>=0) {
								$irogtext = translate_inline("`3Du siehst, dass Irog gerade ".$arena[$irog]['wache']." `3nach Dir sucht. Er wirkt ein wenig lustlos, jedenfalls schaut er nicht in Deine Richtung.`n`n");
							} else {
								$wechseltext = "Du hörst wie jemand hastig in deine Richtung rennt. Es kann nur Irog sein, der eben noch ".$arena[$irog]['wache']." war. ";
								$wechseltext2 = "ist zurückgekehrt, nachdem er etwas Merkwürdiges gehört hatte und";
								$irog = $spieler;
								set_module_pref("schleichenortirog",$irog, "wettkampf");
							}
						}
						if ($spieler==$irog) {
							$fwirog = get_module_setting("fwirog", "wettkampf"); $mod = 0; $probe = probe($fwirog, $mod);
							$irogwert = $probe['wert']; $diff = $schleichenwert - $irogwert;
							// output("DEBUG Irog probe %s Schleich/Versteckwert %s`dif %sn`n", $irogwert, $schleichenwert,$diff);
							if ($thawert<0 || $diff>=0) {
								$irogtext=translate_inline("`3Irog ".$wechseltext2." sucht hier gerade nach Dir. Für einen Moment glaubst Du, er schaue Dir direkt in die Augen, aber dann wendet er sich wieder ab ...`@`n`n"); 
							} else { 
								$erwischt=true;
								if ($diff<0) { $erwischt=true; output("`3%sPlötzlich spürst Du eine eiskalte Hand auf Deiner Schulter. Als Du Dich umdrehst, starrst Du in sein teuflisch grinsendes Gesicht.`n`n",$wechseltext); }
							}
						}
					}
				}
				// *** Ende ** ?
				$gegenstand1 = get_module_pref("schleichengegenstand1", "wettkampf");
				$gegenstand2 = get_module_pref("schleichengegenstand2", "wettkampf");
				if ($spieler==2 && $gegenstand1==1 && $gegenstand2==1 && $erwischt==false){
					$ende = true;
				}
				// Falls erwischt wird sofort hier beendet, und die restliche Anzeige ignoriert.
				If ($erwischt || $ende) {
					If ($erwischt) {
						output("<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=ende&result=tot'>`nWeiter.</a>", true);
						addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=ende&result=tot");
						addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=ende&result=tot");
					}
					If ($ende) {
						output("`@Herzlichen Glückwunsch, Du hast beide Gegenstände hergebracht, ohne entdeckt zu werden!`n`n");
						output("<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=ende&result=gewonnen'>Weiter.</a>", true);
						addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=ende&result=gewonnen");
						addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=ende&result=gewonnen");
					}
				} else {
					switch($spieler) {
						case 1 : 
							if ($imversteck) $versteck=translate_inline(", zumal es Dir gelungen ist, Dich zwischen zwei Ständen zu verstecken");
							output("`@Du befindest Dich im oberen Marktbereich, wo die Wanderhändler ihre Waren feilbieten. Im ".
							       "dichten und lauten Gedränge bist Du gut geschützt%s.`n`n",$versteck);
							       
						break;
						case 2 : 
							if ($imversteck) $versteck=translate_inline(" in Deinem Versteck hinter einigen Kisten");
							output("`@Du befindest Dich%s am großen Eingangstor, durch das die Besuchermassen hereinströmen. ".
							"Es herrscht ein reges Treiben, in dessen Schutz man Dich nur schwer entdecken kann.`n`n",$versteck);
						break;
						case 3 : 
							if ($imversteck) $versteck=translate_inline(" und lugst aus Deinem Versteck in einem leeren Salzfaß.");
							output("`@Du befindest Dich am Küchenhaus der Echsen%s. Es riecht so gut in dieser Gegend, dass ".
							       "Du am liebsten sofort in das Haus hineingehen würdest ...`n`n", $versteck);
						break;
						case 4 : 
							$tipp=e_rand(1,3);
							if ($tipp==1) {
								if ($tha==2 || $tha==3 || $tha==6 || $tha==8 || $tha==9) {
									$tipptha=translate_inline("`6Du bekommst von einem kleinen Mädchen den Hinweis, dass sich Tha gerade ".$arena[$tha]['wache']." aufhält.`n`n`@");
								} 
							}
							if ($imversteck) $versteck=translate_inline("Dennoch hast Du Dich verstecken können - in einem Stapel großer Teppiche.");
							output("`@Du befindest Dich im unteren Marktbereich, wo die Stände etwas geordneter stehen, so dass Du ".
							       "leichter auszumachen bist als im oberen Bereich. Dennoch kannst Du hier recht gut umherschleichen. %s`n`n", $versteck);break;
						case 5 :
							output("`@Du befindest Dich in der Mitte des 'Platzes der Völker', an der überlebensgroßen Statue der Vermittlerin. Einige ".
							       "Leute legen gerade Blumen nieder. Außer ihnen wagt es niemand, den gepflasterten Weg zu verlassen, so dass ein ".
							       "ständiges Gedränge herrscht, das Dich unweigerlich in eine Richtung mitzieht - und das ist gut so, denn hier ".
							       "befindest Du Dich geradezu auf dem Präsentierteller!`n`n"); break;
						case 6 :
							$tipp=e_rand(1,3);
							if ($tipp==1) {
								if ($tha==1 || $tha==2 || $tha==4 || $tha==7 || $tha==8) {
									$tipptha=translate_inline("`6Du schließt aus einem Gespräch, das Du zufällig mitanhörst, dass sich Tha gerade ".$arena[$tha]['wache']." aufhalten muss.`@`n`n");
									}
							}
							if ($imversteck) {$versteck=translate_inline("gut versteckt `i`bim`b`i"); }else{ $versteck=translate_inline("am"); } 
							output("`@Du befindest Dich %s Schlammtümpel, an dessen Rand zwei Trolle stehen und mit Trommelschlägen ".
							       "die Zeit nehmen. Ein kleiner Pfad führt den Hügel zum Tiefenschacht hinauf, aber dorthin willst Du nicht.`n`n", $versteck);
						break;
						case 7 :
							if ($imversteck) $versteck=translate_inline("Irgendwie ist es Dir gelungen, hinter den Holzzaun zu gelangen, wo Dich hoffentlich niemand finden wird.");
							$gegenstand1 = get_module_pref("schleichengegenstand1", "wettkampf");
							$gegenstand2 = get_module_pref("schleichengegenstand2", "wettkampf");
							output("`@Du befindest Dich an der Schießanlage der Elfen, die mit einem Holzzaun abgesperrt ist. ".
							       "Aus Angst, ein Pfeil könnte sich dennoch verirren, halten sich hier nur wenige Leute auf, und ".
							       "sie alle haben es eilig. Kein sicherer Ort für jemanden wie Dich ... %s`n`n",$versteck);
							if ($gegenstand1==0){
								if ($gegenstand2==0) { $text=translate_inline("Jetzt musst Du nur noch den anderen finden und dann zum Eingangstor zurückkehren.");
								}else{ $text=translate_inline("Jetzt musst Du nur noch zum Eingangstor zurückkehren."); }
								output("`2An der vereinbarten Stelle findest Du einen der beiden Gegenstände: eine kleine Brosche. `n`n%s`n`n`@", $text);
								set_module_pref("schleichengegenstand1", 1, "wettkampf");
							}
						break;
						case 8 :  
							if ($imversteck) {$versteck=translate_inline("gut versteckt `i`bhinter`b`i"); }else{$versteck=translate_inline("an"); }
							$mod = $arena[8]['schleichen'];
							if ($mod==10) $bühne=translate_inline("Sie wird gerade für den nächsten Auftritt umgebaut, weshalb einige Zuschauer bereits gegangen sind. Dennoch fühlst Du Dich sicher in ihrem Schutz, wenngleich nicht sehr.");
							if ($mod>10 && $mod<=15) $bühne=translate_inline("Die Menge ist nur mäßig begeistert von dem derzeitigen Interpreten, weshalb sie relativ ruhig bleibt. Dennoch fühlst Du Dich recht sicher in ihrem Schutz.");
							if ($mod>15) $bühne=translate_inline("Die Menge ist angesichts des derzeitigen Interpreten außer sich! Alle gröhlen, hüpfen und tanzen! Wunderbar, in einer solchen Umgebung kann man gut umherschleichen.");  
							output("`@Du befindest Dich %s der Bühne für den Gesangs- und Musikwettbewerb der Vanthira. %s`n`n", $versteck, $bühne);
						break;
						case 9 : 
							if ($imversteck) $versteck=translate_inline("In Deinem Versteck zwischen drei Pferden fühlst Du Dich unbehaglich. Hoffentlich bleiben sie ruhig ...");
							$gegenstand1=get_module_pref("schleichengegenstand1", "wettkampf");
							$gegenstand2=get_module_pref("schleichengegenstand2", "wettkampf");
							output("`@Du befindest Dich an der abgesteckten Wiese, auf der die Menschen den Reitwettbewerb abhalten. Wegen der wertvollen Pferde gibt es hier viele Wachen, die sofort mißtrauisch werden, wenn sich jemand auffällig benimmt, weshalb Du hier kaum eine Möglichkeit hast, Dich zu verstecken. %s `n`n", $versteck);
							if ($gegenstand2==0){ 
								if ($gegenstand1==0) { $text=translate_inline("Jetzt musst Du nur noch den anderen finden und dann zum Eingangstor zurückkehren.");
								}else{ $text=translate_inline("Jetzt musst Du nur noch zum Eingangstor zurückkehren."); }
								output("`2An der vereinbarten Stelle findest Du einen der beiden Gegenstände: eine schmale Halskette. `n`n%s`n`n`@", $text);
								set_module_pref("schleichengegenstand2", 1, "wettkampf");
							}
						break;
					}
					output("%s %s %s",$thatext,$irogtext,$tipptha);
					$minuten = (int)($zeit / 60);
					$sekunden = $zeit % 60;
					if ($sekunden!=0) {
						output("`n`@Bis jetzt bist Du seit `^%s`@ Minuten und `^%s`@ Sekunden unterwegs.`n`n", $minuten, $sekunden);
					} else {
						output("`@Du bist jetzt seit genau `^%s`@ Minuten unterwegs.`n`n", $minuten); 
					}
					erstelle_navpoints($spieler, &$arena, $imversteck);
				}
			}
		}
		page_footer();
}
?>