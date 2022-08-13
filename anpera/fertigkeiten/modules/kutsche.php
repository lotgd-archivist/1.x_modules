<?php

//translator ready
//addnews ready

/*
Die Kutsche (f�r logtd ab 0.98)

Wetterabh�ngiges Ereignis f�r das Fertigkeitensystem

Benutzte Fertigkeit: Reiten

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
require_once("lib/commentary.php");

function kutsche_getmoduleinfo(){
	$info = array(
		"name"=>"Die Kutsche",
		"version"=>"1.0",
		"author"=>"Oliver Wellinghoff",
		"category"=>"Fertigkeiten - Wald",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("fertigkeiten"=>"1.0|Fertigkeitensystem von Oliver Wellinghoff und Michael Jandke",
			"weather"=>"2.0|By Talisman, part of the core download"),
		);
	return $info;
}

function kutsche_install(){
	module_addeventhook("forest", "return 100;");
	module_addhook("newday");
	return true;
}

function kutsche_uninstall(){
	return true;
}

function kutsche_dohook($hookname,$args){
	switch($hookname){
		case "newday":
			global $session;
			set_module_pref("kutsche_gehabt", 0, "kutsche");
		break;
	}
	return $args;
}

function user_dies_kutsche($news_text){
	global $session;
	output("`\$`n`nDu bist tot!");
	output("`n`nDu verlierst `^%s`\$ Erfahrungspunkte und all Dein Gold!", round($session[user][experience]*0.03));
	output("`n`nDu kannst morgen weiterspielen.");
	addnav("T�gliche News","news.php");
	addnews("`\$%s `4kam bei dem Versuch zu Tode, die durchgegangenen Pferde einer Reisekutsche%s unter Kontrolle zu bringen.", $session['user']['name'], $news_text);
	$session[user][alive]=false;
	$session[user][hitpoints]=0;
	$session[user][gold]=0;
	$session[user][experience]=round($session[user][experience]*0.97);	
	$session[user][specialinc]="";	
}
	
function kutsche_runevent($type){
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:kutsche";
	
	//Man trifft die Leute h�chstens einmal am Tag
	if (get_module_pref("kutsche_gehabt") == 1){
		output("`2Du kommst wieder an der Stelle vorbei, wo die Pferde mit der Reisekutsche durchgegangen waren.");
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
		set_module_pref("reiten", -1);
		output("`2Ein anstrengender Tag, ein Tag voller Arbeit. Du befindest Dich am Rande eines langen, ausgetrampelten Waldweges im Schutze der B�ume und verschnaufst von den bisherigen Strapazen. ");
		if ($type == 1){
			output("`2Zumindest das Wetter macht Dir heute keinen Strich durch die Rechnung ... `n`nPl�tzlich h�rst Du das Poltern einer Kutsche, das Poltern einer `iunkontrollierten`i Kutsche! Jemand schreit verzweifelt um Hilfe! Du musst Dich schnell entscheiden, in wenigen Sekunden ist die Kutsche schon bei Dir.");
			
			addnav("Aufspringen", $from."op=weiter&subop=1");
			output("`n`n<a href=\"".$from."op=weiter&subop=1\">Ein echter Held springt auf den Kutschbock und bringt die Pferde zur�ck unter Kontrolle - gesagt, getan!</a>", true);
			addnav("", $from."op=weiter&subop=1");
		}else if ($type == 2){
			output("`2Zu allem �berfluss hat es jetzt auch noch angefangen zu regnen, zwar nicht stark, aber best�ndig ... `n`nPl�tzlich h�rst Du das Poltern einer Kutsche, das Poltern einer `iunkontrollierten`i Kutsche! Jemand schreit verzweifelt um Hilfe! Du musst Dich schnell entscheiden, in wenigen Sekunden ist die Kutsche schon bei Dir.");
			
			addnav("Aufspringen", $from."op=weiter&subop=2");
			output("`n`n<a href=\"".$from."op=weiter&subop=2\">Ein echter Held springt auf den Kutschbock und bringt die Pferde zur�ck unter Kontrolle - gesagt, getan!</a>", true);
			addnav("", $from."op=weiter&subop=2");
		}else if ($type == 3){
			output("`2Besonders der starke Nebel hat Dir sehr zugesetzt, kommt man doch nur schleppend voran, wenn man keinen Unfall riskieren will.`n`nPl�tzlich h�rst Du das Poltern einer Kutsche, das Poltern einer `iunkontrollierten`i Kutsche! Jemand schreit verzweifelt um Hilfe! Du musst Dich schnell entscheiden, in wenigen Sekunden ist die Kutsche schon bei Dir.");
			
			addnav("Aufspringen", $from."op=weiter&subop=3");
			output("`n`n<a href=\"".$from."op=weiter&subop=3\">Ein echter Held springt auf den Kutschbock und bringt die Pferde zur�ck unter Kontrolle - gesagt, getan!</a>", true);
			addnav("", $from."op=weiter&subop=3");
		}else if ($type == 4){
			output("`2Besonders der �berfrorene Boden hat Dir sehr zugesetzt, kommt man doch nur schleppend voran, wenn man keinen Unfall riskieren will.`n`nPl�tzlich h�rst Du das Poltern einer Kutsche, das Poltern einer `iunkontrollierten`i Kutsche! Jemand schreit verzweifelt um Hilfe! Du musst Dich schnell entscheiden, in wenigen Sekunden ist die Kutsche schon bei Dir.");
			
			addnav("Aufspringen", $from."op=weiter&subop=4");
			output("`n`n<a href=\"".$from."op=weiter&subop=4\">Ein echter Held springt auf den Kutschbock und bringt die Pferde zur�ck unter Kontrolle - gesagt, getan!</a>", true);
			addnav("", $from."op=weiter&subop=4");
		}else if ($type == 5){
			output("`2Doch so recht will Dir auch das nicht gelingen, denn starker Wind peitscht Dir den Regen ins Gesicht.`n`nPl�tzlich h�rst Du das Poltern einer Kutsche, das Poltern einer `iunkontrollierten`i Kutsche! Jemand schreit verzweifelt um Hilfe! Du musst Dich schnell entscheiden, in wenigen Sekunden ist die Kutsche schon bei Dir.");
			
			addnav("Aufspringen", $from."op=weiter&subop=5");
			output("`n`n<a href=\"".$from."op=weiter&subop=5\">Ein echter Held springt auf den Kutschbock und bringt die Pferde zur�ck unter Kontrolle - gesagt, getan!</a>", true);
			addnav("", $from."op=weiter&subop=5");
		}else if ($type == 6){
			output("`2Doch so recht will Dir auch das nicht gelingen, denn �ber Dir haben sich dunkle Wolken zusammengezogen. Du hoffst bei allen G�ttern, dass jetzt blo� kein Blitz in Deiner N�he einschlagen wird - bei jedem Donnern zuckst Du zusammen.`n`nPl�tzlich h�rst Du das Poltern einer Kutsche, das Poltern einer `iunkontrollierten`i Kutsche! Jemand schreit verzweifelt um Hilfe! Du musst Dich schnell entscheiden, in wenigen Sekunden ist die Kutsche schon bei Dir.");
			
			addnav("Aufspringen", $from."op=weiter&subop=6");
			output("`n`n<a href=\"".$from."op=weiter&subop=6\">Ein echter Held springt auf den Kutschbock und bringt die Pferde zur�ck unter Kontrolle - gesagt, getan!</a>", true);
			addnav("", $from."op=weiter&subop=6");
		}
		addnav("Nichts tun", $from."op=abbruch");
		output("`n`n<a href=\"".$from."op=abbruch\">Also, wenn selbst ein ausgebildeter Kutscher das nicht ... tut mir leid.</a>", true);
		addnav("", $from."op=abbruch");
		addnav("Nichts tun und lachen", $from."op=abbruch2");
		output("`n`n<a href=\"".$from."op=abbruch2\">Das nenne ich mal Abwechslung! Ich kann mich k�stlich �ber das Leid der Fahrg�ste am�sieren ...</a>`n", true);
		addnav("", $from."op=abbruch2");
	break;
	
	case "weiter":
		$subop=httpget('subop');	
		$reiten=get_fertigkeit(reiten);
				
		//Wie viele Proben hat man schon geschaftt?
		$reiten_proben=get_module_pref("reiten");
				
		//Wettermodifikatoren | $reitengrenze gibt an, wie viele Proben man mindestens ablegen muss
		switch($subop) {
			case "1": $exp_bonus=0.025; $mod_wetter=0; $reiten_grenze=2; $news_text=translate_inline(""); break;
			case "2": $exp_bonus=0.035; $mod_wetter=-10; $reiten_grenze=2; $news_text=translate_inline(" bei str�mendem Regen"); break;
			case "3": $exp_bonus=0.07;  $mod_wetter=-30; $reiten_grenze=3; $news_text=translate_inline(" in dichtem Nebel"); break;
			case "4": $exp_bonus=0.06;  $mod_wetter=-25; $reiten_grenze=3; $news_text=translate_inline(" bei Schnee und Gl�tte"); break;
			case "5": $exp_bonus=0.05;  $mod_wetter=-20; $reiten_grenze=2; $news_text=translate_inline(" w�hrend eines schweren Regengusses"); break;
			case "6": $exp_bonus=0.06; $mod_wetter=-25; $reiten_grenze=2; $news_text=translate_inline(" w�hrend eines tosenden Gewitters"); break;
		}
	
		//Wer die Pferde schon ein wenig geb�ndigt hat, bekommt einen Bonus
		if ($reiten_proben > 0) $mod_versuche=$reiten_proben*3;
		
		//Der effektive Gesamtmodifikator				
		$mod = $mod_wetter + $mod_versuche;
									
		//Die Probe
		$probe=probe($reiten, $mod);
		$wert=$probe[wert];
							
		if ($reiten_proben == -1){
			output("`2Das ist ein Wort! Du rennst in dieselbe Richtung, in die die Kutsche f�hrt und versuchst aufzuspringen, als sie auf Deiner H�he ist ... Geschafft!`nSofort greifst Du nach den Z�geln des Kutschers - er scheint entweder tot oder bewusstlos zu sein. Aber darum musst Du Dich sp�ter k�mmern ...`n`n");
			set_module_pref("reiten", 0);
			$reiten_proben=0;
			//Der gute Wille z�hlt schon mal ...
			if (is_module_active('alignment')) align("1");
		}
		
		//output("`b`@DEBUG-INFO:`b`n Proben-Ergebnis: %s (Sofortiger Tod ab < -50)`n `nWettermodifikator: %s`nWieviele Proben vor dieser hier bereits geschafft: %s/%s`n`n`2", $wert, $mod, get_module_pref("reiten"), $reiten_grenze);
		
		if ($wert <  -50){
			output("`\$Gerade hast Du noch das Gef�hl, die Pferde in den Griff zu bekommen, als Du--- Die Kutsche �berschl�gt sich und als Du im Totenreich wieder erwachst, kannst Du Dir nicht erkl�ren, wie Du das nun wieder `igeschafft`i hast ...");
			user_dies_kutsche($news_text);
		}else if ($wert >= -50 && $wert < 0){
			$meldung=e_rand(1,9);
			
			if ($meldung == 1 || $meldung == 2) 						output("`2Die Pferde sind kaum zu b�ndigen, sie m�ssen besessen sein!");
			else if ($meldung == 3 || $meldung == 4 || $meldung == 5)	output("`2Du verlierst die Kontrolle - `boh nein, Du verlierst die Kontrolle!`b");
			else if ($meldung == 6 || $meldung == 7) 					output("`2Verzweifelt zerrst Du an den Z�geln - ohne jedoch genau zu wissen, was Du da eigentlich machst! Und so bemerkst Du auch den Ast zu sp�t, der Dich vom Kutschbock schleudert.");
			else if ($meldung == 8 || $meldung == 9) 					output("`2Du bekommst Panik, denn die Pferde sind einfach nicht zu b�ndigen! Wild hin- und herschlingernd jagst Du die Kutsche durch Gestr�pp und Dornen - oder jagt sie Dich?");
					
			$hp_malus=e_rand(1,3);
			if ($meldung == 6 || $meldung == 7){
					//Hinabgeschleudert
					$hp_loss=round($session[user][hitpoints] * $hp_malus * 0.2);
					output("`n`n`2F�r einen Moment wei�t Du nicht, wo oben und unten ist. Du f�hlst Dich, als w�rde man von allen Seiten auf Dich einpr�geln.");
					if ($hp_loss < $session[user][hitpoints]-1){
						//-> Schwerverletzt
						output("`2`n`nAls Du aus Deiner Ohnmacht wieder erwachst, ist von der Kutsche nichts mehr zu sehen. Du rappelst Dich schwerverletzt auf und versuchst, Deine Ausr�stung wiederzufinden ...");
						output("`n`n`@Du erh�ltst `^%s`@ Erfahrungspunkte!", round($session['user']['experience']*0.02));
       					output("`n`n`\$Du verlierst `^%s`\$ Lebenspunkte!", $hp_loss);
						$session[user][hitpoints]-=$hp_loss;
						$session['user']['experience']=round($session['user']['experience']*1.02);
						set_module_pref("kutsche_gehabt", 1);
						$session[user][specialinc]="";	
					}else{
						//-> Tot
						user_dies_kutsche($news_text);
					}
				}else if ($meldung == 8 || $meldung == 9){
					//Gestr�pp
					if ($session[user][hitpoints] > 2){
						$hp_loss=round($session[user][hitpoints] * $hp_malus * 0.05);
						if ($hp_loss > $session[user][hitpoints]) $hp_loss=$session[user][hitpoints]-2;
						output("`n`n`\$Du verlierst `^%s`\$ Lebenspunkte!", $hp_loss);
						$session[user][hitpoints]-=$hp_loss;
						addnav("Weiter!", $from."op=weiter&subop=".$subop."");
						addnav("Abspringen ...", $from."op=abbruch3");
					}
				}else{
					addnav("Weiter!", $from."op=weiter&subop=".$subop."");
					addnav("Abspringen ...", $from."op=abbruch3");
				}
		}else if ($wert >= 0 && $reiten_proben < $reiten_grenze){
			$meldung=e_rand(1,4);
			if ($meldung == 1)		output("`2Es gelingt Dir, die Pferde auf gerader Bahn zu halten ...");
			else if ($meldung == 2)	output("`2Es gelingt Dir, die Pferde abzubremsen ...`b");
			else if ($meldung == 3)	output("`2Du bringst die Kutsche sicher um eine enge Kurve!");
			else if ($meldung == 4)	output("`2Du hast das Gef�hl, die Pferde gut unter Kontrolle zu bekommen.");
			set_module_pref("reiten", $reiten_proben+1);
			addnav("Weiter!", $from."op=weiter&subop=".$subop."");
			addnav("Abspringen ...", $from."op=abbruch3");
		}else if ($wert >= 0 && $reiten_proben == $reiten_grenze){
			output("`2Puh, geschafft! Die Pferde sind zur Ruhe gekommen und die Kutsche dank Deines K�nnens zum endg�ltigen Stillstand. Erleichtert steigen die Fahrg�ste aus, um Dir zu danken, aber der Schrecken steht ihnen deutlich ins Gesicht geschrieben.`n");
			$exp_plus=round($session['user']['experience']*$exp_bonus);
			output("`n`n`@Du erh�ltst `^%s`@ Erfahrungspunkte f�r diese gekonnte Fahrt!", $exp_plus);
			$session['user']['experience']+=$exp_plus;
			
			addnav("Dich verabschieden", $from."op=ende1");
			output("`n`n<a href=\"".$from."op=ende1\">Ich helfe ihnen beim Aussteigen und empfehle mich dann.</a>", true);
			addnav("", $from."op=ende1");
			addnav("Ausrauben", $from."op=ende2");
			output("`n`n<a href=\"".$from."op=ende2\">Eine ganze Reisekutsche - nur f�r mich allein! Ich raube ihnen alle Wertsachen.</a>", true);
			addnav("", $from."op=ende2");
			addnav("Ausrauben und t�ten", $from."op=ende3");
			output("`n`n<a href=\"".$from."op=ende3\">Eine ganze Reisekutsche - nur f�r mich allein! Ich raube ihnen alle Wertsachen und bringe die Leute dann um. Zeugen kann ich nicht gebrauchen.</a>", true);
			addnav("", $from."op=ende3");
		}
	break;
	case "abbruch":
		$malus=e_rand(1,3);
		if ($malus > $session[user][turns]) $malus=$session[user][turns];
			output("`2Als die Reisekutsche an Dir vor�berpoltert, erkennst Du, dass der Kutscher offenbar ohnm�chtig ist. Vielleicht h�tten sie doch Deine Hilfe gebraucht. Die panischen Hilferufe der Fahrg�ste sprechen �brigens auch daf�r ...`n`n`\$Weil Du eine ganze Weile von Gewissensbissen geplagt wirst, verlierst Du `^%s`\$ %s!", $malus, ($malus==1?"Waldkampf":"Waldk�mpfe"));
			$session[user][turns]-=$malus;
			set_module_pref("kutsche_gehabt", 1);
			if (is_module_active('alignment')) align("-1");
			$session[user][specialinc]="";
	break;
	case "abbruch2":
		$malus=e_rand(1,3);
		if ($malus > $session[user][turns]) $malus=$session[user][turns];
			output("`2Als die Reisekutsche an Dir vor�berpoltert, erkennst Du, dass der Kutscher offenbar ohnm�chtig ist. Haha, einfach zu k�stlich! Du klatschst fr�hlich in die H�nde, als Du die panischen Hilferufe der Fahrg�ste h�rst ... Mann, Mann, eigentlich sollte man sie f�r diese Darbietung bezahlen.`n`n`@Derart am�siert erh�ltst Du einen zus�tzlichen Waldkampf!");
			$session[user][turns]+=1;
			set_module_pref("kutsche_gehabt", 1);
			if (is_module_active('alignment')) align("-3");
			$session[user][specialinc]="";
	break;
	case "abbruch3":
		//Absprung
			$hp_malus=e_rand(1,2);
			$hp_loss=round($session[user][hitpoints] * $hp_malus * 0.07);
			output("`n`n`@Wenn Du schon die Leben der Fahrg�ste nicht retten kannst, dann doch zumindest Dein eigenes. Du springst ab ...");
		//-> Verletzt
		if ($hp_loss < $session[user][hitpoints]-1){
			output("`@ verletzt Dich aber dabei. Was soll's, Hauptsache, es ist vorbei!");
			output("`n`n`\$Du verlierst `^%s`\$ Lebenspunkte!", $hp_loss);
			output("`n`n`@Du erh�ltst `^%s`@ Erfahrungspunkte!", round($session['user']['experience']*0.015));
       		$session[user][hitpoints]-=$hp_loss;
			$session['user']['experience']=round($session['user']['experience']*1.015);
			set_module_pref("kutsche_gehabt", 1);
			$session[user][specialinc]="";	
		}else{
			//-> Tot
			output(" und zwar direkt in Dein Grab. Was steht dieser Baum auch im Weg rum ...");
			user_dies_kutsche($news_text);
		}
	break;
	case "ende1":
			$fahrgaeste=e_rand(2,6);
			$bonus=e_rand(1,4);
				if ($bonus == 1){
					$gold=e_rand(500,4500);
					output("`@Unter den Fahrg�sten befindet sich ein wohlhabender, trollischer Kaufmann, der Dich reichlich belohnt!`n`nDu erh�ltst `^%s`@ Goldst�cke!", $gold);
					$session['user']['gold']+=$gold;
				}else if ($bonus == 2){
					output("`@Unter den Fahrg�sten befindet sich eine Gr�fin der Zwerge, die Dich reichlich belohnt!`n`nDu erh�ltst `^zwei`@ Edelsteine - `bbeim allm�chtigen Zrarek!`b");
					$session[user][gems]+=2;
				}else if ($bonus == 3){
					$charme=e_rand(2,3);
					output("`@Unter den Fahrg�sten befindet sich ein bekannter Barde aus Chrizzak, der beschlie�t, in Liedern von Dir zu berichten!`n`nDein Charme erh�ht sich!");
					$body = sprintf_translate("/me `\@wird von einem bekannten, echsischen Barden aufs h�chste verehrt! Es geht um die heldenhafte Rettung von %s Fahrg�sten einer Reisekutsche!", $fahrgaeste);
					injectcommentary("village", "","$body", $schema=false);
				}else if ($bonus == 4) output("`@Jetzt wartest Du schon fast eine Viertelstunde und so richtig bedankt hat sich eigentlich niemand. Um genau zu sein: Du bekommst sogar Vorw�rfe f�r Deinen ruppigen Fahrstil zu h�ren ... Undank ist der Welten Lohn.");
			
			addnews("`@%s `2hat die `^%s`2 Fahrg�ste einer Reisekutsche vor dem sicheren Tod bewahrt!", $session['user']['name'], $fahrgaeste);
			$bonus=2*$fahrgaeste;
			if (is_module_active('alignment')) align($bonus);
			set_module_pref("kutsche_gehabt", 1);
			$session[user][specialinc]="";	
	break;
	case "ende2":
			$gold=e_rand(500,4500);
			$gems=e_rand(0,2);
			
			$session['user']['gold']+=$gold;
			$session['user']['gems']+=$gems;
			
			output("`@Du setzt Dein fiesestes Grinsen auf und verlangst von den Fahrg�sten in einer unmissverst�ndlichen Art und Weise, Dich reichlich zu 'bezahlen'.`n`nInsgesamt erbeutest Du `^%s`@ Goldst�cke", $gold);
			if ($gems > 0) output("und `^%s`@ %s!", $gems, ($gems==1?"Edelstein":"Edelsteine"));
			else output("!");
			$fahrgaeste=e_rand(2,6);
			addnews("`\$%s`4 hat eine Reisekutsche mit `^%s`4 Fahrg�sten ausgeraubt!", $session['user']['name'], $fahrgaeste);
			//Barde dabei? Simpel bestimmt:
			if ($gems == 2) injectcommentary("village", "","/me `\$wird von einem bekannten, echsischen Barden aufs �belste denunziert! Es geht um den Raub�berfall auf eine Reisekutsche ...", $schema=false);
			set_module_pref("kutsche_gehabt", 1);
			if (is_module_active('alignment')) align("-5");
			if (is_module_active('pdvdiebstahl')){
				$erwischt=get_module_pref("erwischt","pdvdiebstahl");
				$erwischtneu=$erwischt+1;
				set_module_pref("erwischt", $erwischtneu, "pdvdiebstahl");
			}
			$session[user][specialinc]="";
	break;
	case "ende3":
			$fahrgaeste=e_rand(2,6);
			$gold=e_rand(500,4500);
			$gems=e_rand(0,2);
			
			$session['user']['gold']+=$gold;
			$session['user']['gems']+=$gems;
			
			output("`@Du setzt Dein fiesestes Grinsen auf und verlangst von den Fahrg�sten in einer unmissverst�ndlichen Art und Weise, Dich reichlich zu 'bezahlen'. Danach genie�t Du dir aufkeimende Hoffnung in %s Gesichtern und - schreitest zur blutigen Tat.`n`nInsgesamt erbeutest Du `^%s`@ Goldst�cke", $fahrgaeste, $gold);
			if ($gems > 0) output("und `^%s`@ %s!", $gems, ($gems==1?"Edelstein":"Edelsteine"));
			else output("!");
			addnews("`^%s`4 Fahrg�ste wurden erschlagen und ausgeraubt neben einer Reisekutsche aufgefunden ... Vom T�ter fehlt jede Spur.", $fahrgaeste, true);
			set_module_pref("kutsche_gehabt", 1);
			$malus=-3*$fahrgaeste;
			if (is_module_active('alignment')) align($malus);
			if (is_module_active('pdvdiebstahl')){
				$erwischt=get_module_pref("erwischt","pdvdiebstahl");
				$erwischtneu=$erwischt+1;
				set_module_pref("erwischt", $erwischtneu, "pdvdiebstahl");
			}
			$session[user][specialinc]="";	
	break;
}}}
function kutsche_run(){
}
?>
