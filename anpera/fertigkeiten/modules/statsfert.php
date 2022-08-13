<?php
// VERSION : 2004-03-15-1.00   (jjjj-mm-tt-v.vv)
// 
// ****************************************************************************
// Ausgabe der Fertigkeitswerte für wettkampf.php
// funktioniert nur in Zusammenhang mit dem Platz der Völker und Fertigkeiten
//
// (p) 2005 by Nico Lachmann
//
// mail : hisssan@gmx.net
// icq  : #29539379
// ****************************************************************************

/*
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
// ****************************************************************************
// Benötigt die zusätzliche Library zur Verfügungstellung der Fertigkeitswerte
// ****************************************************************************

require_once("lib/fert.php");

// ****************************************************************************

function statsfert_getmoduleinfo(){
	$info = array(
		"name"=>"Fertigkeiten-Anzeige",
		"version"=>"1.0",
		"author"=>"Nico Lachmann",
		"category"=>"Stat Display",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array(
			"fertigkeiten"=>"1.0|Library für Fertigkeitswerte von Oliver Wellinghoff und Michael Jandke"
		),
		"settings"=>array(
			"barsize"=>"Größe des grafischen Fertigkeitsstreifens in Pixel,int|100",
			"malcolor"=>"Html Colorcode für den Malus-Teilstreifen,textarea|#ff8888",
			"boncolor"=>"Html Colorcode für den Bonus-Teilstreifen,textarea|#aaffaa"
		),
	
		
			"prefs"=>array(
				"Vital-Info Einstellungen,title",
					"user_fertigkeiten"=>"Anzeigen der Fertigkeiten,enum,nicht,nicht anzeigen,numerisch,numerisch,grafisch,grafisch,numerischgrafisch,numerisch und grafisch|grafisch",
		), 
	);
	return $info;
}//statsfert_getmoduleinfo()


function statsfert_install()
{
	if (is_module_active("fertigkeiten")) {
		module_addhook("charstats");
		return true;
	} else {
		return false;
	}
}//statsfert_install()

function statsfert_uninstall()
{
	return true;
}//statsfert_uninstall()


function modsign($mod_arg) 
{
	if ($mod_arg == 0) {
		$result = "";
	}
	else {
		if ($mod_arg < 0) {
			$result = "`$".$mod_arg;
		}
		else {
			$result = "`@+".$mod_arg;
		}
	}
	return $result;
}//modsign()


function pctcolor($pct) 
{
	switch($pct) {
		case 0:  $color = "#FF0900"; break; 
		case 1:  $color = "#FF1100"; break; 
		case 2:  $color = "#FF1300"; break; 
		case 3:  $color = "#FF1500"; break; 
		case 4:  $color = "#FF1700"; break; 
		case 5:  $color = "#FF1900"; break; 
		case 6:  $color = "#FF1E00"; break; 
		case 7:  $color = "#FF2300"; break; 
		case 8:  $color = "#FF2800"; break; 
		case 9:  $color = "#FF2D00"; break; 
		case 10: $color = "#FF3200"; break; 
		case 11: $color = "#FF3700"; break; 
		case 12: $color = "#FF3C00"; break; 
		case 13: $color = "#FF4100"; break; 
		case 14: $color = "#FF4600"; break; 
		case 15: $color = "#FF4b00"; break; 
		case 16: $color = "#FF5000"; break; 
		case 17: $color = "#FF5500"; break; 
		case 18: $color = "#FF5A00"; break; 
		case 19: $color = "#FF5F00"; break; 
		case 20: $color = "#FF6400"; break; 
		case 21: $color = "#FF6900"; break; 
		case 22: $color = "#FF6E00"; break; 
		case 23: $color = "#FF7300"; break; 
		case 24: $color = "#FF7800"; break; 
		case 25: $color = "#FF7d00"; break; 
		case 26: $color = "#FF8200"; break; 
		case 27: $color = "#FF8700"; break; 
		case 28: $color = "#FF8C00"; break; 
		case 29: $color = "#FF9100"; break; 
		case 30: $color = "#FF9600"; break; 
		case 31: $color = "#FF9B00"; break; 
		case 32: $color = "#FFA000"; break; 
		case 33: $color = "#FFA500"; break; 
		case 34: $color = "#FFAA00"; break; 
		case 35: $color = "#FFAF00"; break; 
		case 36: $color = "#FFB400"; break; 
		case 37: $color = "#FFB900"; break; 
		case 38: $color = "#FFBE00"; break; 
		case 39: $color = "#FFC300"; break; 
		case 40: $color = "#FFc800"; break; 
		case 41: $color = "#FFcd00"; break; 
		case 42: $color = "#FFe200"; break; 
		case 43: $color = "#FFe700"; break; 
		case 44: $color = "#FFec00"; break; 
		case 45: $color = "#FFd200"; break; 
		case 46: $color = "#FFd700"; break; 
		case 47: $color = "#FFdc00"; break; 
		case 48: $color = "#FFe100"; break; 
		case 49: $color = "#FFe600"; break; 
		case 50: $color = "#ffff00"; break;
		case 51: $color = "#e6ff00"; break;
		case 52: $color = "#e1ff00"; break;
		case 53: $color = "#dcff00"; break;
		case 54: $color = "#d7ff00"; break;
		case 55: $color = "#d2ff00"; break;
		case 56: $color = "#ecff00"; break;
		case 57: $color = "#e7ff00"; break;
		case 58: $color = "#e2ff00"; break;
		case 59: $color = "#cdff00"; break;
		case 60: $color = "#c8ff00"; break; 
		case 61: $color = "#c3ff00"; break;
		case 62: $color = "#beff00"; break;
		case 63: $color = "#b9ff00"; break;
		case 64: $color = "#b4ff00"; break;
		case 65: $color = "#afff00"; break;
		case 66: $color = "#aaff00"; break;
		case 67: $color = "#a5ff00"; break;
		case 68: $color = "#a0ff00"; break;
		case 69: $color = "#9bff00"; break;
		case 70: $color = "#96ff00"; break;
		case 71: $color = "#91ff00"; break;
		case 72: $color = "#8cff00"; break;
		case 73: $color = "#87ff00"; break;
		case 74: $color = "#82ff00"; break;
		case 75: $color = "#7dff00"; break;
		case 76: $color = "#78ff00"; break;
		case 77: $color = "#73ff00"; break;
		case 78: $color = "#6eff00"; break;
		case 79: $color = "#69ff00"; break;
		case 80: $color = "#64ff00"; break;
		case 81: $color = "#5fff00"; break;
		case 82: $color = "#5aff00"; break;
		case 83: $color = "#55ff00"; break;
		case 84: $color = "#50ff00"; break;
		case 85: $color = "#4bff00"; break;
		case 86: $color = "#46ff00"; break;
		case 87: $color = "#41ff00"; break;
		case 88: $color = "#3cff00"; break;
		case 89: $color = "#37ff00"; break;
		case 90: $color = "#32ff00"; break; 
		case 91: $color = "#2dff00"; break;
		case 92: $color = "#28ff00"; break;
		case 93: $color = "#23ff00"; break;
		case 94: $color = "#1eff00"; break; 
		case 95: $color = "#006400"; break; 
		case 96: $color = "#BBBBBB"; break; 
		case 97: $color = "#CCCCCC"; break; 
		case 98: $color = "#DDDDDD"; break; 
		case 99: $color = "#EEEEEE"; break; 
		case 100: $color = "#FFFFFF"; break;
	}//switch
	return $color;
}//pctcolor()
	


function bar($fw,$mod,$size,$malcolor,$boncolor) 
{
	$p1a = "<table style='border: solid 1px #000000;' bgcolor='#777777'  cellpadding='0' cellspacing='0' width='$size' height='5'><tr><td width=";
	$p1b = " bgcolor=";
	$p2a = "></td><td width=";
	$p2b = " bgcolor=";
	$p3a = " ></td><td width=";
	$p4 = " ></td></tr></table>";

	$out1 =""; 
	// Zunächst den Modifikator herausrechnen
	$pct = $fw - $mod;
	
	if ($mod<0) {
		$modcolor = $malcolor;
		$mod = abs($mod);
		if ($pct>=100) {
			$abschnitt_leer = 0; 
			$abschnitt_mod = max(100-($pct-$mod), 0);
			$abschnitt_fw = 100 - $abschnitt_mod;
		} else {
			$abschnitt_leer = 100 - $pct; 
			$abschnitt_mod = $mod;
			$abschnitt_fw = $pct - $mod;
		}
		$color = pctcolor($fw);
	} 
	elseif ($mod>0) {
		$modcolor = $boncolor;
		$mod = abs($mod);
		if ($pct<=0) {
			$abschnitt_fw =0;
			$abschnitt_mod = max(($pct + $mod), 0);
			$abschnitt_leer = 100 - $abschnitt_mod; 
			
		} else {
			$abschnitt_fw = $pct;
			$abschnitt_mod = max(0,$mod);
			$abschnitt_leer = 100 - $abschnitt_fw - $abschnitt_mod; 
			
		}
		$color = pctcolor($fw);
	} else {
		$abschnitt_fw = $pct;
		$abschnitt_mod = 0;
		$abschnitt_leer = 100 - $abschnitt_fw; 
		$color = pctcolor($pct);
	}
	// Tabelle kürzen falls eine Spalte Größe 0 % hat (verkürzt den html Code)
	if ($abschnitt_leer == 0) {
		$out1 = $p1a."'$abschnitt_fw%'".$p1b."'$color'".$p2a."'$abschnitt_mod%'".$p2b."'$modcolor'".$p4;
	} elseif ($abschnitt_fw == 0) {
		$out1 = $p1a."'$abschnitt_mod%'".$p1b."'$modcolor'".$p3a."'$abschnitt_leer%'".$p4;
	} else {
		$out1 = $p1a."'$abschnitt_fw%'".$p1b."'$color'".$p2a."'$abschnitt_mod%'".$p2b."'$modcolor'".$p3a."'$abschnitt_leer%'".$p4;
	}
	$out1 .= "\n";
	return $out1;
}//bar()


function statsfert_dohook($hookname,$args) {

	switch($hookname) {
	case "choose-specialty":
		
		$werte = get_fertigkeiten_array();
		break;
	case "charstats":

		if (is_module_active("fertigkeiten")) {
			
			$modus = get_module_pref("user_fertigkeiten"); 
			
			if ($modus =="") { $modus="grafisch"; } 
			
			switch ($modus) {
				case "nicht" : $shownum = false; $showbar = false; break;
				case "numerisch" : $shownum = true; $showbar = false; break;
				case "grafisch"  : $shownum = false; $showbar = true; break;
				case "numerischgrafisch" : $shownum = true; $showbar = true; break;
			} //switch
			if ($shownum || $showbar) {
				$werte = get_fertigkeiten_array();
				$mod = get_mod_array();
				if ($shownum) 
				{
					$out1 = "`^".($werte["bogen"] - $mod["bogen"]).           modsign($mod["bogen"]);
					$out2 = "`^".($werte["schleichen"] - $mod["schleichen"]). modsign($mod["schleichen"]);
					$out3 = "`^".($werte["schwimmen"] - $mod["schwimmen"]).   modsign($mod["schwimmen"]);
					$out4 = "`^".($werte["klettern"] - $mod["klettern"]).     modsign($mod["klettern"]);
					$out5 = "`^".($werte["kochen"] - $mod["kochen"]).         modsign($mod["kochen"]);
					$out6 = "`^".($werte["musik"] - $mod["musik"]).           modsign($mod["musik"]);
					$out7 = "`^".($werte["reiten"] -  $mod["reiten"]).        modsign($mod["reiten"]);
				}//if
				if ($showbar) 
				{
					$barsize  = get_module_setting("barsize"); 
					$malcolor = get_module_setting("malcolor"); 
					$boncolor = get_module_setting("boncolor"); 
					
					$pre ="";
					if ($shownum) { $pre .= "<br />"; }
					
					$out1 .= $pre . bar( $werte["bogen"], $mod["bogen"], $barsize, $malcolor, $boncolor); 
					$out2 .= $pre . bar( $werte["schleichen"], $mod["schleichen"], $barsize, $malcolor, $boncolor); 
					$out3 .= $pre . bar( $werte["schwimmen"], $mod["schwimmen"], $barsize, $malcolor, $boncolor); 
					$out4 .= $pre . bar( $werte["klettern"], $mod["klettern"], $barsize, $malcolor, $boncolor); 
					$out5 .= $pre . bar( $werte["kochen"], $mod["kochen"], $barsize, $malcolor, $boncolor); 
					$out6 .= $pre . bar( $werte["musik"], $mod["musik"], $barsize, $malcolor, $boncolor); 
					$out7 .= $pre . bar( $werte["reiten"], $mod["reiten"], $barsize, $malcolor, $boncolor); 
				}//if
				addcharstat("Fertigkeiten");
				setcharstat("Fertigkeiten","Bogen", $out1);
				setcharstat("Fertigkeiten","Klettern", $out4);
				setcharstat("Fertigkeiten","Kochen", $out5);
				setcharstat("Fertigkeiten","Musizieren", $out6);
				setcharstat("Fertigkeiten","Reiten", $out7);
				setcharstat("Fertigkeiten","Schleichen", $out2);
				setcharstat("Fertigkeiten","Schwimmen", $out3);
			}//if
			break;
		}//if
	}//switch
	return $args; 
}//function

function statsfert_run(){
}

?>
