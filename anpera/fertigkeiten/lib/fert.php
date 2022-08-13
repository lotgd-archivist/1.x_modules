<?php
/*
Letzte Änderung am 30.04.2005 von Michael Jandke
 
>> Abfragefunktionen für fertigkeiten.php <<

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

Bei Modulen die auf die Fertigkeitswerte zugreifen wollen, immer mit
	require_once("lib/fert.php");
einbinden.

///////////////
Die Funktionen:
///////////////

get_fertigkeit($fertigkeit, $user=false) - liefert den Wert einer bestimmten Fertigkeit, optional von einem anderen als dem aktuellen Spieler
	Bsp.:
	$bogen = get_fertigkeit("bogen");	- liefert den effektiven Bogenwert des aktuellen Spielers, d.h. mit Berücksichtigung der Boni/Mali
	$bogen = get_fertigkeit("bogen",5);	- liefert den effektiven Bogenwert des Spielers mit der ID 5

get_mod($fertigkeit, $user=false) - liefert den (Rassen-)Modifikator einer bestimmten Fertigkeit, optional von einem anderen als dem aktuellen Spieler
	Bsp.:
	$bogenmod = get_mod("bogen"); - liefert für die Rasse mit Bogenmalus -(Höhe des Rassenmodifikators), bei Bogenbonus +(Höhe des Rassenmodifikators), ansonsten 0

get_fertigkeiten_array($user=false) - liefert ein Array mit den Fertigkeiten als Key und ihrem effektiven Wert als Value,
									- optional wieder für einen anderen als den aktuellen Spieler
	Bsp.:
	$werte = get_fertigkeiten_array();
	Das Array $werte hat dann folgende Struktur (als Beispiel für einen Menschen):
	"bogen"=>30,"klettern"=>40,"kochen"=>45,"musik"=>60,"reiten"=>75,"schleichen"=>80,"schwimmen"=>90

	Besonders in Modulen die mehrere oder alle Fertigkeiten benutzen, lohnt es sich also, am Anfang einmal diese Funktion einmal wie im
	Beispiel oben aufzurufen. Dann kann man bequem auf alle Werte zugreifen, als Beispiel:
		$bogen = $werte['bogen']; usw.
Bemerkung: Ich denke das ist eine der bequemsten Weisen, auf alle Werte Zugriff zu haben, sollte auf jeden Fall in wettkampf.php verwendet werden 

get_mod_array($user=false) - liefert ein Array mit den Modifikatoren auf alle Werte, Key ist die Fertigkeit, der Modifikator als Value
	Bsp.:
	$mods = get_mod_array();
	Das Array $mods hat dann folgende Struktur (als Beispiel für einen Menschen bei Standardeinstellungen):
	"bogen"=>0,"klettern"=>0,"kochen"=>-5,"musik"=>0,"reiten"=>5,"schleichen"=>0,"schwimmen"=>0

get_grundfertigkeiten_array($user=false) - liefert ein Array mit den Fertigkeiten als Index und ihrem Grundwert (ohne Modifikatoren) als Value
										 - optional wieder für einen anderen als den aktuellen Spieler
	Bsp.:
	$grundwerte = get_grundfertigkeiten_array();
	Das Array $grundwerte hat dann folgende Struktur:
	"bogen"=>5,"klettern"=>40,"kochen"=>50,"musik"=>60,"reiten"=>70,"schleichen"=>80,"schwimmen"=>95

set_fertigkeit($fertigkeit, $wert, $user=false) - setzt den Grundwert der gewählten Fertigkeit auf den übergebenen Wert
												- optional für einen anderen als den aktuellen Spieler
												- es werden die Grenzen von 5 und 95 für die Grundwerte eingehalten
Bemerkung: Die Grenzüberprüfung ist hier nur drin, damit niemand Dummheiten macht, die Grenzen sollten immer schon vorher geprüft werden!

probe($fertigkeitswert, $modifikatoren=0, $schwierigkeitsgrad=0, $grenzekriterfolg=5, $grenzekritmisserfolg=96) 
	- liefert ein Würfelergebnis sowie eine Bewertung des Wurfes als kritischer Erfolg/Misserfolg, Erfolg, Misserfolg
	- das Ergebnis wird als Array geliefert, mit den Keys ['probe_wert'] (=> der eigentliche Probenwert)
	  und ['probe_ergebnis'] (=> liefert die strings "kritischer_erfolg","kritischer_misserfolg","erfolg" oder "misserfolg")
	- Parameter:	- $fertigkeitswert : der  Wert(!) den man in der Fertigkeit hat, gegen die man würfeln möchte
					- $modifikatoren : sonstige Modifikatoren die man beachten möchte (z.B. Entfernungsmods, Erschöpfungsmods, Konzentrationsmods und was sonst noch irgendwie gebraucht wird...) Wichtig: Das ist nicht der Rassenmodifikator! (der ist schon im Fertigkeitswert)
					- $schwierigkeitsgrad : hiernach richtet sich die Beurteilung  des Wurfes nach Erfolg/Misserfolg
					- $grenzekriterfolg : erreicht man beim W100 einen Wert zwischen 1 und $grenzekriterfolg, so wird dieses Wurf als "kritischer Erfolg" gewertet. Kann mit durch setzen auf 0 abschalten (kann man aber auch einfach ignorieren).
					- $grenzekritmisserfolg : erreicht man beim W100 einen Wert zwischen $grenzekritmisserfolg und 100, so wird dieses Wurf als "kritischer Misserfolg" gewertet. Kann mit durch setzen auf 0 abschalten.
	Bsp.:
	// ganz simple Probe
	$bogen = get_fertigkeit("bogen");
	$wurf = probe($bogen);
	if ($wurf['probe_ergebnis']=="erfolg") ...
	
	// Test auf Kritische Würfe
	$bogen = get_fertigkeit("bogen");
	$wurf = probe($bogen);
	if ($wurf['probe_ergebnis']=="kritischer_erfolg") {
		...
	}elsif($wurf['probe_ergebnis']=="kritischer_misserfolg") {
		...
	}
	
	// usw.
	
	
Alle derzeit verfügbaren Funktionen sind:

	get_fertigkeit($fertigkeit, $user=false)
	get_mod($fertigkeit, $user=false)
	get_fertigkeiten_array($user=false)
	get_mod_array($user=false)
	get_grundfertigkeiten_array($user=false)
	set_fertigkeit($fertigkeit,$wert,$user=false)
	probe($fertigkeitswert, $modifikator=0, $kritischer_erfolg=2.5, $kritischer_misserfolg=97.5)
	
To Do:	- meinem Umbenennungsfimmel fröhnen und fertigkeit durch skill ersetzen, das ist kürzer
		- datacache benutzen oder auf get_module_pref umstellen um performance zu testen
		- prüfen was passiert wenn ein Spieler keine Rasse hat, z.B. bei Cedricks Tränken
*/

