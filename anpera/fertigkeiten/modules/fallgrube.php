<?php
/*
Letzte �nderung am 16.08.2005 von Michael Jandke

Idee zu einem f�higkeitsabh�ngigen Ereignis
Benutzte F�higkeiten:	Klettern

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

To Do:	- bis jetzt nicht multiple cities-kompatibel (systemkommentare)

*/

function fallgrube_getmoduleinfo(){
	$info = array(
		"name"=>"Die Fallgrube",
		"version"=>"1.0",
		"author"=>"Michael Jandke",
		"category"=>"Fertigkeiten - Wald",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array(
			"fertigkeiten"=>"1.0|von Oliver Wellinghoff und Michael Jandke",
			"alignment"=>"1.12|By WebPixie and Lonny Luberts and Chris Vorndran",
		),
		"prefs"=>array(
			"Die Fallgrube - Spielereinstellungen,title",
			"anzahlwarten"=>"Wie oft hat der Spieler schon gewartet?,int|0",
			"hadevent"=>"Hatte der Spieler das Ereignis heute schon?,bool|0",
        )
	);
	return $info;
}

function fallgrube_install(){
	module_addeventhook("forest", "\$hadevent=get_module_pref(\"hadevent\", \"fallgrube\");return (\$hadevent?0:100);");
	module_addhook("newday");
	return true;
}

function fallgrube_uninstall(){
	return true;
}

function fallgrube_dohook($hookname,$args){
	switch($hookname) {
	case "newday":
		set_module_pref("hadevent",0);
		break;
	}
	return $args;
}

