<?php
/*
Letzte Änderung am 04.04.2005 von Michael Jandke

Begegnungen/Ereignisse auf dem Platz der Völker

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

To Do:	- beliebig Textausgaben erweitern
*/

function begegnungen_getmoduleinfo(){
	$info = array(
		"name"=>"PdV - Begegnungen",
		"version"=>"1.0",
		"author"=>"Michael Jandke",
		"category"=>"Der Platz der Voelker",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"requires"=>array("wettkampf"=>"1.0|von Oliver Wellinghoff"),
		"settings"=>array(
			"Begegnungen auf dem PdV - Einstellungen,title",
			"pdvchance"=>"Chance für Zufallsereignisse auf dem PdV in Prozent.,range,0,100,1|25",
		)
	);
	return $info;
}

function begegnungen_install(){
	module_addhook("pdv-desc");
	module_addhook("pdv-desc-keinfest");
	return true;
}

function begegnungen_uninstall(){
	return true;
}

function begegnungen_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "pdv-desc":
		// kein Ereignis beim Scrollen, Refreshen bzw. Chatten (nötig?)
		$com = httpget('comscroll');
		$refresh = httpget("refresh");
		$comment = httppost('insertcommentary');
		$chance = get_module_setting("pdvchance");
		if (e_rand(1, 100)<=$chance && !$comment && !$refresh && !$com) {
			output("`n`c`b`^~~ Etwas Besonderes! ~~`b`c`6");	// tun wir mal so als wäre es ein echtes Special
			switch(e_rand(1,16)) {	// bei Erweiterungen darauf achten das die Anzahl mit den cases übereinstimmt!!
			case 1:
				output("Du begegnest einer Gruppe elegant wirkender Elfen, die sich mit ihren kunstvoll gefertigten Bögen zum Bogenwettkampf aufmachen. Du gehst ihnen nach, um den Wettkampf zu beobachten.");
				break;
			case 2:
				output("Zwei schlammverkrustete Trolle ziehen lachend und sichtlich guter Laune vom Schlammtümpel in Richtung Cedricks Kneipe.");
				break;
			case 3:
				output("Eine Gruppe Zwerge zieht mit wunderlich anmutender Ausrüstung zum Kletterschacht hinauf. Bestimmt sind es angereiste Wettkämpfer aus dem Drassoria-Gebirge.");
				break;
			case 4:
				output("Du hast das komische Gefühl beobachtet zu werden. Instinktiv fühlst Du nach Deinem Gold ... glücklicherweise ist noch alles da.");
				break;
			case 5:
				output("Du findest eine Goldmünze auf dem Boden. Nachdem Du sie einmal in die Luft geschnippt und wieder aufgefangen hast, steckst Du sie lächelnd in Deinen Goldbeutel.");
				$session['user']['gold']++;
				break;
			case 6:
				output("Gedankenverloren betrachtest du einen kleinen Jungen, als Du plötzlich bemerkst, wie er einer Frau den Geldbeutel stiehlt. Du rufst laut über den Festplatz, aber er ist sofort im Gedränge verschwunden.");
				break;
			case 7:
				output("Hinter Dir ertönen Fanfaren. Du springst ein Stück zur Seite, als Du einen berühmten Wettkämpfer der Menschen erkennst, der mit seinem Tross über den Festplatz zieht. Mit großen Augen starrst Du auf sein prunkvolles Roß.");
				break;
			case 8:
				output("Du siehst, wie sich zwei Betrunkene auf dem Festplatz prügeln. Kopfschüttelnd gehst Du weiter.");
				break;
			case 9:
				output("Ein kleiner, stämmiger Zwerg zieht eine große, schwerbeladene Karre zur Reitbahn. Es ist Merick, der anscheinend Futter für die Pferde der Wettkämpfer bringt.");
				break;
			case 10:
				output("Du siehst, wie einige Zugereiste staunend vor der Statue der großen Vermittlerin stehen. Dabei fällt Dir ein, dass Du doch noch Blumen zum Niederlegen kaufen wolltest!");
				break;
			case 11:
				output("Von der Bühne der Vanthira hallt laute Musik zu Dir herüber. Du überlegst, ob Du dort nicht mal vorbeischaust, die Stimmung scheint gerade auf dem Höhepunkt zu sein.");
				break;
			case 12:
				output("Aus Richtung des Küchenhauses der Echsen weht ein betörender Duft über den Platz, der Dir das Wasser im Munde zusammenlaufen läßt. Du beschließt, dort gleich einmal vorbeizuschauen.");
				break;
			case 13:
				output("Wie erstarrt bleibst Du stehen, als Du Tha durch die Menge schleichen siehst. Mit wachsamen Augen überblickt er den Platz der Völker. Glücklicherweise bist Du diesmal nicht %s Gesuchte.", ($session['user']['sex']?"die":"der"));
				break;
			case 14:
				output("Du mußt grinsen, als Du `3Irog`6 gelangweilt über den Platz schlendern siehst. Im Augenwinkel bemerkst du wie jemand, einem Schatten gleich, zur Bogenschießanlage huscht.");
				break;
			case 15:
				output("Ein lautes Krachen und ein erschrockenes Quieken lassen Dich herumfahren. Du sieht, wie Tha einen Wettkämpfer des Schleichen-Wettbewerbes an der Kehle gepackt hat und ihn hinter ein paar Fässern hervorzieht.");
				break;
			case 16:
				output("Ein Blumenhändler bietet dir einige wunderschöne Sträuße an, die Du an der Statue der Großen Vermittlerin Ihr zu Ehren niederlegen könntest.");
				break;
			} // end switch
			output_notl("`n`0");
		} // end if
		break;
	case "pdv-desc-keinfest":
		$com = httpget('comscroll');
		$refresh = httpget("refresh");
		$comment = httppost('insertcommentary');
		$chance = get_module_setting("pdvchance");
		if (e_rand(1, 100)<=$chance && !$comment && !$refresh && !$com) {
			output("`n`c`b`^~~ Etwas Besonderes! ~~`b`c`6");	// tun wir mal so als wäre es ein echtes Special
			switch(e_rand(1,10)) {	// bei Erweiterungen darauf achten das die Anzahl mit den cases übereinstimmt!!
			case 1:
				$tage = get_module_setting("tage","wettkampf");
				output("Eine Gruppe Kinder läuft lachend vor dir über den Festplatz. `Q\"Nur noch %s %s bis zum nächsten Fest!\"`6, rufen sie fröhlich.", $tage, $tage==1?"Tag":"Tage");
				break;
			case 2:
				output("Ein alter Mann läuft, einen Besen schwingend, über den Festplatz und macht sauber.");
				break;
			case 3:
				output("Zwei junge Elfenkinder laufen über den Festplatz und suchen die verirrten Pfeile vom letzten Bogenwettbewerb zusammen.");
				break;
			case 4:
				output("Ein alter Zwerg kommt mit einer Schubkarre voller Gestein vom Tiefenschacht über den Platz. `q\"Tiefer, immer tiefer muß er werden...\"");
				break;
			case 5:
				output("Irog läuft gelangweilt über den Platz und versucht, ohne Erfolg, sich an ein paar Kinder anzuschleichen. Sie bemerken ihn und laufen kreischend davon.");
				break;
			case 6:
				output("Drei alte Männer stehen vor der Statue der Vermittlerin und einer raunt leise: `7\"Das letzte Fest war wieder ein voller Erfolg, oder?\"");
				break;
			case 7:
				output("Der Duft von frisch zubereitetem Essen schwebt vom Küchenhaus herüber. Sehnsüchtig denkst du an das nächste Fest und den dann wieder stattfindenden Kochwettbewerb.");
				break;
			case 8:
				output("Du siehst einige Handwerker, die auf der Bühne der Vanthira ein paar Bretter ausbessern. Beim letzten Wettbewerb scheinen einige zu Bruch gegangen zu sein.");
				break;
			case 9:
				output("Eine Trollfamilie läuft vor dir vergnügt und voller Vorfreude zum Schlammtümpel. Wenn gerade kein Fest ist, steht er der gesamten Bevölkerung für Schlammbäder zur Verfügung.");
				break;
			case 10:
				output("Du siehst wie Hannes VI. mißmutig an der Reitbahn sitzt. Er hat vor sich sein 'Chronometer', das anscheinend nicht richtig funktioniert. Aber du willst nicht stören und läßt ihn in Ruhe weiterbasteln.");
				break;
			} // end switch
			output_notl("`n`n`0");
		} // end if
		break;
	} // end switch ($hookname)
	return $args;
}

function begegnungen_runevent($type){
}

function begegnungen_run(){
}
?>