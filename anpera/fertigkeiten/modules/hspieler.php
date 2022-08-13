<?php
/*
Letzte �nderungen am 22.03.2005 von Michael Jandke

Ein H�tchenspieler f�r den Platz der V�lker 
- Basisversion -

*********************************************************
*	Diese Datei sollte aus fertigkeiten.zip stammen.	*
*														*
*	N�heres siehe: dokumentation.txt					*
*														*
*	Achtung: Wer diese Datei benutzt, verpflichtet		*
*	sich, alle Module, die er f�r das Fertigkeiten-		*
*	system entwickelt frei und �ffentlich zug�nglich	*
*	zu machen!											*
*	Wir entwickeln f�r Euch - Ihr entwickelt f�r uns.	*
*														*
*********************************************************

To Do:	- m�glicherweise noch einen Tip f�r die Auswahl, bassierend auf einem noch zu bestimmenden Wert, z.B. Gl�cksspiel, oder allgemein Gl�ck...
		  bei niedrigem Gl�ckswert "schummelt" der H�tchenspieler und gewinnt immer, bei h�herem Gl�ckswert ein immer verl��licher werdender Tip
		- die drei H�tchen als nebeneinander angeordnete Buttons
		- m�gliche Erweiterung: - man kann "S�ldner" einmal pro Tag anheuern f�r Edelsteine (den Troll (starker Kampfbuff), den Mensch (Kampfbuff) oder einen kleinen Jungen (mehr Gold aus Wk)) 
		- Speichern des Gewinnes des Goldspielers (einmal interessehalber, andererseits kann man damit ja auch wieder was machen - Auszahlung, Spende, Ereignis wenn Pott voll,...)
		- "Schwarzmarkt" - Edelsteinhandel zwischen Spielern �ber ihn (Balanceideen noch nicht ausgereift)
		- DK-abh�ngig? zumindest anheuern
		- Balnacing der Buffs!!! Scherge!
*/