function fallgrube_runevent($type){
	global $session;
	require_once("lib/experience.php");
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:fallgrube";
	
	function ogre_fight() {
		$op = httpget("op");
		global $session;
		if ($op=="fight"){
			$badguy = createarray($session['user']['badguy']);
			if ($badguy['type']!="fallgrube") {
				$badguy = array(
					"creaturename"=>"Blutr�nstiger Oger",	// der Gute ist ohne K�nste ein harter Brocken...
					"creaturelevel"=>$session['user']['level'],
					"creatureweapon"=>"Riesige Keule",
					"creatureattack"=>round($session['user']['attack']*1.25),
					"creaturedefense"=>round($session['user']['defense']*0.75),
					"creaturehealth"=>round($session['user']['maxhitpoints']*1.3,0), 
					"diddamage"=>0,
					"type"=>"fallgrube");
				$session['user']['badguy']=createstring($badguy);
			}
			$battle=true;
		}
		if ($battle){
			include_once("battle.php");
			if ($victory){
				output("`n`2Der Oger liegt erschlagen zu Deinen F��en. Du schnaufst nach diesem harten Kampf und musst Dich erst einmal kurz ausruhen.");
				$badguy=array();
				$session['user']['badguy']="";
				addnav(" Sieg ");
				addnav("Geschafft!", "forest.php?op=gewonnen");
			}elseif ($defeat){
				$badguy=array();
				$session['user']['badguy']="";
				output("`n`bDu bist tot!`b`nDer Oger hat Dich besiegt und wird Dich nun wohl verspeisen!`nDu verlierst 10 Prozent deiner Erfahrung sowie all Dein Gold.`nDu kannst morgen weiterspielen.`0");
				addnews("`b`4%s`b `\$wurde zur willkommenen Mahlzeit f�r einen hungrigen `qOger`\$.`0", $session['user']['name']);
				addnav(" Niederlage ");
				addnav("Daily news","news.php");
				debuglog("lost {$session['user']['gold']} gold to an ogre.");
				$session['user']['hitpoints']=0;
				$session['user']['alive']=false;
				$session['user']['gold']=0;
				$session['user']['experience']*=0.9;
				$session['user']['specialinc'] = "";
			}else{
				fightnav(true,false);
			}
		}
	} //end ogre_fight
	
	$op=httpget('op');
	switch($op) {
	case "":
	case "search":
		output("`n`2Ein pl�tzliches Knacken im Unterholz macht dich aufmerksam!`0");	// hihi, genau wie bei der Jagd...
		addnav("Gehe aufmerksam weiter", $from."op=weiter");
		set_module_pref("anzahlwarten",1);
		set_module_pref("erschoepfung",5);
		set_module_pref("adrenalin",0);
		set_module_pref("hadevent",1);
		break;
	case "weiter":
		$vermeiden = e_rand(1,10);
		if ($vermeiden<=1) {	// 10% Chance nicht in die Grube zu fallen
			output("`n`2Mit geschultem Blick und etwas Gl�ck f�llt Dir eine Fallgrube, gut getarnt direkt vor Dir im Boden, auf. ");
			output("Du kannst gerade noch so Dein Gleichgewicht halten und ein Hineinfallen verhindern! Eine lehrreiche Erfahrung.`n");
			$curlevel = $session['user']['level'];
			$curdk = $session['user']['dragonkills'];
			$exppct = e_rand(10,20);
			$exp = round((exp_for_next_level($curlevel, $curdk)-exp_for_next_level($curlevel-1, $curdk))*$exppct/100);
			$session['user']['experience']+=$exp;
			output("`n`@Du erh�lst `^%s `@Erfahrungspunkte!`n`0", $exp);
			$session['user']['specialinc'] = "";
		}else {		// hier beginnt das Unheil, der Spieler ist hineingefallen...
			output("`n`2Vorsichtig setzt Du einen Fu� nach vorn, als Du pl�tzlich mit einem lauten Krachen in eine hinterh�ltig gelegte `b`@Fallgrube`b`2 hinabf�llst!`n");
			output("Als Deine Benommenheit von dem Sturz etwas nachgelassen hat und Deine Augen sich an das wenige Licht gew�hnt haben, siehst Du Dich um. ");
			output("Die Fallgrube scheint gut vier Meter tief zu sein, an den mit groben Werkzeugen bearbeiten W�nden ist ein Herausklettern nur schwer m�glich. ");
			output("Dir fallen auch noch ein paar angespitzte, in den Boden gerammte Baumst�mme auf und Du bist froh �berhaupt noch am Leben zu sein.`n");
			$rand = e_rand(1,10);
			if ($rand<=3) {		// zu 30% Lebenspunktverlust von 10%, aber man kann nicht daran sterben
				$hploss = round($session['user']['maxhitpoints']*0.1);
				if (($session['user']['hitpoints']-$hploss)<1) {
					$session['user']['hitpoints']=1;
				}else {
					$session['user']['hitpoints']-=$hploss;
				}
				switch($rand) {
				case 1: $verletzung = translate_inline("verstauchst Du Dir einen Fu�"); break;
				case 2: $verletzung = translate_inline("verletzt Du Dich an einem der angespitzten Baumst�mme"); break;
				case 3: $verletzung = translate_inline("schl�gst Du hart auf"); break;
				}
				output("`n`\$Bei dem Sturz %s und verlierst ein paar Lebenspunkte!`n`0", $verletzung);
			}
			addnav("Was tust du");
			addnav("Klettern", $from."op=klettern");
			output("`n`n<a href=\"".$from."op=klettern\">Versuche aus der Fallgrube zu klettern.</a>`n", true);
			addnav("", $from."op=klettern");
			addnav("Warten (-1 Wk)", $from."op=warten");
			output("`n<a href=\"".$from."op=warten\">Warte still auf Hilfe. `7(Das kostet Dich einen Waldkampf)</a>`n", true);
			addnav("", $from."op=warten");
			addnav("Rufen", $from."op=rufen");
			output("`n<a href=\"".$from."op=rufen\">Rufe laut nach Hilfe.</a>`n", true);
			addnav("", $from."op=rufen");
			addnav("Einsehen", $from."op=konsequent");
			output("`n<a href=\"".$from."op=konsequent\">Sieh die Aussichtslosigkeit Deiner Lage ein.</a>`n", true);
			addnav("", $from."op=konsequent");
		}		
		break;
// Klettern
	case "klettern":
		require_once("lib/fert.php");
		$klettern = get_fertigkeit("klettern");
		$erschoepfung = get_module_pref("erschoepfung");
		$adrenalin = get_module_pref("adrenalin");
		$mods = $erschoepfung + $adrenalin;
//		output("`nDEBUG: Mods : %s	Ersch�pfung : %s	Adrenalin : %s",$mods,$erschoepfung,$adrenalin);
		$probeklettern = probe($klettern,$mods,5,95);
//		output("`nDEBUG Probe: %s`n", $probe['wert']);
		if ($probeklettern['ergebnis']=="kritischer erfolg") {	// Kritischen Erfolg/Misserfolg auswerten...
			output("`n`2In einer dunklen Ecke der Fallgrube entdeckst Du eine Wurzel die fast wie ein Seil bis zu Dir herunterh�ngt. Du schnappst sie Dir und kletterst mit ihrer Hilfe unbeschadet hinauf.`n");
			output("`n`b`^Da hast Du ja mehr Gl�ck als Verstand gehabt!`b`0`n");
			addnav("Rausklettern", $from."op=rausklettern");
			output("`n`n<a href=\"".$from."op=rausklettern\">Ziehe Dich zum Rand der Fallgrube hoch.</a>`n", true);
			addnav("", $from."op=rausklettern");
		}elseif($probeklettern['ergebnis']=="kritischer misserfolg") {
			output("`n`2Du beginnst die W�nde der Grube zu erklimmen und h�lst dich dazu an einer in die Grube ragenden Wurzel fest. ");
			output("Beim Versuch Dich an ihr hochzuziehen gibt sie pl�tzlich nach und Du f�llst mit dem Rest der Wurzel in Deiner Hand r�ckw�rts in die Grube zur�ck. ");
			output("Ungl�cklicherweise brichst Du Dir beim Aufprall das Genick.`n");
			output("`n`b`\$Das war wohl Schicksal!`b`n");
			output("`n`4`bDu bist tot.`b`nDu verlierst all Dein Gold und 10 Prozent Deiner Erfahrung.`nDu kannst morgen weiterspielen.`0");
			addnav("Daily news","news.php");
			addnews("`q%s `)wurde tot in einer Fallgrube gefunden!", $session['user']['name']);
			debuglog("lost {$session['user']['gold']} gold dying after a critical miss in a pitfall.");
			$session['user']['hitpoints']=0;
			$session['user']['alive']=false;
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['specialinc'] = "";
		}else{			//... ansonsten normale Kletterprobe
			$geschafft = false;
			$hploss = 0;
			output("`n`2Skeptisch betrachtest Du die hohen W�nde der Fallgrube und suchst nach einer geeigneten Stelle, um zum Rand hinaufzuklettern. Dann beginnst du Deinen Versuch, der Fallgrube zu entkommen.`n");
			if ($erschoepfung>=5) {
				output("`n`@Noch hast du Kraftreserven!`n");
			}elseif($erschoepfung==0) {
				output("`n`@Du hast Deine Kraftreserven aufgezehrt!`n");
			}elseif($erschoepfung=-5) {
				output("`n`@Du bist ersch�pft von den Anstrengungen!`n");
			}else {
				output("`n`@Du kannst Dich kaum noch auf den Beinen halten!`n");
			}
			if ($adrenalin!=0) output("`n`@Im Rausch des durch deinen K�rper fliessenden Adrenalins mobilisierst du zus�tzliche Kr�fte!`n");
			$probe = $probeklettern['wert'];
			if ($probe<-50) {
				output("`n`b`\$Nicht geschafft!`b`n`2Du rutschst ab und schl�gst hart mit dem Hinterkopf auf, verletzt Dir ein Bein an den angespitzten Baumst�mmen und brichst Dir eine Rippe.`n`n`4Du verlierst viele Lebenspunkte.`n");
				$hploss = round($session['user']['maxhitpoints']*0.4);
			}elseif($probe>=-50 && $probe<-25) {
				output("`n`b`\$Nicht geschafft!`b`n`2Du rutschst ab und verletzt Dich beim Aufprall auf den Boden am Bein.`n`n`4Du verlierst ein paar Lebenspunkte.`n");
				$hploss = round($session['user']['maxhitpoints']*0.25);
			}elseif($probe>=-25 && $probe<0) {
				output("`n`b`QFast geschafft!`b`n`2Kurz vorm Ziel rutschst Du ab, aber du kannst Dich gerade noch abfangen und einen harten Aufprall verhindern.`n");
			}elseif($probe>=0 && $probe<25) {
				output("`n`b`^Geschafft!`b`n`2Du holst dir zwar auf dem Weg einige Schrammen und Kratzer, aber Du erreichst den Rand der Fallgrube.`n`n`4Du verlierst ein paar Lebenspunkte.`n");
				addnav("Rausklettern", $from."op=rausklettern");
				output("`n`n<a href=\"".$from."op=rausklettern\">Klettere aus der Fallgrube.</a>`n", true);
				addnav("", $from."op=rausklettern");
				$geschafft = true;
				$hploss = round($session['user']['maxhitpoints']*0.06);
				if (($session['user']['hitpoints']-$hploss)<1) $session['user']['hitpoints']=1;
				else $session['user']['hitpoints']-=$hploss;
			}elseif($probe>=25) {
				output("`n`b`^Geschafft!`b`n`2Mit geschickten Griffen und Z�gen erreichst Du sicher den Rand der Fallgrube.`n");
				addnav("Rausklettern", $from."op=rausklettern");
				output("`n`n<a href=\"".$from."op=rausklettern\">Klettere aus der Fallgrube.</a>`n", true);
				addnav("", $from."op=rausklettern");
				$geschafft = true;
			}
			if (!$geschafft) {
				if (($session['user']['hitpoints']-$hploss)>0) {
					$session['user']['hitpoints']-=$hploss;
					set_module_pref("erschoepfung",$erschoepfung-5);
					set_module_pref("adrenalin",0);
					addnav("Was tust Du");
					addnav("Klettern", $from."op=klettern");
					output("`n`n<a href=\"".$from."op=klettern\">Versuche noch einmal aus der Fallgrube zu klettern.</a>`n", true);
					addnav("", $from."op=klettern");
					addnav("Warten (-1 Wk)", $from."op=warten");
					output("`n<a href=\"".$from."op=warten\">Warte still auf Hilfe. `7(Das kostet Dich einen Waldkampf)</a>`n", true);
					addnav("", $from."op=warten");
					addnav("Rufen", $from."op=rufen");
					output("`n<a href=\"".$from."op=rufen\">Rufe laut nach Hilfe.</a>`n", true);
					addnav("", $from."op=rufen");
					addnav("Einsehen", $from."op=konsequent");
					output("`n<a href=\"".$from."op=konsequent\">Sieh nun endlich die Aussichtslosigkeit Deiner Lage ein.</a>`n", true);
					addnav("", $from."op=konsequent");
				}else{
					output("`n`4Leider endete der Sturz t�dlich f�r Dich.`nDu verlierst all dein Gold und 10 Prozent Deiner Erfahrung.`nDu kannst morgen weiterspielen.`n");
					addnews("`q%s `)wurde tot in einer Fallgrube gefunden!", $session['user']['name']);
					addnav("Daily news","news.php");
					debuglog("lost {$session['user']['gold']} gold dying in a pitfall.");
					$session['user']['hitpoints']=0;
					$session['user']['alive']=false;
					$session['user']['gold']=0;
					$session['user']['experience']*=0.9;
					$session['user']['specialinc'] = "";
				}
			}
		}
		break;
	case "rausklettern":
		output("`n`2Endlich aus der Fallgrube entkommen, atmest Du erst einmal tief durch. Dann durchf�hrt ein Gedanke Deinen Kopf:`n");
		addnav("Was tust Du");
		addnav("Verdecken", $from."op=verdecken");
		output("`n`n<a href=\"".$from."op=verdecken\">Ich sollte diese Fallgrube wieder zudecken, damit der n�chste auch hineinf�llt.</a>`n", true);
		addnav("", $from."op=verdecken");
		addnav("Markieren", $from."op=markieren");
		output("`n<a href=\"".$from."op=markieren\">Ich sollte diese gef�hrliche Fallgrube markieren, damit niemand mehr hier aus Versehen hineinst�rzt.</a>`n", true);
		addnav("", $from."op=markieren");
		break;
	case "verdecken":
		if (is_module_active('alignment')) align("-2");
		output("`n`2Du suchst Dir ein paar gr��ere �ste, dann ausreichend Zweige und Gr�nzeug und verdeckst die Fallgrube wieder. Zufrieden betrachtest Du Dein Werk. \"`6Warum sollte nur mir das passieren? Alle anderen haben das genauso verdient!`2\" Mit einem Grinsen machst Du Dich wieder auf in den Wald.`n`0");
		$session['user']['specialinc'] = "";
		break;
	case "markieren":
		if (is_module_active('alignment')) align("2");
		output("`n`2Du beseitigst die letzten Abdeckungen die noch auf der Fallgrube liegen und trampelst rundherum alles nieder, damit man sie sofort bemerkt. Schlie�lich baust Du noch einen kleinen Zaun aus St�ckern um die Grube. Zufrieden betrachtest Du Dein Werk: \"`6Nun ist alles erdenkliche getan, damit diese Fallgrube jedem auff�llt.`2\" Mit einem fr�hlichen Pfeifen machst Du Dich wieder auf in den Wald.`n`0");
		$session['user']['specialinc'] = "";
		break;
// Warten
	case "warten":
		if ($session['user']['turns']>0) {
			$session['user']['turns']--;
			$anzahlwarten = get_module_pref("anzahlwarten");
			set_module_pref("anzahlwarten", $anzahlwarten+1);
			$rand = e_rand(1,10);
			if ($anzahlwarten==4) $rand = 10;	// beim vierten Versuch soll der Spieler garantiert erl�st werden
			switch($rand) {
			case 1:
				switch(e_rand(0,1)) {
				case 0:
					output("`n`2Still kauerst Du in der Fallgrube und bist schon fast eingenickt, als das Ger�usch schwerer Schritte Dich aufhorchen l��t. Geistesgegenw�rtig rutschst Du in die dunkelste Ecke, als auch schon die riesige Gestalt eines Ogers am Rand der Fallgrube auftaucht. Er schn�ffelt kurz und schaut umher, dann h�rst Du nur noch, wie sich seine Schritte wieder entfernen.`n`n`@Gl�ck gehabt! Der Oger hat Dich nicht entdeckt!`n");
					addnav("Was tust du");
					addnav("Klettern", $from."op=klettern");
					output("`n`n<a href=\"".$from."op=klettern\">Versuche aus der Fallgrube zu klettern.</a>`n", true);
					addnav("", $from."op=klettern");
					addnav("Warten (-1 Wk)", $from."op=warten");
					output("`n<a href=\"".$from."op=warten\">Warte weiterhin still auf Hilfe. `7(Das kostet dich einen Waldkampf)</a>`n", true);
					addnav("", $from."op=warten");
					addnav("Rufen", $from."op=rufen");
					output("`n<a href=\"".$from."op=rufen\">Rufe laut nach Hilfe.</a>`n", true);
					addnav("", $from."op=rufen");
					addnav("Einsehen", $from."op=konsequent");
					output("`n<a href=\"".$from."op=konsequent\">Sieh nun endlich die Aussichtslosigkeit Deiner Lage ein.</a>`n", true);
					addnav("", $from."op=konsequent");
					break;
				case 1:
					output("`n`2Still kauerst Du in der Fallgrube und bist schon fast eingenickt, als Du pl�tzlich am Kragen nach oben gerissen wirst... ");
					output("Vor Dir steht ein Oger, der sich sofort auf Dich st�rzt, Dir bleibt keine Chance zur Flucht.`n");
					addnav("K�mpfe", $from."op=fight");
					output("`n`n<a href=\"".$from."op=fight\">Ziehe Deine Waffe und versuche Dein Leben zu retten.</a>`n", true);
					addnav("", $from."op=fight");
					break;
				}
				break;
			case 2:
			case 3:
				output("`n`2Unt�tig sitzt Du herum und drehst D�umchen. Du bildest dir ein, Stimmen geh�rt zu haben und schreist nach Leibeskr�ften um Hilfe. ");
				output("Aufgeregt springst Du auf dem Boden der Grube auf und ab und schlie�lich versuchst Du im Rausch des Adrenalins die Grubenw�nde zu erklimmen!`n");
				set_module_pref("adrenalin",25);
				addnav("Klettern", $from."op=klettern");
				output("`n`n<a href=\"".$from."op=klettern\">Versuche mit neuer Kraft aus der Fallgrube zu klettern.</a>`n", true);
				addnav("", $from."op=klettern");
				break;
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
				switch($rand) {	// das ist irgendwie technisch noch nicht so sch�n...
				case 4: 
					$text = translate_inline("Du beobachtest einen Regenwurm, der direkt vor Deinem Gesicht aus der Wand der Fallgrube gekrochen kommt. Das ist das Aufregendste was passiert.");
					break;
				case 5:
					$text = translate_inline("Du h�lst dir Deinen etwas schmerzenden Fu� und wartest. Doch kein Gr�usch dringt an Dein Ohr, nicht einmal das Zwitschern der V�gel des Waldes.");
					break;
				case 6: 
					$text = translate_inline("Es passiert nichts. Gar nichts, wirklich. Absolut und �berhaupt nichts.");
					break;
				case 7:
					$text = translate_inline("Du verfluchst Dein Ungeschick und grummelst w�tend vor Dich hin. Die Zeit vergeht, aber niemand erscheint.");
					break;
				case 8: 
					$text = translate_inline("Vom Rand der Fallgrube rieselt etwas Erde hinab. Als Du voller Hoffnung nach oben schaust, entdeckst Du jedoch nur einen Hasen, der neugierig zu Dir hinab sieht.");
					break;
				case 9:
					$text = translate_inline("Du beobachtest wie die Wolken �ber Dir hinziehen und sitzt verzweifelt auf dem kalten Boden der Fallgrube.");
					break;
				}
				output("`n`@Nichts passiert!`n");
				output("`n`2%s`n",$text);
				set_module_pref("erschoepfung",get_module_pref("erschoepfung")+5);
				addnav("Was tust du");
				addnav("Klettern", $from."op=klettern");
				output("`n`n<a href=\"".$from."op=klettern\">Versuche jetzt, wo du ausgeruhter bist, aus der Fallgrube zu klettern.</a>`n", true);
				addnav("", $from."op=klettern");
				addnav("Warten (-1 Wk)", $from."op=warten");
				output("`n<a href=\"".$from."op=warten\">Warte weiterhin still auf Hilfe. `7(Das kostet dich einen Waldkampf)</a>`n", true);
				addnav("", $from."op=warten");
				addnav("Rufen", $from."op=rufen");
				output("`n<a href=\"".$from."op=rufen\">Rufe laut nach Hilfe.</a>`n", true);
				addnav("", $from."op=rufen");
				addnav("Einsehen", $from."op=konsequent");
				output("`n<a href=\"".$from."op=konsequent\">Sieh nun endlich die Aussichtslosigkeit Deiner Lage ein.</a>`n", true);
				addnav("", $from."op=konsequent");
				break;
			case 10:	//�berarbeiten
				output("`n`2Ein Waldl�ufer, der sich Dir nicht vorstellt, kommt zuf�llig vorbei und rettet Dich. ");
				output("Voller Dankbarkeit gibst Du Deinem Retter etwas von Deinem Gold.`0");
				$session['user']['gold'] -= round($session['user']['gold']*0.25);
				$session['user']['specialinc'] = "";
				break;
			}
		} else {
			output("`n`2Auf dem Boden der Fallgrube wartend bemerkst Du die herannahende D�mmerung gar nicht. ");
			output("Als es schon fast ganz dunkel ist, kommt ein F�rster vorbei und rettet Dich aus Deiner misslichen Lage. ");
			output("�berschwenglich bedankst Du Dich und er gibt Dir den guten Rat mit auf den Weg, k�nftig darauf zu achten wo Du Deine Schritte hinsetzt.`0");
			$session['user']['specialinc'] = "";
		}
		break;
// Um Hilfe rufen
	case "rufen":
		output("`n`2Du legst Deine H�nde an den Mund und rufst so laut Du kannst: `@\"HILFE\"`2. Und dann gleich noch einmal: `@\"HIIILFE\"`2. ");
		output("Gespannt wartest Du einen Moment und lauschst, ob sich irgendetwas tut. Schliesslich rufst Du ein drittes Mal: `@\"HIIIIIILFE\"`2 und wartest dann was passiert.`n");
		if (is_module_active('mod_rp')) {	// das ganze ist bis jetzt nicht multiple-cities kompatibel!
			if (e_rand(1,6)==1) {	// Chance f�r Kommentar hier einstellen
				$comment = "`7Einige Anwesende glauben, einen schwachen Hilferuf aus dem Wald vernommen zu haben.";
				system_commentary('village',$comment);// das village m��te eine variable werden (f�r mult. cities), ist aber nicht kompatibel mit z.B. "village-$loc"...
			}
		}
		switch(e_rand(1,7)) {
		case 1:
		case 2:
		case 3:
			output("`n`2Du h�rst ein leises Ger�usch und schaust gespannt nach oben. Ein Kopf erscheint am Grubenrand und jemand schaut zu Dir hinunter. Aber leider kannst Du nicht erkennen wer es ist, denn Deine Augen haben sich schon an das Dunkel der Grube gew�hnt.");
			addnav("Flehe um Hilfe", $from."op=flehe");
			break;
		case 4:
		case 5:
		case 6:
		case 7:
			output("`2`nVon kr�ftigen H�nden wirst Du am Kragen gepackt und in die H�he gerissen. Ein Oger steht Dir gegen�ber und sabbert beim Gedanken an sein baldiges Festmahl. Deine einzige Chance ist ihn zu besiegen!`n");
			addnav("K�mpfe", $from."op=fight");
			output("`n`n<a href=\"".$from."op=fight\">Ziehe Deine Waffe und gib Dein Bestes.</a>`n", true);
			addnav("", $from."op=fight");
			break;
		}
		break;
	case "flehe":	// hier k�nnte man mit alignment was machen... zuf�lligen Spieler aus der DB suchen, nach seinem Alignment schauen, wenn gut rettet er dich, bei neutral 50/50, bei b�se lacht er dich aus
		$sql = "SELECT acctid,name,sex FROM ". db_prefix("accounts") ." WHERE loggedin=1 AND acctid<>".$session['user']['acctid']."";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$alleretter = array();
		$i = 0;
		while ($row) {
			$alleretter[$i]['acctid'] = $row['acctid'];
			$alleretter[$i]['name'] = $row['name'];
			$alleretter[$i]['sex'] = $row['sex'];
			$row = db_fetch_assoc($result);
			$i++;
		}
		if (sizeof($alleretter)==0) {
			$retter['name'] = "Balduin, der F�rster";
			$retter['sex'] = 0;
			$retteralign = 100;
		} else {
			$r = e_rand(0,sizeof($alleretter)-1);
			$retter = $alleretter[$r];
			if (is_module_active('alignment')) $retteralign = get_align($retter['acctid']);
		}
		// test auf ehepaar einbauen? damit man den partner immer rettet... welches heiratsmodul?
		output("`n`6\"Wer ist da? Helft mir!\"`n`n`2 Auf Knien flehst Du um Rettung, obwohl Du noch nicht einmal wei�t, zu wem Du eigentlich sprichst. Du kneifst die Augen zusammen und blinzelst nach oben.`n");
		output("`n`3\"Mein Name ist `#%s`3\"`2, h�rst Du die Antwort von oben. ", $retter['name']);
		if ($session['user']['marriedto']==$retter['acctid']) {
			$gerettet = 1;
			output("`n`n`6%s`6, ich bin's, %s!`n", $retter['name'], $session['user']['sex']?"deine Frau":"dein Mann");
			output("`n`3Um Himmels Willen, %s, was ist dir passiert? Warte kurz, ich helfe dir heraus...", $session['user']['sex']?"Liebste":"Liebster");
		}else {
			if ($retteralign<get_module_setting("evilalign","alignment")) {
				output("`2%s lacht Dich an. `3\"Aber Du glaubst doch nicht das ich Dich hier heraushole. Wer bin ich denn?\"`n`n`2Dann h�rst Du nur noch ein lautes Lachen das sich langsam entfernt.`n", $retter['sex']?"Sie":"Er");
				$gerettet = 0;
			}elseif ($retteralign>get_module_setting("goodalign","alignment")) {
				output("`n`3\"Ich hole Euch hier heraus, wartet einen Moment!\"`n`n`2Nach kurzer Zeit f�llt neben Dir das Ende eines Seiles herab, Du ergreifst es und kletterst mit seiner Hilfe aus der Fallgrube.`n");
				$gerettet = 1;
			}else{	// bei neutral 50/50 Chance
				output("`2Etwas unschl�ssig schaut %s auf Dich hinab und �berlegt kurz. ", $retter['sex']?"sie":"er");
				if (e_rand(1,10)<6) {
					output("`3\"Nein, ich weiss nicht... Seht, ich k�nnte auch in die Grube hinabst�rzen, dann w�ren wir beide gefangen. Das ist ein zu gro�es Risiko. Aber ich, �h, werde jemandem Bescheid sagen, wenn ich wieder in %s bin.\" ", $session['user']['location']);
					output("`2Damit verschwindet `#%s`2 und Dich beschleicht das dumpfe Gef�hl, das heute wohl niemand zur�ckkommen wird um Dir zu helfen.`n", $retter['name']);
					$gerettet = 0;
				}else{
					output("`3\"Mhh, wie soll ich Euch da nur rausholen? Ich schaue mich kurz mal um.\" `2Als der Kopf am Grubenrand verschwindet, sp�rst Du ein beklemmendes Angstgef�hl in Dir aufsteigen, aber Du bleibst ruhig und wartest. ");
					output("Erleichtert h�rst Du kurz darauf ein Schnaufen und Schritte. `3\"Wie konntet Ihr aber auch nur so unvorsichtig sein?\" `2Mit diesen Worten erscheint `#%s`2 wieder am Rand der Fallgrube. ", $retter['name']);
					output("`3\"Ich habe ein altes Seil gefunden, es sollte halten. Aber seid trotzdem vorsichtig beim Hinaufklettern!\"`n");
					$gerettet = 1;
				}
			}
		}
		if ($gerettet) {
			output("`n`@`bDu wurdest aus der Fallgrube befreit!`b`n");
			output("`n`2Die Rettungsaktion ben�tigt einige Zeit, so da� Du nun einen Waldkampf weniger zur Verf�gung hast.`0");
			if ($session['user']['turns']) $session['user']['turns']--;
			$session['user']['specialinc'] = "";
		}else {
			output("`n`\$`bDu bist immer noch in der Fallgrube!`b`n");
			addnav("Was tust du");
			addnav("Klettern", $from."op=klettern");
			output("`n`n<a href=\"".$from."op=klettern\">Versuche aus der Fallgrube zu klettern.</a>`n", true);
			addnav("", $from."op=klettern");
			addnav("Warten (-1 Wk)", $from."op=warten");
			output("`n<a href=\"".$from."op=warten\">Warte still auf Hilfe. `7(Das kostet dich einen Waldkampf)</a>`n", true);
			addnav("", $from."op=warten");
			addnav("Rufen", $from."op=rufen");
			output("`n<a href=\"".$from."op=rufen\">Rufe noch einmal laut nach Hilfe.</a>`n", true);
			addnav("", $from."op=rufen");
			addnav("Einsehen", $from."op=konsequent");
			output("`n<a href=\"".$from."op=konsequent\">Sieh nun endlich die Aussichtslosigkeit deiner Lage ein.</a>`n", true);
			addnav("", $from."op=konsequent");
		}
		break;
// Kampf
	case "fight":
		ogre_fight();
		break;
	case "gewonnen":
		output("`n`2Mit Abscheu betrachtest du den erschlagenen Oger, dem du deinen Aufenthalt in der Fallgrube verdankst. Wieder etwas bei Kr�ften raffst du dich auf und durchsuchst die Taschen des Ogers");
		$rand = e_rand(1,4);
		switch($rand) {
		case 1:
		case 2:
			output(", aber findest nichts n�tzliches. Letztendlich bist du froh, aus der Fallgrube entkommen zu sein und den Kampf �berlebt zu haben.");
			break;
		case 3:
			$gold = e_rand(20,40)*$session['user']['level'];
			$session['user']['gold']+=$gold;
			output(" und findest etwas Gold. Beim genaueren Nachz�hlen stellst du fest, das es `^%s`2 Goldst�cke sind.", $gold);
			debuglog("found $gold gold after killing an ogre.");
			break;
		case 4:
			$curlevel = $session['user']['level'];
			$curdk = $session['user']['dragonkills'];
			$exppct = e_rand(5,10);
			$exp = round((exp_for_next_level($curlevel, $curdk)-exp_for_next_level($curlevel-1, $curdk))*$exppct/100);
			$session['user']['experience']+=$exp;
			output(", aber findest nichts n�tzliches. Aber das war dir eine Lehre, du erh�lst `^%s`2 Erfahrungspunkte.", $exp);
			break;
		}
		output_notl("`0");
		$session['user']['specialinc'] = "";
		break;
// Selbstmord - Erfahrungsbonus f�r verlorenes Gold
	case "konsequent":
		output("`n`2Du siehst die Aussichtlosigkeit deiner Lage ein und beschlie�t die entsprechenden Konsequenzen zu ziehen:`n`n`c`\$~~~ Einen `behrenhaften`b Tod! ~~~`c`n");
		addnews("`b`4%s`b `\$brachte sich in aussichtsloser Lage selbst um!", $session['user']['name']);
		addnav("Daily news","news.php");
		debuglog("lost {$session['user']['gold']} gold dying in a pitfall.");
		$session['user']['hitpoints']=0;
		$session['user']['alive']=false;
		$session['user']['experience']+=round(($session['user']['gold']/10),0);
		$session['user']['gold']=0;
		$session['user']['specialinc'] = "";
		break;
	} //end switch
}

function fallgrube_run(){
}
?>
