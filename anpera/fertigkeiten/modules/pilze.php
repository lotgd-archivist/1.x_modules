<?php
/*
Letzte Änderung am 28.08.2005 von Michael Jandke

Idee zu einem fähigkeitsabhängigen Ereignis
Benutzte Fähigkeit:	Kochen und Pflanzenkunde (in diesem Fall die Pflanzenkunde ;-))

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

function pilze_getmoduleinfo(){
	$info = array(
		"name"=>"Pilze, Kräuter und Beeren",
		"version"=>"1.0",
		"author"=>"Michael Jandke",
		"category"=>"Fertigkeiten - Wald",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("fertigkeiten"=>"1.0|von Oliver Wellinghoff und Michael Jandke"),
		"prefs"=>array(
			"alleitems"=>"Alle Pflanzen die der Spieler hat:,viewonly|a:0:{}",// das ist ein etwas komischer Weg ein leeres Array zu definieren, aber mir ist nichts besseres eingefallen...
		),
	);
	return $info;
}

function pilze_install(){
	module_addeventhook("forest", "return 100;");
	module_addhook("potion");
	module_addhook("pdvnavsonstiges");
	module_addhook("dragonkill");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	return true;
}

function pilze_uninstall(){
	return true;
}

function pilze_dohook($hookname, $args){
	require_once("modules/pilze/pilze_hooks.php");
	$args = func_get_args();
	return call_user_func_array("pilze_dohook_private",$args);
}

function pilze_runevent($type){
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:pilze";
	require_once("lib/fert.php");
	
	$op=httpget('op');
	switch($op) {
	case "":
	case "search":
		set_module_pref("item",0);	// Array für das Item das man gerade findet, wenn man es mitnimmt wird es in "alleitems" gespeichert!
		// Änderung, man muß bei Erweiterung der Arten bzw. Kategorien auch hier die e_rand-Werte ändern! Auch in der Probe aufpassen!
		$art = e_rand(1,3);		// zufällige Art bestimmen
		$kat = e_rand(1,3);		// zufällige Kategorie bestimmen
		$buff = $kat + ($art-1)*3;	// funktioniert nur wenn es bei allen Arten auch 3 Kategorien gibt!
		$item = array("art"=>$art, "kat"=>$kat, "buff"=>$buff, "identifiziert"=>0);
		//debug($item);
		set_module_pref("item",createstring($item));
		switch ($art) {
		case 1:
			output("`n`2Du gehst einen Hang im Wald hinunter, als etwas deine Aufmerksamkeit erregt. Vorsichtigen Schrittes gehst Du auf eine moosbedeckte Erhebung im Waldboden zu und entfernst das Moos. Darunter ist ein Pilz!`n");
			break;
		case 2:
			output("`n`2Auf einer sonnenbeschienenen Lichtung fällt dein Blick auf eine ungewöhnliche Pflanze. Es scheint ein besonderes Kraut zu sein!`n");
			break;
		case 3:
			output("`n`2Am Wegesrand steht ein Busch, der deine Aufmerksamkeit erregt. Er ist über und über mit Beeren behangen!`n");
			break;
		}
		addnav(array("%s", $art==1?"Pilze":($art==2?"Kräuter":"Beeren")));
		output("`n`n<a href=\"".$from."op=ident\">%s und versuchen zu identifizieren.</a>`n", $art==1?"Den Pilz einsammeln":($art==2?"Das Kraut abschneiden":"Die Beeren pflücken"), true);
		addnav("", $from."op=ident");
		addnav("Identifizieren", $from."op=ident");
		output("`n<a href=\"".$from."op=ohneident\">%s ohne Identifizierung einpacken.</a>`n", $art==1?"Den Pilz":($art==2?"Das Kraut":"Die Beeren"), true);
		output("`7(Nicht identifizierte Objekte können nicht benutzt werden.)");
		addnav("", $from."op=ohneident");
		addnav("Gleich so einpacken", $from."op=ohneident");
		output("`n`n<a href=\"".$from."op=verlassen\">Die Finger von Sachen lassen, von denen man keine Ahnung hat.</a>`n", true);
		addnav("", $from."op=verlassen");
		addnav("Verlassen", $from."op=verlassen");
		break;
	case "ident":
		require_once("modules/pilze/pilze_lib.php");
	
		$subop = httpget('subop');
		$item = createarray(get_module_pref("item"));
		if ($subop=="") {
			// abhängig vom Fertigkeitswert in Pflanzenkunde eine kleine einleitende Bemerkung
			$kochen = get_fertigkeit("kochen");
			if ($kochen<25) {
				output("`n`2Du hast keine Ahnung was für eine Pflanze du da vor dir hast, aber wenn du raten müßtest...`n");
			}elseif ($kochen>=25 && $kochen<50) {
				output("`n`2Etwas unsicher und zweifelnd kommst du zu einer Erkenntnis.`n");
			}elseif ($kochen>=50 && $kochen<75) {
				output("`n`2Ziemlich sicher und ohne lange zu überlegen weißt du, was es ist.`n");
			}elseif ($kochen>=75) {
				output("`n`2Du kennst dich mittlerweile in der Pflanzenwelt gut genug aus um dir absolut sicher zu sein und sofort zu wissen um was es sich handelt.`n");
			}
			//Probe, bestimmt wieviele Kategorien man danebenliegt...
			// Mods(?): WA +5, DR +10, TS -5, MO -10
			switch ($session['user']['specialty']) {
			case "MO": $mod = -10; break;
			case "TS": $mod = -5; break;
			case "WA": $mod = 5; break;
			case "DR": $mod = 10; break;
			default: $mod = 0;
			}
			//debug($mod);
			$probe = probe($kochen,$mod,0,0);	// kritische Treffer? halte ich für nicht nötig bzw unglaubwürdig
			//debug($probe['wert']);
			if ($probe['wert']<-30) {	// grob verhauen
				$idkat = e_rand(1,3);	// vollkommen beliebig erkannt, wird glaube auch als raten bezeichnet ;-)
			}elseif ($probe['wert']>=-30 && $probe['wert']<10) {	// unsicher
				switch ($item['kat']) {
				case 1:
					if (e_rand(1,100)>50) $idkat = 1;
					else $idkat = 3;
					break;									// gut als gut oder giftig - neutral,giftig?
				case 2: $idkat = e_rand(1,2); break;		// neutral als neutral oder gut
				case 3:										// giftig als giftig oder gut
					if (e_rand(1,100)>50) $idkat = 3;
					else $idkat = 1;
					break;
				}
			}elseif ($probe['wert']>=10) {	// geschafft
				$idkat = $item['kat'];						// erkannt als das was es ist
			}
			$item['identifiziert'] = 1;
			$item['idkat'] = $idkat;		// Item identifiziert als
			$item['idname'] = pilze_randname($item['art'],$item['idkat']);
			switch ($item['art']) {
			case 1:
				output("`n`@Ja, dieser Pilz ist ein `^%s`@. ", $item['idname']);
				if ($idkat==1) output("Diese speziellen Pilze sollen einem wundersame Kräfte verleihen.");
				if ($idkat==2) output("Nach gekonnter Zubereitung soll er ganz gut schmecken.");
				if ($idkat==3) output("Dieser Pilz ist einfach nur häßlich und sehr giftig.");
				break;
			case 2:
				output("`n`@Ja, dieses Kraut heißt `^%s`@. ", $item['idname']);
				if ($idkat==1) output("Ihm werden heilende Kräfte nachgesagt!");
				if ($idkat==2) output("Es soll verschiedenen Suppen erst die richtige Würze geben.");
				if ($idkat==3) output("Ein sehr giftiges Kraut, von jedwedem Verzehr ist abzuraten.");
				break;
			case 3:
				output("`n`@Ja, das ist eine `^%s`@.", $item['idname']);
				if ($idkat==1) output("Es ist bekannt, das sie für kurze Zeit die körperlicher Fähigkeiten stärken.");
				if ($idkat==2) output("Diese Beeren machen angeblich Flecke, die nicht wieder aus der Kleidung gehen.");
				if ($idkat==3) output("Sollte man von diesen Beeren essen, so überkommt einen eine unnatürliche Schwäche.");
				break;
			}
			//debug($item);
			set_module_pref("item",createstring($item));
			addnav("Mitnehmen", $from."op=ident&subop=mit");
			addnav("Wegschmeissen", $from."op=ident&subop=weg");
		}elseif ($subop=="mit") {
			output("`n`2Du packst %s ein und gehst wieder deiner Wege.`0", $item['art']==1?"den Pilz":($item['art']==2?"das Kraut":"die Beeren"));
			$alleitems = createarray(get_module_pref("alleitems"));
			//debug($alleitems);
			array_push($alleitems,$item);
			//debug($alleitems);
			set_module_pref("alleitems",createstring($alleitems));
			set_module_pref("item",0);
			$session['user']['specialinc'] = "";
		}elseif ($subop=="weg") {
			output("`n`2Du schmeisst %s weg und läufst seufzend weiter durch den Wald. `6Man, hab ich immer ein Glück...`0", $item['art']==1?"den Pilz":($item['art']==2?"das Kraut":"die Beeren"));
			$session['user']['specialinc'] = "";
		}
		break;
	case "ohneident":	// Achtung! Text noch an Standort der Identifizierung/Verkauf anpassen!!!
		$item = createarray(get_module_pref("item"));
		output("`n`2Du packst %s ein und überlegst, bei wem du eine fachgerechte Identifizierung bekommen kannst. ", $item['art']==1?"den Pilz":($item['art']==2?"das Kraut":"die Beeren"));
		//output("Dir fällt erst einmal nur der Heiler ein und bei dem Gedanken an diesen alten Halsabschneider kannst du schon fühlen, wie dein Goldbeutel bedeutend leichter wird.");
		output("Du hast gehört, das im Küchenhaus der Echsen ein alter Mann mit hervorragenden Kenntnissen in der Pflanzenkunde anzutreffen ist. Vielleicht solltest du dort mal vorbeischauen.");
		$alleitems = createarray(get_module_pref("alleitems"));
		//debug($alleitems);
		array_push($alleitems,$item);
		//debug($alleitems);
		set_module_pref("alleitems",createstring($alleitems));
		$session['user']['specialinc'] = "";
		break;
	case "verlassen":
		output("`n`2Du stapfst weiter durch den Wald und murmelst:`6 War ganz sicher nichts besonderes...`0");
		$session['user']['specialinc'] = "";
		break;
	}
}

function pilze_run(){
	global $session;
	$from = "runmodule.php?module=pilze&";
	$op = httpget('op');
	switch ($op) {
	case "identandsell":
		page_header("Phsela");
		require_once("modules/pilze/pilze_lib.php");
		
		output("`5`b`cDas Küchenhaus der Echsen`c`b");
		$subop = httpget('subop');
		$alleitems = createarray(get_module_pref("alleitems"));
		//debug($alleitems);
		$pident = $session['user']['level']*100;	// das soll teuer sein, damit man eine billigere Alternative bieten kann... 100-200?
		$pkat1 = 75*$session['user']['level'];
		$pkat2 = 50*$session['user']['level'];
		if ($subop=="") {
			if ($alleitems) {
				output("`&`nPhsela bewegt sich nicht, sondern dreht nur kurz seine Augen in deine Richtung. Dann, als scheine er schon zu wissen warum du da bist, sagt er leise:`n");
				output("`n\"`6Wenn ich etwas für dich identifizieren soll, kostet dich das `\$%s`6 Goldstücke. Wenn du etwas verkaufen willst, schau auf die Preistafel dort.`&\"`n", $pident);
				if ($session['user']['gold']<$pident) $hatgold = false;
				else $hatgold = true;
				output_notl("`n`n<table border=0 cellpadding=2 cellspacing=1 align='center' bgcolor='#999999'>", true);
				rawoutput("<colgroup><col width=220><col width=120><col width=120></colgroup>");
				$a = translate_inline("`bName`b");
				$s = translate_inline("`bStatus`b");
				$ac = translate_inline("`bAktion`b");
				$id = translate_inline("Identifizieren");
				$sell = translate_inline("Verkaufen");
				rawoutput("<tr align='center' class='trhead'><td>");
				output_notl($a);
				rawoutput("</td><td>");
				output_notl($s);
				rawoutput("</td><td>");
				output_notl($ac);
				rawoutput("</td></tr>");
				for ($i=0;$i<(sizeof($alleitems));$i++) {
					if ($alleitems[$i]['identifiziert']==0) {
						rawoutput("<tr align='center' class='".($i%2==1?"trlight":"trdark")."'><td>");
						output_notl("`&%s</td><td>`4Unidentifiziert</td><td>", $alleitems[$i]['art']==1?"Unbekannter Pilz":($alleitems[$i]['art']==2?"Unbekanntes Kraut":"Unbekannte Beeren"), true);
						if ($hatgold) {
							output_notl("<a href=\"".$from."op=identandsell&subop=ident&key=".$i."\">[ $id ]</a>", true);
							addnav("",$from."op=identandsell&subop=ident&key=".$i);
						}else output_notl("`7[ $id ]");
						rawoutput("</td></tr>");
					}else {
						switch ($alleitems[$i]['idkat']) {	// Items farblich markieren, je nach Wirkung (Kategorie)
						case 1: $cc = "`@"; break;	// gut
						case 2: $cc = "`7"; break;	// neutral
						case 3: $cc = "`4"; break;	// schlecht
						}
						rawoutput("<tr align='center' class='".($i%2==1?"trlight":"trdark")."'><td>");
						output_notl("%s%s</td><td>`2Identifiziert</td><td>",$cc, $alleitems[$i]['idname'], true);
						output_notl("<a href=\"".$from."op=identandsell&subop=sell&key=".$i."\">[ $sell ]</a>", true);
						addnav("",$from."op=identandsell&subop=sell&key=".$i);
						rawoutput("</td></tr>");
					}
				}
				rawoutput("</table>");
				output("`n`n`&Gleich hinter Phsela siehst du ein Schild mit den Ankaufspreisen für verschiedene Kräuter, Gewürze, etc.`n");
				output("`n`^`c`bPreise:`b`c`n`0");
				output_notl("<table border=0 cellpadding=2 cellspacing=1 align='center' bgcolor='#999999'>", true);
				output_notl("<tr align='center' class='trdark'><td>`@Besondere Pflanzen</td><td>`^$pkat1 Goldstücke</td></tr>", true);
				output_notl("<tr align='center' class='trlight'><td>`7Normale Pflanzen</td><td>`^$pkat2 Goldstücke</td></tr>", true);
				output_notl("<tr align='center' class='trdark'><td>`4Minderwertige Pflanzen</td><td>`^0 Goldstücke</td></tr>", true);
				rawoutput("</table>");
			}else {
				output("`n`&Phsela regt sich überhaupt nicht, als du auf ihn zugehst. Scheinbar erweckt nichts an dir sein Interesse.");
			}
		}elseif ($subop=="ident") {
			$key = httpget('key');
			output("`n`&Phsela wartet bis du das Gold auf die Theke gelegt hast, zählt es kurz nach und läßt es danach in einer Schublade verschwinden. Dann streckt er seine Hand aus und läßt sich %s geben.`n", $alleitems[$key]['art']==1?"den Pilz":($alleitems[$key]['art']==2?"das Kraut":"die Beeren"));
			output("`n\"`6Nun. dann zeig mal her, was du da Schönes mitgebracht hast.`&\" ");
			$session['user']['gold']-=$pident;
			$alleitems[$key]['idkat'] = $alleitems[$key]['kat'];
			$alleitems[$key]['idname'] = pilze_randname($alleitems[$key]['art'],$alleitems[$key]['idkat']);
			$alleitems[$key]['identifiziert'] = 1;
			set_module_pref("alleitems", createstring($alleitems));
			switch ($alleitems[$key]['art']) {
			case 1: 
				output("Er dreht den Pilz ein wenig zwischen den Fingern. \"`6Es ist ein `^%s`6! ", $alleitems[$key]['idname']);
				if ($alleitems[$key]['idkat']==1) output("Diese speziellen Pilze sollen einem wundersame Kräfte verleihen.");
				if ($alleitems[$key]['idkat']==2) output("Nach gekonnter Zubereitung soll er ganz gut schmecken.");
				if ($alleitems[$key]['idkat']==3) output("Dieser Pilz ist einfach nur häßlich und giftig.");
				break;
			case 2: 
				output("Er zupft etwas von dem Kraut, zerreibt es zwischen seinen Fingern und riecht dann daran. \"`6Dieses Kraut heißt `^%s`6! ", $alleitems[$key]['idname']);
				if ($alleitems[$key]['idkat']==1) output("Ihm werden heilende Kräfte nachgesagt!");
				if ($alleitems[$key]['idkat']==2) output("Es soll verschiedenen Suppen erst die richtige Würze geben.");
				if ($alleitems[$key]['idkat']==3) output("Ein sehr giftiges Kraut, von jedwedem Verzehr ist abzuraten.");
				break;
			case 3: 
				output("Er begutachtet die Beeren eine Weile und hält eine gegen das Licht. \"`6Das ist eine `^%s`6! ", $alleitems[$key]['idname']);
				if ($alleitems[$key]['idkat']==1) output("Es ist bekannt, das sie für kurze Zeit die körperlicher Fähigkeiten stärken.");
				if ($alleitems[$key]['idkat']==2) output("Diese Beeren machen angeblich Flecke, die nicht wieder aus der Kleidung gehen.");
				if ($alleitems[$key]['idkat']==3) output("Sollte man von diesen Beeren essen, so überkommt einen eine unnatürliche Schwäche.");
				break;
			}
			output("`&\"");
			addnav("Zurück zu Phsela", $from."op=identandsell");
		}elseif ($subop=="sell") {
			$key = httpget('key');
			output("`n`&\"`6Du willst etwas verkaufen? Gut, dann zeig mal her.`&\" Du greifst in deine Taschen und legst ihm %s in seine faltige Hand.`n", $alleitems[$key]['art']==1?"den Pilz":($alleitems[$key]['art']==2?"das Kraut":"die Beeren"));
			output_notl("`n\"`6");
			switch ($alleitems[$key]['idkat']) {
			case 1:
				if ($alleitems[$key]['kat']==2) {
					output("Dafür gebe ich dir ... Halt. Willst du mich reinlegen? %s heißt nicht %s, %s kennt man unter dem Namen %s! Dafür bekommst du nur %s Gold.`n", $alleitems[$key]['art']==1?"Dieser Pilz":($alleitems[$key]['art']==2?"Dieses Kraut":"Diese Beere"), $alleitems[$key]['idname'], $alleitems[$key]['art']==1?"diesen Pilz":($alleitems[$key]['art']==2?"dieses Kraut":"diese Beeren"), pilze_randname($alleitems[$key]['art'],2), $pkat2);
					$session['user']['gold']+=$pkat2;
				}elseif ($alleitems[$key]['kat']==3) {
					output("Dafür gebe ich dir ... Halt. Willst du mich reinlegen? %s heißt nicht %s, %s kennt man unter dem Namen %s! Dafür bekommst du kein Gold.`n", $alleitems[$key]['art']==1?"Dieser Pilz":($alleitems[$key]['art']==2?"Dieses Kraut":"Diese Beere"), $alleitems[$key]['idname'], $alleitems[$key]['art']==1?"diesen Pilz":($alleitems[$key]['art']==2?"dieses Kraut":"diese Beeren"), pilze_randname($alleitems[$key]['art'],3));
				}else {
					output("Gut, gut, dafür bekommst du %s Gold.", $pkat1);
					$session['user']['gold']+=$pkat1;
				}
				output_notl("`&\"");
				break;
			case 2:	// als neutral erkannt, gibt kein Gold bei kat=3, normales gold bei kat=1,2
				if ($alleitems[$key]['kat']==3) {
					output("Dafür gebe ich dir ... Halt. Willst du mich reinlegen? %s heißt nicht %s, %s kennt man unter dem Namen %s! Dafür bekommst du kein Gold.`n", $alleitems[$key]['art']==1?"Dieser Pilz":($alleitems[$key]['art']==2?"Dieses Kraut":"Diese Beere"), $alleitems[$key]['idname'], $alleitems[$key]['art']==1?"diesen Pilz":($alleitems[$key]['art']==2?"dieses Kraut":"diese Beeren"), pilze_randname($alleitems[$key]['art'],3));
				}else {
					output("Gut, gut, dafür bekommst du %s Gold.`&\" ", $pkat2);
					$session['user']['gold']+=$pkat2;
					if (($alleitems[$key]['kat']==1)) {
						output("Als Phsela %s nimmt, glaubst du ein kurzes Lächeln auf seinem Gesicht zu sehen. ", $alleitems[$key]['art']==1?"den Pilz":($alleitems[$key]['art']==2?"das Kraut":"die Beeren"));
						output("Irgendwie hast du das Gefühl über den Tisch gezogen worden zu sein, aber wie willst du es beweisen, wenn du dich doch in der Pflanzenkunde nicht gut genug auskennst?");
					}
				}
				break;
			case 3:	// ist es als schlecht erkannt, bekommt man nichts, egal was es wirklich ist
				output("Für solch minderwertige Ware kann ich dir leider nichts geben.`&\" ");
				if ($alleitems[$key]['idkat']!=$alleitems[$key]['kat']) {
					output("Als Phsela %s nimmt, glaubst du ein kurzes Lächeln auf seinem Gesicht zu sehen. ", $alleitems[$key]['art']==1?"den Pilz":($alleitems[$key]['art']==2?"das Kraut":"die Beeren"));
					output("Irgendwie hast du das Gefühl über den Tisch gezogen worden zu sein, aber wie willst du es beweisen, wenn du dich doch in der Pflanzenkunde nicht gut genug auskennst?");
				}
				break;
			}
			array_splice($alleitems, $key , 1);
			set_module_pref("alleitems",createstring($alleitems));
			addnav("Zurück zu Phsela", $from."op=identandsell");
		}
		addnav("Zurück zum Küchenhaus", $from."op=khaus");
		page_footer();
		break;
	case "khaus":
		page_header("Das Küchenhaus der Echsen");
		output("`c`b`5Das Küchenhaus der Echsen`b`c`&");
		$subop = httpget('subop');
		if ($subop=="") {
			output("`nAngezogen von den wunderbaren Gerüchen die sich von hier über den ganzen Platz der Völker ausbreiten, betritts du das Küchenhaus der Echsen. ");
			output("Hier im vorderen Bereich befinden sich Tische und Sitzgelegenheiten an denen immer jemand anzutreffen ist, der gerade das wunderbare Essen der Köchinnen und Köche genießt. ");
			output("Über eine Theke hinweg, die die Küche vom Restaurantbereich abteilt, kannst du einen Blick auf die in der großen Küche Arbeitenden werfen. ");
			output("An der Theke kannst du dir etwas zu essen bestellen oder eine private Feierlichkeit anmelden.`n");
			output("`nGanz am Ende der Theke steht Phsela, ein alter Echsenmann, der für die Organisation zuständig ist. ");
			output("Früher war er der Küchenchef, jetzt stellt er, aufgrund seines hohen Alters, nur noch sein großes Wissen zur Verfügung und regelt den Ankauf des Küchenhauses.");
			addnav("Essen");
			addnav("Bestelle Essen", $from."op=khaus&subop=bestellung");
			addnav("Privates Essen anmelden", $from."op=khaus&subop=privat");
			addnav("Sonstiges");
			addnav("Phsela ansprechen", $from."op=identandsell");
			addnav("Ausgang");
			addnav("Verlassen","runmodule.php?module=wettkampf");
		}elseif ($subop=="bestellung") {
			output("`n`7(Noch) `^Nicht implementierte Funktion.`n");
			addnav("Zurück", $from."op=khaus");
		}elseif ($subop=="privat") {
			output("`n`7(Noch) `^Nicht implementierte Funktion.`n");
			addnav("Zurück", $from."op=khaus");
		}
		page_footer();
		break;
	case "healpoison":
		page_header();
		$subop = httpget('subop');
		$preis = $session['user']['level']*50;
		output("`#`b`cDie Hütte des Heilers`c`b");
		if ($subop=="") {
			output("`n`6Du siehst schlecht aus, %s. Hast was giftiges gegessen, hmm? Kotz mir hier ja nichts voll!`n", $session['user']['sex']?"meine Kleine":"mein Kleiner");
			output("`n`3Der Heiler kramt zwischen seinen Phiolen herum. Dann holt er ein kleines Fläschen mit einer trüben Flüssigkeit hervor.`n");
			output("`n`6Das hier könnte dir helfen, aber es kostet dich trotzdem `b`\$%s`b `6Goldstücke.`n", $preis);
			if ($session['user']['gold']<$preis) {
				output("`n`3Als er sieht wie du verzweifelt Dein Gold zählst, aber es anscheinend nicht reicht, schmeißt er dich ohne ein Wort raus.`n");
			}else {
				addnav("Heilen",$from."op=healpoison&subop=heal");
			}
		}elseif ($subop=="heal") {
			output("`n`3Du schluckst die Flüssigkeit hinunter ohne lange Fragen zu stellen und tatsächlich, nach kurzer Zeit geht es dir schon viel besser. Du willst dich beim Heiler bedanken, aber der schiebt dich einfach zur Tür hinaus.");
			$session['user']['gold']-=$preis;
			strip_buff("pilzschlecht");
			strip_buff("krautschlecht");
			// auch noch Lebenspunkte heilen? 50%?
			$heal = round(($session['user']['maxhitpoints']-$session['user']['hitpoints'])*0.5);
			$session['user']['hitpoints']+=$heal;
		}
		addnav("Zurück in den Wald","forest.php");
		page_footer();
		break;
	}
}
?>