function hspieler_getmoduleinfo(){
    $info = array(
		"name"=>"PdV - H�tchenspieler",
		"version"=>"1.0",
		"author"=>"Michael Jandke",
		"category"=>"Der Platz der Voelker",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("wettkampf"=>"1.0|Platz der V�lker von Oliver Wellinghoff"),
		"settings"=>array(
			"Der H�tchenspieler - Settings,title",
			"maxeinsatz"=>"Wie hoch ist der maximale Spieleinsatz (mal Spielerlevel) ?, range,1,200,1|30",
			"gewinnfaktor"=>"Das wievielfache des Einsatzes erh�lt der Spieler als Gewinn?,floatrange,1,10,0.5|3",
			"winsprotag"=>"Wie oft darf der Spieler maximal pro Tag gewinnen?, range,1,5,1|3",
			"bilanz"=>"Die Bilanz des H�tchenspielers,viewonly|0",	
			"anheuern"=>"Kann man beim H�tchenspieler S�ldner anheuern?,bool|1",
			"jungegold"=>"Wieviel mehr Gold l��t der kleine Junge den Spieler im Wald finden (in Prozent),range,0,100,1|20",
			"chance"=>"Wie gro� ist die Chance das er w�hrend des Festes erscheint (in Prozent)?,range,0,100,1|100",
			"appear"=>"Ist der Stand gerade anwesend (nur w�hrend Fest)?,bool|0",
		),
		"prefs"=>array(
			"Der H�tchenspieler - User Settings,title",
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
	module_addhook("pdvst�nde");
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
	case "pdvst�nde":
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
		if ($buttonresult=='Linkes H�tchen') { $op="linke";  }
		if ($buttonresult=='Rechtes H�tchen') { $op="rechte"; }
		if ($buttonresult=='Mittleres H�tchen') { $op="mittlere"; }
	}
	$from = "runmodule.php?module=hspieler&";
	$maxbet = get_module_setting("maxeinsatz") * $session['user']['level'];
	$gewinnfaktor = get_module_setting("gewinnfaktor");
	$anheuern = (int)get_module_setting("anheuern");
	$ct = "`2";		// Colorcode f�r normalen Text
	$cs = "`q";		// Colorcode f�r den H�tchenspieler
	$ce = "`&";		// Colorcode f�r das Alte Ego
	page_header("Der H�tchenspieler");
	
	switch($op) {
	case "spiele":
		if ($session['user']['gold']>0) {					// Spieler mu� Gold haben
			$bet = (int)httppost('bet');
			if ($bet<=0) {		// kleiner trotz abs() ??
				addnav("Doch nicht spielen","runmodule.php?module=hspieler");
				output("`n%s\"%sAlso gut.%s\" Trajan knackt kurz mit den Fingern und setzt sich dann hinter seine Obstkiste.", $ct, $cs, $ct);
				output("`n`n%s\"%sDer h�chste Einsatz um den ich mit dir spielen werde, sind `^$maxbet %sGoldst�cke. Wenn du gewinnst, bekommst du das ".$gewinnfaktor."-fache deines Einsatzes zur�ck.%s\"`n`0", $ct, $cs, $cs, $ct);
				rawoutput("<form action='runmodule.php?module=hspieler&op=spiele' method='POST'>");
				rawoutput("<input name='bet' id='bet'>");
				$play = translate_inline("Spielen");
				rawoutput("<input type='submit' class='button' value='$play'>");
				rawoutput("</form>");
				output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
				addnav("","runmodule.php?module=hspieler&op=spiele");
			}elseif($bet>$session['user']['gold']) {		// Spieler hat nicht soviel Gold
				output("`n%sW�tend schaut er dich an. \"%sWillst du um Gold spielen das du gar nicht hast? Verschwinde blo�, bevor mein Freund hier mal mit dir spielt!%s\" Der Troll hinter ihm tritt aus dem Schatten, du blickst in seine h��liche Fratze und suchst lieber schnell das Weite.`n`0", $ct, $cs, $ct);
				addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
				output("`n<a href = 'runmodule.php?module=wettkampf'>Zur�ck zum Platz der V�lker.</a>`n", true);
				addnav("","runmodule.php?module=wettkampf");
			}elseif ($bet>$maxbet) {						// Der Maximaleinsatz wird �berschritten
				output("`n%sTrajan sch�ttelt den Kopf.`n`n\"%sDer Maximaleinsatz ist `^$maxbet%s Gold. Um mehr Gold spiele ich nicht, irgendwer kommt sonst blo� noch auf die Idee, das es sich hier um ein illegales Gl�cksspiel handeln k�nnte...$ct\"`n", $ct, $cs, $cs, $ct);
				addnav("Spielen","runmodule.php?module=hspieler&op=spiele");
				output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Spiele mit einem erlaubten Einsatz.</a>`n", true);
				addnav("","runmodule.php?module=hspieler&op=spiele");	
				addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
				output("`n<a href = 'runmodule.php?module=wettkampf'>Zur�ck zum Platz der V�lker.</a>`n", true);
				addnav("","runmodule.php?module=wettkampf");
			}else {											// ansonsten wird gespielt
				$session['user']['gold']-=$bet;
				set_module_setting("bilanz",get_module_setting("bilanz")+$bet);
				debuglog("setzte $bet Gold beim H�tchenspieler.");
				$melone = e_rand(1,3);						// zweimal Fake
				output("`n%s\"%sViel Gl�ck und pass gut auf.%s\" Er legt die Erbse unter das mittlere H�tchen und beginnt langsam einige H�tchen zu vertauschen. \"%sSo, jetzt ist sie hier%s\" sagt er und zeigt dir die Erbse unter dem `^".($melone==1?"linken":($melone==2?"mittleren":"rechten"))."%s H�tchen.`n", $ct, $cs, $ct, $cs, $ct, $ct);
				$melone = e_rand(1,3);
				output("`n%sDann macht er weiter, diesmal etwas schneller und l�nger und zeigt dir dann die Erbse unter dem `^".($melone==1?"linken":($melone==2?"mittleren":"rechten"))."%s H�tchen.`n", $ct, $ct);
				output("`n%sDu machst ein Gesicht als sei dir das nat�rlich klar gewesen und hoffst, er merkt nichts davon das es dir schon jetzt viel zu schnell ging. \"%sUnd nun z�hlt es%s\" meint er, schaut dich nochmal grinsend an und beginnt dann in rasender Geschwindigkeit die H�tchen zu vertauschen. Kurzzeitig glaubst du drei Arme zu sehen, dann h�rt er ganz pl�tzlich auf und in triumphierendem Ton fragt er:`n`n\"%sNun, unter welchem H�tchen ist die Erbse?%s\"`n", $ct, $cs, $ct, $cs, $ct);
								
				$melone = e_rand(1,3);						// echtes Ergebnis ausw�rfeln
				set_module_pref("hit", $melone);			// speichern... geht es auch anders, das die Variablen ihren Wert behalten?
				$gewinn = round($bet*$gewinnfaktor);
				set_module_pref("gewinn", $gewinn);			// speichern... 
//				output("`nDEBUG: Erbse ist unter dem ".($melone==1?"linken":($melone==2?"mittleren":"rechten"))." H�tchen.`nErbse: %s", $melone);
				output("`n%sEtwas z�gernd antwortest du:`n`n", $ct);
				addnav("Unter dem linken","runmodule.php?module=hspieler&op=linke");
				addnav("Unter dem mittleren","runmodule.php?module=hspieler&op=mittlere");
				addnav("Unter dem rechten","runmodule.php?module=hspieler&op=rechte");
				rawoutput("<center>");	
				rawoutput("<form action='$from"."op=viaForm' method='POST'>");
				rawoutput("<input name='LinkerButton' type='submit' class='button' value='Linkes H�tchen'>");
				rawoutput("<input name='MittlererButton' type='submit' class='button' value='Mittleres H�tchen'>");
				rawoutput("<input name='RechterButton' type='submit' class='button' value='Rechtes H�tchen'>");
				rawoutput("</form>");
				rawoutput("</center>");
				addnav("",$from."op=viaForm");
				}
		}else{
			output("`n%sSkeptisch schaut er erst dich an, dann mit pr�fendem Blick deinen Geldbeutel und schnauzt \"%sDu hast doch �berhaupt kein Gold, du armer Schlucker. Jetzt verschwinde schon, bevor ich meine Freunde hier auf dich loslasse.%s\"`n", $ct, $cs, $ct);	
			addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
			output("`n<a href = 'runmodule.php?module=wettkampf'>Zur�ck zum Platz der V�lker.</a>`n", true);
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
//			output("`nIrgendjemand betr�gt hier! Oder besser gesagt, du hast verloren!`n");
		}
		if ($gewonnen == true) {
			set_module_pref("anzwins",get_module_pref("anzwins")+1);
			$gewinn = get_module_pref("gewinn");
			$session['user']['gold']+=$gewinn;
			set_module_setting("bilanz",get_module_setting("bilanz")-$gewinn);
			debuglog("gewann $gewinn Gold beim H�tchenspieler.");
			output("`n%sLangsam hebt er das `^$op%s H�tchen an und die Erbse ist darunter!`n", $ct, $ct);
			output("`n`b`^Du hast gewonnen!`b`n`n%sEtwas �berrascht schaut er auf die Erbse und zwingt sich dann zu einem L�cheln. \"%sWie hast du das gemacht... ich meine, woher wu�test... na egal, hier hast du deinen Gewinn.%s\"`nDu bekommst `^%s%s Goldst�cke!`n`0", $ct, $cs, $ct, $gewinn, $ct);
			if (get_module_pref("anzwins")<get_module_setting("winsprotag")) {
				addnav("Nochmal spielen","runmodule.php?module=hspieler&op=spiele");
				output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Spiele gleich noch einmal.</a>`n", true);
				addnav("","runmodule.php?module=hspieler&op=spiele");
			}else{
				output("`n%sEtwas gereizt schaut er dich an.`n`n\"%sDu hast jetzt ".get_module_setting("winsprotag")." mal gewonnen. Das war's dann f�r heute, aus ...�h, gesch�ftlichen Gr�nden mu� ich dich auffordern meine Dienste nicht weiter in Anspruch zu nehmen.%s\" \"%sGerade jetzt wo meine Gl�cksstr�hne anf�ngt%s\" schreist du, aber als die beiden Schl�ger des H�tchenspielers drohend aus dem Schatten hervortreten, ziehst du es doch vor, zu verschwinden.`n`n`0", $ct, $cs, $ct, $ce, $ct);	
			}
			addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
			output("`n<a href = 'runmodule.php?module=wettkampf'>Zur�ck zum Platz der V�lker.</a>`n", true);
			addnav("","runmodule.php?module=wettkampf");
		}else{
			output("`n`b`\$Du hast verloren!`b`n`n%s Trajan hebt das `^".($melone==1?"linke":($melone==2?"mittlere":"rechte"))."%s H�tchen hoch und zeigt dir die darunter liegende Erbse.`n\"%sDa w�re sie gewesen. Leider falsch geraten.%s\" Mit einem L�cheln auf den Lippen nimmt er deine Goldm�nzen und steckt sie in die Taschen seiner alten Weste.`n`0", $ct, $ct, $cs, $ct);
			addnav("Nochmal spielen","runmodule.php?module=hspieler&op=spiele");
			output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Spiele gleich noch einmal.</a>`n", true);
			addnav("","runmodule.php?module=hspieler&op=spiele");
			addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
			output("`n<a href = 'runmodule.php?module=wettkampf'>Zur�ck zum Platz der V�lker.</a>`n", true);
			addnav("","runmodule.php?module=wettkampf");				
		}
		break;
// Regeln
	case "regeln":	
		output("%s\"%sAlso das mit den Regeln ist ganz einfach.%s\" erkl�rt dir Trajan. \"%sSiehst du diese Erbse hier?%s\" In seinen Fingern h�lt er eine kleine, gelb-braune Erbse, die er dir nahe unter deine Nase h�lt. \"%sUnd hier habe ich drei H�tchen%s\" sagt er und deutet auf das Brett vor ihm. \"%sJetzt tue ich diese Erbse hier unter das mittlere H�tchen und dann werde ich die H�tchen vertauschen. Achte genau auf das mit der Erbse drunter!%s\" ", $ct, $cs, $ct, $cs, $ct, $cs, $ct, $cs, $ct);
		output("Dann greift Trajan sich mit der rechten Hand das rechte H�tchen und mit der linken Hand das mittlere H�tchen und vertauscht beide. %s\"Na, wo ist die Erbse jetzt?%s\" fragt er mit s�ffisantem L�cheln. Deine Antwort kommt sofort \"%sUnter dem rechten nat�rlich!%s\" Er hebt das rechte H�tchen an und zeigt dir die darunter liegende Erbse. \"%sGut gemacht! Und jetzt weiter...%s\" ", $cs, $ct, $ce, $ct, $cs, $ct);
		output("Er vertauscht, diesmal ein klein wenig schneller, das rechte und das linke H�tchen, danach das linke mit dem mittleren. \"%sWei�t du auch jetzt noch wo die Erbse ist?%s\" \"%sWieder unter dem mittleren?%s\" Er hebt das mittlere H�tchen langsam an und die Erbse darunter kommt zum Vorschein. \"%sDu bist ja ein echtes Naturtalent! Damit das ganze etwas reizvoller ist, spiele ich aber eigentlich um Gold. ",$cs, $ct, $ce, $ct, $cs);
		output("H�ttest du gerade eben `^10%s Goldst�cke gesetzt, so h�ttest du jetzt `^".round(10*$gewinnfaktor)."%s Goldst�cke als Gewinn wiederbekommen. Der Maximaleinsatz um den ich spiele sind `^".$maxbet."%s Goldst�cke... Ich denke jetzt ist alles klar, willst du ein Spielchen machen?%s\"`n", $cs, $cs, $cs, $ct);
		if (get_module_pref("anzwins")<get_module_setting("winsprotag")) {
			addnav("Spielen","runmodule.php?module=hspieler&op=spiele");
			output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Wage mal ein Spiel.</a>`n", true);
			addnav("","runmodule.php?module=hspieler&op=spiele");
		}
		addnav("H�h?","runmodule.php?module=hspieler&op=weg");
		output("`n<a href = 'runmodule.php?module=hspieler&op=weg'>Schaue ihn mit gro�en Augen an und sage \"H�h?\"</a>`n", true);
		addnav("","runmodule.php?module=hspieler&op=weg");
		break;
	case "weg":
		output("`n%s\"%sWie kann man das nicht verstehen, das ist doch total simpel... Oder willst du mich hier veralbern?%s\" Als du ein breites Grinsen nicht unterdr�cken kannst, nickt er w�tend zu den beiden hinter ihm im Schatten stehenden Gestalten und zischt \"%sVerschwinde du Spa�vogel, oder meine Freunde werden dir zeigen was ich von Leuten wie dir halte...%s\" Du streckst ihm die Zunge raus, haust dann aber schnell ab und versteckst dich in der Menge der Festbesucher.`n", $ct, $cs, $ct, $cs, $ct);
		addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
		output("`n`n<a href = 'runmodule.php?module=wettkampf'>Zur�ck zum Platz der V�lker.</a>`n", true);
		addnav("","runmodule.php?module=wettkampf");
		break;
// Begr��ung
	case "":
		output("`n%sIm Schatten, halb zwischen zwei anderen St�nden verborgen, lungert ein etwas verwahrlost wirkender Mensch mit Stoppelbart und langen, schwarzen und fettigen Haaren. Ein St�ck hinter ihm, sich im Schatten haltend, stehen zwei kr�ftige Gestalten von denen mindestens einer ein Troll zu sein scheint. Der Mann hat vor sich auf einer alten Obstkiste ein Brett und drei H�tchen stehen. `n", $ct);		
		if (get_module_pref("anzwins")<get_module_setting("winsprotag")) {
			output("%sAls er Deiner Aufmerksamkeit gewahr wird, entbl��t er seine gelben Z�hne kurz bei einem schiefen Grinsen, dann nickt er Dir zu und fragt: \"%sSei gegr��t ".($session['user']['sex']?"kleines Fr�ulein":"mein Junge").", ich bin Trajan. Hast Du Interesse an einem Spielchen?%s\"`n", $ct, $cs, $ct);
			if (get_module_pref("playedtoday")==true) output("`n%s\"%sVersuch doch nochmal Dein Gl�ck. Nur noch ein Spielchen, komm schon.%s\"`n", $ct, $cs, $ct);
			if ($anheuern) output("`n%s\"%sSolltest du Probleme haben, die man mit einem spitzen Dolch oder einer gro�en Keule l�sen kann, dann kannst Du Dich auch vertrauensvoll an mich wenden, frage nur...%s\"`n", $ct, $cs, $ct);
			output("`n`n<a href = 'runmodule.php?module=hspieler&op=spiele'>Spiele das H�tchenspiel mit ihm.</a>`n", true);
			addnav("","runmodule.php?module=hspieler&op=spiele");
			addnav("Spielen","runmodule.php?module=hspieler&op=spiele");
		}else{
			output("`n%sEtwas gereizt f�hrt er Dich an: \"%sMit Dir spiele ich heute nicht mehr, Du hast mich schon vollkommen ausgenommen!%s\"`n", $ct, $cs, $ct);	
			if ($anheuern) output("`n%s\"%sSolltest Du Probleme haben, die man mit einem spitzen Dolch oder einer gro�en Keule l�sen kann, dann kannst Du Dich auch vertrauensvoll an mich wenden, frage nur...%s\"`n", $ct, $cs, $ct);
		}
		output("`n<a href = 'runmodule.php?module=hspieler&op=regeln'>Frage Trajan nach den Regeln des Spieles.</a>`n", true);
		addnav("","runmodule.php?module=hspieler&op=regeln");
		addnav("R?Regeln","runmodule.php?module=hspieler&op=regeln");
		if ($anheuern) {
			output("`n<a href = 'runmodule.php?module=hspieler&op=anheuern'>Frage ihn nach Probleml�sungen.</a>`n", true);
			addnav("","runmodule.php?module=hspieler&op=anheuern");
			addnav("Probleml�sungen","runmodule.php?module=hspieler&op=anheuern");
		}
		addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
		output("`n<a href = 'runmodule.php?module=wettkampf'>Zur�ck zum Platz der V�lker.</a>`n", true);
		addnav("","runmodule.php?module=wettkampf");
		break;
// S�ldner anheuern
	case "anheuern":
		output("`n%s\"%sAh, ich seh schon. Du brauchst etwas Hilfe bei Deinen Unternehmungen und suchst nun nach einem vertrauensw�rdigen Partner, der Dir die n�tige Unterst�tzung zukommen lassen kann. Da bist Du bei mir genau richtig! ", $ct, $cs);
		output("%sF�r nur 2 Edelsteine kannst Du einen meiner 3 Kumpane anheuern.%s\"`n", $cs, $ct);
		if ($session['user']['gems']>1) {
			output("`n%s\"%sDrei?%s\", fragst Du. \"%sIch seh nur zwei.%s\" `n`n", $ct, $ce, $ct, $ce, $ct);
			output("%sEin breites Grinsen geht �ber Trajans Gesicht und er deutet mit einem Kopfnicken zu einem kleinen Jungen, der die ganze Zeit v�llig unauff�llig neben Dir stand. \"%sKommt ganz darauf an, wobei Du Hilfe brauchst.%s\"`n", $ct, $cs, $ct);
			output("`n%s\"<a href = 'runmodule.php?module=hspieler&op=troll'>`2Der Troll</a>%s haut drauf wie nichts, aber er ist etwas langsam. Zeige ihm die Richtung und er wird durch Deine Feinde pfl�gen.%s\"`n", $ct, $cs, $ct, true);
			addnav("", "runmodule.php?module=hspieler&op=troll");
			output("`n%s\"<a href = 'runmodule.php?module=hspieler&op=mensch'>`!Der Mensch</a>%s ist da schon geschickter, er ist ein hinterh�ltiger K�mpfer. Er scheut den offenen Kampf und wird Dich aus dem Schatten heraus unterst�tzen, wenn Du verstehst was ich meine.%s\"`n", $ct, $cs, $ct, true);
			addnav("", "runmodule.php?module=hspieler&op=mensch");
			output("`n%s\" <a href = 'runmodule.php?module=hspieler&op=junge'>`7Der kleine Junge</a>%s wird Dir im Kampf nicht viel n�tzen, da h�lt er sich geschickt raus. Aber wenn Du nach Deinem Sieg wirklich ALLES Gold finden willst, das Dein Gegner bei sich hatte, dann gibt es keinen Besseren.%s\"`n", $ct, $cs, $ct, true);
			addnav("", "runmodule.php?module=hspieler&op=junge");
			addnav("T?Troll", "runmodule.php?module=hspieler&op=troll");
			addnav("M?Mensch", "runmodule.php?module=hspieler&op=mensch");
			addnav("J?Kleiner Junge", "runmodule.php?module=hspieler&op=junge");
		}else {
			output("`n%s\"%sDu solltest nat�rlich die Edelsteine dabei haben, sonst kann ich nichts f�r Dich tun.%s\"`n", $ct, $cs, $ct);	
		}
		addnav("s?Lieber spielen","runmodule.php?module=hspieler&op=spiele");
		addnav("Z?Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
		break;
	case "troll":
		$session['user']['gems']-=2;
		output("`n%sBlitzschnell verschwinden die Edelsteine in seinen Taschen. \"%sGute Wahl, der Troll ist zwar etwas dumm, aber stark wie sonst keiner! Zeige ihm die Richtung und er wird durch Deine Feinde pfl�gen.%\"`0", $ct, $cs, $ct);
		strip_buff("hspielerjunge");
		apply_buff("hspielerscherge",array(
			"name"=>"`2Trajan's Troll",
			"rounds"=>e_rand(15,25),
			"wearoff"=>"`2Der Troll grunzt und trabt zur�ck zu Trajan.",
			"atkmod"=>1.3,
			"defmod"=>1.1,			
			"roundmsg"=>"`2Der Troll verteilt wuchtige Schl�ge und {badguy} bekommt sie zu sp�ren.",
			"schema"=>"module-hspieler"
			)
		);
		addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
		break;
	case "mensch":
		$session['user']['gems']-=2;
		output("`n%sBlitzschnell verschwinden die Edelsteine in seinen Taschen. \"%sGute Wahl, nicht so stark wie der Troll, daf�r aber auch etwas hinterh�ltiger.%s\" `0", $ct, $cs, $ct);
		strip_buff("hspielerjunge");
		apply_buff("hspielerscherge",array(
			"name"=>"`!Trajan's Scherge",
			"rounds"=>e_rand(15,35),
			"minioncount"=>floor(($session['user']['level']-1)/5)+1,
			"maxbadguydamage"=>round($session['user']['attack']/3) + floor($session['user']['dragonkills']/4),
			"effectmsg"=>"`!Der Scherge schleicht sich hinter {badguy} und trifft f�r `^{damage}`! Schaden.",
			"effectnodmgmsg"=>"`!{badguy} hat den Schergen entdeckt und `\$weicht aus`!!",
			"wearoff"=>"`!Der Scherge verdr�ckt sich unauff�llig zur�ck zu Trajan.",
			"schema"=>"module-hspieler"
			)
		);
		addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
		break;
	case "junge":
		$session['user']['gems']-=2;
		output("`n%sBlitzschnell verschwinden die Edelsteine in seinen Taschen. \"%sGute Wahl, im Kampf selbst wird er Dich nicht unterst�tzen k�nnen, er ist ja nur ein kleiner Junge. Aber im Gold besorgen ist er Spitze!%s\"`0", $ct, $cs, $ct);
		strip_buff("hspielerscherge");
		$rounds = getsetting("turns",10)*7;
		debug($rounds);
		apply_buff("hspielerjunge",array(
				"name"=>"`7Trajan's kleiner Junge",
				"rounds"=>$rounds,//-1,	// wieviele Runden aktiv? ganzen Tag? - wahrscheinlich zu gut, wk-abh�ngig...
				"atkmod"=>1,
				"wearoff"=>"Mit einem Grinsen und einem deutlich volleren Goldbeutel als vorher, verschwindet der kleine Junge zur�ck zu Trajan.",	// Schwachsinn bei unbegrenzter L�nge
				"schema"=>"module-hspieler",
				)
			);
		addnav("Zur�ck zum Platz der V�lker", "runmodule.php?module=wettkampf");
		break;
	} // end switch
	page_footer();
}

?>