// Die Funktion liefert den effektiven Fertigkeitswert, d.h. Boni/Mali sind beachtet
function get_fertigkeit($fertigkeit, $user=false) {
	global $session;
	// Höhe des Modifaktors durch die Rasse bestimmen
	$rassenmod = 5;	// jetzt fest...
	// Rasse des gewünschten Spielers bestimmen
	$race ="";
	if ($user){
		$sql = "SELECT race FROM ".db_prefix("accounts")." WHERE acctid=$user";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$race = $row['race']; 
	} else { 
		$race = $session['user']['race']; 
	}
	// Modifikatoren und den effektiven Fertigkeitswert bestimmen
	$mod = 0;	// für Bonus oder Malus
	if ($race==get_module_setting("bonus".$fertigkeit,"fertigkeiten")) $mod += $rassenmod;
	if ($race==get_module_setting("malus".$fertigkeit,"fertigkeiten")) $mod -= $rassenmod;
	$additionalmods = modulehook("fert-mod");
	foreach ($additionalmods as $module => $werte) {
		$mod += $werte[$fertigkeit];
	}
	$wert = get_module_pref($fertigkeit,"fertigkeiten",$user) + $mod;
	// obere Grenze von 100 beachten und notfalls reduzieren
	$wert = min (100, $wert);
	return $wert;
}

// Die Funktion liefert den aktuellen Modifikator auf die gewählte Fertigkeit
function get_mod($fertigkeit, $user=false) {
	global $session;
	$rassenmod = 5;	// jetzt fest...
	$race = "";
	if ($user){
		$sql = "SELECT race FROM ".db_prefix("accounts")." WHERE acctid=$user";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$race = $row['race']; 
	} else { 
		$race = $session['user']['race']; 
	}
	$mod = 0;
	if ($race==get_module_setting("bonus".$fertigkeit."","fertigkeiten")) $mod += $rassenmod;
	if ($race==get_module_setting("malus".$fertigkeit."","fertigkeiten")) $mod -= $rassenmod;
	
	$additionalmods = modulehook("fert-mod");
	//debug("Fert Additionalmods = ");
	//debug($additionalmods);
	foreach ($additionalmods as $module => $werte) {
		$mod += $werte[$fertigkeit];
	}
	//debug("Zurückgelieferter Mod von Fert = ".$mod);
	
	return $mod;
}

