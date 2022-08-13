<?php
/*
Letzte Änderungen am 22.03.2005 von Michael Jandke

Ein Hütchenspieler für den Platz der Völker 
- Basisversion -

*********************************************************
*	Diese Datei sollte aus fertigkeiten.zip stammen.	*
*														*
*	Näheres siehe: dokumentation.txt					*
*														*
*	Achtung: Wer diese Datei benutzt, verpflichtet		*
*	sich, alle Module, die er für das Fertigkeiten-		*
*	system entwickelt frei und öffentlich zugänglich	*
*	zu machen!											*
*	Wir entwickeln für Euch - Ihr entwickelt für uns.	*
*														*
*********************************************************

To Do:	- möglicherweise noch einen Tip für die Auswahl, bassierend auf einem noch zu bestimmenden Wert, z.B. Glücksspiel, oder allgemein Glück...
		  bei niedrigem Glückswert "schummelt" der Hütchenspieler und gewinnt immer, bei höherem Glückswert ein immer verläßlicher werdender Tip
		- die drei Hütchen als nebeneinander angeordnete Buttons
		- mögliche Erweiterung: - man kann "Söldner" einmal pro Tag anheuern für Edelsteine (den Troll (starker Kampfbuff), den Mensch (Kampfbuff) oder einen kleinen Jungen (mehr Gold aus Wk)) 
		- Speichern des Gewinnes des Goldspielers (einmal interessehalber, andererseits kann man damit ja auch wieder was machen - Auszahlung, Spende, Ereignis wenn Pott voll,...)
		- "Schwarzmarkt" - Edelsteinhandel zwischen Spielern über ihn (Balanceideen noch nicht ausgereift)
		- DK-abhängig? zumindest anheuern
		- Balnacing der Buffs!!! Scherge!
*/


function hspieler_getmoduleinfo(){
    $info = array(
		"name"=>"PdV - Hütchenspieler",
		"version"=>"1.0",
		"author"=>"Michael Jandke",
		"category"=>"Der Platz der Voelker",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("wettkampf"=>"1.0|Platz der Völker von Oliver Wellinghoff"),
		"settings"=>array(
			"Der Hütchenspieler - Settings,title",
			"maxeinsatz"=>"Wie hoch ist der maximale Spieleinsatz (mal Spielerlevel) ?, range,1,200,1|30",
			"gewinnfaktor"=>"Das wievielfache des Einsatzes erhält der Spieler als Gewinn?,floatrange,1,10,0.5|3",
			"winsprotag"=>"Wie oft darf der Spieler maximal pro Tag gewinnen?, range,1,5,1|3",
			"bilanz"=>"Die Bilanz des Hütchenspielers,viewonly|0",	
			"anheuern"=>"Kann man beim Hütchenspieler Söldner anheuern?,bool|1",
			"jungegold"=>"Wieviel mehr Gold läßt der kleine Junge den Spieler im Wald finden (in Prozent),range,0,100,1|20",
			"chance"=>"Wie groß ist die Chance das er während des Festes erscheint (in Prozent)?,range,0,100,1|100",
			"appear"=>"Ist der Stand gerade anwesend (nur während Fest)?,bool|0",
		),
		"prefs"=>array(
			"Der Hütchenspieler - User Settings,title",
			"anzwins"=>"Wie oft hat der Spieler heute schon gewonnen?,int|0",
			"playedtoday"=>"Hat der Spieler heute schon einmal gespielt?,bool|0",
			"hit"=>"Wo war die Erbse versteckt?,viewonly|0",
			"gewinn"=>"Letzter Gewinn waren,viewonly|0"
		)
	);
	return $info;
}

function hspieler_install(){
	module_addhook("newday");
	module_addhook("newday-runonce");
	module_addhook("pdvstände");
	module_addhook("creatureencounter");
	return true;
}

function hspieler_uninstall(){
	return true;
}

