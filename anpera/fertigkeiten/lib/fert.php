<?php
/*
Letzte �nderung am 30.04.2005 von Michael Jandke
 
>> Abfragefunktionen f�r fertigkeiten.php <<

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

Bei Modulen die auf die Fertigkeitswerte zugreifen wollen, immer mit
	require_once("lib/fert.php");
einbinden.

///////////////
Die Funktionen:
///////////////

get_fertigkeit($fertigkeit, $user=false) - liefert den Wert einer bestimmten Fertigkeit, optional von einem anderen als dem aktuellen Spieler
	Bsp.:
	$bogen = get_fertigkeit("bogen");	- liefert den effektiven Bogenwert des aktuellen Spielers, d.h. mit Ber�cksichtigung der Boni/Mali
	$bogen = get_fertigkeit("bogen",5);	- liefert den effektiven Bogenwert des Spielers mit der ID 5

get_mod($fertigkeit, $user=false) - liefert den (Rassen-)Modifikator einer bestimmten Fertigkeit, optional von einem anderen als dem aktuellen Spieler
	Bsp.:
	$bogenmod = get_mod("bogen"); - liefert f�r die Rasse mit Bogenmalus -(H�he des Rassenmodifikators), bei Bogenbonus +(H�he des Rassenmodifikators), ansonsten 0

get_fertigkeiten_array($user=false) - liefert ein Array mit den Fertigkeiten als Key und ihrem effektiven Wert als Value,
									- optional wieder f�r einen anderen als den aktuellen Spieler
	Bsp.:
	$werte = get_fertigkeiten_array();
	Das Array $werte hat dann folgende Struktur (als Beispiel f�r einen Menschen):
	"bogen"=>30,"klettern"=>40,"kochen"=>45,"musik"=>60,"reiten"=>75,"schleichen"=>80,"schwimmen"=>90

	Besonders in Modulen die mehrere oder alle Fertigkeiten benutzen, lohnt es sich also, am Anfang einmal diese Funktion einmal wie im
	Beispiel oben aufzurufen. Dann kann man bequem auf alle Werte zugreifen, als Beispiel:
		$bogen = $werte['bogen']; usw.
Bemerkung: Ich denke das ist eine der bequemsten Weisen, auf alle Werte Zugriff zu haben, sollte auf jeden Fall in wettkampf.php verwendet werden 

get_mod_array($user=false) - liefert ein Array mit den Modifikatoren auf alle Werte, Key ist die Fertigkeit, der Modifikator als Value
	Bsp.:
	$mods = get_mod_array();
	Das Array $mods hat dann folgende Struktur (als Beispiel f�r einen Menschen bei Standardeinstellungen):
	"bogen"=>0,"klettern"=>0,"kochen"=>-5,"musik"=>0,"reiten"=>5,"schleichen"=>0,"schwimmen"=>0

get_grundfertigkeiten_array($user=false) - liefert ein Array mit den Fertigkeiten als Index und ihrem Grundwert (ohne Modifikatoren) als Value
										 - optional wieder f�r einen anderen als den aktuellen Spieler
	Bsp.:
	$grundwerte = get_grundfertigkeiten_array();
	Das Array $grundwerte hat dann folgende Struktur:
	"bogen"=>5,"klettern"=>40,"kochen"=>50,"musik"=>60,"reiten"=>70,"schleichen"=>80,"schwimmen"=>95

set_fertigkeit($fertigkeit, $wert, $user=false) - setzt den Grundwert der gew�hlten Fertigkeit auf den �bergebenen Wert
												- optional f�r einen anderen als den aktuellen Spieler
												- es werden die Grenzen von 5 und 95 f�r die Grundwerte eingehalten
Bemerkung: Die Grenz�berpr�fung ist hier nur drin, damit niemand Dummheiten macht, die Grenzen sollten immer schon vorher gepr�ft werden!

probe($fertigkeitswert, $modifikatoren=0, $schwierigkeitsgrad=0, $grenzekriterfolg=5, $grenzekritmisserfolg=96) 
	- liefert ein W�rfelergebnis sowie eine Bewertung des Wurfes als kritischer Erfolg/Misserfolg, Erfolg, Misserfolg
	- das Ergebnis wird als Array geliefert, mit den Keys ['probe_wert'] (=> der eigentliche Probenwert)
	  und ['probe_ergebnis'] (=> liefert die strings "kritischer_erfolg","kritischer_misserfolg","erfolg" oder "misserfolg")
	- Parameter:	- $fertigkeitswert : der  Wert(!) den man in der Fertigkeit hat, gegen die man w�rfeln m�chte
					- $modifikatoren : sonstige Modifikatoren die man beachten m�chte (z.B. Entfernungsmods, Ersch�pfungsmods, Konzentrationsmods und was sonst noch irgendwie gebraucht wird...) Wichtig: Das ist nicht der Rassenmodifikator! (der ist schon im Fertigkeitswert)
					- $schwierigkeitsgrad : hiernach richtet sich die Beurteilung  des Wurfes nach Erfolg/Misserfolg
					- $grenzekriterfolg : erreicht man beim W100 einen Wert zwischen 1 und $grenzekriterfolg, so wird dieses Wurf als "kritischer Erfolg" gewertet. Kann mit durch setzen auf 0 abschalten (kann man aber auch einfach ignorieren).
					- $grenzekritmisserfolg : erreicht man beim W100 einen Wert zwischen $grenzekritmisserfolg und 100, so wird dieses Wurf als "kritischer Misserfolg" gewertet. Kann mit durch setzen auf 0 abschalten.
	Bsp.:
	// ganz simple Probe
	$bogen = get_fertigkeit("bogen");
	$wurf = probe($bogen);
	if ($wurf['probe_ergebnis']=="erfolg") ...
	
	// Test auf Kritische W�rfe
	$bogen = get_fertigkeit("bogen");
	$wurf = probe($bogen);
	if ($wurf['probe_ergebnis']=="kritischer_erfolg") {
		...
	}elsif($wurf['probe_ergebnis']=="kritischer_misserfolg") {
		...
	}
	
	// usw.
	
	
Alle derzeit verf�gbaren Funktionen sind:

	get_fertigkeit($fertigkeit, $user=false)
	get_mod($fertigkeit, $user=false)
	get_fertigkeiten_array($user=false)
	get_mod_array($user=false)
	get_grundfertigkeiten_array($user=false)
	set_fertigkeit($fertigkeit,$wert,$user=false)
	probe($fertigkeitswert, $modifikator=0, $kritischer_erfolg=2.5, $kritischer_misserfolg=97.5)
	
To Do:	- meinem Umbenennungsfimmel fr�hnen und fertigkeit durch skill ersetzen, das ist k�rzer
		- datacache benutzen oder auf get_module_pref umstellen um performance zu testen
		- pr�fen was passiert wenn ein Spieler keine Rasse hat, z.B. bei Cedricks Tr�nken
*/

