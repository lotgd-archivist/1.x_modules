<?php
/*
Specialty: Waldl�ufer
Letzte �nderung am 26.04.05 von Michael Jandke

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

function specialtywaldlaeufer_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Waldl�ufer",
		"author" => "Michael Jandke",
		"version" => "0.96",
		"download" =>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"category" => "Specialties",
		"requires"=>array(
			"fertigkeiten"=>"1.0|Grundmodul f�r Fertigkeitswerte von Oliver Wellinghoff und Michael Jandke",
			"alignment"=>"1.13|Alignment by WebPixie, Lonny Luberts and Chris Vorndran",
		),
		"settings"=> array(
			"Specialty - Waldl�ufer Einstellungen,title",
			"mindk"=>"Wieviele Dks braucht man bevor der Waldl�ufer verf�gbar ist?,int|0",
			"minbogen"=>"Welcher Mindestwert in Bogen ist f�r den Waldl�ufer notwendig?,range,0,100,1|50",
			"minschleichen"=>"Welcher Mindestwert in Schleichen ist f�r den Waldl�ufer notwendig?,range,0,100,1|50",
		),
		"prefs" => array(
			"Specialty - Waldl�ufer Spielereinstellungen,title",
			"skill"=>"Stufen als Waldl�ufer,int|0",
			"uses"=>"Wieviele Anwendungen als Waldl�ufer,int|0",
		),
	);
	return $info;
}

function specialtywaldlaeufer_install(){
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

function specialtywaldlaeufer_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='WA'";
	db_query($sql);
	return true;
}

function specialtywaldlaeufer_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "WA";
	$name = "Waldl�ufer";
	$ccode = "`@";
	
	switch ($hookname) {
	case "biblio-spec":	// In der Bibliothek kann man unter Spiel|K�nste dazu nachlesen...
		addnav("Kapitel");
		addnav("Waldl�ufer", "runmodule.php?module=biblio&op1=spec&buch=waldlaeufer");
		if (httpget('buch')=="waldlaeufer") {
			output("`c`b%sDie Waldl�ufer`b`c`n", $ccode);
			output("`nSie vertrauen niemandem mehr als ihrem Bogen und ihrer F�higkeit, lautlos durch die W�lder streifen zu k�nnen. Viele Elfen sind unter ihnen, aber auch in den anderen V�lkern gibt es Kundschafter, Soldaten und Abenteurer, die sich dem Wald zugeh�rig f�hlen. Wegen ihrer positiven Einstellung zur Natur k�nnen sie niemals b�swillig gesinnt sein.`n`0");
			output_notl("`0");
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
			if (get_fertigkeit("bogen")<get_module_setting("minbogen")) break;
			if (get_fertigkeit("schleichen")<get_module_setting("minschleichen")) break;
			if (is_module_active('alignment')) if (get_align()<get_module_setting("evilalign","alignment")) break;	// der Spieler darf nicht b�se sein...
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Leben im Einklang mit der Natur, als Besch�tzer des Waldes.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`n%sSie vertrauen niemandem mehr als ihrem Bogen und ihrer F�higkeit, lautlos durch die W�lder streifen zu k�nnen. Viele Elfen sind unter ihnen, aber auch in den anderen V�lkern gibt es Kundschafter, Soldaten und Abenteurer, die sich dem Wald zugeh�rig f�hlen. Wegen ihrer positiven Einstellung zur Natur k�nnen sie niemals b�swillig gesinnt sein.`n`0", $ccode);
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":	// die ganzen Abbruchbedingungen sind hier, damit man bei Cedrick auch nur zu dieser Specialty wechseln kann, wenn man die Bedingungen erf�llt
		require_once("lib/fert.php");
		if ($session['user']['dragonkills']<get_module_setting("mindk")) break;
		if (get_fertigkeit("bogen")<get_module_setting("minbogen")) break;
		if (get_fertigkeit("schleichen")<get_module_setting("minschleichen")) break;
		if (is_module_active('alignment')) if (get_align()<get_module_setting("evilalign","alignment")) break;
		$args[$spec] = translate_inline($name);
		break;
	case "specialtymodules":
		$args[$spec] = "specialtywaldlaeufer";
		break;
	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$c = $args['color'];
			$name = translate_inline($name);
			output("`n%sDu steigst ein Stufe als `&%s%s auf `#%s%s auf!", $c, $name, $c, $new, $c);
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
			if (is_module_active('alignment')) 
				if (get_align()<get_module_setting("evilalign","alignment")) {	// der Spieler darf nicht b�se sein...
					output("`n`4Da du eine b�se Gesinnung an den Tag gelegt hast, entziehen die G�tter dir deine Spezialkr�fte.`0`n");
				} else {	// nur die Ausgabe wird unterbunden, den Bonus bekommt man unsichtbar trotzdem, denn man kann ja seine Gesinnung wieder auf neutral bringen
					if ($bonus == 1) {
						output("`n`2Als %s%s`2 erh�lst Du heute `^eine `2zus�tzliche Anwendung.`n",$ccode,$name);
					} else {
						output("`n`2Als %s%s`2 erh�lst Du heute `^%s `2zus�tzliche Anwendungen.`n",$ccode,$name,$bonus);
					}
				}
		}
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt++;
		set_module_pref("uses", $amt);
		break;
	case "fightnav-specialties":
		if (is_module_active('alignment')) if (get_align()<get_module_setting("evilalign","alignment")) break;	// der Spieler darf nicht b�se sein...
		$uses = get_module_pref("uses");
		$script = $args['script'];
		if ($uses > 0) {
			addnav(array("%s%s (%s points)`0", $ccode, $name, $uses), "");
			addnav(array("%s &#149; Gezielter Schuss`7 (%s)`0", $ccode, 1),
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("%s &#149; Tierische Hilfe`7 (%s)`0", $ccode, 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("%s &#149; Verstecken`7 (%s)`0", $ccode, 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("%s &#149; Mehrfachschuss`7 (%s)`0", $ccode, 5),
					$script."op=fight&skill=$spec&l=5",true);
		}
		break;
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:	// halbes Voodoo bzw. bei kritischem Treffer wie Voodoo
					if (e_rand(1,100)<=5) { // 5% kritischer Treffer (doppelter Schaden)
						apply_buff('wa1-crit', array(
							"startmsg"=>"`&`bKritischer Treffer!`b `^{badguy}`@ wird an einer empfindlichen Stelle getroffen.",
							"effectmsg"=>"`@Du triffst `^{badguy}`@ f�r `^{damage}`@ Schaden.",
							"minioncount"=>1,
							"minbadguydamage"=>round($session['user']['attack']*1.5,0),
							"maxbadguydamage"=>round($session['user']['attack']*3,0),
							"schema"=>"specialtywaldlaeufer"
						));
					}else{
						apply_buff('wa1', array(
							"startmsg"=>"`@Du zielst auf `^{badguy}`@ und l�sst die Bogensehne schnippen.",
							"effectmsg"=>"`@Du triffst `^{badguy}`@ f�r `^{damage}`@ Schaden.",
							"minioncount"=>1,
							"minbadguydamage"=>round($session['user']['attack']*0.75),
							"maxbadguydamage"=>round($session['user']['attack']*1.5),
							"schema"=>"specialtywaldlaeufer"
						));
					}
					break;
				case 2:	// wie Erdenfaust
					$tiere = array("ein Wolf","ein B�r","ein Adler","ein Monstereichh�rnchen","eine Riesenschlange");
					$tier = $tiere[e_rand(0,count($tiere)-1)];
					apply_buff('wa2', array(
						"name"=>"`@Tierische Hilfe",
						"startmsg"=>"`@Du rufst die Tiere des Waldes um Hilfe. Es erscheint $tier und greift `^{badguy}`@ an.",
						"rounds"=>5,
						"minioncount"=>1,
						"minbadguydamage"=>1,
						"maxbadguydamage"=>$session['user']['level']*3,
						"effectmsg"=>"`@D$tier trifft `^{badguy}`@f�r `^{damage}`@ Schaden.",
						"wearoff"=>"`@D$tier verschwindet wieder im Wald.",
						"schema"=>"specialtywaldlaeufer"
					));
					break;
				case 3:	// wie Versteckter Angriff
					switch (e_rand(1,5)) {
					case 1: $ort = translate_inline("im Unterholz"); break;
					case 2: $ort = translate_inline("zwischen ein paar B�umen"); break;
					case 3: $ort = translate_inline("auf einem Baum"); break;
					case 4: $ort = translate_inline("hinter einem gro�en Felsen"); break;
					case 5: $ort = translate_inline("hinter ein paar B�schen"); break;
					}
					apply_buff('wa3', array(
						"name"=>"`@Verstecken",
						"startmsg"=>"`@Du versteckst dich geschickt $ort.",
						"rounds"=>5,
						"badguyatkmod"=>0,
						"roundmsg"=>"`^{badguy}`@ findet dich nicht und kann dich nicht angreifen.",
						"wearoff"=>"`^{badguy}`@ hat dich $ort entdeckt.",
						"schema"=>"specialtywaldlaeufer"
					));
					break;
				case 5:	// z.Z. als hochskalierte Erdenfaust, dk-abh�ngigen Schaden einbauen??
					apply_buff('wa5', array(
						"name"=>"`@Mehrfachschuss",
						"startmsg"=>"`@Du schnappst dir gleich mehrere Pfeile auf einmal und schie�t sie auf `^{badguy}`@.",
						"rounds"=>5,
						"minioncount"=>3,
						"maxbadguydamage"=>round($session['user']['level']*2.5) + floor($session['user']['dragonkills']*0.2),
						"effectmsg"=>"`@Einer der Pfeile trifft `^{badguy}`@ f�r `^{damage}`@ Schaden.",
						"effectnodmgmsg"=>"`@Einer der Pfeile `\$VERFEHLT `^{badguy}`@.",
						"wearoff"=>"`@Du hast keine Pfeile mehr.",
						"schema"=>"specialtywaldlaeufer"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('wa0', array(
					"startmsg"=>"`@Du greifst nach einem Pfeil, mu�t aber feststellen, das dein K�cher leer ist. `^{badguy}`@ grinst siegessicher.",
					"rounds"=>1,
					"schema"=>"specialtywaldlaeufer"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtywaldlaeufer_run(){
}
?>