function hspieler_dohook($hookname,$args){
	global $session;
	switch($hookname) {
	case "newday":
		set_module_pref("anzwins",0);
		set_module_pref("playedtoday",0);
		set_module_pref("hit",0);
		set_module_pref("gewinn",0);
		break;
	case "newday-runonce":
		if (get_module_setting("fest","wettkampf")==1) {
			$chance = get_module_setting("chance","hspieler");
			if (e_rand(1,100)<=$chance) set_module_setting("appear",1,"hspieler");
			else set_module_setting("appear",0,"hspieler");
			debug("hspieler appear = ".get_module_setting("appear"));
		}
		break;
	case "pdvstände":
		$werte = array(	"name"=>"Zwielichter Geselle",		// Text der im Link erscheinen soll
						"appear"=>get_module_setting("appear","hspieler"));	// Abfrage ob anwesend oder nicht
		$args['hspieler'] = $werte;
		break;
	case "creatureencounter":		// Zwerge davon ausnehmen?
		if (has_buff("hspielerjunge")){
			$gain = (get_module_setting("jungegold")/100) + 1;
			$args['creaturegold']=round($args['creaturegold']*$gain,0);
		}
		break;
	}
    return $args;    
}

function hspieler_run() {
    global $session;
	$op = httpget('op');
	if ($op=="viaForm") {
		$buttonresult = httppost("LinkerButton").httppost("MittlererButton").httppost("RechterButton");
		if ($buttonresult=='Linkes Hütchen') { $op="linke";  }
		if ($buttonresult=='Rechtes Hütchen') { $op="rechte"; }
		if ($buttonresult=='Mittleres Hütchen') { $op="mittlere"; }
	}
	$from = "runmodule.php?module=hspieler&";
	$maxbet = get_module_setting("maxeinsatz") * $session['user']['level'];
	$gewinnfaktor = get_module_setting("gewinnfaktor");
	$anheuern = (int)get_module_setting("anheuern");
	$ct = "`2";		// Colorcode für normalen Text
	$cs = "`q";		// Colorcode für den Hütchenspieler
	$ce = "`&";		// Colorcode für das Alte Ego
	page_header("Der Hütchenspieler");
	
	switch($op) {
	case "spiele":
		if ($session['user']['gold']>0) {					// Spieler muß Gold haben
			$bet = (int)httppost('bet');
			if ($bet<=0) {		// kleiner trotz abs() ??
				addnav("Doch nicht spielen","runmodule.php?module=hspieler");
				output("`n%s\"%sAlso gut.%s\" Trajan knackt kurz mit den Fingern und setzt sich dann hinter seine Obstkiste.", $ct, $cs, $ct);
				output("`n`n%s\"%sDer höchste Einsatz um den ich mit dir spielen werde, sind `^$maxbet %sGoldstücke. Wenn du gewinnst, bekommst du das ".$gewinnfaktor."-fache deines Einsatzes zurück.%s\"`n`0", $ct, $cs, $cs, $ct);
				rawoutput("<form action='runmodule.php?module=hspieler&op=spiele' method='POST'>");
				rawoutput("<input name='bet' id='bet'>");
				$play = translate_inline("Spielen");
				rawoutput("<input type='submit' class='button' value='$play'>");
				rawoutput("</form>");
				output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
				addnav("","runmodule.php?module=hspieler&op=spiele");
			}elseif($bet>$session['user']['gold']) {		// Spieler hat nicht soviel Gold
				output("`n%sWütend schaut er dich an. \"%sWillst du um Gold spielen das du gar nicht hast? Verschwinde bloß, bevor mein Freund hier mal mit dir spielt!%s\" Der Troll hinter ihm tritt aus dem Schatten, du blickst in seine häßliche Fratze und suchst lieber schnell das Weite.`n`0", $ct, $cs, $ct);
				addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
				output("`n<a href = 'runmodule.php?module=wettkampf'>Zurück zum Platz der Völker.</a>`n", true);
				addnav("","runmodule.php?module=wettkampf");
			}elseif ($bet>$maxbet) {						// Der Maximaleinsatz wird überschritten
				output("`n%sTrajan schüttelt den Kopf.`n`n\"%sDer Maximaleinsatz ist `^$maxbet%s Gold. Um mehr Gold spiele ich nicht, irgendwer kommt sonst bloß noch auf die Idee, das es sich hier um ein illegales Glücksspiel handeln könnte...$ct\"`n", $ct, $cs, $cs, $ct);
				addnav("Spielen","runmodule.php?module=hspieler&op=spiele");
				output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Spiele mit einem erlaubten Einsatz.</a>`n", true);
				addnav("","runmodule.php?module=hspieler&op=spiele");	
				addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
				output("`n<a href = 'runmodule.php?module=wettkampf'>Zurück zum Platz der Völker.</a>`n", true);
				addnav("","runmodule.php?module=wettkampf");
			}else {											// ansonsten wird gespielt
				$session['user']['gold']-=$bet;
				set_module_setting("bilanz",get_module_setting("bilanz")+$bet);
				debuglog("setzte $bet Gold beim Hütchenspieler.");
				$melone = e_rand(1,3);						// zweimal Fake
				output("`n%s\"%sViel Glück und pass gut auf.%s\" Er legt die Erbse unter das mittlere Hütchen und beginnt langsam einige Hütchen zu vertauschen. \"%sSo, jetzt ist sie hier%s\" sagt er und zeigt dir die Erbse unter dem `^".($melone==1?"linken":($melone==2?"mittleren":"rechten"))."%s Hütchen.`n", $ct, $cs, $ct, $cs, $ct, $ct);
				$melone = e_rand(1,3);
				output("`n%sDann macht er weiter, diesmal etwas schneller und länger und zeigt dir dann die Erbse unter dem `^".($melone==1?"linken":($melone==2?"mittleren":"rechten"))."%s Hütchen.`n", $ct, $ct);
				output("`n%sDu machst ein Gesicht als sei dir das natürlich klar gewesen und hoffst, er merkt nichts davon das es dir schon jetzt viel zu schnell ging. \"%sUnd nun zählt es%s\" meint er, schaut dich nochmal grinsend an und beginnt dann in rasender Geschwindigkeit die Hütchen zu vertauschen. Kurzzeitig glaubst du drei Arme zu sehen, dann hört er ganz plötzlich auf und in triumphierendem Ton fragt er:`n`n\"%sNun, unter welchem Hütchen ist die Erbse?%s\"`n", $ct, $cs, $ct, $cs, $ct);
								
				$melone = e_rand(1,3);						// echtes Ergebnis auswürfeln
				set_module_pref("hit", $melone);			// speichern... geht es auch anders, das die Variablen ihren Wert behalten?
				$gewinn = round($bet*$gewinnfaktor);
				set_module_pref("gewinn", $gewinn);			// speichern... 
//				output("`nDEBUG: Erbse ist unter dem ".($melone==1?"linken":($melone==2?"mittleren":"rechten"))." Hütchen.`nErbse: %s", $melone);
				output("`n%sEtwas zögernd antwortest du:`n`n", $ct);
				addnav("Unter dem linken","runmodule.php?module=hspieler&op=linke");
				addnav("Unter dem mittleren","runmodule.php?module=hspieler&op=mittlere");
				addnav("Unter dem rechten","runmodule.php?module=hspieler&op=rechte");
				rawoutput("<center>");	
				rawoutput("<form action='$from"."op=viaForm' method='POST'>");
				rawoutput("<input name='LinkerButton' type='submit' class='button' value='Linkes Hütchen'>");
				rawoutput("<input name='MittlererButton' type='submit' class='button' value='Mittleres Hütchen'>");
				rawoutput("<input name='RechterButton' type='submit' class='button' value='Rechtes Hütchen'>");
				rawoutput("</form>");
				rawoutput("</center>");
				addnav("",$from."op=viaForm");
				}
		}else{
			output("`n%sSkeptisch schaut er erst dich an, dann mit prüfendem Blick deinen Geldbeutel und schnauzt \"%sDu hast doch überhaupt kein Gold, du armer Schlucker. Jetzt verschwinde schon, bevor ich meine Freunde hier auf dich loslasse.%s\"`n", $ct, $cs, $ct);	
			addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
			output("`n<a href = 'runmodule.php?module=wettkampf'>Zurück zum Platz der Völker.</a>`n", true);
			addnav("","runmodule.php?module=wettkampf");
		}
		break;
		
// Auswertung
	case "linke":
	case "mittlere":
	case "rechte":
		set_module_pref("playedtoday",true);
		$gewonnen = false;	
		$melone = get_module_pref("hit");
//		output("`nDEBUG: Auswahl: %s und Erbse: %s.`n", $op, $melone);
		if ($op=="linke" && $melone==1) {
			$gewonnen = true;
		}elseif ($op=="mittlere" && $melone==2) {
			$gewonnen = true;
		}elseif ($op=="rechte" && $melone==3) {
			$gewonnen = true;
		}else{
//			output("`nIrgendjemand betrügt hier! Oder besser gesagt, du hast verloren!`n");
		}
		if ($gewonnen == true) {
			set_module_pref("anzwins",get_module_pref("anzwins")+1);
			$gewinn = get_module_pref("gewinn");
			$session['user']['gold']+=$gewinn;
			set_module_setting("bilanz",get_module_setting("bilanz")-$gewinn);
			debuglog("gewann $gewinn Gold beim Hütchenspieler.");
			output("`n%sLangsam hebt er das `^$op%s Hütchen an und die Erbse ist darunter!`n", $ct, $ct);
			output("`n`b`^Du hast gewonnen!`b`n`n%sEtwas überrascht schaut er auf die Erbse und zwingt sich dann zu einem Lächeln. \"%sWie hast du das gemacht... ich meine, woher wußtest... na egal, hier hast du deinen Gewinn.%s\"`nDu bekommst `^%s%s Goldstücke!`n`0", $ct, $cs, $ct, $gewinn, $ct);
			if (get_module_pref("anzwins")<get_module_setting("winsprotag")) {
				addnav("Nochmal spielen","runmodule.php?module=hspieler&op=spiele");
				output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Spiele gleich noch einmal.</a>`n", true);
				addnav("","runmodule.php?module=hspieler&op=spiele");
			}else{
				output("`n%sEtwas gereizt schaut er dich an.`n`n\"%sDu hast jetzt ".get_module_setting("winsprotag")." mal gewonnen. Das war's dann für heute, aus ...äh, geschäftlichen Gründen muß ich dich auffordern meine Dienste nicht weiter in Anspruch zu nehmen.%s\" \"%sGerade jetzt wo meine Glückssträhne anfängt%s\" schreist du, aber als die beiden Schläger des Hütchenspielers drohend aus dem Schatten hervortreten, ziehst du es doch vor, zu verschwinden.`n`n`0", $ct, $cs, $ct, $ce, $ct);	
			}
			addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
			output("`n<a href = 'runmodule.php?module=wettkampf'>Zurück zum Platz der Völker.</a>`n", true);
			addnav("","runmodule.php?module=wettkampf");
		}else{
			output("`n`b`\$Du hast verloren!`b`n`n%s Trajan hebt das `^".($melone==1?"linke":($melone==2?"mittlere":"rechte"))."%s Hütchen hoch und zeigt dir die darunter liegende Erbse.`n\"%sDa wäre sie gewesen. Leider falsch geraten.%s\" Mit einem Lächeln auf den Lippen nimmt er deine Goldmünzen und steckt sie in die Taschen seiner alten Weste.`n`0", $ct, $ct, $cs, $ct);
			addnav("Nochmal spielen","runmodule.php?module=hspieler&op=spiele");
			output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Spiele gleich noch einmal.</a>`n", true);
			addnav("","runmodule.php?module=hspieler&op=spiele");
			addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
			output("`n<a href = 'runmodule.php?module=wettkampf'>Zurück zum Platz der Völker.</a>`n", true);
			addnav("","runmodule.php?module=wettkampf");				
		}
		break;
// Regeln
	case "regeln":	
		output("%s\"%sAlso das mit den Regeln ist ganz einfach.%s\" erklärt dir Trajan. \"%sSiehst du diese Erbse hier?%s\" In seinen Fingern hält er eine kleine, gelb-braune Erbse, die er dir nahe unter deine Nase hält. \"%sUnd hier habe ich drei Hütchen%s\" sagt er und deutet auf das Brett vor ihm. \"%sJetzt tue ich diese Erbse hier unter das mittlere Hütchen und dann werde ich die Hütchen vertauschen. Achte genau auf das mit der Erbse drunter!%s\" ", $ct, $cs, $ct, $cs, $ct, $cs, $ct, $cs, $ct);
		output("Dann greift Trajan sich mit der rechten Hand das rechte Hütchen und mit der linken Hand das mittlere Hütchen und vertauscht beide. %s\"Na, wo ist die Erbse jetzt?%s\" fragt er mit süffisantem Lächeln. Deine Antwort kommt sofort \"%sUnter dem rechten natürlich!%s\" Er hebt das rechte Hütchen an und zeigt dir die darunter liegende Erbse. \"%sGut gemacht! Und jetzt weiter...%s\" ", $cs, $ct, $ce, $ct, $cs, $ct);
		output("Er vertauscht, diesmal ein klein wenig schneller, das rechte und das linke Hütchen, danach das linke mit dem mittleren. \"%sWeißt du auch jetzt noch wo die Erbse ist?%s\" \"%sWieder unter dem mittleren?%s\" Er hebt das mittlere Hütchen langsam an und die Erbse darunter kommt zum Vorschein. \"%sDu bist ja ein echtes Naturtalent! Damit das ganze etwas reizvoller ist, spiele ich aber eigentlich um Gold. ",$cs, $ct, $ce, $ct, $cs);
		output("Hättest du gerade eben `^10%s Goldstücke gesetzt, so hättest du jetzt `^".round(10*$gewinnfaktor)."%s Goldstücke als Gewinn wiederbekommen. Der Maximaleinsatz um den ich spiele sind `^".$maxbet."%s Goldstücke... Ich denke jetzt ist alles klar, willst du ein Spielchen machen?%s\"`n", $cs, $cs, $cs, $ct);
		if (get_module_pref("anzwins")<get_module_setting("winsprotag")) {
			addnav("Spielen","runmodule.php?module=hspieler&op=spiele");
			output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Wage mal ein Spiel.</a>`n", true);
			addnav("","runmodule.php?module=hspieler&op=spiele");
		}
		addnav("Häh?","runmodule.php?module=hspieler&op=weg");
		output("`n<a href = 'runmodule.php?module=hspieler&op=weg'>Schaue ihn mit großen Augen an und sage \"Häh?\"</a>`n", true);
		addnav("","runmodule.php?module=hspieler&op=weg");
		break;
	case "weg":
		output("`n%s\"%sWie kann man das nicht verstehen, das ist doch total simpel... Oder willst du mich hier veralbern?%s\" Als du ein breites Grinsen nicht unterdrücken kannst, nickt er wütend zu den beiden hinter ihm im Schatten stehenden Gestalten und zischt \"%sVerschwinde du Spaßvogel, oder meine Freunde werden dir zeigen was ich von Leuten wie dir halte...%s\" Du streckst ihm die Zunge raus, haust dann aber schnell ab und versteckst dich in der Menge der Festbesucher.`n", $ct, $cs, $ct, $cs, $ct);
		addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
		output("`n`n<a href = 'runmodule.php?module=wettkampf'>Zurück zum Platz der Völker.</a>`n", true);
		addnav("","runmodule.php?module=wettkampf");
		break;