// Die Funktion liefert alle effektiven Fertigkeitswerte in einem Array zurück
function get_fertigkeiten_array($user=false) {
	global $session;
	$rassenmod = 5;	// jetzt fest...
	// id für sql-Abfrage bestimmen
	$id = 0;
	if ($user) $id = $user; 
	else $id = $session['user']['acctid'];
	// Basiswerte holen
	$array = array();
	$statement = 
		"select userid as id, setting as fertigkeit, value as wert from ".db_prefix("module_userprefs").
		" where modulename='fertigkeiten' and".
		" userid = $id and".
		" (setting IN ('bogen','klettern','kochen','musik','reiten','schleichen','schwimmen') )";
	$result = db_query($statement) or die(db_error(LINK));
	while ($arr = db_fetch_assoc($result)) {
    	$array[$arr['fertigkeit']]=$arr['wert'];
	}
	// Rasse des Spielers bestimmen
	$race = "";
	if ($user) {
		$sql = "select race from ".db_prefix("accounts")." where acctid=$user";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$race = $row['race']; 
//		output("`nDEBUG: Rasse für id %s: %s", $user, $race);
	}
	else {
		$race = $session['user']['race'];
	}
	// Modifikatoren setzen, dann den Fertigkeitswert entsprechend ändern
	$statement = 
		"select setting as modifikator, value as race from ".db_prefix("module_settings").
		" where modulename='fertigkeiten' and".
		" (setting IN ('bonusbogen','bonusklettern','bonuskochen','bonusmusik','bonusreiten','bonusschleichen','bonusschwimmen','malusbogen','malusklettern','maluskochen','malusmusik','malusreiten','malusschleichen','malusschwimmen') )";
	$result = db_query($statement) or die(db_error(LINK));
	while ($arr = db_fetch_assoc($result)) {
    	$mods[$arr['modifikator']]=$arr['race'];
	}
	$additionalmods = modulehook("fert-mod");
	$modarray = array();
	foreach ($additionalmods as $module => $werte) {
		foreach ($werte as $fertigkeit => $val) {
			$modarray[$fertigkeit] = $val;
		}
	}
	//debug("get_fertigkeiten_array ModArray =");
	//debug($modarray);
	foreach ($array as $key => $val) {
		$array[$key] += (($race == $mods["bonus".$key])?$rassenmod:0) + (($race == $mods["malus".$key])?-$rassenmod:0);
		$array[$key] += $modarray[$key];
		$array[$key] = min($array[$key],100);	// obere Grenze sicherheitshalber beachten
		$array[$key] = max($array[$key],0);		// untere Grenze sicherheitshalber beachten
	}
	//debug("get_fertigkeiten_array Return =");
	//debug($array);
	
	return $array;
}

function get_mod_array($user=false) {
	global $session;
	$rassenmod = 5;	// jetzt fest...
	// Rasse des Spielers bestimmen
	$race = "";
	if ($user) {
		$sql = "select race from ".db_prefix("accounts")." where acctid=$user";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$race = $row['race']; 
	}
	else {
		$race = $session['user']['race'];
	}
	// Die Rasse, die den entsprechenden Bonus/Malus bekommt, bestimmen
	$statement = 
		"select setting as modifikator, value as race from ".db_prefix("module_settings").
		" where modulename='fertigkeiten' and".
		" (setting IN ('bonusbogen','bonusklettern','bonuskochen','bonusmusik','bonusreiten','bonusschleichen','bonusschwimmen','malusbogen','malusklettern','maluskochen','malusmusik','malusreiten','malusschleichen','malusschwimmen') )";
	$result = db_query($statement) or die(db_error(LINK));
	while ($arr = db_fetch_assoc($result)) {
    	$mods[$arr['modifikator']]=$arr['race'];
	}
	// Modifikatoren setzen
	$array['bogen'] = (($race == $mods['bonusbogen'])?$rassenmod:0) + (($race == $mods['malusbogen'])?-$rassenmod:0);
	$array['klettern'] = (($race == $mods['bonusklettern'])?$rassenmod:0) + (($race == $mods['malusklettern'])?-$rassenmod:0);
	$array['kochen'] = (($race == $mods['bonuskochen'])?$rassenmod:0) + (($race == $mods['maluskochen'])?-$rassenmod:0);
	$array['musik'] = (($race == $mods['bonusmusik'])?$rassenmod:0) + (($race == $mods['malusmusik'])?-$rassenmod:0);
	$array['reiten'] = (($race == $mods['bonusreiten'])?$rassenmod:0) + (($race == $mods['malusreiten'])?-$rassenmod:0);
	$array['schleichen'] = (($race == $mods['bonusschleichen'])?$rassenmod:0) + (($race == $mods['malusschleichen'])?-$rassenmod:0);
	$array['schwimmen'] = (($race == $mods['bonusschwimmen'])?$rassenmod:0) + (($race == $mods['malusschwimmen'])?-$rassenmod:0);
	
	$additionalmods = modulehook("fert-mod");
	foreach ($additionalmods as $module => $werte) {
		foreach ($werte as $fertigkeit => $val) {
			$array[$fertigkeit] += $val;
		}
	}
	//debug("Get_mod_array Array = ");
	//debug($array);
	return $array;
}

