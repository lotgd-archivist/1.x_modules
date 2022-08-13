<?php
/*
Specialty: Druide
Letzte Änderung am 20.04.05 von Michael Jandke

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

function specialtydruid_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Druide",
		"author" => "Michael Jandke",
		"version" => "0.95",
		"download" => "http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"category" => "Specialties",
		"requires"=>array(
			"fertigkeiten"=>"1.0|Grundmodul für Fertigkeitswerte von Oliver Wellinghoff und Michael Jandke",
			"alignment"=>"1.13|Alignment by WebPixie, Lonny Luberts and Chris Vorndran",
		),
		"settings"=> array(
			"Specialty - Druide Einstellungen,title",
			"mindk"=>"Ab welchem DK ist der Druide verfügbar?,int|0",
			"minkochen"=>"Ab welchem Mindestwert in Kochen und Pflanzenkunde ist der Druide verfügbar?,range,0,100,1|50",
		),
		"prefs" => array(
			"Specialty - Druide Spielereinstellungen,title",
			"skill"=>"Stufen als Druide,int|0",
			"uses"=>"Wieviele Anwendungen als Druide,int|0",
		),
	);
	return $info;
}

function specialtydruid_install(){
	module_addhook("choose-specialty");
	module_addhook("set-specialty");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addhook("incrementspecialty");
	module_addhook("specialtynames");
	module_addhook("specialtymodules");
	module_addhook("specialtycolor");
	module_addhook("dragonkill");
	module_addhook("biblio-spec");
	return true;
}

function specialtydruid_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='DR'";
	db_query($sql);
	return true;
}

function specialtydruid_dohook($hookname,$args){
	global $session,$resline;

	$spec = "DR";
	$name = "Druide";
	$ccode = "`2";

	switch ($hookname) {
	case "biblio-spec":	// In der Bibliothek kann man unter Spiel|Künste dazu nachlesen...
		addnav("Kapitel");
		addnav("Druide", "runmodule.php?module=biblio&op1=spec&buch=druide");
		if (httpget('buch')=="druide") {
			output("`c`b%sDie Druiden`b`c`n", $ccode);
			output("`nViele Vanthira sind unter ihnen, aber auch Ausgestoßene anderer Völker, Einzelgänger oder Wesen, die genug von den Wirren der Gemeinschaft haben. In der Abgeschiedenheit der Natur suchen sie den Ausgleich zwischen den widerstrebenden Kräften, dem Guten und dem Bösen. Für beides haben sie nur Verachtung übrig, führt doch letzlich beides nur zu Hass und Gewalt. Druiden gelten als profundeste Kenner der Tier- und Pflanzenwelt.`n");
			output("`nPraktiken: Über die Magie der Druiden ist nur bekannt, dass sie auf rituell zubereiteten Tränken und Talismanen beruht. Aus diesem Grund sind gute Kenntnisse in 'Kochen und Pflanzenkunde' die Grundvoraussetzung eines jeden Druiden.`n");
		}
		break;
	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;
	case "choose-specialty":
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			require_once("lib/fert.php");
			if ($session['user']['dragonkills']<get_module_setting("mindk")) break;
			if (get_fertigkeit("kochen")<get_module_setting("minkochen")) break;
			if (is_module_active('alignment')) {
				$align = get_align();
				if ($align<get_module_setting("evilalign","alignment") || $align>get_module_setting("goodalign","alignment")) break;	// der Druide muß neutral sein...
			}
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Wissen um die Kräfte und Mächte der Natur.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("%sSchon als %s hast du viel Zeit im Wald verbracht, dort die Kräfte der Natur entdeckt und begonnen sie dir zu Nutze zu machen. ", $ccode, ($session['user']['sex']?"kleines Mädchen":"kleiner Junge"));
			output("Du lerntest dir die Unterstützung der Tiere des Waldes zu sichern und hast dich mit den Heilkräften der Natur vertraut gemacht. ");
			output("Nach Jahren geduldvollen Lernens gelangen dir Einblicke in die Fähigkeit des Gestalwandels, du kannst dich in einen furchterregenden Bären verwandeln!`n");
			//output("`nViele Vanthira sind unter ihnen, aber auch Ausgestoßene anderer Völker, Einzelgänger oder Wesen, die genug von den Wirren der Gemeinschaft haben. In der Abgeschiedenheit der Natur suchen sie den Ausgleich zwischen den widerstrebenden Kräften, dem Guten und dem Bösen. Für beides haben sie nur Verachtung übrig, führt doch letzlich beides nur zu Hass und Gewalt. Druiden gelten als profundeste Kenner der Tier- und Pflanzenwelt.`n");
			//output("`nPraktiken: Über die Magie der Druiden ist nur bekannt, dass sie auf rituell zubereiteten Tränken und Talismanen beruht. Aus diesem Grund sind gute Kenntnisse in 'Kochen und Pflanzenkunde' die Grundvoraussetzung eines jeden Druiden.`n");
			output_notl("`0");
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":
		require_once("lib/fert.php");
		if ($session['user']['dragonkills']<get_module_setting("mindk")) break;
		if (get_fertigkeit("kochen")<get_module_setting("minkochen")) break;
		if (is_module_active('alignment')) {
			$align = get_align();
			if ($align<get_module_setting("evilalign","alignment") || $align>get_module_setting("goodalign","alignment")) break;	// der Druide muß neutral sein...
		}
		$args[$spec] = translate_inline($name);
		break;
	case "specialtymodules":
		$args[$spec] = "specialtydruid";
		break;
	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$name = translate_inline($name);
			$c = $args['color'];
			output("`n%sDu steigst eine Stufe als `&%s%s `#auf %s%s auf!",
					$c, $name, $c, $new, $c);
			$x = $new % 3;
			if ($x == 0){
				output("`n`^Du erhälst eine zusätzliche Anwendung!!`n");
				set_module_pref("uses", get_module_pref("uses") + 1);
			}else{
				if (3-$x == 1) {
					output("`n`^Nur noch eine Stufe, bis Du eine zusätzliche Anwendung bekommst!`n");
				} else {
					output("`n`^Nur noch %s Stufen, bis Du eine zusätzliche Anwendung bekommst!`n", (3-$x));
				}
			}
			output_notl("`0");
		}
		break;
	case "newday":
		$bonus = getsetting("specialtybonus", 1);
		if($session['user']['specialty'] == $spec) {
			if (is_module_active('alignment')) {
				$align = get_align();
				if ($align<get_module_setting("evilalign","alignment") || $align>get_module_setting("goodalign","alignment")) {
					output("`n`4Dein spirituelles Gleichgewicht wurde gestört, als Du durch Deine Handlungen das Neutralitätsgebot der Druiden verletzt hast. Die Götter entziehen Dir Deine Spezialkräfte.`n`0");
				} else {
					$name = translate_inline($name);
					if ($bonus == 1) {
						output("`n`2Als %s%s`2 erhälst Du heute `^eine `2zusätzliche Anwendung.`n",$ccode,$name);
					} else {
						output("`n`2Als %s%s`2 erhälst Du heute `^%s `2zusätzliche Anwendungen.`n",$ccode,$name,$bonus);
					}
				}
			}
		}
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt = $amt + $bonus;
		set_module_pref("uses", $amt);
		break;
	case "fightnav-specialties":
		if (is_module_active('alignment')) {
			$align = get_align();
			if ($align<get_module_setting("evilalign","alignment") || $align>get_module_setting("goodalign","alignment")) break;	// der Spieler muß neutral sein...
		}
		$uses = get_module_pref("uses");
		$script = $args['script'];
		if ($uses > 0) {
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode &#149; Schlingpflanze`7 (%s)`0", 1), 
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Wölfe rufen`7 (%s)`0", 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Heilung`7 (%s)`0", 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Gestaltwandel`7 (%s)`0", 5),
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
					apply_buff('dr1',array(
						"name"=>"`2Schlingpflanze",
						"startmsg"=>"`2Eine Schlingpflanze sprießt aus dem Boden und umschlingt `^{badguy}'s`2 Füße.",
						"rounds"=>5,
						"badguydefmod"=>0.5,
						"roundmsg"=>"`^{badguy}`2 hat sich in der Pflanze verheddert und findet kaum Zeit zur Verteidigung!",
						"wearoff"=>"`^{badguy}`2 hat sich von der Schlingpflanze befreit.",
						"schema"=>"specialtydruid"
					));
					break;
				case 2:
					apply_buff('dr2',array(
						"name"=>"`2Wolfsrudel",
						"startmsg"=>"`2Du rufst ein Rudel Wölfe zu deiner Unterstützung!",
						"rounds"=>5,
						"minioncount"=>round($session['user']['level']/3)+1,
						"maxbadguydamage"=>$session['user']['level'],
						"effectmsg"=>"`2Ein Wolf stürzt sich auf `^{badguy}`2 und beißt für `^{damage} `2Schaden zu!",
						"effectnodmgmsg"=>"`2Ein Wolf stürzt sich auf `^{badguy}`2, aber `\$VERFEHLT`2 das Ziel!",
						"wearoff"=>"`2Die Wölfe ziehen sich in den Wald zurück.",
						"schema"=>"specialtydruid"
					));
					break;
				case 3:
					apply_buff('dr3', array(
						"startmsg"=>"`2Du murmelst eine alte druidische Heilformel und deine Wunden schliessen sich!",
						"rounds"=>1,
						"regen"=>$session['user']['level']*10 + $session['user']['dragonkills']*5,
						"schema"=>"specialtydruid"
					));
					break;
				case 5:
					apply_buff('dr5',array(
						"name"=>"`2Gestaltwandel!",
						"startmsg"=>"`2Du verwandelst dich in einen riesigen Bären und stürzt dich auf `^{badguy}`2!",
						"rounds"=>5,
						"atkmod"=>2.5,
						"defmod"=>2,
						"wearoff"=>"`2Du verwandelst dich zurück.",
						"schema"=>"specialtydruid"
					));
					$session['user']['hitpoints'] += round($session['user']['maxhitpoints']*0.5);
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('dr0', array(
					"startmsg"=>"`2Du beschwörst die Kräfte der Natur, aber es erscheint nur eine Ameise auf dem Boden vor dir. `^{badguy}`2 zertritt sie.",
					"rounds"=>1,
					"schema"=>"specialtydruid"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtydruid_run(){
}
?>