// Begrüßung
	case "":
		output("`n%sIm Schatten, halb zwischen zwei anderen Ständen verborgen, lungert ein etwas verwahrlost wirkender Mensch mit Stoppelbart und langen, schwarzen und fettigen Haaren. Ein Stück hinter ihm, sich im Schatten haltend, stehen zwei kräftige Gestalten von denen mindestens einer ein Troll zu sein scheint. Der Mann hat vor sich auf einer alten Obstkiste ein Brett und drei Hütchen stehen. `n", $ct);		
		if (get_module_pref("anzwins")<get_module_setting("winsprotag")) {
			output("%sAls er Deiner Aufmerksamkeit gewahr wird, entblößt er seine gelben Zähne kurz bei einem schiefen Grinsen, dann nickt er Dir zu und fragt: \"%sSei gegrüßt ".($session['user']['sex']?"kleines Fräulein":"mein Junge").", ich bin Trajan. Hast Du Interesse an einem Spielchen?%s\"`n", $ct, $cs, $ct);
			if (get_module_pref("playedtoday")==true) output("`n%s\"%sVersuch doch nochmal Dein Glück. Nur noch ein Spielchen, komm schon.%s\"`n", $ct, $cs, $ct);
			if ($anheuern) output("`n%s\"%sSolltest du Probleme haben, die man mit einem spitzen Dolch oder einer großen Keule lösen kann, dann kannst Du Dich auch vertrauensvoll an mich wenden, frage nur...%s\"`n", $ct, $cs, $ct);
			output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Spiele das Hütchenspiel mit ihm.</a>`n", true);
			addnav("","runmodule.php?module=hspieler&op=spiele");
			addnav("Spielen","runmodule.php?module=hspieler&op=spiele");
		}else{
			output("`n%sEtwas gereizt fährt er Dich an: \"%sMit Dir spiele ich heute nicht mehr, Du hast mich schon vollkommen ausgenommen!%s\"`n", $ct, $cs, $ct);	
			if ($anheuern) output("`n%s\"%sSolltest Du Probleme haben, die man mit einem spitzen Dolch oder einer großen Keule lösen kann, dann kannst Du Dich auch vertrauensvoll an mich wenden, frage nur...%s\"`n", $ct, $cs, $ct);
		}
		output("`n<a href = 'runmodule.php?module=hspieler&op=regeln'>Frage Trajan nach den Regeln des Spieles.</a>`n", true);
		addnav("","runmodule.php?module=hspieler&op=regeln");
		addnav("R?Regeln","runmodule.php?module=hspieler&op=regeln");
		if ($anheuern) {
			output("`n<a href = 'runmodule.php?module=hspieler&op=anheuern'>Frage ihn nach Problemlösungen.</a>`n", true);
			addnav("","runmodule.php?module=hspieler&op=anheuern");
			addnav("Problemlösungen","runmodule.php?module=hspieler&op=anheuern");
		}
		addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
		output("`n<a href = 'runmodule.php?module=wettkampf'>Zurück zum Platz der Völker.</a>`n", true);
		addnav("","runmodule.php?module=wettkampf");
		break;