function get_grundfertigkeiten_array($user=false) {
	global $session;
	// id für sql-Abfrage bestimmen
	if (!$user) $user = $session['user']['acctid'];
	// Basiswerte holen
	$statement = 
		"select userid as id, setting as fertigkeit, value as wert from ".db_prefix("module_userprefs").
		" where modulename='fertigkeiten' and".
		" userid = $user and".
		" (setting IN ('bogen','klettern','kochen','musik','reiten','schleichen','schwimmen') )";
	$result = db_query($statement) or die(db_error(LINK));
	while ($arr = db_fetch_assoc($result)) {
    	$array[$arr['fertigkeit']]=$arr['wert'];
	}
	return $array;
}

// verkapptes set_module_pref mit Beachtung der Grenzwerte der Grundfertigkeiten
function set_fertigkeit($fertigkeit, $wert, $user=false) {
	global $session;
	if (!$user) $user = $session['user']['acctid'];
	$wert = min(95,$wert);
	$wert = max(5,$wert);
	set_module_pref($fertigkeit,$wert,"fertigkeiten",$user);
}

//Die Fertigkeitsprobe
//
// $modifikator: Summe aller allgemeinen und individuellen Erschwernisse und Vereinfachungen.
// Negative Werte erschweren und positive erleichtern die Probe.
// 
// $kritischer_erfolg: Schwellenwert für den eigentlichen Würfelwurf (Standard: 2,5 %) -> wenn <=
// $kritischer_misserfolg: Schwellenwert für den eigentlichen Würfelwurf (Standard: 2,5 %) -> wenn >=
// $runden: Soll das Ergebnis der Probe auf einen ganzzahligen Wert gerundet werden? Standard: Nein.
//
// Ausgabe:
// wert: Fertigkeitswert + Modifikatoren - Würfelwurf (Werte von 0.00 bis 100.00 möglich)
// ergebnis: "erfolg"  / "kritischer erfolg" / "misserfolg" / "kritischer misserfolg"
// Die Probe gelingt, wenn "wert" >= 0 ist

function probe($fertigkeitswert, $modifikator=0, $kritischer_erfolg=2.5, $kritischer_misserfolg=97.5, $runden=false) {
	
	$wurf=(e_rand(0,5000) + e_rand(0,5000)) / 100;
	
	//Simple Absicherung falls sich die Grenzen überschneiden, man muss schliesslich mit dem DAU rechnen ;-)
	if ($kritischer_erfolg >= $kritischer_misserfolg && $kritischer_erfolg != 0 && $kritischer_misserfolg != 0) {
		output("`n`\$`bACHTUNG: Überschneidung der Grenzen für kritische Erfolge und Misserfolge. Beide Werte auf Null gesetzt.`b`n");
		$kritischer_erfolg = 0;
		$kritischer_misserfolg = 0;
	}
	
	//Zahlenergebnis
	$probe = $fertigkeitswert + $modifikator - $wurf;
	if ($runden == true) $probe=round($probe);
	
	//Simples Ergebnis
	if ($kritischer_erfolg != 0 && $wurf <= $kritischer_erfolg) $ergebnis="kritischer erfolg";
	else if ($kritischer_misserfolg !=0 && $wurf >= $kritischer_misserfolg) $ergebnis="kritischer misserfolg";
	else if ($probe >= 0) $ergebnis="erfolg";
	else $ergebnis="misserfolg";
	
	return array(
		"wert"=>"$probe",
		"ergebnis"=>"$ergebnis"
	);
}

// einmal alle werte mir get_pref aufrufen, damit die default-werte gesetzt werden,
// wird für neue Spieler benutzt, sowie bei der Installation von fertigkeiten.php
function init_werte($user=false) {
	global $session;
	if (!$user) $user = $session['user']['acctid'];
	$a = get_module_pref("bogen","fertigkeiten",$user);
	$a = get_module_pref("klettern","fertigkeiten",$user);
	$a = get_module_pref("kochen","fertigkeiten",$user);
	$a = get_module_pref("musik","fertigkeiten",$user);
	$a = get_module_pref("reiten","fertigkeiten",$user);
	$a = get_module_pref("schleichen","fertigkeiten",$user);
	$a = get_module_pref("schwimmen","fertigkeiten",$user);
}
?>