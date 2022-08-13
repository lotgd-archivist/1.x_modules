<?php
/*
Letzte �nderung am 21.03.2005 von Michael Jandke

Basisversion zu einem f�higkeitsabh�ngigen Ereignis
Benutzte F�higkeiten:	Bogen
						Schleichen

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

To Do:	- Begrenzung der Anzahl der Schleichversuche? 5?
		- noch einen Treffermodofikator f�r die unterschiedlichen Tiere einf�hren? (z.B. Fuchs ist klein -> schwerer zu treffen)?
		- mit Itemsystem kompatibel machen? Items statt Gold als Belohnung?
		- Ablieferm�glichkeit des Fleisches f�r Hungermod?
*/

function jagd_getmoduleinfo(){
	$info = array(
		"name"=>"Die Jagd",
		"version"=>"1.0",
		"author"=>"Michael Jandke",
		"category"=>"Fertigkeiten - Wald",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("fertigkeiten"=>"1.0|von Oliver Wellinghoff und Michael Jandke"),
	);
	return $info;
}

function jagd_install(){
	module_addeventhook("forest", "return 100;");
	return true;
}

function jagd_uninstall(){
	return true;
}

function jagd_dohook($hookname,$args){
	return $args;
}

function jagd_runevent($type){
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:jagd";
	require_once("lib/fert.php");
	
	$tiere = array(	1=>array("name"=>'Reh',"artikel"=>'das',"pronomen"=>'es',"wertmod"=>1),	// Verdopplung im Array f�r unterschiedliche Wahrscheinlichkeiten
					array("name"=>'Reh',"artikel"=>'das',"pronomen"=>'es',"wertmod"=>1),
					array("name"=>'Wildschwein',"artikel"=>'das',"pronomen"=>'es',"wertmod"=>1),
					array("name"=>'Wildschwein',"artikel"=>'das',"pronomen"=>'es',"wertmod"=>1),
					array("name"=>'Fuchs',"artikel"=>'der',"pronomen"=>'er',"wertmod"=>1.2),	// Treffermodifikator hinzuf�gen?
					array("name"=>'Fuchs',"artikel"=>'der',"pronomen"=>'er',"wertmod"=>1.2),
					array("name"=>'Hirsch',"artikel"=>'der',"pronomen"=>'er',"wertmod"=>1.5),
	);
	if (get_module_pref("schl�ssel")==0) {
		$schl�ssel = e_rand(1,count($tiere));			// zuf�llige Auswahl eines der vorhandenen Tiere
		set_module_pref("schl�ssel", $schl�ssel);
	}
	$tier = $tiere[get_module_pref("schl�ssel")];		// ... und Zuweisung in $tier

	$op=httpget('op');
	switch($op) {
	case "":
	case "search":
		output("`n`2Ein pl�tzliches Knacken im Unterholz macht dich aufmerksam!`0");
		addnav("Gehe aufmerksam weiter", $from."op=weiter");
		set_module_pref("entfernung", 25);
		set_module_pref("schl�ssel", 0);
		break;
	case "weiter":
		$entfernung = get_module_pref("entfernung");
		output("`n`2Vorsichtig schleichst du voran, als pl�tzlich in einiger Entfernung `^ein %s`2 sichtbar wird. Friedlich steht %s auf einer kleinen Lichtung. ", $tier['name'], $tier['pronomen']);
		if ($tier['name']=="Hirsch") output("Solch einen prachtvollen Hirsch sieht man nicht oft! ");
		output("Pr�fend streckst du deinen Zeigefinger in die Luft, der Wind steht g�nstig. ");
		output("Dann sch�tzt du die Entfernung, es sind ungef�hr `^%s Meter`2. Was willst du tun?`n", ($entfernung+e_rand(31,39)));
		addnav("Schiessen", $from."op=bogen");
		output("`n`n<a href=\"".$from."op=bogen\">Einen Pfeil aus deinem K�cher nehmen und einen Schuss wagen.</a>`n", true);
		addnav("", $from."op=bogen");
		addnav("Anschleichen", $from."op=schleichen");
		output("`n<a href=\"".$from."op=schleichen\">Versuche vorsichtig dich n�her heranzuschleichen.</a>`n", true);
		addnav("", $from."op=schleichen");
		addnav("Verlassen", $from."op=verlassen");
		output("`n<a href=\"".$from."op=verlassen\">Lasse das arme Tier in Ruhe.</a>`n", true);
		addnav("", $from."op=verlassen");
		break;
	case "bogen":
		output("`n`2Langsam und nahezu ger�uschlos nimmst du einen Pfeil aus deinem K�cher, legst ihn ein, spannst deinen Bogen und zielst.`n");
		$entfernung = get_module_pref("entfernung");
		$getroffen = false;
		$bogen = get_fertigkeit("bogen");
		$probebogen = probe($bogen,-$entfernung); //$bogen-e_rand(0,100)-$entfernung;		// Entfernung ist Malus! - je weiter weg, desto schlechtere Chance zu treffen
//		output("`nDEBUG: W�rfelergebnis: %s`n", $probebogen['probe_wert']);
		if ($probebogen['ergebnis']=="kritischer erfolg") {	// Kritischen Erfolg auswerten...
			output("`n`^`bDie G�tter der Jagd lenken deinen Pfeil!`b`n");
			output("`n`^`bDu triffst!`b`2`n`nPerfekter Treffer, das Fell kann zu einem Spitzenpreis verkauft werden.`n");
			$wert = round($session['user']['level']*90*$tier['wertmod']*4);
			if ($tier['name']=="Hirsch") output("`2Das sch�ne Geweih dieses Hirsches wird noch etwas zus�tzliches Gold bringen!`n");
			$session['user']['gold']+=$wert;
			output("`n`2Das Fell ");
			if ($tier['name']=="Hirsch") output("und das Geweih bringen ");
			else output("bringt ");
			output("dir `^%s`2 Gold!`n`0", $wert);
			debuglog("jagte erfolgreich und bekam $wert Gold.");
			$session['user']['specialinc'] = "";
		}elseif($probebogen['ergebnis']=="kritischer misserfolg") {	// Kritischen Misserfolg auswerten...
			output("`n`2Kurz bevor du den Pfeil loslassen willst, sp�rst du ein eigenartiges Vibrieren in dem Bogen. Mit einem mal reisst die Sehne und schl�gt dir ins Gesicht, der Pfeil zerbricht und f�llt zu Boden. Als du dich von der �berraschung erholt hast, ist %s %s nirgends mehr zu sehen.`n", $tier['artikel'], $tier['name']);
			output("`n`b`\$Das war wohl Schicksal!`b`n");
			$hploss = round($session['user']['maxhitpoints']*0.06);
			output("`n`2Du verlierst einige Lebenspunkte!`n");
			$session['user']['hitpoints']-=$hploss;
			if ($session['user']['hitpoints']<1) $session['user']['hitpoints'] = 1;
			$session['user']['specialinc'] = "";
		}else{			//... ansonsten normale Bogenprobe
			$probe = $probebogen['wert'];
			if ($probe<-50) {
				output("`n`\$`bDu triffst nicht!`b`2`n`nDu h�ttest dich beinahe mit dem Pfeil selbst verletzt. Hattest du schon jemals einen Bogen in der Hand?`n");
			}elseif ($probe>=-50 && $probe<-25) {
				output("`n`\$`bDu triffst nicht!`b`2`n`nDu schiesst weit vorbei, der Pfeil bohrt sich in einen Baum und %s %s flieht.`n", $tier['artikel'], $tier['name']);
			}elseif ($probe>=-25 && $probe<0) {
				output("`n`^`bDu triffst!`b`2`n`nAber was f�r ein schlechter Treffer. %s %s ist nur leicht verwundet und flieht in den Wald!`n", ucfirst($tier['artikel']), $tier['name']);
			}elseif ($probe>=0 && $probe<25) {
				output("`n`^`bDu triffst!`b`2`n`n%s %s ist erlegt, aber das Fell ist durch die ung�nstige Platzierung deines Pfeiles fast nichts wert.`n", ucfirst($tier['artikel']), $tier['name']);
				$wert = round($session['user']['level']*$probe*$tier['wertmod']);
				$getroffen = true;
			}elseif ($probe>=25 && $probe<50) {
				output("`n`^`bDu triffst!`b`2`n`nGuter Treffer, zwar hast du nicht perfekt getroffen, aber das Fell wird einen h�bschen Preis bringen.`n");
				$wert = round($session['user']['level']*$probe*$tier['wertmod']*2);
				$getroffen = true;	
			}elseif ($probe>=50) {
				output("`n`^`bDu triffst!`b`2`n`nPerfekter Treffer, das Fell kann zu einem Spitzenpreis verkauft werden.`n");
				$wert = round($session['user']['level']*$probe*$tier['wertmod']*4);
				$getroffen = true;
			}	
			if ($getroffen==true) {
				if ($tier['name']=="Hirsch") output("`2Das sch�ne Geweih dieses Hirsches wird noch etwas zus�tzliches Gold bringen!`n");
				$session['user']['gold']+=$wert;
				output("`n`2Das Fell ");
				if ($tier['name']=="Hirsch") output("und das Geweih bringen ");
				else output("bringt ");
				output("dir `^%s`2 Gold!`n", $wert);
				debuglog("jagte erfolgreich und bekam $wert Gold.");
			}	
			output_notl("`0");
			$session['user']['specialinc']="";
		}
		break;
	case "schleichen":		// Begrenzung der Anzahl der Schleichversuche?
		output("`n`2Du duckst dich und versuchst mit vorsichtigen Schritten einige Meter n�her heranzukommen.`n");
		$entfernung = get_module_pref("entfernung");
		$schleichen = get_fertigkeit("schleichen");
		$probeschleichen = probe($schleichen,$entfernung);	// Entfernung hier als Bonus! - je n�her, desto schwieriger das Schleichen	
		if ($probeschleichen['ergebnis']=="kritischer erfolg") {	// Kritischen Erfolg auswerten...
			output("`n`b`^Die G�tter der Jagd meinen es gut mit dir!`b`n");
			output("`n`2Durch irgendetwas scheint %s %s abgelenkt zu sein und du kommst so nah heran wie es nur irgend m�glich ist. Diese einmalige Chance mu�t du nutzen!`n", $tier['artikel'], $tier['name']);
			set_module_pref("entfernung",-25);
			addnav("Schiessen", $from."op=bogen");
			output("`n`n<a href=\"".$from."op=bogen\">Nimm einen Pfeil aus deinem K�cher und nutze diese einmalige Chance.</a>`n", true);
			addnav("", $from."op=bogen");
		}elseif($probeschleichen['ergebnis']=="kritischer misserfolg") {	// Kritischen Misserfolg auswerten...
			output("`n`@Du hast das Tier aufgescheucht!`n`n`2Du bist noch keine zwei Meter vorangekommen, als sich %s %s pl�tzlich aufmerksam in deine Richtung dreht. Wie erstarrt bleibst du stehen und stellst fest das sich der Wind gedreht zu haben scheint, denn das Tier hat dich gewittert und fl�chtet nun schnell zwischen den B�umen hindurch in das dichte Unterholz.`n", $tier['artikel'], $tier['name']);
			output("`n`b`\$Das war wohl Schicksal!`b`n");
			$session['user']['specialinc'] = "";
		}else{			//... ansonsten normale Schleichprobe
			$probe = $probeschleichen['wert'];
			if ($probe>25) {
				$entfernung -=25;
				set_module_pref("entfernung", $entfernung);
				output("`n`@Du kommst n�her!`n`n`2Ruhig steht %s %s im Wald und hat dich nicht bemerkt. Deine Entfernung betr�gt jetzt nur noch ungef�hr `^".($entfernung+e_rand(34,41))." Meter`2.`n", $tier['artikel'], $tier['name']);
				addnav("Schiessen", $from."op=bogen");
				output("`n`n<a href=\"".$from."op=bogen\">Einen Pfeil aus deinem K�cher nehmen und einen Schuss wagen.</a>`n", true);
				addnav("", $from."op=bogen");
				if ($entfernung== -25) {
					output("`n`@Du kannst dich nicht noch n�her anschleichen!`n");
				}else{
					addnav("Anschleichen", $from."op=schleichen");
					output("`n<a href=\"".$from."op=schleichen\">Versuche vorsichtig dich noch etwas n�her heranzuschleichen.</a>`n", true);
					addnav("", $from."op=schleichen");
				}
				addnav("Verlassen", $from."op=verlassen");
				output("`n<a href=\"".$from."op=verlassen\">Lasse das arme Tier in Ruhe.</a>`n", true);
				addnav("", $from."op=verlassen");
			}elseif ($probe>= -25 && $probe<= 25) {
				output("`n`@Du bist nicht n�her gekommen!`n`n`2Nach wenigen Metern verursachts du ein leises Gr�usch, was %s %s aber bemerkt und alarmiert den Kopf hebt. Nach ein paar Momenten regungslosen Verharrens bewegt %s sich ein paar Meter von dir weg, so da� die Entfernung ungef�hr gleich bleibt. Es sind immer noch ca. `^".($entfernung+e_rand(31,39))." Meter`2.`n", $tier['artikel'], $tier['name'], $tier['pronomen']);
				addnav("Schiessen", $from."op=bogen");
				output("`n`n<a href=\"".$from."op=bogen\">Einen Pfeil aus deinem K�cher nehmen und einen Schuss wagen.</a>`n", true);
				addnav("", $from."op=bogen");
				addnav("Anchleichen", $from."op=schleichen");
				output("`n<a href=\"".$from."op=schleichen\">Versuche noch einmal dich n�her heranzuschleichen.</a>`n", true);
				addnav("", $from."op=schleichen");
				addnav("Verlassen", $from."op=verlassen");
				output("`n<a href=\"".$from."op=verlassen\">Lasse das arme Tier in Ruhe.</a>`n", true);
				addnav("", $from."op=verlassen");
				
			}elseif ($probe< -25) {
				output("`n`@Du hast das Tier aufgescheucht!`n`n`2Unter deinem Fu� knackt ein Ast und das Ger�usch hallt wie ein Donnerschlag durch den Wald. Sofort hebt %s %s den Kopf und blickt in deine Richtung. Regungslos stehst du da, aber es n�tzt nichts mehr, %s %s fl�chtet in den dichteren Wald und nach ein paar Sekunden ist %s nicht mehr zu sehen.`n", $tier['artikel'], $tier['name'], $tier['artikel'], $tier['name'], $tier['pronomen']);
				$session['user']['specialinc']="";
			}
		}
		output_notl("`0");
		break;
	case "verlassen":
		output("`n`2Du beobachtest das Tier noch einen Moment und nachdem %s %s wieder in den Wald verschwunden ist, gehst du zur�ck auf die Suche nach wirklich gef�hrlichen Monstern.`n`0", $tier['artikel'], $tier['name']);
		if (is_module_active('alignment')) align("1");
		$session['user']['specialinc']="";
		break;
	}
}

function jagd_run(){
}
?>
