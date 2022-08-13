<?php
/*
Specialty: Waldläufer
Letzte Änderung am 26.04.05 von Michael Jandke

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

function specialtywaldlaeufer_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Waldläufer",
		"author" => "Michael Jandke",
		"version" => "0.96",
		"download" =>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"category" => "Specialties",
		"requires"=>array(
			"fertigkeiten"=>"1.0|Grundmodul für Fertigkeitswerte von Oliver Wellinghoff und Michael Jandke",
			"alignment"=>"1.13|Alignment by WebPixie, Lonny Luberts and Chris Vorndran",
		),
		"settings"=> array(
			"Specialty - Waldläufer Einstellungen,title",
			"mindk"=>"Wieviele Dks braucht man bevor der Waldläufer verfügbar ist?,int|0",
			"minbogen"=>"Welcher Mindestwert in Bogen ist für den Waldläufer notwendig?,range,0,100,1|50",
			"minschleichen"=>"Welcher Mindestwert in Schleichen ist für den Waldläufer notwendig?,range,0,100,1|50",
		),
		"prefs" => array(
			"Specialty - Waldläufer Spielereinstellungen,title",
			"skill"=>"Stufen als Waldläufer,int|0",
			"uses"=>"Wieviele Anwendungen als Waldläufer,int|0",
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
	$name = "Waldläufer";
	$ccode = "`@";
	
	switch ($hookname) {
	case "biblio-spec":	// In der Bibliothek kann man unter Spiel|Künste dazu nachlesen...
		addnav("Kapitel");
		addnav("Waldläufer", "runmodule.php?module=biblio&op1=spec&buch=waldlaeufer");
		if (httpget('buch')=="waldlaeufer") {
			output("`c`b%sDie Waldläufer`b`c`n", $ccode);
			output("`nSie vertrauen niemandem mehr als ihrem Bogen und ihrer Fähigkeit, lautlos durch die Wälder streifen zu können. Viele Elfen sind unter ihnen, aber auch in den anderen Völkern gibt es Kundschafter, Soldaten und Abenteurer, die sich dem Wald zugehörig fühlen. Wegen ihrer positiven Einstellung zur Natur können sie niemals böswillig gesinnt sein.`n`0");
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
			if (is_module_active('alignment')) if (get_align()<get_module_setting("evilalign","alignment")) break;	// der Spieler darf nicht böse sein...
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Leben im Einklang mit der Natur, als Beschützer des Waldes.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`n%sSie vertrauen niemandem mehr als ihrem Bogen und ihrer Fähigkeit, lautlos durch die Wälder streifen zu können. Viele Elfen sind unter ihnen, aber auch in den anderen Völkern gibt es Kundschafter, Soldaten und Abenteurer, die sich dem Wald zugehörig fühlen. Wegen ihrer positiven Einstellung zur Natur können sie niemals böswillig gesinnt sein.`n`0", $ccode);
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":	// die ganzen Abbruchbedingungen sind hier, damit man bei Cedrick auch nur zu dieser Specialty wechseln kann, wenn man die Bedingungen erfüllt
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
				output("`n`^Du erhälst eine zusätzliche Anwendung!`n");
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
			if (is_module_active('alignment')) 
				if (get_align()<get_module_setting("evilalign","alignment")) {	// der Spieler darf nicht böse sein...
					output("`n`4Da du eine böse Gesinnung an den Tag gelegt hast, entziehen die Götter dir deine Spezialkräfte.`0`n");
				} else {	// nur die Ausgabe wird unterbunden, den Bonus bekommt man unsichtbar trotzdem, denn man kann ja seine Gesinnung wieder auf neutral bringen
					if ($bonus == 1) {
						output("`n`2Als %s%s`2 erhälst Du heute `^eine `2zusätzliche Anwendung.`n",$ccode,$name);
					} else {
						output("`n`2Als %s%s`2 erhälst Du heute `^%s `2zusätzliche Anwendungen.`n",$ccode,$name,$bonus);
					}
				}
		}
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt++;
		set_module_pref("uses", $amt);
		break;
	case "fightnav-specialties":
		if (is_module_active('alignment')) if (get_align()<get_module_setting("evilalign","alignment")) break;	// der Spieler darf nicht böse sein...
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
							"effectmsg"=>"`@Du triffst `^{badguy}`@ für `^{damage}`@ Schaden.",
							"minioncount"=>1,
							"minbadguydamage"=>round($session['user']['attack']*1.5,0),
							"maxbadguydamage"=>round($session['user']['attack']*3,0),
							"schema"=>"specialtywaldlaeufer"
						));
					}else{
						apply_buff('wa1', array(
							"startmsg"=>"`@Du zielst auf `^{badguy}`@ und lässt die Bogensehne schnippen.",
							"effectmsg"=>"`@Du triffst `^{badguy}`@ für `^{damage}`@ Schaden.",
							"minioncount"=>1,
							"minbadguydamage"=>round($session['user']['attack']*0.75),
							"maxbadguydamage"=>round($session['user']['attack']*1.5),
							"schema"=>"specialtywaldlaeufer"
						));
					}
					break;
				case 2:	// wie Erdenfaust
					$tiere = array("ein Wolf","ein Bär","ein Adler","ein Monstereichhörnchen","eine Riesenschlange");
					$tier = $tiere[e_rand(0,count($tiere)-1)];
					apply_buff('wa2', array(
						"name"=>"`@Tierische Hilfe",
						"startmsg"=>"`@Du rufst die Tiere des Waldes um Hilfe. Es erscheint $tier und greift `^{badguy}`@ an.",
						"rounds"=>5,
						"minioncount"=>1,
						"minbadguydamage"=>1,
						"maxbadguydamage"=>$session['user']['level']*3,
						"effectmsg"=>"`@D$tier trifft `^{badguy}`@für `^{damage}`@ Schaden.",
						"wearoff"=>"`@D$tier verschwindet wieder im Wald.",
						"schema"=>"specialtywaldlaeufer"
					));
					break;
				case 3:	// wie Versteckter Angriff
					switch (e_rand(1,5)) {
					case 1: $ort = translate_inline("im Unterholz"); break;
					case 2: $ort = translate_inline("zwischen ein paar Bäumen"); break;
					case 3: $ort = translate_inline("auf einem Baum"); break;
					case 4: $ort = translate_inline("hinter einem großen Felsen"); break;
					case 5: $ort = translate_inline("hinter ein paar Büschen"); break;
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
				case 5:	// z.Z. als hochskalierte Erdenfaust, dk-abhängigen Schaden einbauen??
					apply_buff('wa5', array(
						"name"=>"`@Mehrfachschuss",
						"startmsg"=>"`@Du schnappst dir gleich mehrere Pfeile auf einmal und schießt sie auf `^{badguy}`@.",
						"rounds"=>5,
						"minioncount"=>3,
						"maxbadguydamage"=>round($session['user']['level']*2.5) + floor($session['user']['dragonkills']*0.2),
						"effectmsg"=>"`@Einer der Pfeile trifft `^{badguy}`@ für `^{damage}`@ Schaden.",
						"effectnodmgmsg"=>"`@Einer der Pfeile `\$VERFEHLT `^{badguy}`@.",
						"wearoff"=>"`@Du hast keine Pfeile mehr.",
						"schema"=>"specialtywaldlaeufer"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('wa0', array(
					"startmsg"=>"`@Du greifst nach einem Pfeil, mußt aber feststellen, das dein Köcher leer ist. `^{badguy}`@ grinst siegessicher.",
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