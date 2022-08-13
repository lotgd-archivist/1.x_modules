<?php

function wettkampf_wmusik_wmusik0_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
		require_once("lib/fert.php");
		function auswahl_instrument($instrument=0, $navs=true){
				//Wie gut gelingt es, zufällig ein Instrument zu erwischen, das nicht gehasst wird?
				// Immer:		Vanthira
				// Einfach:   	Echsen, Vampire, Menschen
				// Mittel:		Elfen
				// Schwierig: 	Trolle, Zwerge
				
				$instrumente = array(
					//Gemocht von: Zwerge, Trolle | Gehasst von: Elfen, Vampire | Gleichgültig: Menschen, Echsen, Vanthira
					"i1"  => array("name"=>"die Rassel", 				"sg"=>"20", "qualitaet"=>"-20"),
					"i2"  => array("name"=>"die Bongotrommel",		 	"sg"=>"0", "qualitaet"=>"0"),
					"i3"  => array("name"=>"den großen Gong",		 	"sg"=>"-15", "qualitaet"=>"15"),
					
					//Gemocht von: Vampire, Echsen, Elfen | Gehasst von: Zwerge, Trolle | Gleichgültig: Menschen, Vanthira
					"i4"  => array("name"=>"die Geige", "sg"=>"-20", "qualitaet"=>"20"),
					
					//Gemocht von: Menschen, Elfen | Gehasst von: Zwerge, Trolle, Echsen | Gleichgültig: Vanthira
					"i5"  => array("name"=>"die Harfe",					"sg"=>"-10", "qualitaet"=>"10"),
					"i6"  => array("name"=>"die sechssaitige Laute", 	"sg"=>"-15", "qualitaet"=>"15"),
					"i7"  => array("name"=>"die zwölfsaitige Laute", 	"sg"=>"-20", "qualitaet"=>"20"),
					
					//Alle lieben dieses Instrument
					"i8"  => array("name"=>"die magisch beseelte, äußerst wohlklingende, aber nur sehr schwer kontrollierbare Laute names 'Vinisha'", "sg"=>"-40", "qualitaet"=>"40"),
					
					//Gemocht von: Menschen, Elfen | Gehasst von: Zwerge, Trolle | Gleichgültig: Vampire, Vanthira, Echsen
					"i9"  => array("name"=>"die Panflöte",	"sg"=>"-10", "qualitaet"=>"10"),
					"i10" => array("name"=>"die Querflöte",	"sg"=>"-15", "qualitaet"=>"15"),
					
					//Gemocht von: Vampire, Vanthira | Gehasst von: Elfen, Menschen | Gleichgültig: Zwerge, Trolle, Echsen
					"i11" => array("name"=>"das riesige Krummhorn eines Peindämonen, dem man nur mit Mühe einen, dann aber wahrhaft infernalischen Ton entlocken kann", "sg"=>"-25", "qualitaet"=>"25")
				);
							
				if ($navs==true)	{
				//Dazugehörige Navs ausgeben	
					while (list($key, $value) = each ($instrumente)) {
						switch($key2){
						case ""  : output("`@Beliebt bei Zwergen und Trollen`n"); break;
						case "3"  : output("`n`@Beliebt bei Echsen`n"); break;
						case "4"  : output("`n`@Beliebt bei Menschen`n"); break;					
						case "7"  : output("`n`@Allseits beliebt`n"); break;
						case "8"  : output("`n`@Beliebt bei Elfen`n"); break;
						case "10" : output("`n`@Beliebt bei Vampiren`n"); break;
						}
						$key2++;
						output("<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auswahl-lied&subop3=i".$key2."'>`^$key2. `2$value[name].`n</a>", true);
						addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auswahl-lied&subop3=i".$key2."");
						if ($key2 <10) addnav("$key2?Instrument $key2","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auswahl-lied&subop3=i".$key2."");
						else if ($key2 == 10) addnav("0?Instrument $key2","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auswahl-lied&subop3=i".$key2."");
						else if ($key2 == 11) addnav("ß?Instrument $key2","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auswahl-lied&subop3=i".$key2."");
					}
					output("`@`n--> Die Tipps, welchen Völkern welche Instrumente gefallen, hast Du aus einem Leitfaden von Ra'esha. Sie meinte aber, dass die Vorlieben damit nur ganz grob erfasst sind ... Abneigungen wollte sie Dir leider nicht verraten.");
					
				}else{
					$auswahl=array("instrument" => array("name"=>$instrumente[$instrument][name], "sg"=>$instrumente[$instrument][sg], "qualitaet"=>$instrumente[$instrument][qualitaet]));
					set_module_pref("musik_wk_data", createstring($auswahl), "wettkampf");
					return $auswahl;
				}	
	}
	
	//Funktion zur Auswahl des Liedes
	function auswahl_lied($instrument=0, $einzellied=0, $navs=true){
		switch ($instrument) {		
			case "i1" :
			case "i2" :
			case "i3" :	
				//Rassel, Bongotrommel, Tambourin
				$lieder=array(
					"l1"  => array("name"=>"Das Lied vom kleinen, rastlosen Troll", "sg"=>"20", "qualitaet"=>"-40"),
					"l2"  => array("name"=>"Gewölbegrollen (allegro ma non troppo)", "sg"=>"10", "qualitaet"=>"-20"),
					"l3"  => array("name"=>"Das Lied vom Bahnenschwimmen im Schlammtümpel", "sg"=>"0", "qualitaet"=>"0"),
					"l4"  => array("name"=>"Es war ein Troll, ein Zwerg, die wollt'n woll in'n Berg (allegro assai)", "sg"=>"-10", "qualitaet"=>"10"),
					"l5"  => array("name"=>"Die zwergische Blitz- und Donnersonate 37 (allegro furioso)", "sg"=>"-20", "qualitaet"=>"20"),
					);
			break;
			case "i4" :
				//Geige
				$lieder=array(
					"l1"  => array("name"=>"Und durstig geh'n wir jagen, jagen jede Nacht", "sg"=>"20", "qualitaet"=>"-40"),
					"l2"  => array("name"=>"Die 945. Lobpreisung Ssslassars (largo)", "sg"=>"10", "qualitaet"=>"-20"),
					"l3"  => array("name"=>"In uns'ren lichten, tiefen Wäldern (adagio)", "sg"=>"0", "qualitaet"=>"0"),
					"l4"  => array("name"=>"Chara, Du mein Licht (andante)", "sg"=>"-10", "qualitaet"=>"10"),
					"l5"  => array("name"=>"Laue Nacht, oh warmer Schatz (allegretto)", "sg"=>"-20", "qualitaet"=>"20"),
					);
			break;
			case "i5" :
			case "i6" :
			case "i7" :
				//Harfe, Laute 1 + 2 m, e
				$lieder=array(
					"l1"  => array("name"=>"Vom Kinde, das den Elfen sah", "sg"=>"20", "qualitaet"=>"-40"),
					"l2"  => array("name"=>"Vom Elfen, der den Menschen sah", "sg"=>"10", "qualitaet"=>"-20"),
					"l3"  => array("name"=>"Reitersonate 7", "sg"=>"0", "qualitaet"=>"0"),
					"l4"  => array("name"=>"Wie lieb ich Dich nicht, Stadt, doch Wald und Dich, die Wiese (moderato)", "sg"=>"-10", "qualitaet"=>"10"),
					"l5"  => array("name"=>"Und 'naus in Liebe gehen wir", "sg"=>"-20", "qualitaet"=>"20"),
					);
			break;	
			case "i8" :
				//Vinisha
				$lieder=array(
					"l1"  => array("name"=>"Oh Elfen, oh Zwerge, ihr beide (lento)", "sg"=>"20", "qualitaet"=>"-40"),
					"l2"  => array("name"=>"Ach Menschen, ach Echsen, wir beiden (allegretto)", "sg"=>"10", "qualitaet"=>"-20"),
					"l3"  => array("name"=>"Wenn Troll und Echse (andantino)", "sg"=>"0", "qualitaet"=>"0"),
					"l4"  => array("name"=>"Wir sind ein Teil vom Ganzen (vivacissimo)", "sg"=>"-10", "qualitaet"=>"10"),
					"l5"  => array("name"=>"Das Lied der Völker (maestoso)", "sg"=>"-20", "qualitaet"=>"20"),
					);
			break;				
			case "i9" :
			case "i10" :
				//Querflöte, Panflöte
				$lieder=array(
					"l1"  => array("name"=>"Der Wald, die Stadt, fiderallala", "sg"=>"20", "qualitaet"=>"-40"),
					"l2"  => array("name"=>"Wir sind einander einst begegnet", "sg"=>"10", "qualitaet"=>"-20"),
					"l3"  => array("name"=>"Kein Troll, kein Zwerg, ach wunderbar", "sg"=>"0", "qualitaet"=>"0"),
					"l4"  => array("name"=>"Es ist vergangen längst, doch Neues kehrt bald ein", "sg"=>"-10", "qualitaet"=>"10"),
					"l5"  => array("name"=>"Wohin Winde wohlig wehen wollen ...", "sg"=>"-20", "qualitaet"=>"20"),
					);
			break;	
			case "i11" :
				//Dämonenhorn
				$lieder=array(
					"l1"  => array("name"=>"Die erste Anrufung: Ramius, oh Herr und Freund (adagio)", "sg"=>"20", "qualitaet"=>"-40"),
					"l2"  => array("name"=>"Die zweite Anrufung: Kein Leben kann dies bieten", "sg"=>"10", "qualitaet"=>"-20"),
					"l3"  => array("name"=>"Die dritte Anrufung: Wo kein Licht, nur Dunkelheit (largo)", "sg"=>"0", "qualitaet"=>"0"),
					"l4"  => array("name"=>"Die vierte Anrufung: Oh Du Dunkle, einzig Mächt'ge (larghetto)", "sg"=>"-10", "qualitaet"=>"10"),
					"l5"  => array("name"=>"Die fünfte Anrufung: Streck uns nieder, nimm uns mit (prestissimo)", "sg"=>"-20", "qualitaet"=>"20"),
					);
			break;
		}			
				if ($navs==true){
					//Dazugehörige Navs ausgeben	
					while (list($key, $value) = each ($lieder)) {
						$key2++;
						output("<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auftritt&subop3=".$instrument."&subop3=l".$key2."'>`^$key2. `2$value[name].`n`n</a>", true);
						addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auftritt&subop3=".$instrument."&subop3=l".$key2."");
						addnav("$key2?Lied $key2","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auftritt&subop3=".$instrument."&subop3=l".$key2."");
					}	
				}else{
					//Werte des Liedes ausgeben	und in den Array einfügen, in dem sich das Instrument befindet
					$auswahl = createarray(get_module_pref("musik_wk_data"), "wettkampf");			
					$auswahl['lied'] = array("name" => $lieder[$einzellied][name], "sg" => $lieder[$einzellied][sg], "qualitaet" => $lieder[$einzellied][qualitaet]);
					return $auswahl;
				}
	}
	
		output("`@`bMusizieren und Singen`b`n");
		
		$subop = $_GET['subop'];
		$subop3 = $_GET['subop3'];
		
		switch ($subop){
			case "anfang" :
				$teilnahme=get_module_setting("teilnahme", "wettkampf");	
				
				if ($session[user][gold]<$session[user][level]*$teilnahme){
					output("Ra'esha schüttelt den Kopf: `#'Nur der Tod ist umsonst - zum Glück, wäre auch schade drum.'");
					addnav("Zurück","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=musik");
				}else {
					output("`@Kurz vor Deinem Auftritt erklärt Dir Ra'esha hinter der Bühne, was von Dir erwartet wird: `#'Eure Aufgabe ist es, soviele Leute wie möglich vor die Bühne zu bekommen und sie in einen ekstatischen Rausch zu versetzen, indem Ihr Instrumente wählt, die zu den anwesenden Völkern passen - und gut spielt! Dabei habt Ihr die Verpflichtung, zehn Lieder zu spielen und dazu zu singen. Danach endet Euer Auftritt. Vorzeitig abgebrochen wird er hingegen nur, wenn Ihr das Publikum zu sehr vergrault. Aus Zuschauermenge und Stimmung errechnen wir Juroren dann Euren Punktestand. Habt Ihr noch Fragen?`n`n `@Schon vom Lampenfieber gepackt, fällt Dir nichts ein.`n`n `#'Gut, dann ab auf die Bühne mit Euch! Und seid weise in der Wahl Eurer Lieder und Instrumente!'");
					$session[user][gold]-=$session[user][level]*$teilnahme;
					
					output("`@`n`n<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auswahl-instrument&subop3=neu'>Auf die Bühne!</a>", true);
					addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auswahl-instrument&subop3=neu");
					addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auswahl-instrument&subop3=neu");			
				}
			break;				
			case "auswahl-instrument" :
				switch ($subop3) {
					case "neu" :
						output("Du gehst mit Herzklopfen nach draußen auf die Bühne und überschaust eine Menge von etwa siebzig Zuschauern - also etwa zehn aus jedem der Völker. Es herrscht eine Totenstille und alle Blicke sind erwartungsvoll auf Dich gerichtet ...`n`nBevor die Stille unerträglich werden kann, nimmst Du:`n`n");
						
						//Startwerte: von jedem Volk 10
						$anzahl=array(
							"vanthira"=>"10",
							"menschen"=>"10",
							"echsen"=>"10",
							"vampire"=>"10",
							"elfen"=>"10",
							"trolle"=>"10",
							"zwerge"=>"10",
							"gesamt"=>"70",
							"stimmung"=>"700"
						);				
						set_module_pref("musik_runde", 1, "wettkampf");
						set_module_pref("anzahl_publikum", createstring($anzahl), "wettkampf");
						auswahl_instrument();
					break;
						case "laeuft" :
						
						$runde=get_module_pref("musik_runde", "wettkampf")+1;
						
						$anzahl=createarray(get_module_pref("anzahl_publikum", "wettkampf"));
						
						$vanthira=$anzahl[vanthira];
						$menschen=$anzahl[menschen];
						$echsen=$anzahl[echsen];
						$vampire=$anzahl[vampire];
						$elfen=$anzahl[elfen];
						$trolle=$anzahl[trolle];
						$zwerge=$anzahl[zwerge];
						$gesamt=$anzahl[gesamt];
						$stimmung=$anzahl[stimmung];
						
						if ($runde <= 10) output("`@`2Runde %s/10`n`n", $runde);
						else output("`@`2Dein Endergebnis`n`n");
						output("`@Im Publikum befinden sich etwa:`n`n%s`@ Echsen.`n%s`@ Elfen.`n%s`@ Menschen.`n%s`@ Trolle.`n%s`@ Vampire.`n%s`@ Vanthira.`n%s`@ Zwerge.", ($echsen==0?"`\$0":"`^$echsen"), ($elfen==0?"`\$0":"`^$elfen"), ($menschen==0?"`\$0":"`^$menschen"), ($trolle==0?"`\$0":"`^$trolle"), ($vampire==0?"`\$0":"`^$vampire"), ($vanthira==0?"`\$0":"`^$vanthira"), ($zwerge==0?"`\$0":"`^$zwerge"));
						output("`n`n`@Gesamtpublikum etwa: %s`@ Wesen.`n", ($gesamt==0?"`\$0":"`^$gesamt"));
						if ($runde < 11 && $stimmung > 0 && $anzahl > 0){
							set_module_pref("musik_runde", $runde, "wettkampf");
							output("`nAls nächstes wählst Du:`n`n");
							auswahl_instrument();
						}else{
						//Ende des Wettbewerbs!
						if ($gesamt == 0){
							output("`n`n`\$Da stehst Du nun ... und niemand will Dich hören. Mit einem hämischen Grinsen holt Ra'esha Dich von der Bühne.");
							$stimmung=0;
						}else if ($stimmung == 0){
							output("`n`n`\$Du wirst regelrecht ausgebuht. Bevor es zu Gewalttätigkeiten kommen kann, holt Dich Raesha von der Bühne.");
						}else if ($runde >= 11) output("`n`nRa'esha kommt auf die Bühne, und damit weißt Du, dass der Wettbewerb vorbei ist.");
						
						$wmusik1p=round($stimmung * 0.152);
						if ($wmusik1p > 500) $wmusik1p=500;
						
						$wmusik2=round($gesamt * 1.17) + $wmusik1p;
						
						set_module_pref("wmusik0", $gesamt, "wettkampf");
						set_module_pref("wmusik1", $wmusik1p, "wettkampf");
						set_module_pref("wmusik2", $wmusik2, "wettkampf");
						
						$bestmusik0=get_module_pref("bestmusik0", "wettkampf");
						$bestmusik1=get_module_pref("bestmusik1", "wettkampf");
						$bestmusik2=get_module_pref("bestmusik2", "wettkampf");
						
						if ($gesamt > $bestmusik0 && $gesamt != 0) set_module_pref("bestmusik0", $gesamt, "wettkampf");
						if ($stimmung*0.5 > $bestmusik1 && $stimmung != 0) set_module_pref("bestmusik1", $stimmung*0.5, "wettkampf");
				
						set_module_pref("wmusiklevel", $session[user][level], "wettkampf");
						set_module_pref("wmusikdk", $session[user][dragonkills], "wettkampf");
						set_module_pref("wmusikfw", $musik, "wettkampf");
		
						if ($wmusik2 > $bestmusik2 && $wmusik2 != 0){
							set_module_pref("bestmusik2", $wmusik2, "wettkampf");
							set_module_pref("bestmusiklevel", $session[user][level], "wettkampf");
							set_module_pref("bestmusikdk", $session[user][dragonkills], "wettkampf");
							set_module_pref("bestmusikfw", $musik, "wettkampf");
						}
						
						addnav("Weiter.","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=musik");
						}
					break;
				}
			break;
			case "auswahl-lied":				
				$auswahl=auswahl_instrument("$subop3", false);
				output("`@Du hast `^%s`@ als Instrument ausgewählt. Welches Lied möchtest Du spielen? Je größer seine Zahl, desto schwieriger ist es.`n`n", $auswahl[instrument][name]);
				auswahl_lied("$subop3");
			break;
			case "auftritt":
				$subop3 = $_GET['subop3'];	
				$auswahl=auswahl_lied("$subop3", "$subop3", false);
							
				//Wer ist da?
				$anzahl=createarray(get_module_pref("anzahl_publikum", "wettkampf"));
						
				$vanthira=$anzahl[vanthira];
				$menschen=$anzahl[menschen];
				$echsen=$anzahl[echsen];
				$vampire=$anzahl[vampire];
				$elfen=$anzahl[elfen];
				$trolle=$anzahl[trolle];
				$zwerge=$anzahl[zwerge];
				$gesamt=$anzahl[gesamt];
				$stimmung=$anzahl[stimmung];
				
				$var_stimmung=0;
				$var_gesamt=0;
				$var_menschen=0;
				$var_echsen=0;
				$var_vampire=0;
				$var_elfen=0;
				$var_trolle=0;
				$var_zwerge=0;
							
				$l_qualitaet=$auswahl[lied][qualitaet];
				$qualitaet=$auswahl[instrument][qualitaet]+$l_qualitaet;
																			
				$musik=get_fertigkeit(musik);
									
				//Schwierigkeitsgrad Instrument + Lied	
				$mod=$auswahl[instrument][sg]+$auswahl[lied][sg];
				
				//Die Probe
				$probe=probe($musik, $mod, 0.9, 99.1, true);
				$wert=$probe[wert];
				if ($probe[ergebnis] == "kritischer erfolg") $wert=100;
				else if ($probe[ergebnis] == "kritischer misserfolg") $wert=-100;
													
				//Verrechnung gegen die Völker
							
				//Vanthira
					if ($subop3 == "i8" || $subop3 == "i11"){
						// Mögen dieses Instrument -> Stimmung steigt besonders an
						$reaktion=$wert+$l_qualitaet;
					}else{
						// Normale Reaktion
						$reaktion=$wert;
					}
					//Stimmungsveränderung
					$var_stimmung+=$reaktion;
					
					//Publikumsveränderung
					$var_vanthira=round($reaktion / 10);
					if ($vanthira == 0) $var_vanthira=0;
					else if ($var_vanthira < 0 && $vanthira < -1*$var_vanthira) $var_vanthira=-1*$vanthira;
				
				//Echsen
					if ($subop3 == "i4" || $subop3 == "i8"){
						// Mögen dieses Instrument -> Stimmung steigt besonders an
						$reaktion=$wert+$qualitaet;
					}else if ($subop3 == "i5" || $subop3 == "i6" || $subop3 == "i7"){ 
						// Hassen dieses Instrument -> Stimmung sinkt besonders / Kann mit gutem Lied ausgeglichen werden
						$reaktion=$wert+$l_qualitaet;
					}else{
						// Normale Reaktion
						$reaktion=$wert;
					}
					//Stimmungsveränderung
					$var_stimmung+=$reaktion;
					
					//Publikumsveränderung
					$var_echsen=round($reaktion / 10);
					if ($echsen == 0) $var_echsen=0;
					else if ($var_echsen < 0 && $echsen < -1*$var_echsen) $var_echsen=-1*$echsen;
				
				//Vampire
					if ($subop3 == "i4" || $subop3 == "i8" || $subop3 == "i11"){ 
						// Mögen dieses Instrument -> Stimmung steigt besonders an
						$reaktion=$wert+$qualitaet;
					}else if ($subop3 == "i1" || $subop3 == "i2" || $subop3 == "i3"){ 
						// Hassen dieses Instrument -> Stimmung sinkt besonders / Kann mit gutem Lied ausgeglichen werden
						$reaktion=$wert+$l_qualitaet;
					}else{
						// Normale Reaktion
						$reaktion=$wert;
					}
					//Stimmungsveränderung
					$var_stimmung+=$reaktion;
					
					//Publikumsveränderung
					$var_vampire=round($reaktion / 10);
					if ($vampire == 0) $var_vampire=0;
					else if ($var_vampire < 0 && $vampire < -1*$var_vampire) $var_vampire=-1*$vampire;
							
				//Menschen
					if ($subop3 == "i5" || $subop3 == "i6" || $subop3 == "i7" || $subop3 == "i8" || $subop3 == "i9" || $subop3 == "i10"){ 
						// Mögen dieses Instrument -> Stimmung steigt besonders an
						$reaktion=$wert+$qualitaet;
					}else if ($subop3 == "i11"){ 
						// Hassen dieses Instrument -> Stimmung sinkt besonders / Kann mit gutem Lied ausgeglichen werden
						$reaktion=$wert+$l_qualitaet;
					}else{
						// Normale Reaktion
						$reaktion=$wert;
					}
					//Stimmungsveränderung
					$var_stimmung+=$reaktion;
					
					//Publikumsveränderung
					$var_menschen=round($reaktion / 10);
					if ($menschen == 0) $var_menschen=0;
					else if ($var_menschen < 0 && $menschen < -1*$var_menschen) $var_menschen=-1*$menschen;
								
				//Elfen
					if ($subop3 == "i4" || $subop3 == "i5" || $subop3 == "i6" || $subop3 == "i7" || $subop3 == "i8" || $subop3 == "i9" || $subop3 == "i10"){ 
						// Mögen dieses Instrument -> Stimmung steigt besonders an
						$reaktion=$wert+$qualitaet;
					}else if ($subop3 == "i1" || $subop3 == "i2" || $subop3 == "i3" || $subop3 == "i11"){ 
						// Hassen dieses Instrument -> Stimmung sinkt besonders / Kann mit gutem Lied ausgeglichen werden
						$reaktion=$wert+$l_qualitaet;
					}else{
						// Normale Reaktion
						$reaktion=$wert;
					}
					//Stimmungsveränderung
					$var_stimmung+=$reaktion;
					
					//Publikumsveränderung
					$var_elfen=round($reaktion / 10);	
					if ($elfen == 0) $var_elfen=0;
					else if ($var_elfen < 0 && $elfen < -1*$var_elfen) $var_elfen=-1*$elfen;
									
				//Trolle und Zwerge (sind in dieser Hinsicht gleich ...)
					if ($subop3 == "i1" || $subop3 == "i2" || $subop3 == "i3" || $subop3 == "i8"){ 
						// Mögen dieses Instrument -> Stimmung steigt besonders an
						$reaktion=$wert+$qualitaet;
					}else if ($subop3 == "i4" || $subop3 == "i5" || $subop3 == "i6" || $subop3 == "i7" || $subop3 == "i9" || $subop3 == "i10"){ 
						// Hassen dieses Instrument -> Stimmung sinkt besonders / Kann mit gutem Lied ausgeglichen werden
						$reaktion=$wert+$l_qualitaet;
					}else{
						// Normale Reaktion
						$reaktion=2*$wert;
					}
					//Stimmungsveränderung
					$var_stimmung+=$reaktion;
					
					//Publikumsveränderung (Zufallswert: sind unberechenbar)
					$var_trolle=round(($reaktion + (e_rand(-10,10))) / 10);	
					$var_zwerge=round(($reaktion + (e_rand(-10,10))) / 10);	
					if ($trolle == 0) $var_trolle=0;
					else if ($var_trolle < 0 && $trolle < -1*$var_trolle) $var_trolle=-1*$trolle;
					if ($zwerge == 0) $var_zwerge=0;
					else if ($var_zwerge < 0 && $zwerge < -1*$var_zwerge) $var_zwerge=-1*$zwerge;
							
				//Gesamtveränderung des Publikums
				$var_gesamt=$var_vanthira+$var_echsen+$var_vampire+$var_menschen+$var_elfen+$var_trolle+$var_zwerge;
				
				if ($var_gesamt < 0 && $gesamt < -1*$var_gesamt) $var_gesamt=-1*$gesamt;
				if ($var_stimmung < 0 && $stimmung < -1*$var_stimmung) $var_stimmung=-1*$stimmung;
								
				//Wer ist jetzt noch da?
				$anzahl=array(
						"vanthira"=>$vanthira+$var_vanthira,
						"menschen"=>$menschen+$var_menschen,
						"echsen"=>$echsen+$var_echsen,
						"vampire"=>$vampire+$var_vampire,
						"elfen"=>$elfen+$var_elfen,
						"trolle"=>$trolle+$var_trolle,
						"zwerge"=>$zwerge+$var_zwerge,
						"gesamt"=>$gesamt+$var_gesamt,
						"stimmung"=>$stimmung+$var_stimmung,
					);	
				set_module_pref("anzahl_publikum", createstring($anzahl), "wettkampf");
										
				if ($stimmung == 0) $stimmung_text=translate_inline("`\$Du kannst Deine eigene Musik kaum noch hören, so aufgebracht ist das Publikum über Deine grauenhafte Darbietung. Sie stehen kurz davor, Dich von der Bühne zu prügeln.");
				else if ($stimmung <  50  && $stimmung >  0) $stimmung_text=translate_inline("`\$Du kannst Dich vor faulen Eiern kaum mehr retten!");
				else if ($stimmung <  150 && $stimmung >= 50) $stimmung_text=translate_inline("`\$Es fliegen jede Menge Tomaten und auch einige faule Eier!");
				else if ($stimmung <  250 && $stimmung >= 150) $stimmung_text=translate_inline("`\$Es fliegen ein paar Tomaten ...");
				else if ($stimmung <  350 && $stimmung >= 250) $stimmung_text=translate_inline("`\$Die Gespräche der Zuschauer sind sehr laut geworden, man beachtet Dich kaum noch ...");
				else if ($stimmung <  450 && $stimmung >= 350) $stimmung_text=translate_inline("`\$Die Zuschauer beginnen sich zu unterhalten.");
				else if ($stimmung <  550 && $stimmung >= 450) $stimmung_text=translate_inline("`\$Einige Zuschauer gähnen, andere schauen zu Boden.");
				else if ($stimmung <  650 && $stimmung >= 550) $stimmung_text=translate_inline("`\$Einige Zuschauer gähnen.");
				else if ($stimmung >= 650 && $stimmung <= 750) $stimmung_text=translate_inline("`@Das Publikum zeigt keine nennenswerten Gefühlsregungen.");
				else if ($stimmung >  750 && $stimmung <= 850) $stimmung_text=translate_inline("`@Einige Zuschauer beginnen mitzuschunkeln.");
				else if ($stimmung >  850 && $stimmung <= 950) $stimmung_text=translate_inline("`@Direkt vor der Bühne beginnen einige Leute zu tanzen.");
				else if ($stimmung >  950 && $stimmung <= 1050) $stimmung_text=translate_inline("`@Die Leute vor der Bühne hüpfen und springen, weiter hinten tanzt man.");
				else if ($stimmung >  1050 && $stimmung <= 1150) $stimmung_text=translate_inline("`@Die ganze Menge hüpft und springt vor Freude.");
				else if ($stimmung >  1150 && $stimmung <= 1300) $stimmung_text=translate_inline("`@Einige Leute beginnen wie verrückt zu kreischen!");
				else if ($stimmung >  1300 && $stimmung <= 1400) $stimmung_text=translate_inline("`@Man ruft Deinen Namen im Zusammenhang mit eindeutig zweideutigen Versprechungen!");
				else if ($stimmung >  1400 && $stimmung <= 1650) $stimmung_text=translate_inline("`@Einige Fans stürmen auf die Bühne, können aber zurückgehalten werden!");
				else if ($stimmung >  1650 && $stimmung <= 1850) $stimmung_text=translate_inline("`@Die Wachmänner haben alle Hände voll zu tun, die kreischenden Fans von Dir abzuhalten!");
				else if ($stimmung >  1850 && $stimmung <= 2200) $stimmung_text=translate_inline("`@Die Wachmänner sind überrumpelt worden! Die Bühne ist voller Fans, die mitsingen und teils vor Dir niederknien.");
				else if ($stimmung >  2200) $stimmung_text=translate_inline("`@Das `bgesamte`b Publikum singt mit! Vor lauter Fans, die sich Dir um den Hals werfen wollen, kommst Du kaum noch zum Spielen - aber darauf kommt es ja nun auch nicht mehr an ...");
				
				output("`@Du hast `^%s`@ als Instrument ausgewählt und beginnst '`^%s`@' zu spielen ... %s", $auswahl[instrument][name], $auswahl[lied][name], $stimmung_text);						
				output("%s %s`n", ($anzahl[gesamt]==0?"`b`\$Du hast keine Zuschauer mehr!`b":"`@Du hast im Moment etwa `^$anzahl[gesamt]`@ Zuschauer."), ($var_gesamt==0?" `@Das dürften genauso viele sein wie nach dem vorherigen Lied.":($var_gesamt<0?"`\$Das dürften `^ ".(-1*$var_gesamt)."`\$ weniger sein als nach dem vorherigen Lied ...":"`@Das dürften `^$var_gesamt`@ mehr sein als nach dem vorherigen Lied!")));
				output("`n`b%s`b`n`n", ($var_stimmung==0?"`@Insgesamt hast Du den Eindruck, dass sich die Stimmung durch dieses Lied nicht verändert.":($var_stimmung<0?"`\$Insgesamt hast Du den Eindruck, dass die Stimmung nachlässt ...":"`@Insgesamt hast Du den Eindruck, dass die Stimmung ansteigt!")));
											
				output("`@Bei:`nden Echsen%s`n", ($var_echsen==0?" ist niemand hinzugekommen, aber auch niemand gegangen.":($var_echsen<0?":`^ ".(-1*$var_echsen)."`\$ gegangen ...":": `^$var_echsen`@ hinzugekommen!")));	
				output("`@den Elfen%s`n", ($var_elfen==0?" ist niemand hinzugekommen, aber auch niemand gegangen.":($var_elfen<0?":`^ ".(-1*$var_elfen)."`\$ gegangen ...":": `^$var_elfen`@ hinzugekommen!")));				
				output("`@den Menschen%s`n", ($var_menschen==0?" ist niemand hinzugekommen, aber auch niemand gegangen.":($var_menschen<0?":`^ ".(-1*$var_menschen)."`\$ gegangen ...":": `^$var_menschen`@  hinzugekommen!")));				
				output("`@den Trollen%s`n", ($var_trolle==0?" ist niemand hinzugekommen, aber auch niemand gegangen.":($var_trolle<0?":`^ ".(-1*$var_trolle)."`\$ gegangen ...":": `^$var_trolle`@  hinzugekommen!")));	
				output("`@den Vampiren%s`n", ($var_vampire==0?" ist niemand hinzugekommen, aber auch niemand gegangen.":($var_vampire<0?":`^ ".(-1*$var_vampire)."`\$ gegangen ...":": `^$var_vampire`@ hinzugekommen!")));	
				output("`@den Vanthira%s`n", ($var_vanthira==0?" ist niemand hinzugekommen, aber auch niemand gegangen.":($var_vanthira<0?":`^ ".(-1*$var_vanthira)."`\$ gegangen ...":": `^$var_vanthira`@ hinzugekommen!")));	
				output("`@den Zwergen%s`n`n", ($var_zwerge==0?" ist niemand hinzugekommen, aber auch niemand gegangen.":($var_zwerge<0?":`^ ".(-1*$var_zwerge)."`\$ gegangen ...":": `^$var_zwerge`@ hinzugekommen!")));							
				
				addnav("Weiter.","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=wmusik0&subop=auswahl-instrument&subop3=laeuft");
			break;
	}
	page_footer();
}
?>