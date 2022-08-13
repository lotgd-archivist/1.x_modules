<?php
/*
Letzte �nderung am 24.05.05 von Michael Jandke

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

Schmied auf dem PdV, gedacht als Ausgleich f�r die Orkburg bzw. Drachental

To Do:	- falls man noch ein Modul f�r eigene Waffennamen benutzt, mu� entweder dieses Modul oder das hier angepasst werden...
		
*/

function pdvschmied_getmoduleinfo(){
	$info = array(
		"name"=>"PdV - Schmied",
		"version"=>"1.0",
		"author"=>"Michael Jandke",
		"category"=>"Der Platz der Voelker",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array(
			"wettkampf"=>"1.0|Platz der V�lker von Oliver Wellinghoff",
		),
		"settings"=>array(
			"PdV - Schmied Einstellungen,title",
			"goldcost"=>"Wieviel Gold kostet die Verbesserung (* Waffenlevel),int|200",
			"improvement"=>"Um wieviel Punkte wird die Waffe/R�stung verbessert?,range,1,3,1|2",
			"chance"=>"Wie gro� ist die Chance das der Stand w�hrend des Festes erscheint (in Prozent)?,range,0,100,1|75",
			"appear"=>"Ist der Stand gerade anwesend (nur w�hrend Fest)?,bool|0", // man kann ihn hiermit zur Not "herzwingen"
		),
	);
	return $info;
}

function pdvschmied_install(){
	module_addhook("pdvst�nde");
	module_addhook("newday-runonce");
	return true;
}

function pdvschmied_uninstall(){
	return true;
}

function pdvschmied_dohook($hookname,$args){
	
	switch ($hookname) {
	case "newday-runonce":
		if (get_module_setting("fest","wettkampf")==1) {
			$chance = get_module_setting("chance","pdvschmied");
			if (e_rand(1,100)<=$chance) set_module_setting("appear",1,"pdvschmied");
			else set_module_setting("appear",0,"pdvschmied");
			//debug("PdVSchmied appear = ".get_module_setting("appear"));
		}
		break;
	case "pdvst�nde":
		$werte = array(	"name"=>"Die Schmiede",		// Text der im Link erscheinen soll
						"appear"=>get_module_setting("appear","pdvschmied"));	// Abfrage ob anwesend oder nicht
		$args['pdvschmied'] = $werte;
		break;
	}
	return $args;
}

function pdvschmied_runevent($type, $link) {
}

