<?php

function wettkampf_wschleichen_schleichen_wettkampf_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
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
						if ($wert>30 && $wert <=50){ output("`@Du hast ein sehr gutes Versteck gefunden. Hier wird man Dich kaum finden k�nnen ...");} 
						if ($wert>50){ output("`@Du hast ein absolut brilliantes Versteck gefunden. Hier wird Dich bestimmt niemand finden."); }
					set_module_pref("schleichenprobespieler", $wert, "wettkampf");
					set_module_pref("schleichenversteckt", 1, "wettkampf");
				}else{
					output("`@Du versuchst ein passendes Versteck zu finden, ger�tst dabei aber so sehr in Hektik, dass es Dir nicht gelingt.");
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
				// W�chter bewegen sich jedes Mal (auch beim Versteckversuch)
				// output("DEBUG : THA war im Sektor %s IROG im Sektor %s`n",$tha,$irog);
				$tha=bewege_wache($tha, &$arena);
				$irog=bewege_wache($irog, &$arena);
				set_module_pref("schleichenortirog",$irog, "wettkampf");
				set_module_pref("schleichenorttha",$tha, "wettkampf"); 
				// output("DEBUG : THA jetzt im Sektor %s IROG im Sektor %s`n",$tha,$irog);
				// Schleichenprobe zur Bestimmung der Geschwindigkeit des Spielers beim Herumschleichen (Zeit)
				// und wie gut er sich vor den W�chtern verbergen kann (wenn er versteckt ist wird
				if (!$imversteck) {
					$mod = $arena[$spieler]['schleichen'];
					$fw  = get_fertigkeit("schleichen"); 
					$probe = probe($fw, $mod);
					$schleichenwert = $probe['wert'];
					// gez�hlt werden die Sekunden (minimal 60 Sekunden pro Sektor)
					$zeit += (160-$schleichenwert);
				}else{
				// stattdessen die hinterlegte Probe "schleichenprobespieler" aus den prefs verwendet
				// und die Zeit um eine Minute erh�ht. ( 60 Sekunden ) 
					$zeit += 60;
					$schleichenwert = get_module_pref("schleichenprobespieler", "wettkampf");
				}
				set_module_pref("schleichenzeit", $zeit, "wettkampf");
				
				// Wenn Tha im Sektor ist, �berpr�fen, ob er den Spieler erwischt hat.
				// Falls er im angrenzenden Gebiet ist, bekommt er einen Probenwurf mit modifikator $arena[$spieler]['entdecken']
				// der, falls er gelingt, ihm erlaubt, sofort zu dem Spielersektor zu gehen und dort erneut zu suchen
				If ($spieler==$tha || verbindung_existiert($spieler, $tha, &$arena) ) {
					if (verbindung_existiert($spieler, $tha, &$arena)) {
						// Fall 1 : im angrenzenden Sektor
						$fwtha = get_module_setting("fwtha", "wettkampf"); $mod = $arena[$spieler]['entdecken']; $probe = probe($fwtha, $mod);
						$thawert = $probe['wert']; $diff = $schleichenwert - $thawert;
						// output("DEBUG Tha probe %s Schleich/Versteckwert %s Dif %s`n`n", $thawert, $schleichenwert, $diff);
						// Konnte Spieler nicht sehen �ber die Grenze hinweg
						if ($thawert<0 || $diff>=0) {
							$thatext = translate_inline("`6Dir wird mulmig zumute, als Du Tha erblickst, der gerade ".$arena[$tha]['wache']." nach Dir sucht. Er scheint gerade nach Dir zu fragen ... Schnell weiter, noch hat er Dich nicht gesehen!`n`n");
						} else {
							// Hat Spieler entdeckt und wechselt den Sektor
							$wechseltext = "Du h�rst leise Schritte, kannst aber ihre genaue Richtung nicht ausmachen! Das kann nur Tha sein, der sich eben noch ".$arena[$tha]['wache']." aufgehalten hatte. ";
							$wechseltext2 = "seinem Instinkt folgend zur�ckgekehrt ist und ";
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
							$thatext=translate_inline("`6Du bekommst Panik, als Du Tha erblickst, der ".$wechseltext2." hier gerade mit dem wachen Blick eines J�gers nach Dir sucht. Er sieht direkt in Deine Richtung! Jetzt blo� die Luft anhalten ...`n`n`@"); 
						// Tha hat gut gesucht und war �berlegen
						} elseif ($diff<0) {
							$erwischt=true;
							output("`6%sPl�tzlich rei�t Dich etwas von hinten zu Boden. Tha sitzt auf Deinem R�cken und bricht Dir fast das Genick, als er Deinen Kopf mit einem Krachen herumdreht. Wie gel�hmt starrst Du auf seine blitzenden Rei�z�hne ...`n`n",$wechseltext);
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
								$wechseltext = "Du h�rst wie jemand hastig in deine Richtung rennt. Es kann nur Irog sein, der eben noch ".$arena[$irog]['wache']." war. ";
								$wechseltext2 = "ist zur�ckgekehrt, nachdem er etwas Merkw�rdiges geh�rt hatte und";
								$irog = $spieler;
								set_module_pref("schleichenortirog",$irog, "wettkampf");
							}
						}
						if ($spieler==$irog) {
							$fwirog = get_module_setting("fwirog", "wettkampf"); $mod = 0; $probe = probe($fwirog, $mod);
							$irogwert = $probe['wert']; $diff = $schleichenwert - $irogwert;
							// output("DEBUG Irog probe %s Schleich/Versteckwert %s`dif %sn`n", $irogwert, $schleichenwert,$diff);
							if ($thawert<0 || $diff>=0) {
								$irogtext=translate_inline("`3Irog ".$wechseltext2." sucht hier gerade nach Dir. F�r einen Moment glaubst Du, er schaue Dir direkt in die Augen, aber dann wendet er sich wieder ab ...`@`n`n"); 
							} else { 
								$erwischt=true;
								if ($diff<0) { $erwischt=true; output("`3%sPl�tzlich sp�rst Du eine eiskalte Hand auf Deiner Schulter. Als Du Dich umdrehst, starrst Du in sein teuflisch grinsendes Gesicht.`n`n",$wechseltext); }
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
						output("`@Herzlichen Gl�ckwunsch, Du hast beide Gegenst�nde hergebracht, ohne entdeckt zu werden!`n`n");
						output("<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=ende&result=gewonnen'>Weiter.</a>", true);
						addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=ende&result=gewonnen");
						addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=ende&result=gewonnen");
					}
				} else {
					switch($spieler) {
						case 1 : 
							if ($imversteck) $versteck=translate_inline(", zumal es Dir gelungen ist, Dich zwischen zwei St�nden zu verstecken");
							output("`@Du befindest Dich im oberen Marktbereich, wo die Wanderh�ndler ihre Waren feilbieten. Im ".
							       "dichten und lauten Gedr�nge bist Du gut gesch�tzt%s.`n`n",$versteck);
							       
						break;
						case 2 : 
							if ($imversteck) $versteck=translate_inline(" in Deinem Versteck hinter einigen Kisten");
							output("`@Du befindest Dich%s am gro�en Eingangstor, durch das die Besuchermassen hereinstr�men. ".
							"Es herrscht ein reges Treiben, in dessen Schutz man Dich nur schwer entdecken kann.`n`n",$versteck);
						break;
						case 3 : 
							if ($imversteck) $versteck=translate_inline(" und lugst aus Deinem Versteck in einem leeren Salzfa�.");
							output("`@Du befindest Dich am K�chenhaus der Echsen%s. Es riecht so gut in dieser Gegend, dass ".
							       "Du am liebsten sofort in das Haus hineingehen w�rdest ...`n`n", $versteck);
						break;
						case 4 : 
							$tipp=e_rand(1,3);
							if ($tipp==1) {
								if ($tha==2 || $tha==3 || $tha==6 || $tha==8 || $tha==9) {
									$tipptha=translate_inline("`6Du bekommst von einem kleinen M�dchen den Hinweis, dass sich Tha gerade ".$arena[$tha]['wache']." aufh�lt.`n`n`@");
								} 
							}
							if ($imversteck) $versteck=translate_inline("Dennoch hast Du Dich verstecken k�nnen - in einem Stapel gro�er Teppiche.");
							output("`@Du befindest Dich im unteren Marktbereich, wo die St�nde etwas geordneter stehen, so dass Du ".
							       "leichter auszumachen bist als im oberen Bereich. Dennoch kannst Du hier recht gut umherschleichen. %s`n`n", $versteck);break;
						case 5 :
							output("`@Du befindest Dich in der Mitte des 'Platzes der V�lker', an der �berlebensgro�en Statue der Vermittlerin. Einige ".
							       "Leute legen gerade Blumen nieder. Au�er ihnen wagt es niemand, den gepflasterten Weg zu verlassen, so dass ein ".
							       "st�ndiges Gedr�nge herrscht, das Dich unweigerlich in eine Richtung mitzieht - und das ist gut so, denn hier ".
							       "befindest Du Dich geradezu auf dem Pr�sentierteller!`n`n"); break;
						case 6 :
							$tipp=e_rand(1,3);
							if ($tipp==1) {
								if ($tha==1 || $tha==2 || $tha==4 || $tha==7 || $tha==8) {
									$tipptha=translate_inline("`6Du schlie�t aus einem Gespr�ch, das Du zuf�llig mitanh�rst, dass sich Tha gerade ".$arena[$tha]['wache']." aufhalten muss.`@`n`n");
									}
							}
							if ($imversteck) {$versteck=translate_inline("gut versteckt `i`bim`b`i"); }else{ $versteck=translate_inline("am"); } 
							output("`@Du befindest Dich %s Schlammt�mpel, an dessen Rand zwei Trolle stehen und mit Trommelschl�gen ".
							       "die Zeit nehmen. Ein kleiner Pfad f�hrt den H�gel zum Tiefenschacht hinauf, aber dorthin willst Du nicht.`n`n", $versteck);
						break;
						case 7 :
							if ($imversteck) $versteck=translate_inline("Irgendwie ist es Dir gelungen, hinter den Holzzaun zu gelangen, wo Dich hoffentlich niemand finden wird.");
							$gegenstand1 = get_module_pref("schleichengegenstand1", "wettkampf");
							$gegenstand2 = get_module_pref("schleichengegenstand2", "wettkampf");
							output("`@Du befindest Dich an der Schie�anlage der Elfen, die mit einem Holzzaun abgesperrt ist. ".
							       "Aus Angst, ein Pfeil k�nnte sich dennoch verirren, halten sich hier nur wenige Leute auf, und ".
							       "sie alle haben es eilig. Kein sicherer Ort f�r jemanden wie Dich ... %s`n`n",$versteck);
							if ($gegenstand1==0){
								if ($gegenstand2==0) { $text=translate_inline("Jetzt musst Du nur noch den anderen finden und dann zum Eingangstor zur�ckkehren.");
								}else{ $text=translate_inline("Jetzt musst Du nur noch zum Eingangstor zur�ckkehren."); }
								output("`2An der vereinbarten Stelle findest Du einen der beiden Gegenst�nde: eine kleine Brosche. `n`n%s`n`n`@", $text);
								set_module_pref("schleichengegenstand1", 1, "wettkampf");
							}
						break;
						case 8 :  
							if ($imversteck) {$versteck=translate_inline("gut versteckt `i`bhinter`b`i"); }else{$versteck=translate_inline("an"); }
							$mod = $arena[8]['schleichen'];
							if ($mod==10) $b�hne=translate_inline("Sie wird gerade f�r den n�chsten Auftritt umgebaut, weshalb einige Zuschauer bereits gegangen sind. Dennoch f�hlst Du Dich sicher in ihrem Schutz, wenngleich nicht sehr.");
							if ($mod>10 && $mod<=15) $b�hne=translate_inline("Die Menge ist nur m��ig begeistert von dem derzeitigen Interpreten, weshalb sie relativ ruhig bleibt. Dennoch f�hlst Du Dich recht sicher in ihrem Schutz.");
							if ($mod>15) $b�hne=translate_inline("Die Menge ist angesichts des derzeitigen Interpreten au�er sich! Alle gr�hlen, h�pfen und tanzen! Wunderbar, in einer solchen Umgebung kann man gut umherschleichen.");  
							output("`@Du befindest Dich %s der B�hne f�r den Gesangs- und Musikwettbewerb der Vanthira. %s`n`n", $versteck, $b�hne);
						break;
						case 9 : 
							if ($imversteck) $versteck=translate_inline("In Deinem Versteck zwischen drei Pferden f�hlst Du Dich unbehaglich. Hoffentlich bleiben sie ruhig ...");
							$gegenstand1=get_module_pref("schleichengegenstand1", "wettkampf");
							$gegenstand2=get_module_pref("schleichengegenstand2", "wettkampf");
							output("`@Du befindest Dich an der abgesteckten Wiese, auf der die Menschen den Reitwettbewerb abhalten. Wegen der wertvollen Pferde gibt es hier viele Wachen, die sofort mi�trauisch werden, wenn sich jemand auff�llig benimmt, weshalb Du hier kaum eine M�glichkeit hast, Dich zu verstecken. %s `n`n", $versteck);
							if ($gegenstand2==0){ 
								if ($gegenstand1==0) { $text=translate_inline("Jetzt musst Du nur noch den anderen finden und dann zum Eingangstor zur�ckkehren.");
								}else{ $text=translate_inline("Jetzt musst Du nur noch zum Eingangstor zur�ckkehren."); }
								output("`2An der vereinbarten Stelle findest Du einen der beiden Gegenst�nde: eine schmale Halskette. `n`n%s`n`n`@", $text);
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