// Söldner anheuern
	case "anheuern":
		output("`n%s\"%sAh, ich seh schon. Du brauchst etwas Hilfe bei Deinen Unternehmungen und suchst nun nach einem vertrauenswürdigen Partner, der Dir die nötige Unterstützung zukommen lassen kann. Da bist Du bei mir genau richtig! ", $ct, $cs);
		output("%sFür nur 2 Edelsteine kannst Du einen meiner 3 Kumpane anheuern.%s\"`n", $cs, $ct);
		if ($session['user']['gems']>1) {
			output("`n%s\"%sDrei?%s\", fragst Du. \"%sIch seh nur zwei.%s\" `n`n", $ct, $ce, $ct, $ce, $ct);
			output("%sEin breites Grinsen geht über Trajans Gesicht und er deutet mit einem Kopfnicken zu einem kleinen Jungen, der die ganze Zeit völlig unauffällig neben Dir stand. \"%sKommt ganz darauf an, wobei Du Hilfe brauchst.%s\"`n", $ct, $cs, $ct);
			output("`n%s\"<a href = 'runmodule.php?module=hspieler&op=troll'>`2Der Troll</a>%s haut drauf wie nichts, aber er ist etwas langsam. Zeige ihm die Richtung und er wird durch Deine Feinde pflügen.%s\"`n", $ct, $cs, $ct, true);
			addnav("", "runmodule.php?module=hspieler&op=troll");
			output("`n%s\"<a href = 'runmodule.php?module=hspieler&op=mensch'>`!Der Mensch</a>%s ist da schon geschickter, er ist ein hinterhältiger Kämpfer. Er scheut den offenen Kampf und wird Dich aus dem Schatten heraus unterstützen, wenn Du verstehst was ich meine.%s\"`n", $ct, $cs, $ct, true);
			addnav("", "runmodule.php?module=hspieler&op=mensch");
			output("`n%s\" <a href = 'runmodule.php?module=hspieler&op=junge'>`7Der kleine Junge</a>%s wird Dir im Kampf nicht viel nützen, da hält er sich geschickt raus. Aber wenn Du nach Deinem Sieg wirklich ALLES Gold finden willst, das Dein Gegner bei sich hatte, dann gibt es keinen Besseren.%s\"`n", $ct, $cs, $ct, true);
			addnav("", "runmodule.php?module=hspieler&op=junge");
			addnav("T?Troll", "runmodule.php?module=hspieler&op=troll");
			addnav("M?Mensch", "runmodule.php?module=hspieler&op=mensch");
			addnav("J?Kleiner Junge", "runmodule.php?module=hspieler&op=junge");
		}else {
			output("`n%s\"%sDu solltest natürlich die Edelsteine dabei haben, sonst kann ich nichts für Dich tun.%s\"`n", $ct, $cs, $ct);	
		}
		addnav("s?Lieber spielen","runmodule.php?module=hspieler&op=spiele");
		addnav("Z?Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
		break;
	case "troll":
		$session['user']['gems']-=2;
		output("`n%sBlitzschnell verschwinden die Edelsteine in seinen Taschen. \"%sGute Wahl, der Troll ist zwar etwas dumm, aber stark wie sonst keiner! Zeige ihm die Richtung und er wird durch Deine Feinde pflügen.%\"`0", $ct, $cs, $ct);
		strip_buff("hspielerjunge");
		apply_buff("hspielerscherge",array(
			"name"=>"`2Trajan's Troll",
			"rounds"=>e_rand(15,25),
			"wearoff"=>"`2Der Troll grunzt und trabt zurück zu Trajan.",
			"atkmod"=>1.3,
			"defmod"=>1.1,			
			"roundmsg"=>"`2Der Troll verteilt wuchtige Schläge und {badguy} bekommt sie zu spüren.",
			"schema"=>"module-hspieler"
			)
		);
		addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
		break;
	case "mensch":
		$session['user']['gems']-=2;
		output("`n%sBlitzschnell verschwinden die Edelsteine in seinen Taschen. \"%sGute Wahl, nicht so stark wie der Troll, dafür aber auch etwas hinterhältiger.%s\" `0", $ct, $cs, $ct);
		strip_buff("hspielerjunge");
		apply_buff("hspielerscherge",array(
			"name"=>"`!Trajan's Scherge",
			"rounds"=>e_rand(15,35),
			"minioncount"=>floor(($session['user']['level']-1)/5)+1,
			"maxbadguydamage"=>round($session['user']['attack']/3) + floor($session['user']['dragonkills']/4),
			"effectmsg"=>"`!Der Scherge schleicht sich hinter {badguy} und trifft für `^{damage}`! Schaden.",
			"effectnodmgmsg"=>"`!{badguy} hat den Schergen entdeckt und `\$weicht aus`!!",
			"wearoff"=>"`!Der Scherge verdrückt sich unauffällig zurück zu Trajan.",
			"schema"=>"module-hspieler"
			)
		);
		addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
		break;
	case "junge":
		$session['user']['gems']-=2;
		output("`n%sBlitzschnell verschwinden die Edelsteine in seinen Taschen. \"%sGute Wahl, im Kampf selbst wird er Dich nicht unterstützen können, er ist ja nur ein kleiner Junge. Aber im Gold besorgen ist er Spitze!%s\"`0", $ct, $cs, $ct);
		strip_buff("hspielerscherge");
		$rounds = getsetting("turns",10)*7;
		debug($rounds);
		apply_buff("hspielerjunge",array(
				"name"=>"`7Trajan's kleiner Junge",
				"rounds"=>$rounds,//-1,	// wieviele Runden aktiv? ganzen Tag? - wahrscheinlich zu gut, wk-abhängig...
				"atkmod"=>1,
				"wearoff"=>"Mit einem Grinsen und einem deutlich volleren Goldbeutel als vorher, verschwindet der kleine Junge zurück zu Trajan.",	// Schwachsinn bei unbegrenzter Länge
				"schema"=>"module-hspieler",
				)
			);
		addnav("Zurück zum Platz der Völker", "runmodule.php?module=wettkampf");
		break;
	} // end switch
	page_footer();
}

?>