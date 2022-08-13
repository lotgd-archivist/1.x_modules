<?php
//addnews ready
// mail ready
// translator ready

/*
Modifiziert f�r die Benutzung mit fertigkeiten.php und alignment.php
Mindestanforderung im Fertigkeitswert "Schleichen" eingebaut
Letzte �nderungen am 20.04.05 von Michael Jandke

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

function specialtythiefskillsfw_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Diebesk�nste",
		"author" => "Eric Stevens<br>mod by Michael Jandke",
		"version" => "1.02",
		"download" => "http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"category" => "Specialties",
		"requires"=>array(
			"fertigkeiten"=>"1.0|Grundmodul f�r Fertigkeitswerte von Oliver Wellinghoff und Michael Jandke",
			"alignment"=>"1.13|Alignment by WebPixie, Lonny Luberts and Chris Vorndran",
		),
		"settings"=> array(
			"Specialty - Diebesk�nste Einstellungen,title",
			"minschleichen"=>"Mindestwert in Schleichen damit Diebesk�nste verf�gbar wird ist ,range,0,100,1|50",
		),
		"prefs" => array(
			"Specialty - Diebesk�nste Spielereinstellungen,title",
			"skill"=>"Stufen in Diebesk�nsten,int|0",
			"uses"=>"Wieviel Anwendungen in Diebesk�nsten,int|0",
		),
	);
	return $info;
}

function specialtythiefskillsfw_install(){

	module_addhook("biblio-spec");
	module_addhook("dragonkill");
	module_addhook("choose-specialty");
	module_addhook("set-specialty");
	module_addhook("specialtycolor");
	module_addhook("specialtynames");
	module_addhook("specialtymodules");
	module_addhook("incrementspecialty");
	module_addhook("newday");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	
	return true;
}

function specialtythiefskillsfw_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='TS'";
	db_query($sql);
	return true;
}

function specialtythiefskillsfw_dohook($hookname,$args){
	global $session,$resline;

	$spec = "TS";
	$name = "Diebesk�nste";
	$ccode = "`^";

	switch ($hookname) {
	case "biblio-spec":	// In der Bibliothek kann man unter Spiel|K�nste dazu nachlesen...
		addnav("Kapitel");
		addnav("Diebeskunst", "runmodule.php?module=biblio&op1=spec&buch=dieb");
		$buch = httpget('buch');
		if ($buch=="dieb") {
			output("`c`b%sDie Diebeskunst`b`c`n", $ccode);
			output("`n`6Diebe sind die Kehrseite des Reichtums - und so gibt es sie in allen V�lkern. Aber mit der Diebeskunst sind keine einfachen Taschendiebe gemeint. Wer in die geheime Gilde der meisterhaften Diebeskunst aufgenommen werden m�chte, muss eine harte Aufnahmepr�fung bestehen. Nur die unauff�lligsten Schleicher, die sich vor allen wachsamen Blicken verstecken k�nnen, sind in der Lage, sie zu bestehen. Zudem w�rde sich ein gutgesinntes Wesen niemals zum Diebstahl herablassen - auch nicht, um die Armen zu beschenken.`n");
		}
		break;
	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;
	case "choose-specialty":
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			require_once("lib/fert.php");
			if (get_fertigkeit("schleichen")<get_module_setting("minschleichen")) break;
			if (is_module_active('alignment')) if (get_align()>get_module_setting("goodalign","alignment")) break;	// der Spieler darf nicht gut sein...
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Stealing from the rich and giving to yourself");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`n`6Diebe sind die Kehrseite des Reichtums - und so gibt es sie in allen V�lkern. Aber mit der Diebeskunst sind keine einfachen Taschendiebe gemeint. Wer in die geheime Gilde der meisterhaften Diebeskunst aufgenommen werden m�chte, muss eine harte Aufnahmepr�fung bestehen. Nur die unauff�lligsten Schleicher, die sich vor allen wachsamen Blicken verstecken k�nnen, sind in der Lage, sie zu bestehen. Zudem w�rde sich ein gutgesinntes Wesen niemals zum Diebstahl herablassen - auch nicht, um die Armen zu beschenken.`n`0");
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":
		require_once("lib/fert.php");
		if (get_fertigkeit("schleichen")<get_module_setting("minschleichen")) break;
		if (is_module_active('alignment')) if (get_align()>get_module_setting("goodalign","alignment")) break;	// der Spieler darf nicht gut sein...
		$args[$spec] = translate_inline($name);
		break;
	case "specialtymodules":
		$args[$spec] = "specialtythiefskills";
		break;
	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$c = $args['color'];
			$name = translate_inline($name);
			output("`n%sDu steigst ein Stufe in den `&%sn%s auf `#%s%s auf!", $c, $name, $c, $new, $c);
			$x = $new % 3;
			if ($x == 0){
				output("`n`^Du erh�lst eine zus�tzliche Anwendung!`n");
				set_module_pref("uses", get_module_pref("uses") + 1);
			}else{
				if (3-$x == 1) {
					output("`n`^Nur noch eine Stufe, bis Du eine zus�tzliche Anwendung bekommst!`n");
				} else {
					output("`n`^Nur noch %s Stufen, bis Du eine zus�tzliche Anwendung bekommst!`n", (3-$x));
				}
			}
			output_notl("`0");
		}
		break;
	case "newday":
		$bonus = getsetting("specialtybonus", 1);
		if($session['user']['specialty'] == $spec) {
			if (is_module_active('alignment')) if (get_align()>get_module_setting("goodalign","alignment")) {	// der Spieler darf nicht gut sein...
				output("`n`4Deine neuerliche gute Gesinnung ist ja zum kotzen, so kannst du dich nicht auf deine Arbeit konzentrieren. Du verlierst deine Spezialkr�fte.`0`n");
			}else{	// nur die Ausgabe wird unterbunden, den Bonus bekommt man unsichtbar trotzdem, denn man kann ja seine Gesinnung wieder auf neutral bringen
				$name = translate_inline($name);
				if ($bonus == 1) {
					output("`n`2F�r dein Spezialgebiet, die %s%s`2, erh�lst du heute `^eine`2 zus�tzliche Anwendung.`n",$ccode,$name);
				} else {
					output("`n`2F�r dein Spezialgebiet, die %s%s`2, erh�lst du heute `^%s`2 zus�tzliche Anwendungen.`n",$ccode,$name,$bonus);
				}
			}
		}
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt = $amt + $bonus;
		set_module_pref("uses", $amt);
		break;
	case "fightnav-specialties":
		if (is_module_active('alignment')) if (get_align()>get_module_setting("goodalign","alignment")) break;	// der Spieler darf nicht gut sein...
		$uses = get_module_pref("uses");
		$script = $args['script'];
		if ($uses > 0) {
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode &#149; Beleidigen`7 (%s)`0", 1), 
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Waffe vergiften`7 (%s)`0", 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Versteckter Angriff`7 (%s)`0", 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Angriff von hinten`7 (%s)`0", 5),
					$script."op=fight&skill=$spec&l=5",true);
		}
		break;
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('ts1',array(
						"startmsg"=>"`^Du gibst deinem Gegner einen schlimmen Namen und bringst {badguy} zum Weinen.",
						"name"=>"`^Beleidigung",
						"rounds"=>5,
						"wearoff"=>"Dein Gegner putzt sich die Nase und h�rt auf zu weinen.",
						"roundmsg"=>"{badguy} ist deprimiert und kann nicht so gut angreifen.",
						"badguyatkmod"=>0.5,
						"schema"=>"specialtythiefskills"
					));
					break;
				case 2:
					apply_buff('ts2',array(
						"startmsg"=>"`^Du streichst etwas Gift auf dein(e/n) {weapon}.",
						"name"=>"`^Vergiftete Waffe",
						"rounds"=>5,
						"wearoff"=>"Das Blut deines Opfers hat das Gift von deiner Waffe gewaschen.",
						"atkmod"=>2,
						"roundmsg"=>"Dein Angriffswert vervielfacht sich!", 
						"schema"=>"specialtythiefskills"
					));
					break;
				case 3:
					apply_buff('ts3', array(
						"startmsg"=>"`^Mit dem Geschick eines erfahrenen Diebes scheinst du zu verschwinden und kannst {badguy} aus einer g�nstigeren und sichereren Position angreifen.",
						"name"=>"`^Versteckter Angriff",
						"rounds"=>5,
						"wearoff"=>"Dein Opfer hat dich gefunden.",
						"roundmsg"=>"{badguy} kann dich nicht finden!",
						"badguyatkmod"=>0,
						"schema"=>"specialtythiefskills"
					));
					break;
				case 5:
					apply_buff('ts5',array(
						"startmsg"=>"`^Mit deinen F�higkeiten als Dieb verschwindest du im Schatten und schiebst {badguy} von hinten eine d�nne Klinge zwischen die R�ckenwirbel!",
						"name"=>"`^Angriff von hinten",
						"rounds"=>5,
						"wearoff"=>"Dein Opfer ist nicht mehr so nett, dich hinter sich zu lassen!",
						"atkmod"=>3,//2.5,
						"defmod"=>2,//2.5,
						"roundmsg"=>"Dein Angriffswert und deine Verteidigung vervielfachen sich!",
						"schema"=>"specialtythiefskills"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('ts0', array(
					"startmsg"=>"Du versuchst {badguy} anzugreifen, indem du deine besten Diebesk�nste in die Praxis umsetzt, aber stattdessen stolperst du �ber deine eigenen F��e.",
					"rounds"=>1,
					"schema"=>"specialtythiefskills"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtythiefskillsfw_run(){
}
?>