function pdvschmied_run(){
	global $session;
	$from = "runmodule.php?module=pdvschmied&";
	$op = httpget('op');
	$wcost = get_module_setting("goldcost") * $session['user']['weapondmg'];
	$acost = get_module_setting("goldcost") * $session['user']['armordef'];
	$improvement = get_module_setting("improvement");
	page_header("Die Schmiede");
	output("`c`bDer Stand der beiden Schmiede`b`c`n");
	if ($op=="") {
		checkday();
		output("`&Du n�herst Dich dem Stand eines reisenden Schmiedes, oder besser gesagt, zweier Schmiede. Unter einem nach allen Seiten offenen Zelt stehen verschiedene Kostproben ihrer Fertigkeit, etwas weiter hinten ihre Schmiedewerkzeuge. ");
		output("Ein hochgewachsener und kr�ftiger Elf tritt mit einem freundlichen L�cheln auf Dich zu.`n");
		output("`n`@Einen wundersch�nen guten Tag, %s. Wie k�nnen wir Ihnen behilflich sein? `qXangrosch`@ und `2Yngrama`@, wir sind die geschicktesten Schmiede weit und breit und w�rden Euch gerne von unserer Kunst �berzeugen.`n", $session['user']['sex']?"die Dame":"der Herr");
		output("`n`&Dabei deutet er auf den Zwerg der weiter hinten gerade konzentriert an einem Ambo� arbeitet und verbeugt sich leicht vor Dir.`n");
		
		addnav("Verbessern");
		addnav(array("Waffe verbessern `n(`^%s Gold`0)", $wcost),$from."op=weapon");
		addnav(array("R�stung verbessern `n(`^%s Gold`0)", $acost),$from."op=armor");
		addnav("Verlassen");
		addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
	}elseif ($op=="weapon") {
		if ($session['user']['gold']>=$wcost) {
			if ($session['user']['weapondmg']!=0) {
				$upgraded = strpos($session['user'][$op],"(verbessert)")!==false ? true : false;
				if ($upgraded) {
					output("`@Seht Ihr diese kleine Gravur dort? Ich habe diese Waffe schon bearbeitet, und besser wird es kein Handwerker auf dieser Welt machen k�nnen.`n");
					output("`n`&Mit diesen Worten gibt er Dir Deine Waffe zur�ck.");
					addnav("Zur�ck zum Schmied",$from."op=");
				}else {
					output("`&L�chelnd nimmt `2Yngrama`& Dein(e/en) %s entgegen und betrachtet die Waffe mit ge�btem Blick.`n", $session['user']['weapon']);
					output("`n`@Hier wird meine Kunst nicht versagen, Eure verbesserte Waffe wird einen Waffenwert von `^%s`@ haben. Wenn Ihr nun ein wenig Geduld haben w�rdet.`n", $session['user']['weapondmg']+$improvement);
					output("`n`&Mit diesen Worten begibt er sich etwas tiefer ins Zelt und beginnt seine Arbeit. Interessiert schaust Du zu und die Zeit vergeht wie im Fluge. Schlie�lich tritt er wieder zu Dir hervor und �berreicht Dir Dein(e/en) %s.`n", $session['user']['weapon']." (verbessert)");
					output("`n`@Bitte sch�n, m�ge die Waffe Euch gute Dienste leisten und Ihr immer zufrieden mit meiner Arbeit sein.`n");
					output("`n`&L�chelnd nimmst du sie entgegen und bezahlst mit Freuden den vereinbarten Preis.");
					$session['user']['gold'] -= $wcost;
					debuglog("hat f�r $wcost Gold seine Waffe beim Schmied auf dem PdV verbessert.");
					$session['user']['weapon'] .=" (verbessert)";
					$session['user']['weapondmg']+=$improvement;
					$session['user']['attack']+=$improvement;
					$session['user']['weaponvalue']+=$wcost;	// das ist nicht die ideale L�sung...
					
					addnav("Zur�ck zum Schmied",$from."op=");
				}
			}else {
				output("`2Yngrama`& schaut Dich etwas ratlos an, als Du ihm Deine F�uste entgegenstreckst. Er wundert sich einen kurzen Augenblick, dann sch�rzt er die Lippen und holt einen riesigen Hammer hervor. Dann deutet er auf seinen Ambo�.`n");
				output("`n`@Bitte kommt zu diesem Ambo� hier...`n");
				output("`n`&Du schluckst, als Du merkst, dass es ihm anscheinend ernst ist. Da Du mit Deinem Scherz anscheinend an den falschen geraten bist, nimmst Du schnell Rei�aus.");
			}
		}else {
			output("`&Mit einem musternden Blick betrachtet `2Yngrama`& Dich eine Zeit lang und aus seinen Worten spricht die jahrelange Erfahrung eines wanderden Schmiedes.`n");
			output("`n`@Mein Herr, es kostet Euch `^%s Gold`@, Eure Waffe verbessern zu lassen. Meine Arbeit ist es wert, also besorgt Euch das Gold, wenn Ihr sie in Anspruch nehmen wollt.", $wcost);
		}
		addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
	}elseif ($op=="armor") {
		if ($session['user']['gold']>=$acost) {
			if ($session['user']['armordef']!=0) {
				$upgraded = strpos($session['user'][$op],"(verbessert)")!==false ? true : false;
				if ($upgraded) {
					output("`qXangrosch`& r�hrt die R�stung nicht an, als Du sie vor ihm ablegst. Fragend schaust Du ihn an, dann antwortet er Dir knapp.`n");
					output("`n`QVerschwendet nicht meine Zeit, diese R�stung ging schon durch meine Hand.");
					addnav("Zur�ck zum Schmied",$from."op=");
				}else {
					output("`2Yngrama`& deutet auf `qXangrosch`&, als Du mit Deiner R�stung auf ihn zukommst. Dankend nickst du ihm zu und wendest Dich an `qXangrosch`&. Dieser blickt von seinem Ambo� auf, als er Dich und Deine R�stung erblickt.`n");
					output("`n`QMoment, gleich fertig... So, was kann ich f�r Euch tun. Mich dieser R�stung hier annehmen?`n");
					output("`n`&Du nickst best�tigend und �berreichst sie ihm zur Bearbeitung. Er beginnt sofort, sich murmelnd an die Arbeit zu machen und Du schaust ihm interessiert zu. Die Zeit vergeht, schlie�lich kommt er wieder auf Dich zu und �berreicht Dir Deine verbesserte R�stung.`n");
					output("`n`QSie hat nun einen R�stungswert von `^%s`Q, ich hoffe sie wird Euch vor allen Gefahren bewahren k�nnen.`n", $session['user']['armordef']+$improvement);
					$session['user']['gold'] -= $acost;
					debuglog("hat f�r $acost Gold seine R�stung beim Schmied auf dem PdV verbessert.");
					$session['user']['armor'] .=" (verbessert)";
					$session['user']['armordef']+=$improvement;
					$session['user']['defense']+=$improvement;
					$session['user']['armorvalue']+=$acost;
					addnav("Zur�ck zum Schmied",$from."op=");
				}
			}else {
				output("`&Du trittst nah vor `qXangrosch`&, ziehst Dein T-Shirt aus und h�lst es ihm hin. Unbeeindruckt von Deinem Prachtk�rper nimmt er es und geht mit ihm zu dem voll unter Feuer stehenden Schmiedeofen. Gerade noch bevor das T-Shirt ein Opfer der Flammen wird, entrei�t Du es ihm und fl�chtest auf den Platz der V�lker. Am Ausgang des Zeltes der Schmiede ziehst Du es hastig wieder �ber, aber es ist durch die gro�e Hitze beim Schmiedeofen schon angesengt!");
				$session['user']['armor'] .= " (angesengt)";
				// Systemkommentar auf dem PdV?
				
			}
		}else {
			output("`QVerschwendet nicht meine Zeit, wenn Ihr nicht genug Gold habt. Geht nun.`n");
			output("`n`&Du hast zwar keine Ahnung woher `qXangrosch`& das wu�te, aber es stimmt, Du hast nicht genug Gold!");
		}
		addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
	}
	page_footer();
}
?>
