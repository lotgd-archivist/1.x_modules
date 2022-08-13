<?php

/*
Letzte Änderung am 04.04.2005 von Michael Jandke

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

>>>>> Variante mit der Auslagerung der Abfragefunktionen nach lib/fert.php <<<<< 

Basisversion für die Verwaltung und Abfrage der Fertigkeitswerte

Wichtig:
In Modulen die auf die Fertigkeitswerte zugreifen wollen ist unbedingt einzufügen:

require_once("lib/fert.php");

In dieser Library sind verschiedene Abfragefunktionen für die Fertigkeitswerte, die dort auch genauer erklärt sind.
Für die Abfrage der Werte bitte nur die dort zur Verfügung gestellten Funktionen benutzen und nur wenn unbedingt nötig
auf die module_settings bzw. module_prefs dieses Modules hier zugreifen.

Die in fert.php verfügbaren Funktionen sind:

siehe lib/fert.php

*/

function fertigkeiten_getmoduleinfo(){
	$info = array(
		"name"=>"Fertigkeiten",
		"author"=>"Oliver Wellinghoff<br>Michael Jandke",
		"version"=>"1.0",
		"category"=>"General",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"settings"=>array(
			"Fertigkeiten: Boni und Mali,title",
			"Achtung! Als Rassennamen die Namen aus den entsprechenden race-Modulen verwenden.,note",
			"Boni,note",
			"bonusbogen"=>"Rasse für Bonus Bogenschießen |Elf",
			"bonusklettern"=>"Rasse für Bonus Klettern |Dwarf",
			"bonuskochen"=>"Rasse für Bonus Kochen und Backen |Echse",
			"bonusmusik"=>"Rasse für Bonus Musik und Gesang |Vanthira",
			"bonusreiten"=>"Rasse für Bonus Reiten |Human",
			"bonusschleichen"=>"Rasse für Bonus Schleichen und Verstecken |Vampir",
			"bonusschwimmen"=>"Rasse für Bonus Schwimmen und Tauchen |Troll",
			"Mali,note",
			"malusbogen"=>"Rasse für Malus Bogenschießen |Vanthira",
			"malusklettern"=>"Rasse für Malus Klettern |Human",
			"maluskochen"=>"Rasse für Malus Kochen und Backen |Vampir",
			"malusmusik"=>"Rasse für Malus Musik und Gesang |Echse",
			"malusreiten"=>"Rasse für Malus Reiten |Dwarf",
			"malusschleichen"=>"Rasse für Malus Schleichen und Verstecken |Troll",
			"malusschwimmen"=>"Rasse für Malus Schwimmen und Tauchen |Elf",
			"Fertigkeiten: Steigerung,title",
			"steigerung"=>"Wieviele Steigerungsversuche sind pro Tag erlaubt?,range,1,5,1|3",
			"dklimit"=>"Wieviele erfolgreiche Steigerungen pro DK erlaubt?,range,5,30,1|15",
			"minvergessen"=>"Ab welchem Fertigkeitswert besteht die Chance auf \"Vergessen\"?,range,75,95,1|85",
		),
		"prefs"=>array(
			"Die Fertigkeiten,title",
			"Grundwerte,note",
			"bogen"=>"Fertigkeitswert: Bogenschießen ,range,5,95,1|5",
			"klettern"=>"Fertigkeitswert: Klettern ,range,5,95,1|5",
			"kochen"=>"Fertigkeitswert: Kochen ,range,5,95,1|5",
			"musik"=>"Fertigkeitswert: Musik und Gesang ,range,5,95,1|5",
			"reiten"=>"Fertigkeitswert: Reiten ,range,5,95,1|5",
			"schleichen"=>"Fertigkeitswert: Schleichen und Verstecken ,range,5,95,1|5",
			"schwimmen"=>"Fertigkeitswert: Schwimmen und Tauchen ,range,5,95,1|5",
			"Steigerungseinstellungen,title",
			"userdklimit"=>"Wieviele gelungene Steigerungen für diesen DK übrig? int|15",
			"usersteigerung"=>"Wieviele Steigerungsversuche für heute übrig? int|3",
		),
	);
	return $info;
}

