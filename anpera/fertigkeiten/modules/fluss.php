<?php

//translator ready
//addnews ready

/*
Der Fluss (f�r logtd ab 0.98)

Wetterabh�ngiges Ereignis f�r das Fertigkeitensystem

Benutzte Fertigkeit: Schwimmen

Erdacht und umgesetzt von Oliver Wellinghoff.

*********************************************************
*	Diese Datei sollte aus fertigkeiten.zip stammen.	*
*														*
*	Achtung: Wer diese Dateien benutzt, verpflichtet	*
*	sich, alle Module, die er f�r das Fertigkeiten-		*
*	system entwickelt frei und �ffentlich zug�nglich	*
*	zu machen! Jegliche Ver�nderungen an diesen Dateien *
*	m�ssen ebenfalls ver�ffentlicht werden!				*
*														*
*	N�heres siehe: dokumentation.txt					*
*														*
*	Wir entwickeln f�r Euch - Ihr entwickelt f�r uns.	*
*														*
*	Jegliche Ver�nderungen an diesen Dateien 			*
*	m�ssen ebenfalls ver�ffentlicht werden - so sieht 	*
*	es die Lizenz vor, unter der LOTGD ver�ffentlicht	*
*	wurde!												*
*														*
*	Zuwiderhandlungen k�nnen empfindliche Strafen		*
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
	addnav("T�gliche News","news.php");
	addnews("`\$%s`4 ertrank bei dem Versuch, %s%s.", $session['user']['name'], ($session[user][gold]<=500?"":"mit etwa `^".$session[user][gold]."`4 Goldst�cken im Gep�ck "), $news_text);
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
	
	//Man verl�uft sich nur einmal am Tag
	if (get_module_pref("fluss_gehabt") == 1){
		output("`2Du �berquerst eine steinerne Bogenbr�cke und gelangst sicher auf die andere Seite eines Flusses.");
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
		output("`2Eine ganze Stunde schon befindest Du Dich auf der Suche nach einem Weg zur�ck in das Gebiet, von dem Du Dir mehr versprichst als einen langsamen Tod aus Langeweile ...");
		if ($type == 1){
			output("`2`n`nAls Du kurz inneh�ltst, um Dir an diesem sonnigen Tag den Schwei� von der Stirn zu wischen, h�rst Du es in der N�he pl�tschern. Du schiebst ein wenig Gestr�pp zur Seite und erkennst einen ruhigen, breiten Flusslauf. Ein Wink der G�tter! Wenn Du den Fluss hier durchschwimmst, gewinnst Du viel Zeit zur�ck.");
			
			addnav("Schwimmen", $from."op=weiter&subop=1");
			output("`n`n<a href=\"".$from."op=weiter&subop=1\">Nichts wie rein ins Wasser!</a>", true);
			addnav("", $from."op=weiter&subop=1");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Nein danke, es wird irgendwo eine Br�cke geben.</a>`n", true);
			addnav("", $from."op=abbruch");
		}else if ($type == 2){
			output("`2`n`nZu allem �berfluss hat es jetzt auch noch angefangen zu regnen! Aber apropos Fluss, da rauscht doch etwas ... Du schiebst ein wenig Gestr�pp zur Seite und erkennst einen stark angeschwollenen, str�menden Flusslauf. Ein Wink der G�tter? Wenn Du den Fluss hier durchschwimmst, gewinnst Du viel Zeit zur�ck.");
			
			addnav("Schwimmen", $from."op=weiter&subop=2");
			output("`n`n<a href=\"".$from."op=weiter&subop=2\">Ich bin sowieso schon nass, also rein ins Wasser ...</a>", true);
			addnav("", $from."op=weiter&subop=2");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Nein danke, es wird irgendwo eine Br�cke geben.</a>`n", true);
			addnav("", $from."op=abbruch");
		}else if ($type == 3){
			output("`2`n`nTja, und der starke Nebel macht es Dir nicht gerade einfacher ... Moment! Da rauscht doch etwas ... Du schiebst ein wenig Gestr�pp zur Seite und findest Dich am Ufer eines Flusslaufs wieder - oder ist es ein See? Schwer zu sagen bei diesen Sichtverh�ltnissen. Auf jeden Fall k�nntest Du eine Menge Zeit sparen, wenn Du schwimmst, denn dies ist eindeutig die richtige Richtung. Immerhin scheint das Wasser recht ruhig zu sein ...");
			
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
			if ($var <= 5)					$eis=translate_inline("Du fast in das Eis einbrichst! So gerade eben gelingt es Dir, Deinen Fu� zur�ckzuziehen");
			if ($var >  5  && $var <= 10)	$eis=translate_inline("Du leicht in das Eis einsackst ..");
			if ($var >  10 && $var <= 20)	$eis=translate_inline("Du ein lautes Knacken vernimmst");
			if ($var >  20  && $var <= 30)	$eis=translate_inline("Du ein leises, aber deutlich h�rbares Knacken vernimmst");
			if ($var >  30  && $var <= 40)	$eis=translate_inline("Du ein leises, kaum h�rbares Knacken vernimmst");
			if ($var >  40)					$eis=translate_inline("Du Dir davon �berzeugt bist, sicheren, festen Boden unter den F��en zu haben");
			
			output("`2`n`nDann erreichst Du einen zugefrorenen Fluss. Du gehst bis an den Rand der schneebedeckten Eisplatte und setzt einen Fu� darauf, woraufhin %s.`n`nWenn Du den Fluss hier �berquerst, hast Du viel Zeit gewonnen. Sonst k�nnte es noch eine ganze Weile dauern, bis Du eine andere Stelle findest. Was m�chtest Du tun?", $eis);
			
			addnav("�berqueren", $from."op=weiter&subop=4");
			output("`n`n<a href=\"".$from."op=weiter&subop=4\">Ich riskiere den Weg �ber das Eis.</a>", true);
			addnav("", $from."op=weiter&subop=4");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Mein Leben ist mir lieb, ich suche woanders weiter.</a>`n", true);
			addnav("", $from."op=abbruch");
		}else if ($type == 5){
			output("`2`n`nZu allem �berfluss hat es jetzt auch noch angefangen zu regnen - und starker Wind bl�st Dir den Regen direkt ins Gesicht! Aber apropos Fluss, da rauscht doch etwas ... Du lehnst Dich gegen den Wind auf, schiebst ein wenig Gestr�pp zur Seite und erkennst einen stark angeschwollenen, str�menden Flusslauf. Ein Wink der G�tter? Wenn Du den Fluss hier durchschwimmst, gewinnst Du viel Zeit zur�ck.");
			
			addnav("Schwimmen", $from."op=weiter&subop=5");
			output("`n`n<a href=\"".$from."op=weiter&subop=5\">Ich bin sowieso schon nass ... Raus aus dem Wind, rein ins Wasser!</a>", true);
			addnav("", $from."op=weiter&subop=5");
			addnav("Weitersuchen", $from."op=abbruch");
			output("`n`n<a href=\"".$from."op=abbruch\">Bei diesem Wetter auch noch schwimmen? Lieber nicht.</a>`n", true);
			addnav("", $from."op=abbruch");
		}else if ($type == 6){
			output("`2`n`nObwohl man diesen Tag nicht gerade als langweilig bezeichnen kann. Die Wolken ziehen rei�end vor�ber und immer wieder wird die Dunkelheit von einem glei�enden Blitz durchbrochen. Du h�rst ein markersch�tterndes Donnergrollen und hoffst abermals, dass der n�chste Blitz -- Aber was ist das ... da rauscht doch etwas ... Du lehnst Dich gegen den peitschenden Wind auf, schiebst ein wenig Gestr�pp zur Seite und erkennst einen stark angeschwollenen, rei�enden Flusslauf. Mm ... Wenn Du den Fluss hier durchschwimmst, gewinnst Du viel Zeit zur�ck, so viel steht fest.");
			
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
		//Gold: 0.005 kg pro St�ck (unrealistisch, aber sonst gehen sie alle unter ...)
		$mod_gold = round($session[user][gold] * 0.007);
		if ($session[user][gold] < 500) $gold_text = translate_inline("bisschen");
		else if ($session[user][gold] >= 500 && $session[user][gold] < 2500) $gold_text = translate_inline("vieles");
		else $gold_text = translate_inline("in gro�en S�cken mitgeschlepptes");
		
		//Edelsteine: 0.01 kg pro St�ck
		$mod_gems = round($session[user][gems] * 0.01);
		if ($session[user][gems] < 10) $gems_text = translate_inline("paar");
		else if ($session[user][gems] >= 10 && $session[user][gems] < 30) $gems_text = translate_inline("vielen");
		else $gems_text = translate_inline("vielen, vielen");
		
		//R�stung: 0.75 kg pro Stufe
		$mod_armor = round($session[user][armordef] * 0.75);
		if ($session[user][armordef] < 5) $armor_text = translate_inline("leichte");
		else if ($session[user][armordef] >= 5 && $session[user][armordef] < 11) $armor_text = translate_inline("mittelschwere");
		else $armor_text = translate_inline("schwere");
				
		//Waffe: 0.25 kg pro Stufe
		$mod_weapon = round($session[user][weapondmg] * 0.25);
		if ($session[user][weapondmg] < 5) $weapon_text = translate_inline("leichte");
		else if ($session[user][weapondmg] >= 5 && $session[user][weapondmg] < 11) $weapon_text = translate_inline("mittelschwere");
		else $weapon_text = translate_inline("schwere");
		
		//Modifikator f�r das Gesamtgewicht
		$mod_gewicht=$mod_weapon + $mod_armor + $mod_gold + $mod_gems;
		
		//Wettermodifikatoren | $schwimmengrenze gibt an, wie viele Proben man mindestens ablegen muss
		switch($subop) {
			case "1": $mod_wetter=0; $schwimmen_grenze=1; $news_text=translate_inline("einen vollkommen ruhigen Fluss zu durchschwimmen"); break;
			case "2": $mod_wetter=-10; $schwimmen_grenze=2;	$news_text=translate_inline("bei str�mendem Regen einen Fluss zu durchschwimmen"); break;
			case "3": $mod_wetter=-5; $schwimmen_grenze=5; $news_text=translate_inline("einen Fluss im Nebel zu durschwimmen"); break;
			case "4": $mod_wetter=-35; $schwimmen_grenze=4; $news_text=translate_inline("einen zugefrorenen Fluss zu �berqueren"); break;
			case "5": $mod_wetter=-15; $schwimmen_grenze=3; $news_text=translate_inline("einen rei�enden Fluss zu durchschwimmen"); break;
			case "6": $mod_wetter=-25; $schwimmen_grenze=3; $news_text=translate_inline("w�hrend eines tosenden Gewitters einen Fluss zu durchschwimmen"); break;
		}
	
		//Berechnung der St�rke des Helden
		//10 kg Last schafft jeder Held ohne auch nur mit der Wimper zu zucken
		$strength=10 + round(($session[user][attack] + $session[user][defense] - $session[user][weapondmg] - $session[user][armordef]) / 6);
		
		//Zus�tzliche St�rke hilft - aber nur bis zu einem bestimmten Grad
		if ($strength > 20) $strength = 20;
		
		//Der effektive Gesamtmodifikator				
		$mod = $mod_wetter + $strength - $mod_gewicht;
									
		//Die Probe
		$probe=probe($schwimmen, $mod);
		$wert=$probe[wert];
							
		if ($schwimmen_proben == -1) output("`2Du �berpr�fst noch einmal, ob alles sicher sitzt, Deine %s R�stung, Dein %s Gold, Deine %s Waffe, Deine %s Edelsteine ...", $armor_text, $gold_text, $weapon_text, $gems_text);
		if ($subop != 4){
		if ($schwimmen_proben == -1){
			output("Dann gehst Du ins flache Wasser und musst schon bald anfangen zu schwimmen.`n`n");
			set_module_pref("schwimmen", 0);
			$schwimmen_proben=0;
		}
		
		//output("`b`@DEBUG-INFO:`b`n Proben-Ergebnis: %s (Sofortiger Tod ab < -50)`n Effektiver Gewichts-Modifikator: -%s (aus: Gold (-%s), Edelsteine (-%s), Waffe (-%s), R�stung (-%s))`n St�rke: +%s (aus: (10 + (Grundschaden + Grundverteidigung - Waffenschaden - R�stungsschutz) / 6) Maximum: 20),`nWettermodifikator: %s`nGesamtmodifikator f�r die Probe: %s`n Tragkraft der Eisfl�che, falls vorhanden: %s`n Wieviele Proben vor dieser hier bereits geschafft: %s/%s`n`n`2", $wert, $mod_gewicht, $mod_gold, $mod_gems, $mod_weapon, $mod_armor, $strength, $mod_wetter, $mod, get_module_pref("modifikator"), get_module_pref("schwimmen"), $schwimmen_grenze);
		
		if ($wert <  -50){
			output("`\$Bei allen G�ttern! Liegt es am Gewicht Deiner Ausr�stung? An Deiner St�rke? Oder kannst Du einfach nur nicht schwiblubblubberblub ...");
			user_dies_fluss($news_text);
		}else if ($wert >= -50 && $wert < 0){
			output("`2Du kannst Dich nur mit M�he vor dem Absinken bewahren und musst st�ndig Wasser schlucken ...");
			$gold_malus=e_rand(0,2);
			if ($gold_malus != 0 && $session[user][gold] > 150){
				$gold_loss=round($session[user][gold] * $gold_malus * 0.1);
				output("`n`nIn gr��ter Panik befreist Du Dich von `^%s`2 Goldst�cken, um etwas mehr Auftrieb zu bekommen!", $gold_loss);
				$session[user][gold]-=$gold_loss;
			}
			$ertrinken=e_rand(2,$ertrinken_max);
			if ($ertrinken < $session[user][hitpoints]){
				$session[user][hitpoints]-=$ertrinken;
				output("`n`n`\$Du verlierst `^%s`\$ Punkte Lebensenergie!", $ertrinken);
			}else{
				output("`2`n`nSchlie�lich kommst Du mit dem Ausatmen von Wasser gar nicht mehr nach ...");
				user_dies_fluss($news_text);
			}
			if ($session[user][hitpoints] > 0) addnav("Weiterschwimmen ...", $from."op=weiter&subop=".$subop."");
		}else if ($wert >= 0 && $schwimmen_proben < $schwimmen_grenze){
			output("`2Du schwimmst mit ruhigen Z�gen und kommst gut voran.");
			set_module_pref("schwimmen", $schwimmen_proben+1);
			addnav("Weiterschwimmen ...", $from."op=weiter&subop=".$subop."");
		}else if ($wert >= 0 && $schwimmen_proben == $schwimmen_grenze){
			output("`2Endlich hast Du das andere Ufer erreicht. Gl�ckwunsch!");
			$bonus=e_rand(-1,3);
			if ($bonus > 0){
				output("`2 Und es war sogar eine Abk�rzung! Daf�r erh�ltst Du `^%s`2 %s.", $bonus, ($bonus==1?"zus�tzlichen Waldkampf":"zus�tzliche Waldk�mpfe"));
				$session[user][turns]+=$bonus;
			}
			output("`n`n`@Du erh�ltst `^%s`@ Erfahrungspunkte!", round($session['user']['experience']*0.035));
       		$session['user']['experience']=round($session['user']['experience']*1.035);
			set_module_pref("fluss_gehabt", 1);
			$session[user][specialinc]="";
		}
		}else{
		if ($schwimmen_proben == -1){
			output("`2Dann gehst Du vorsichtig los und hoffst, dass das Eis Dich sicher tr�gt.`n`n");
		}
		
		//output("`b`@DEBUG-INFO:`b`n Proben-Ergebnis: %s (Sofortiger Tod ab < -50)`n Effektiver Gewichts-Modifikator: -%s (aus: Gold (-%s), Edelsteine (-%s), Waffe (-%s), R�stung (-%s))`n St�rke: +%s (aus: (10 + (Grundschaden + Grundverteidigung - Waffenschaden - R�stungsschutz) / 6) Maximum: 20),`nWettermodifikator: %s`nGesamtmodifikator f�r die Probe: %s`n Tragkraft der Eisfl�che, falls vorhanden: %s`n Wieviele Proben vor dieser hier bereits geschafft: %s/%s`n`n`2", $wert, $mod_gewicht, $mod_gold, $mod_gems, $mod_weapon, $mod_armor, $strength, $mod_wetter, $mod, get_module_pref("modifikator"), get_module_pref("schwimmen"), $schwimmen_grenze);
		
		//Wie gut h�lt das Eis?
		$eis=get_module_pref("modifikator");
		$ergebnis=$eis-$mod_gewicht;
				
		if ($ergebnis >= 0 && $schwimmen_proben == -1){
			output("`2Die G�tter waren Dir gn�dig gestimmt, Du erreichst sicher das andere Ufer.");
			$bonus=e_rand(-1,3);
			if ($bonus > 0){
				output("`2 Und es war sogar eine Abk�rzung! Daf�r erh�ltst Du `^%s`2 %s.", $bonus, ($bonus==1?"zus�tzlichen Waldkampf":"zus�tzliche Waldk�mpfe"));
				$session[user][turns]+=$bonus;
			}
			set_module_pref("fluss_gehabt", 1);
		}else if ($ergebnis < 0 && $schwimmen_proben == -1){
			output("`2Bei allen G�ttern, es bricht! Das Wasser ist eiskalt und Du drohst, jeden Moment zu erfrieren. Jetzt kannst Du nur noch auf Deine Schwimmk�nste vertrauen ...");
			set_module_pref("schwimmen", 0);
			$ertrinken=e_rand(2,$ertrinken_max+10);
			if ($ertrinken < $session[user][hitpoints]){
				$session[user][hitpoints]-=$ertrinken;
				output("`n`n`\$Du verlierst `^%s`\$ Punkte Lebensenergie!", $ertrinken);
			}else{
				output("`2Doch kaum hast Du ein wenig Wasser geschluckt, schon hast Du das Gef�hl, es ... w�rde Deine ... Lungen einfrie- ... frie- ...");
				user_dies_fluss($news_text);
			}
			if ($session[user][hitpoints] > 0) addnav("Weiterschwimmen ...", $from."op=weiter&subop=".$subop."");
		}else if ($ergebnis < 0 && $schwimmen_proben >= 0){
			if ($wert <  -50){
				output("`2Du k�mpfst Dich ... Meter um ... Meter ... voran ... aber ... es ist so ... kalt ...");
				user_dies_fluss($news_text);
			}else if ($wert >= -50 && $wert < 0){
				output("`2Du kannst Dich nur mit M�he vor dem Absinken bewahren und musst st�ndig eiskaltes Wasser schlucken ...");
				$gold_malus=e_rand(0,2);
				if ($gold_malus != 0 && $session[user][gold] > 150){
					$gold_loss=round($session[user][gold]* $gold_malus * 0.1);
					output("`n`nIn gr��ter Panik befreist Du Dich von `^%s`2 Goldst�cken, um etwas mehr Auftrieb zu bekommen!", $gold_loss);
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
				output("`2Du kommst nicht zur�ck aufs Eis und schwimmst weiter - mit ruhigen Z�gen, die Dich gut voranbringen. Dennoch zehrt die K�lte an Dir ...");
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
				output("`2Endlich hast Du das andere Ufer erreicht. Ersch�pft und frierend, aber immerhin.");
				$bonus=e_rand(-2,2);
				if ($bonus > 0){
					output("`2 Und es war sogar eine Abk�rzung! Daf�r erh�ltst Du `^%s`2 %s.", $bonus, ($bonus==1?"zus�tzlichen Waldkampf":"zus�tzliche Waldk�mpfe"));
					$session[user][turns]+=$bonus;
				}
				output("`n`n`@Du erh�ltst `^%s`@ Erfahrungspunkte!", round($session['user']['experience']*0.06));
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
		output("`2Du handelst so, wie es auch Deine Mutter von Dir erwartet h�tte. Zum Gl�ck bist Du ihr keine Rechenschaft mehr dar�ber schuldig, wann Du nach Hause zur�ckkehrst ... denn erst nach einem langen Umweg erreichst Du endlich eine Br�cke.`n`n`\$Du verlierst `^%s`\$ %s!", $malus, ($malus==1?"Waldkampf":"Waldk�mpfe"));
		$session[user][turns]-=$malus;
		set_module_pref("fluss_gehabt", 1);
		$session[user][specialinc]="";
	break;
}}}
function fluss_run(){
}
?>
