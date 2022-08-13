<?php

//translator ready
//addnews ready

/*
Der Fluss (für logtd ab 0.98)

Wetterabhängiges Ereignis für das Fertigkeitensystem

Benutzte Fertigkeit: Schwimmen

Erdacht und umgesetzt von Oliver Wellinghoff.

*********************************************************
*	Diese Datei sollte aus fertigkeiten.zip stammen.	*
*														*
*	Achtung: Wer diese Dateien benutzt, verpflichtet	*
*	sich, alle Module, die er für das Fertigkeiten-		*
*	system entwickelt frei und öffentlich zugänglich	*
*	zu machen! Jegliche Veränderungen an diesen Dateien *
*	müssen ebenfalls veröffentlicht werden!				*
*														*
*	Näheres siehe: dokumentation.txt					*
*														*
*	Wir entwickeln für Euch - Ihr entwickelt für uns.	*
*														*
*	Jegliche Veränderungen an diesen Dateien 			*
*	müssen ebenfalls veröffentlicht werden - so sieht 	*
*	es die Lizenz vor, unter der LOTGD veröffentlicht	*
*	wurde!												*
*														*
*	Zuwiderhandlungen können empfindliche Strafen		*
*	nach sich ziehen!									*
*														*
*	Zudem bitten wir darum, dass Ihr uns eine kurze		*
*	Mail an folgende Adresse zukommen lasst, in der		*
*	Ihr	uns die Adresse des Servers nennt, auf dem das	*
*	Fertigkeitensystem verwendet wird:					*
*	cern AT quantentunnel.de							*
*	(Spamschutz " AT " durch "@" ersetzen)				*
*														*
*	Das komplette Fertigkeitensystem ist zuerst auf		*
*	http://www.green-dragon.info erschienen.			*
*														*
*********************************************************

*/

require_once("lib/fert.php");

function fluss_getmoduleinfo(){
	$info = array(
		"name"=>"Der Fluss",
		"version"=>"1.0",
		"author"=>"Oliver Wellinghoff",
		"category"=>"Fertigkeiten - Wald",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("fertigkeiten"=>"1.0|Fertigkeitensystem von Oliver Wellinghoff und Michael Jandke",
			"weather"=>"2.0|By Talisman, part of the core download"),
		);
	return $info;
}

function fluss_install(){
	module_addeventhook("forest", "return 100;");
	module_addhook("newday");
	return true;
}

function fluss_uninstall(){
	return true;
}

function fluss_dohook($hookname,$args){
	switch($hookname){
		case "newday":
			global $session;
			set_module_pref("fluss_gehabt", 0, "fluss");
		break;
	}
	return $args;
}

function user_dies_fluss($news_text){
	global $session;
	output("`\$`n`nDu bist tot!");
	output("`n`nDu verlierst `^%s`\$ Erfahrungspunkte und all Dein Gold!", round($session[user][experience]*0.03));
	output("`n`nDu kannst morgen weiterspielen.");
	addnav("Tägliche News","news.php");
	addnews("`\$%s`4 ertrank bei dem Versuch, %s%s.", $session['user']['name'], ($session[user][gold]<=500?"":"mit etwa `^".$session[user][gold]."`4 Goldstücken im Gepäck "), $news_text);
	$session[user][alive]=false;
	$session[user][hitpoints]=0;
	$session[user][gold]=0;
	$session[user][experience]=round($session[user][experience]*0.97);	
	$session[user][specialinc]="";	
}
	