function fertigkeiten_install(){
	module_addhook("setrace");
	module_addhook("newday");
	
	// Werte für alle vorhandenen Spieler einmal aktualisieren (damit die module_prefs auf den default-Wert gesetzt werden)
	$sql = "SELECT acctid FROM ".db_prefix("accounts");
	$result = db_query($sql) or die(db_error(LINK));
	$row = db_fetch_assoc($result);
	while ($row) {
		$alleIDs[] = $row['acctid'];
		$row = db_fetch_assoc($result);
	}
	require_once("lib/fert.php");
	foreach ($alleIDs as $id) {
		init_werte($id);
	}
	debug("Aktualisiere Fertigkeitswerte für alle vorhandenen Spieler.");
	
	output("`^`b`n`n"
."******************************************			`n"
."	Diese Datei  (fertigkeiten.php)						`n"
." sollte aus fertigkeiten.zip stammen.					`n"
."														`n"
."	Achtung: Wer diese Dateien benutzt, verpflichtet	`n"
."	sich, alle Module, die er für das Fertigkeiten-		`n"
."	system entwickelt frei und öffentlich zugänglich	`n"
."	zu machen!											`n"
."														`n"
."	Wir entwickeln für Euch - Ihr entwickelt für uns.	`n"
."														`n"
."	Jegliche Veränderungen an diesen Dateien 			`n"
."	müssen ebenfalls veröffentlicht werden - so sieht 	`n"
."	es die Lizenz vor, unter der LOTGD veröffentlicht	`n"
."	wurde!												`n"
."														`n"
."	`\$Zuwiderhandlungen können empfindliche Strafen	`n"
."	nach sich ziehen!`^									`n"
."														`n"
."	Näheres siehe: dokumentation.txt					`n"
."														`n"
."	Zudem bitten wir darum, dass Ihr uns eine kurze		`n"
."	Mail an folgende Adresse zukommen lasst, in der		`n"
."	Ihr	uns die Adresse des Servers nennt, auf dem das	`n"
."	Fertigkeitensystem verwendet wird:					`n"
."	cern AT quantentunnel.de							`n"
."	(Spamschutz ' AT ' durch '@' ersetzen)				`n"
."														`n"
."	Das komplette Fertigkeitensystem ist zuerst auf		`n"
."	http://www.green-dragon.info erschienen.			`n"
."														`n"
."	Viel Spaß!											`n"
."														`n"
."	Oliver Wellinghoff, Michael Jandke					`n"
."	und Nico Lachmann									`n"
."														`n"
."******************************************`b`n`n");
	
	return true;
}

function fertigkeiten_uninstall(){
	return true;
}

function fertigkeiten_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "setrace":
		// Für neue Spieler einmal alle Werte mit get_pref aufrufen (init_werte();), damit die defaults gesetzt werden
		if ($session['user']['dragonkills']==0 && $session['user']['age']<2) {
			require_once("lib/fert.php");
			init_werte();
			//output("`nDEBUG: Initialisierung der Werte wird vorgenommen!`n");
		}
		break;
	case "newday":
		// Chance auf "Vergessen" nach einem Drachenkill; die Fertigkeitswerte werden zufällig (mit steigender Chance je höher
		// der Wert ist) um 1 reduziert, um zu verhindern, das Spieler in allen Bereichen die absolute Meisterschaft erreichen.
		// Der Wert ab dem dieses in Kraft tritt, ist in den settings einstellbar.
		if ($session['user']['age']==1) {
			// Zurücksetzen des DK-Limits nach einem DK
			set_module_pref("userdklimit", get_module_setting("dklimit"));
						
			require_once("lib/fert.php");
			$minvergessen = get_module_setting("minvergessen");
			$werte = get_grundfertigkeiten_array();
			
			$werte['bogentext'] = translate_inline("Die Wiedergeburt hat Dir auch einen Teil Deiner fabelhaften Kenntnisse im Bogenschießen genommen.");
			$werte['kletterntext'] = translate_inline("Die Wiedergeburt hat Dir auch einen Teil Deiner fabelhaften Kenntnisse im Klettern genommen.");
			$werte['kochentext'] = translate_inline("Die Wiedergeburt hat Dir auch einen Teil Deiner fabelhaften Kenntnisse im Kochen und Backen genommen.");
			$werte['musiktext'] = translate_inline("Die Wiedergeburt hat Dir auch einen Teil Deiner fabelhaften Kenntnisse in der Musik und im Gesang genommen.");
			$werte['reitentext'] = translate_inline("Die Wiedergeburt hat Dir auch einen Teil Deiner fabelhaften Kenntnisse im Reiten genommen.");
			$werte['schleichentext'] = translate_inline("Die Wiedergeburt hat Dir auch einen Teil Deiner fabelhaften Kenntnisse im Schleichen und Verstecken genommen.");
			$werte['schwimmentext'] = translate_inline("Die Wiedergeburt hat Dir auch einen Teil Deiner fabelhaften Kenntnisse im Schwimmen und Tauchen genommen.");
			
			for ($i=0;$i<(sizeof($werte)/2);$i++) {
				if (current($werte)>=$minvergessen) {
					$rand = e_rand(1,max(1,(95-$minvergessen)));
					if ($rand<=(current($werte)-$minvergessen)) {
						set_module_pref(key($werte),current($werte)-1);
						output ("`n`4%s`n", $werte[key($werte)."text"]);
					}
				}
				next($werte);
			}
		}
		// tägliches Zurücksetzen der Anzahl der Steigerungsversuche
		set_module_pref("usersteigerung", get_module_setting("steigerung"));
		break;
	}
	return $args;
}
?>