// Die Funktion liefert den effektiven Fertigkeitswert, d.h. Boni/Mali sind beachtet
function get_fertigkeit($fertigkeit, $user=false) {
	global $session;
	// H�he des Modifaktors durch die Rasse bestimmen
	$rassenmod = 5;	// jetzt fest...
	// Rasse des gew�nschten Spielers bestimmen
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
	$mod = 0;	// f�r Bonus oder Malus
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

// Die Funktion liefert den aktuellen Modifikator auf die gew�hlte Fertigkeit
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
	//debug("Zur�ckgelieferter Mod von Fert = ".$mod);
	
	return $mod;
}

// Die Funktion liefert alle effektiven Fertigkeitswerte in einem Array zur�ck
function get_fertigkeiten_array($user=false) {
	global $session;
	$rassenmod = 5;	// jetzt fest...
	// id f�r sql-Abfrage bestimmen
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
//		output("`nDEBUG: Rasse f�r id %s: %s", $user, $race);
	}
	else {
		$race = $session['user']['race'];
	}
	// Modifikatoren setzen, dann den Fertigkeitswert entsprechend �ndern
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
	// id f�r sql-Abfrage bestimmen
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
// $kritischer_erfolg: Schwellenwert f�r den eigentlichen W�rfelwurf (Standard: 2,5 %) -> wenn <=
// $kritischer_misserfolg: Schwellenwert f�r den eigentlichen W�rfelwurf (Standard: 2,5 %) -> wenn >=
// $runden: Soll das Ergebnis der Probe auf einen ganzzahligen Wert gerundet werden? Standard: Nein.
//
// Ausgabe:
// wert: Fertigkeitswert + Modifikatoren - W�rfelwurf (Werte von 0.00 bis 100.00 m�glich)
// ergebnis: "erfolg"  / "kritischer erfolg" / "misserfolg" / "kritischer misserfolg"
// Die Probe gelingt, wenn "wert" >= 0 ist

function probe($fertigkeitswert, $modifikator=0, $kritischer_erfolg=2.5, $kritischer_misserfolg=97.5, $runden=false) {
	
	$wurf=(e_rand(0,5000) + e_rand(0,5000)) / 100;
	
	//Simple Absicherung falls sich die Grenzen �berschneiden, man muss schliesslich mit dem DAU rechnen ;-)
	if ($kritischer_erfolg >= $kritischer_misserfolg && $kritischer_erfolg != 0 && $kritischer_misserfolg != 0) {
		output("`n`\$`bACHTUNG: �berschneidung der Grenzen f�r kritische Erfolge und Misserfolge. Beide Werte auf Null gesetzt.`b`n");
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
// wird f�r neue Spieler benutzt, sowie bei der Installation von fertigkeiten.php
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