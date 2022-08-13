<?php
//translator ready
//mail ready
//addnews ready
//alignment ready

/*
Der Platz der Völker (für LoGD ab 0.98)

Ausbilder und Wettkämpfe für das Fertigkeitensystem

Idee und Umsetzung von Oliver Wellinghoff.


Enthaltene Hooks:

"pdvanfang"
--> Für Ereignisse, die im Hintergrund abgefragt werden (z.B. pdvdiebstahl.php)

"pdv-desc"
--> Für Ergänzungen zum Haupttext während des Festes

"pdv-desc-keinfest"
--> Für Ergänzungen zum Haupttext während der Ausbildungsphase

"pdvstände"
--> Navs für neue Marktstände (z.B. hspieler.php)

"pdvnavsonstiges"
--> Navs unter "Sonstiges"

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

function wettkampf_getmoduleinfo(){
	$info = array(
        "name"=>"PdV - Der Platz der Völker",
        "version"=>"1.0",
        "author"=>"Oliver Wellinghoff",
        "category"=>"Der Platz der Voelker",
        "download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
        "requires"=>array("fertigkeiten"=>"1.0|Fertigkeitensystem von Oliver Wellinghoff und Michael Jandke"),
        "settings"=>array(
			"Der Platz der Völker,title",
			"wettkampfloc"=>"Wo befindet sich der Platz der Völker?,location|".getsetting("villagename", LOCATION_FIELDS),
			"grund"=>"Ausbildung: Grundkosten für Gespräch und FW < 50 (Berechnung der anderen Preise: * Userlevel * Modifikator von bis zu 4.66 bei FW >= 85) |150",
			"Das Fest der Völker,title",
			"dauer1"=>"Wieviele Spieltage dauert jeweils ein Fest? |5",
			"dauer0"=>"Nach wie vielen Tagen das jeweils nächste Fest? |10",
			"fest"=>"Findet gerade ein Fest statt?,bool |0",
			"tage"=>"In wievielen Spieltagen endet das derzeitige bzw. beginnt das nächste Fest? |1",
			"festzahl"=>"Das wievielte Fest ist das nächste bzw. momentane? ,viewonly|1",
			"statueblumen"=>"Seit dem letzten Festende niedergelegte Blumensträuße ,viewonly|",
			"Wettbewerbe - Einstellungen,title",
			"teilnahme"=>"Wieviel kostet die Teilnahme pro Wettbewerb (*Userlevel)? |15",
			"siegspeise"=>"Beste Speise des letzten Wettbewerbs ,hidden,textarea |",
			"bestespeise"=>"Beste Speise aller Zeiten ,hidden,textarea|",												
			"fwirog"=>"Wahrnehmungswert für Irog ,range,1,100,1 |40",
			"fwtha"=>"Wahrnehmungswert für Tha ,range,1,100,1 |65",
			"Wettbewerbe - Letzte Sieger,title",
			"sbogen"=>"Bogenschießen (acctid) ,viewonly|",
			"sklettern"=>"Klettern (acctid) ,viewonly|",
			"skochen"=>"Kochen (acctid) ,viewonly|",
			"smusik"=>"Musik (acctid) ,viewonly|",
			"sreiten"=>"Reiten (acctid) ,viewonly|",
			"sschleichen"=>"Schleichen (acctid) ,viewonly|",
			"sschwimmen"=>"Schwimmen (acctid) ,viewonly|",
			"Wettbewerbe - Wandertrophäen,title",
			"gegenstand1"=>"Name: Gegenstand 1 (WK+1) |den Ring der Ausdauer",
			"bgegenstand1"=>"-> Derzeitiger Besitzer (acctid)? |",
			"gegenstand2"=>"Name: Gegenstand 2 (Deathpower+10) |das Amulett der Vanthira",
			"bgegenstand2"=>"-> Derzeitiger Besitzer (acctid)? |",
			"gegenstand3"=>"Name: Gegenstand 3 (Exp*1.01) |die Halskette der Einsicht",
			"bgegenstand3"=>"-> Derzeitiger Besitzer (acctid)? |",
			"gegenstand4"=>"Name: Gegenstand 4 (Charm v WKs + v -1 v Gold-Bonus) *nicht ändern* , viewonly|den sprechenden Beutel",
			"bgegenstand4"=>"-> Derzeitiger Besitzer (acctid)? |",
			"gegenstand5"=>"Name: Gegenstand 5 *nicht ändern*, viewonly|einen seltsamen Schlüssel",
			"bgegenstand5"=>"-> Derzeitiger Besitzer (acctid)? |",
			"gegenstand6"=>"Name: Gegenstand 6 (Fähigkeit +1 v +2) |den Armreif der schnellen Klinge",
			//Folgendes ist keine schöne Lösung, ich weiß. Aber irgendwann muss die Datei
			//mal fertig sein - und man muss es ohnehin nur einmal konfigurieren.
			"special_gegenstand6"=>"-> Name der Fähigkeit |Waffenmeister",
			"special_file_gegenstand6"=>"-> Dateiname der Fähigkeit (ohne Dateiendung!) |specialtywaffenmeister",
			//Dabei werden bewusst alle etwaigen DK- oder Gesinnungs-Beschränkungen
			//ignoriert - die Gegenstände tragen die Kraft *in sich*
			"bgegenstand6"=>"-> Derzeitiger Besitzer (acctid)? |",
			"gegenstand7"=>"Name: Gegenstand 7 (Fähigkeit +1 v +2)|den Stab der Totenbeschwörung",
			"special_gegenstand7"=>"-> Name der Fähigkeit |Dunkle Künste",
			"special_file_gegenstand7"=>"-> Dateiname der Fähigkeit (ohne Dateiendung!) |specialtydarkarts",
			"bgegenstand7"=>"-> Derzeitiger Besitzer (acctid)? |",
			"gegenstand8"=>"Name: Gegenstand 8 (Fähigkeit +1 v +2)|die Robe der weißen Magie",
			"special_gegenstand8"=>"-> Name der Fähigkeit |Mystische Kräfte",
			"special_file_gegenstand8"=>"-> Dateiname der Fähigkeit (ohne Dateiendung!) |specialtymysticpower",
			"bgegenstand8"=>"-> Derzeitiger Besitzer (acctid)? |",
			),
		"prefs"=>array(
			"Diverses,title",
			"blumenniederlegen"=>"Wie oft Blumen niedergelegt (max. fünfmal bis zum nächsten Festende)? ,viewonly|0",
        	"preis0"=>"Derzeitige Kosten Gespräch ,viewonly |",
			"preis1"=>"Derzeitige Kosten Normal ,viewonly |",
			"preis2"=>"Derzeitige Kosten Intensiv ,viewonly |",	
			"Ergebnisse - Bogenschießen,title",
            "wbogen0"=>"Reiterschießen ,viewonly ,float|10000",
			"wbogen1"=>"Blindschießen ,viewonly ,float|10000",
			"wbogen2"=>"Schnellschießen ,viewonly ,float|10000",
			"wbogen3"=>"Gesamtergebnis Bogenschießen ,viewonly ,float|10000",
            "Rekorde,note",
			"bestbogen0"=>"Reiterschießen ,viewonly ,float|0",
			"bestbogen1"=>"Blindschießen ,viewonly ,float|0",
			"bestbogen2"=>"Schnellschießen ,viewonly ,float|0",
			"bestbogen3"=>"Gesamtergebnis Bogenschießen ,viewonly ,float|0",
			"Ergebnisse - Klettern,title",
			"wklettern0"=>"Klettern ,viewonly ,float|10000",
            "Rekorde,note",
			"bestklettern0"=>"Klettern ,viewonly ,float|0",
			"Ergebnisse - Kochen und Pflanzenkunde,title",
			"wkochen"=>"Kochen und Pflanzenkunde ,viewonly ,float|10000",
			"letztespeise"=>"Letzte Speise beim Kochen ,viewonly,textarea|",
            "Rekorde,note",
			"bestkochen"=>"Kochen und Pflanzenkunde ,viewonly ,float|0",
			"bestespeise"=>"Beste Speise beim Kochen ,viewonly,textarea|",
			"Ergebnisse - Musik und Gesang,title",
            "wmusik0"=>"Zuschaueranzahl ,viewonly ,float|-1",
			"wmusik1"=>"Stimmung ,viewonly ,float|-1",			
			"wmusik2"=>"Gesamtergebnis Musik und Gesang ,viewonly ,float|-1",
            "Rekorde,note",
			"bestmusik0"=>"Zuschaueranzahl ,viewonly ,float|-1",
			"bestmusik1"=>"Stimmung ,viewonly ,float|-1",			
			"bestmusik2"=>"Gesamtergebnis Musik und Gesang ,viewonly ,float|-1",
			"Ergebnisse - Reiten,title",
			"wreiten0"=>"Wettreiten ,viewonly ,float|10000",
			"wreiten1"=>"Bullenreiten ,viewonly ,float|10000",			
			"wreiten2"=>"Gesamtergebnis Reiten ,viewonly ,float|10000",
            "Rekorde,note",
			"bestreiten0"=>"Wettreiten ,viewonly ,float|10000",
			"bestreiten1"=>"Bullenreiten ,viewonly ,float|0",			
			"bestreiten2"=>"Gesamtergebnis Reiten ,viewonly ,float|0",
            "Ergebnisse - Schleichen und Verstecken,title",
			"wschleichen0"=>"Schleichen und Verstecken ,viewonly ,float|10000",
            "Rekorde,note",
			"bestschleichen0"=>"Schleichen und Verstecken ,viewonly ,float|10000",
            "Ergebnisse - Schwimmen und Tauchen,title",
			"wschwimm0"=>"Bahnenschwimmen ,viewonly ,float|10000",
			"wschwimm1"=>"Langzeittauchen ,viewonly ,float|10000",			
			"wschwimm2"=>"Gesamtergebnis Schwimmen und Tauchen ,viewonly ,float|10000",
			"Rekorde,note",
			"bestschwimm0"=>"Bahnenschwimmen ,viewonly ,float|10000",
			"bestschwimm1"=>"Langzeittauchen ,viewonly ,float|0",			
			"bestschwimm2"=>"Gesamtergebnis Schwimmen und Tauchen ,viewonly ,float|0",
			"Wettbewerbe - Diverses,title",
			"schwierigkeit"=>"Schwierigkeitsgrad beim Kochen ,viewonly|",
			"schleichenversuch"=>"Wie viele Versuche beim Schleichen noch übrig? ,viewonly|2",
			"schleichenortspieler"=>"Ort beim Schleichen Spieler ,viewonly|",
			"schleichenortirog"=>"Ort beim Schleichen Irog ,viewonly|",
			"schleichenorttha"=>"Ort beim Schleichen Tha ,viewonly|",
			"schleichenversteckt"=>"Spieler gerade versteckt? ,viewonly|",
			"schleichenprobespieler"=>"Versteckprobe des Spielers ,viewonly|",
			"schleichengegenstand1"=>"In Besitz von gesuchtem Gegenstand 1? ,viewonly, bool|0",
			"schleichengegenstand2"=>"In Besitz von gesuchtem Gegenstand 2? ,viewonly, bool|0",											
			)
    );
    return $info;
}

function wettkampf_install(){
    require_once("modules/wettkampf/wettkampf_install.php");
	$args = func_get_args();
	return call_user_func_array("wettkampf_install_private",$args);
}	

function wettkampf_uninstall(){
    return true;
}

function wettkampf_dohook($hookname, $args){
	require_once("modules/wettkampf/wettkampf_hooks.php");
	$args = func_get_args();
	return call_user_func_array("wettkampf_dohook_private",$args);
}

function wettkampf_run(){
	global $session;

	//Sicherung, falls manuell umgestellt wurde
		$fest=get_module_setting("fest");
		$tage=get_module_setting("tage");
	 
	//Fest endet 
		if ($tage == 0 && $fest == 1){
			require_once("modules/wettkampf/wettkampf_lib.php");
			fest_endet();
		}
	
	//Fest beginnt
		else if ($tage == 0 && $fest == 0){
			require_once("modules/wettkampf/wettkampf_lib.php");
			fest_beginnt();
		}
	
	page_header("Der Platz der Völker");

	$op=$_GET[op1];
	
if($op==""){
	$args=$op;
	require_once("modules/wettkampf/wettkampf_pdv.php");
	return call_user_func_array("wettkampf_pdv_run_private",$args);
}else if($op=="aufruf"){
	$subop1=$_GET[subop1];
	$args=$_GET[subop2];
	require_once("modules/wettkampf/wettkampf_".$subop1.".php");
	return call_user_func_array("wettkampf_".$subop1."_run_private",$args);
}else{
	$args=$op;
	require_once("modules/wettkampf/wettkampf_".$op.".php");
	return call_user_func_array("wettkampf_".$op."_run_private",$args);
}
	
page_footer();
}
?>