function fluss_runevent($type){
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:fluss";
	
	//Man verläuft sich nur einmal am Tag
	if (get_module_pref("fluss_gehabt") == 1){
		output("`2Du überquerst eine steinerne Bogenbrücke und gelangst sicher auf die andere Seite eines Flusses.");
		$session[user][specialinc]="";
	}else{
		
	//Mit welchem Wetter haben wir es gerade zu tun?
	$wetter=get_module_setting("weather","weather");
	switch($wetter){
		case "overcast and cool, with sunny periods": case "warm and sunny": case "hot and sunny": $type=1; break;
		case "rainy": $type=2; break;
		case "foggy": $type=3; break;
		case "cool with blue skies": $type=4; break;
		case "high winds with scattered showers": $type=5; break;
		case "thundershowers": $type=6; break;
	}
	
	$op=httpget('op');
	switch($op) {
	case "":
		set_module_pref("schwimmen", -1);
		output("`2Eine ganze Stunde schon befindest Du Dich auf der Suche nach einem Weg zurück in das Gebiet, von dem Du Dir mehr versprichst als einen langsamen Tod aus Langeweile ...");
		if ($type == 1){
			output("`2`n`nAls Du kurz innehältst, um Dir an diesem sonnigen Tag den Schweiß von der Stirn zu wischen, hörst Du es in der Nähe plätschern. Du schiebst ein wenig Gestrüpp zur Seite und erkennst einen ruhigen, breiten Flusslauf. Ein Wink der Götter! Wenn Du den Fluss hier durchschwimmst, gewinnst Du viel Zeit zurück.");
			
			addnav("Schwimmen", $from."op=weiter&subop=1");
			output("`n`n<a href=\"".$from."op=weiter&subop=1\">Nichts wie rein ins Wasser!</a>", true);
			addnav("", $from."op=weiter&subop=1");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Nein danke, es wird irgendwo eine Brücke geben.</a>`n", true);
			addnav("", $from."op=abbruch");
		}else if ($type == 2){
			output("`2`n`nZu allem Überfluss hat es jetzt auch noch angefangen zu regnen! Aber apropos Fluss, da rauscht doch etwas ... Du schiebst ein wenig Gestrüpp zur Seite und erkennst einen stark angeschwollenen, strömenden Flusslauf. Ein Wink der Götter? Wenn Du den Fluss hier durchschwimmst, gewinnst Du viel Zeit zurück.");
			
			addnav("Schwimmen", $from."op=weiter&subop=2");
			output("`n`n<a href=\"".$from."op=weiter&subop=2\">Ich bin sowieso schon nass, also rein ins Wasser ...</a>", true);
			addnav("", $from."op=weiter&subop=2");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Nein danke, es wird irgendwo eine Brücke geben.</a>`n", true);
			addnav("", $from."op=abbruch");
		}else if ($type == 3){
			output("`2`n`nTja, und der starke Nebel macht es Dir nicht gerade einfacher ... Moment! Da rauscht doch etwas ... Du schiebst ein wenig Gestrüpp zur Seite und findest Dich am Ufer eines Flusslaufs wieder - oder ist es ein See? Schwer zu sagen bei diesen Sichtverhältnissen. Auf jeden Fall könntest Du eine Menge Zeit sparen, wenn Du schwimmst, denn dies ist eindeutig die richtige Richtung. Immerhin scheint das Wasser recht ruhig zu sein ...");
			
			addnav("Schwimmen", $from."op=weiter&subop=3");
			output("`n`n<a href=\"".$from."op=weiter&subop=3\">Ich erkenne einen Fluss, wenn ich einen Fluss sehe, also rein!</a>", true);
			addnav("", $from."op=weiter&subop=3");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Nein danke, das ist mir nicht geheuer.</a>`n", true);
			addnav("", $from."op=abbruch");
		}else if ($type == 4){
			//Wie sicher ist das Eis?
			$var=e_rand(0,45);
			set_module_pref("modifikator", $var);
			if ($var <= 5)					$eis=translate_inline("Du fast in das Eis einbrichst! So gerade eben gelingt es Dir, Deinen Fuß zurückzuziehen");
			if ($var >  5  && $var <= 10)	$eis=translate_inline("Du leicht in das Eis einsackst ..");
			if ($var >  10 && $var <= 20)	$eis=translate_inline("Du ein lautes Knacken vernimmst");
			if ($var >  20  && $var <= 30)	$eis=translate_inline("Du ein leises, aber deutlich hörbares Knacken vernimmst");
			if ($var >  30  && $var <= 40)	$eis=translate_inline("Du ein leises, kaum hörbares Knacken vernimmst");
			if ($var >  40)					$eis=translate_inline("Du Dir davon überzeugt bist, sicheren, festen Boden unter den Füßen zu haben");
			
			output("`2`n`nDann erreichst Du einen zugefrorenen Fluss. Du gehst bis an den Rand der schneebedeckten Eisplatte und setzt einen Fuß darauf, woraufhin %s.`n`nWenn Du den Fluss hier überquerst, hast Du viel Zeit gewonnen. Sonst könnte es noch eine ganze Weile dauern, bis Du eine andere Stelle findest. Was möchtest Du tun?", $eis);
			
			addnav("Überqueren", $from."op=weiter&subop=4");
			output("`n`n<a href=\"".$from."op=weiter&subop=4\">Ich riskiere den Weg über das Eis.</a>", true);
			addnav("", $from."op=weiter&subop=4");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Mein Leben ist mir lieb, ich suche woanders weiter.</a>`n", true);
			addnav("", $from."op=abbruch");
		}else if ($type == 5){
			output("`2`n`nZu allem Überfluss hat es jetzt auch noch angefangen zu regnen - und starker Wind bläst Dir den Regen direkt ins Gesicht! Aber apropos Fluss, da rauscht doch etwas ... Du lehnst Dich gegen den Wind auf, schiebst ein wenig Gestrüpp zur Seite und erkennst einen stark angeschwollenen, strömenden Flusslauf. Ein Wink der Götter? Wenn Du den Fluss hier durchschwimmst, gewinnst Du viel Zeit zurück.");
			
			addnav("Schwimmen", $from."op=weiter&subop=5");
			output("`n`n<a href=\"".$from."op=weiter&subop=5\">Ich bin sowieso schon nass ... Raus aus dem Wind, rein ins Wasser!</a>", true);
			addnav("", $from."op=weiter&subop=5");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Bei diesem Wetter auch noch schwimmen? Lieber nicht.</a>`n", true);
			addnav("", $from."op=abbruch");
		}else if ($type == 6){
			output("`2`n`nObwohl man diesen Tag nicht gerade als langweilig bezeichnen kann. Die Wolken ziehen reißend vorüber und immer wieder wird die Dunkelheit von einem gleißenden Blitz durchbrochen. Du hörst ein markerschütterndes Donnergrollen und hoffst abermals, dass der nächste Blitz -- Aber was ist das ... da rauscht doch etwas ... Du lehnst Dich gegen den peitschenden Wind auf, schiebst ein wenig Gestrüpp zur Seite und erkennst einen stark angeschwollenen, reißenden Flusslauf. Mm ... Wenn Du den Fluss hier durchschwimmst, gewinnst Du viel Zeit zurück, so viel steht fest.");
			
			addnav("Schwimmen", $from."op=weiter&subop=6");
			output("`n`n<a href=\"".$from."op=weiter&subop=6\">Ich bin sowieso schon nass ... Raus aus dem Wind, rein ins Wasser!</a>", true);
			addnav("", $from."op=weiter&subop=6");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Bei diesem Wetter auch noch schwimmen? Lieber nicht.</a>`n", true);
			addnav("", $from."op=abbruch");
		}
	break;
	
	case "weiter":
		$subop=httpget('subop');	
		$schwimmen=get_fertigkeit(schwimmen);
		$ertrinken_max=round($session[user][maxhitpoints] * 0.2);
		
		//Wie viele Proben hat man schon geschaftt?
		$schwimmen_proben=get_module_pref("schwimmen");
		
		//Ermittlung aller allgemeinen Behinderungsmodifikatoren
		//Gold: 0.005 kg pro Stück (unrealistisch, aber sonst gehen sie alle unter ...)
		$mod_gold = round($session[user][gold] * 0.007);
		if ($session[user][gold] < 500) $gold_text = translate_inline("bisschen");
		else if ($session[user][gold] >= 500 && $session[user][gold] < 2500) $gold_text = translate_inline("vieles");
		else $gold_text = translate_inline("in großen Säcken mitgeschlepptes");
		
		//Edelsteine: 0.01 kg pro Stück
		$mod_gems = round($session[user][gems] * 0.01);
		if ($session[user][gems] < 10) $gems_text = translate_inline("paar");
		else if ($session[user][gems] >= 10 && $session[user][gems] < 30) $gems_text = translate_inline("vielen");
		else $gems_text = translate_inline("vielen, vielen");
		
		//Rüstung: 0.75 kg pro Stufe
		$mod_armor = round($session[user][armordef] * 0.75);
		if ($session[user][armordef] < 5) $armor_text = translate_inline("leichte");
		else if ($session[user][armordef] >= 5 && $session[user][armordef] < 11) $armor_text = translate_inline("mittelschwere");
		else $armor_text = translate_inline("schwere");
				
		//Waffe: 0.25 kg pro Stufe
		$mod_weapon = round($session[user][weapondmg] * 0.25);
		if ($session[user][weapondmg] < 5) $weapon_text = translate_inline("leichte");
		else if ($session[user][weapondmg] >= 5 && $session[user][weapondmg] < 11) $weapon_text = translate_inline("mittelschwere");
		else $weapon_text = translate_inline("schwere");
		
		//Modifikator für das Gesamtgewicht
		$mod_gewicht=$mod_weapon + $mod_armor + $mod_gold + $mod_gems;
		
		//Wettermodifikatoren | $schwimmengrenze gibt an, wie viele Proben man mindestens ablegen muss
		switch($subop) {
			case "1": $mod_wetter=0; $schwimmen_grenze=1; $news_text=translate_inline("einen vollkommen ruhigen Fluss zu durchschwimmen"); break;
			case "2": $mod_wetter=-10; $schwimmen_grenze=2;	$news_text=translate_inline("bei strömendem Regen einen Fluss zu durchschwimmen"); break;
			case "3": $mod_wetter=-5; $schwimmen_grenze=5; $news_text=translate_inline("einen Fluss im Nebel zu durschwimmen"); break;
			case "4": $mod_wetter=-35; $schwimmen_grenze=4; $news_text=translate_inline("einen zugefrorenen Fluss zu überqueren"); break;
			case "5": $mod_wetter=-15; $schwimmen_grenze=3; $news_text=translate_inline("einen reißenden Fluss zu durchschwimmen"); break;
			case "6": $mod_wetter=-25; $schwimmen_grenze=3; $news_text=translate_inline("während eines tosenden Gewitters einen Fluss zu durchschwimmen"); break;
		}
	
		//Berechnung der Stärke des Helden
		//10 kg Last schafft jeder Held ohne auch nur mit der Wimper zu zucken
		$strength=10 + round(($session[user][attack] + $session[user][defense] - $session[user][weapondmg] - $session[user][armordef]) / 6);
		
		//Zusätzliche Stärke hilft - aber nur bis zu einem bestimmten Grad
		if ($strength > 20) $strength = 20;
		
		//Der effektive Gesamtmodifikator				
		$mod = $mod_wetter + $strength - $mod_gewicht;
									
		//Die Probe
		$probe=probe($schwimmen, $mod);
		$wert=$probe[wert];
							
		if ($schwimmen_proben == -1) output("`2Du überprüfst noch einmal, ob alles sicher sitzt, Deine %s Rüstung, Dein %s Gold, Deine %s Waffe, Deine %s Edelsteine ...", $armor_text, $gold_text, $weapon_text, $gems_text);
		if ($subop != 4){
		if ($schwimmen_proben == -1){
			output("Dann gehst Du ins flache Wasser und musst schon bald anfangen zu schwimmen.`n`n");
			set_module_pref("schwimmen", 0);
			$schwimmen_proben=0;
		}
		
		//output("`b`@DEBUG-INFO:`b`n Proben-Ergebnis: %s (Sofortiger Tod ab < -50)`n Effektiver Gewichts-Modifikator: -%s (aus: Gold (-%s), Edelsteine (-%s), Waffe (-%s), Rüstung (-%s))`n Stärke: +%s (aus: (10 + (Grundschaden + Grundverteidigung - Waffenschaden - Rüstungsschutz) / 6) Maximum: 20),`nWettermodifikator: %s`nGesamtmodifikator für die Probe: %s`n Tragkraft der Eisfläche, falls vorhanden: %s`n Wieviele Proben vor dieser hier bereits geschafft: %s/%s`n`n`2", $wert, $mod_gewicht, $mod_gold, $mod_gems, $mod_weapon, $mod_armor, $strength, $mod_wetter, $mod, get_module_pref("modifikator"), get_module_pref("schwimmen"), $schwimmen_grenze);
		
		if ($wert <  -50){
			output("`\$Bei allen Göttern! Liegt es am Gewicht Deiner Ausrüstung? An Deiner Stärke? Oder kannst Du einfach nur nicht schwiblubblubberblub ...");
			user_dies_fluss($news_text);
		}else if ($wert >= -50 && $wert < 0){
			output("`2Du kannst Dich nur mit Mühe vor dem Absinken bewahren und musst ständig Wasser schlucken ...");
			$gold_malus=e_rand(0,2);
			if ($gold_malus != 0 && $session[user][gold] > 150){
				$gold_loss=round($session[user][gold] * $gold_malus * 0.1);
				output("`n`nIn größter Panik befreist Du Dich von `^%s`2 Goldstücken, um etwas mehr Auftrieb zu bekommen!", $gold_loss);
				$session[user][gold]-=$gold_loss;
			}
			$ertrinken=e_rand(2,$ertrinken_max);
			if ($ertrinken < $session[user][hitpoints]){
				$session[user][hitpoints]-=$ertrinken;
				output("`n`n`\$Du verlierst `^%s`\$ Punkte Lebensenergie!", $ertrinken);
			}else{
				output("`2`n`nSchließlich kommst Du mit dem Ausatmen von Wasser gar nicht mehr nach ...");
				user_dies_fluss($news_text);
			}
			if ($session[user][hitpoints] > 0) addnav("Weiterschwimmen ...", $from."op=weiter&subop=".$subop."");
		}else if ($wert >= 0 && $schwimmen_proben < $schwimmen_grenze){
			output("`2Du schwimmst mit ruhigen Zügen und kommst gut voran.");
			set_module_pref("schwimmen", $schwimmen_proben+1);
			addnav("Weiterschwimmen ...", $from."op=weiter&subop=".$subop."");
		}else if ($wert >= 0 && $schwimmen_proben == $schwimmen_grenze){
			output("`2Endlich hast Du das andere Ufer erreicht. Glückwunsch!");
			$bonus=e_rand(-1,3);
			if ($bonus > 0){
				output("`2 Und es war sogar eine Abkürzung! Dafür erhältst Du `^%s`2 %s.", $bonus, ($bonus==1?"zusätzlichen Waldkampf":"zusätzliche Waldkämpfe"));
				$session[user][turns]+=$bonus;
			}
			output("`n`n`@Du erhältst `^%s`@ Erfahrungspunkte!", round($session['user']['experience']*0.035));
       		$session['user']['experience']=round($session['user']['experience']*1.035);
			set_module_pref("fluss_gehabt", 1);
			$session[user][specialinc]="";
		}
		}else{
		if ($schwimmen_proben == -1){
			output("`2Dann gehst Du vorsichtig los und hoffst, dass das Eis Dich sicher trägt.`n`n");
		}
		
		//output("`b`@DEBUG-INFO:`b`n Proben-Ergebnis: %s (Sofortiger Tod ab < -50)`n Effektiver Gewichts-Modifikator: -%s (aus: Gold (-%s), Edelsteine (-%s), Waffe (-%s), Rüstung (-%s))`n Stärke: +%s (aus: (10 + (Grundschaden + Grundverteidigung - Waffenschaden - Rüstungsschutz) / 6) Maximum: 20),`nWettermodifikator: %s`nGesamtmodifikator für die Probe: %s`n Tragkraft der Eisfläche, falls vorhanden: %s`n Wieviele Proben vor dieser hier bereits geschafft: %s/%s`n`n`2", $wert, $mod_gewicht, $mod_gold, $mod_gems, $mod_weapon, $mod_armor, $strength, $mod_wetter, $mod, get_module_pref("modifikator"), get_module_pref("schwimmen"), $schwimmen_grenze);
		
		//Wie gut hält das Eis?
		$eis=get_module_pref("modifikator");
		$ergebnis=$eis-$mod_gewicht;
				
		if ($ergebnis >= 0 && $schwimmen_proben == -1){
			output("`2Die Götter waren Dir gnädig gestimmt, Du erreichst sicher das andere Ufer.");
			$bonus=e_rand(-1,3);
			if ($bonus > 0){
				output("`2 Und es war sogar eine Abkürzung! Dafür erhältst Du `^%s`2 %s.", $bonus, ($bonus==1?"zusätzlichen Waldkampf":"zusätzliche Waldkämpfe"));
				$session[user][turns]+=$bonus;
			}
			set_module_pref("fluss_gehabt", 1);
		}else if ($ergebnis < 0 && $schwimmen_proben == -1){
			output("`2Bei allen Göttern, es bricht! Das Wasser ist eiskalt und Du drohst, jeden Moment zu erfrieren. Jetzt kannst Du nur noch auf Deine Schwimmkünste vertrauen ...");
			set_module_pref("schwimmen", 0);
			$ertrinken=e_rand(2,$ertrinken_max+10);
			if ($ertrinken < $session[user][hitpoints]){
				$session[user][hitpoints]-=$ertrinken;
				output("`n`n`\$Du verlierst `^%s`\$ Punkte Lebensenergie!", $ertrinken);
			}else{
				output("`2Doch kaum hast Du ein wenig Wasser geschluckt, schon hast Du das Gefühl, es ... würde Deine ... Lungen einfrie- ... frie- ...");
				user_dies_fluss($news_text);
			}
			if ($session[user][hitpoints] > 0) addnav("Weiterschwimmen ...", $from."op=weiter&subop=".$subop."");
		}else if ($ergebnis < 0 && $schwimmen_proben >= 0){
			if ($wert <  -50){
				output("`2Du kämpfst Dich ... Meter um ... Meter ... voran ... aber ... es ist so ... kalt ...");
				user_dies_fluss($news_text);
			}else if ($wert >= -50 && $wert < 0){
				output("`2Du kannst Dich nur mit Mühe vor dem Absinken bewahren und musst ständig eiskaltes Wasser schlucken ...");
				$gold_malus=e_rand(0,2);
				if ($gold_malus != 0 && $session[user][gold] > 150){
					$gold_loss=round($session[user][gold]* $gold_malus * 0.1);
					output("`n`nIn größter Panik befreist Du Dich von `^%s`2 Goldstücken, um etwas mehr Auftrieb zu bekommen!", $gold_loss);
					$session[user][gold]-=$gold_loss;
				}
				$ertrinken=e_rand(2,$ertrinken_max+20);
				if ($ertrinken < $session[user][hitpoints]){
					$session[user][hitpoints]-=$ertrinken;
					output("`n`n`\$Du verlierst `^%s`\$ Punkte Lebensenergie!", $ertrinken);
				}else{
					output("`2`n`nDas Wasser scheint Deine Lungen einzufrie- ... frie- ...");
					user_dies_fluss($news_text);
				}
				if ($session[user][hitpoints] > 0) addnav("Weiterschwimmen ...", $from."op=weiter&subop=".$subop."");
			}else if ($wert >= 0 && $schwimmen_proben < $schwimmen_grenze){
				output("`2Du kommst nicht zurück aufs Eis und schwimmst weiter - mit ruhigen Zügen, die Dich gut voranbringen. Dennoch zehrt die Kälte an Dir ...");
				$ertrinken=e_rand(2,$ertrinken_max);
				if ($ertrinken < $session[user][hitpoints]){
					$session[user][hitpoints]-=$ertrinken;
					output("`n`n`\$Du verlierst `^%s`\$ Punkte Lebensenergie!", $ertrinken);
				}else{
					output("`2`n`nSie zehrt ... an ... Dir ...");
					user_dies_fluss($news_text);
				}
				set_module_pref("schwimmen", $schwimmen_proben+1);
				if ($session[user][hitpoints] > 0) addnav("Weiterschwimmen ...", $from."op=weiter&subop=".$subop."");
			}else if ($wert >= 0 && $schwimmen_proben == $schwimmen_grenze){
				output("`2Endlich hast Du das andere Ufer erreicht. Erschöpft und frierend, aber immerhin.");
				$bonus=e_rand(-2,2);
				if ($bonus > 0){
					output("`2 Und es war sogar eine Abkürzung! Dafür erhältst Du `^%s`2 %s.", $bonus, ($bonus==1?"zusätzlichen Waldkampf":"zusätzliche Waldkämpfe"));
					$session[user][turns]+=$bonus;
				}
				output("`n`n`@Du erhältst `^%s`@ Erfahrungspunkte!", round($session['user']['experience']*0.06));
        		$session['user']['experience']=round($session['user']['experience']*1.06);
				set_module_pref("fluss_gehabt", 1);
				$session[user][specialinc]="";
			}
			}
		}
	break;
	case "abbruch":
		$malus=e_rand(1,4);
		if ($malus > $session[user][turns]) $malus=$session[user][turns];
		output("`2Du handelst so, wie es auch Deine Mutter von Dir erwartet hätte. Zum Glück bist Du ihr keine Rechenschaft mehr darüber schuldig, wann Du nach Hause zurückkehrst ... denn erst nach einem langen Umweg erreichst Du endlich eine Brücke.`n`n`\$Du verlierst `^%s`\$ %s!", $malus, ($malus==1?"Waldkampf":"Waldkämpfe"));
		$session[user][turns]-=$malus;
		set_module_pref("fluss_gehabt", 1);
		$session[user][specialinc]="";
	break;
}}}
function fluss_run(){
}
?>
