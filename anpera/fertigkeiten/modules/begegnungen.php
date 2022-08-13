<?php
/*
Letzte �nderung am 04.04.2005 von Michael Jandke

Begegnungen/Ereignisse auf dem Platz der V�lker

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
			"pdvchance"=>"Chance f�r Zufallsereignisse auf dem PdV in Prozent.,range,0,100,1|25",
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
		// kein Ereignis beim Scrollen, Refreshen bzw. Chatten (n�tig?)
		$com = httpget('comscroll');
		$refresh = httpget("refresh");
		$comment = httppost('insertcommentary');
		$chance = get_module_setting("pdvchance");
		if (e_rand(1, 100)<=$chance && !$comment && !$refresh && !$com) {
			output("`n`c`b`^~~ Etwas Besonderes! ~~`b`c`6");	// tun wir mal so als w�re es ein echtes Special
			switch(e_rand(1,16)) {	// bei Erweiterungen darauf achten das die Anzahl mit den cases �bereinstimmt!!
			case 1:
				output("Du begegnest einer Gruppe elegant wirkender Elfen, die sich mit ihren kunstvoll gefertigten B�gen zum Bogenwettkampf aufmachen. Du gehst ihnen nach, um den Wettkampf zu beobachten.");
				break;
			case 2:
				output("Zwei schlammverkrustete Trolle ziehen lachend und sichtlich guter Laune vom Schlammt�mpel in Richtung Cedricks Kneipe.");
				break;
			case 3:
				output("Eine Gruppe Zwerge zieht mit wunderlich anmutender Ausr�stung zum Kletterschacht hinauf. Bestimmt sind es angereiste Wettk�mpfer aus dem Drassoria-Gebirge.");
				break;
			case 4:
				output("Du hast das komische Gef�hl beobachtet zu werden. Instinktiv f�hlst Du nach Deinem Gold ... gl�cklicherweise ist noch alles da.");
				break;
			case 5:
				output("Du findest eine Goldm�nze auf dem Boden. Nachdem Du sie einmal in die Luft geschnippt und wieder aufgefangen hast, steckst Du sie l�chelnd in Deinen Goldbeutel.");
				$session['user']['gold']++;
				break;
			case 6:
				output("Gedankenverloren betrachtest du einen kleinen Jungen, als Du pl�tzlich bemerkst, wie er einer Frau den Geldbeutel stiehlt. Du rufst laut �ber den Festplatz, aber er ist sofort im Gedr�nge verschwunden.");
				break;
			case 7:
				output("Hinter Dir ert�nen Fanfaren. Du springst ein St�ck zur Seite, als Du einen ber�hmten Wettk�mpfer der Menschen erkennst, der mit seinem Tross �ber den Festplatz zieht. Mit gro�en Augen starrst Du auf sein prunkvolles Ro�.");
				break;
			case 8:
				output("Du siehst, wie sich zwei Betrunkene auf dem Festplatz pr�geln. Kopfsch�ttelnd gehst Du weiter.");
				break;
			case 9:
				output("Ein kleiner, st�mmiger Zwerg zieht eine gro�e, schwerbeladene Karre zur Reitbahn. Es ist Merick, der anscheinend Futter f�r die Pferde der Wettk�mpfer bringt.");
				break;
			case 10:
				output("Du siehst, wie einige Zugereiste staunend vor der Statue der gro�en Vermittlerin stehen. Dabei f�llt Dir ein, dass Du doch noch Blumen zum Niederlegen kaufen wolltest!");
				break;
			case 11:
				output("Von der B�hne der Vanthira hallt laute Musik zu Dir her�ber. Du �berlegst, ob Du dort nicht mal vorbeischaust, die Stimmung scheint gerade auf dem H�hepunkt zu sein.");
				break;
			case 12:
				output("Aus Richtung des K�chenhauses der Echsen weht ein bet�render Duft �ber den Platz, der Dir das Wasser im Munde zusammenlaufen l��t. Du beschlie�t, dort gleich einmal vorbeizuschauen.");
				break;
			case 13:
				output("Wie erstarrt bleibst Du stehen, als Du Tha durch die Menge schleichen siehst. Mit wachsamen Augen �berblickt er den Platz der V�lker. Gl�cklicherweise bist Du diesmal nicht %s Gesuchte.", ($session['user']['sex']?"die":"der"));
				break;
			case 14:
				output("Du mu�t grinsen, als Du `3Irog`6 gelangweilt �ber den Platz schlendern siehst. Im Augenwinkel bemerkst du wie jemand, einem Schatten gleich, zur Bogenschie�anlage huscht.");
				break;
			case 15:
				output("Ein lautes Krachen und ein erschrockenes Quieken lassen Dich herumfahren. Du sieht, wie Tha einen Wettk�mpfer des Schleichen-Wettbewerbes an der Kehle gepackt hat und ihn hinter ein paar F�ssern hervorzieht.");
				break;
			case 16:
				output("Ein Blumenh�ndler bietet dir einige wundersch�ne Str�u�e an, die Du an der Statue der Gro�en Vermittlerin Ihr zu Ehren niederlegen k�nntest.");
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
			output("`n`c`b`^~~ Etwas Besonderes! ~~`b`c`6");	// tun wir mal so als w�re es ein echtes Special
			switch(e_rand(1,10)) {	// bei Erweiterungen darauf achten das die Anzahl mit den cases �bereinstimmt!!
			case 1:
				$tage = get_module_setting("tage","wettkampf");
				output("Eine Gruppe Kinder l�uft lachend vor dir �ber den Festplatz. `Q\"Nur noch %s %s bis zum n�chsten Fest!\"`6, rufen sie fr�hlich.", $tage, $tage==1?"Tag":"Tage");
				break;
			case 2:
				output("Ein alter Mann l�uft, einen Besen schwingend, �ber den Festplatz und macht sauber.");
				break;
			case 3:
				output("Zwei junge Elfenkinder laufen �ber den Festplatz und suchen die verirrten Pfeile vom letzten Bogenwettbewerb zusammen.");
				break;
			case 4:
				output("Ein alter Zwerg kommt mit einer Schubkarre voller Gestein vom Tiefenschacht �ber den Platz. `q\"Tiefer, immer tiefer mu� er werden...\"");
				break;
			case 5:
				output("Irog l�uft gelangweilt �ber den Platz und versucht, ohne Erfolg, sich an ein paar Kinder anzuschleichen. Sie bemerken ihn und laufen kreischend davon.");
				break;
			case 6:
				output("Drei alte M�nner stehen vor der Statue der Vermittlerin und einer raunt leise: `7\"Das letzte Fest war wieder ein voller Erfolg, oder?\"");
				break;
			case 7:
				output("Der Duft von frisch zubereitetem Essen schwebt vom K�chenhaus her�ber. Sehns�chtig denkst du an das n�chste Fest und den dann wieder stattfindenden Kochwettbewerb.");
				break;
			case 8:
				output("Du siehst einige Handwerker, die auf der B�hne der Vanthira ein paar Bretter ausbessern. Beim letzten Wettbewerb scheinen einige zu Bruch gegangen zu sein.");
				break;
			case 9:
				output("Eine Trollfamilie l�uft vor dir vergn�gt und voller Vorfreude zum Schlammt�mpel. Wenn gerade kein Fest ist, steht er der gesamten Bev�lkerung f�r Schlammb�der zur Verf�gung.");
				break;
			case 10:
				output("Du siehst wie Hannes VI. mi�mutig an der Reitbahn sitzt. Er hat vor sich sein 'Chronometer', das anscheinend nicht richtig funktioniert. Aber du willst nicht st�ren und l��t ihn in Ruhe weiterbasteln.");
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