<?php

//********************************************************************************************************************
// Allgemeine Funktionen
//********************************************************************************************************************
require_once("lib/fert.php");
require_once("lib/systemmail.php");

//Grundfunktion f�r die Abfrage von Ergebnissen
function abfrage_wettbewerb($wettbewerb2, $fertigkeit, $fertigkeit2, $zahl, $zahl2, $ab=true, $limit){
	global $session;
	$ow = "ASC";
	if ($ab != "true") $order = "ASC";
	else $order = "DESC";
	$fw="".$fertigkeit2."fw";
	$dk="".$fertigkeit2."dk";
	$level="".$fertigkeit2."level";
		
	$sql="SELECT ".db_prefix("accounts").".locked AS locked, ".db_prefix("accounts").".acctid AS acctid, ".db_prefix("accounts").".name AS name, (t1.value+0) AS data1, (t2.value+0) AS data2, (t3.value+0) AS data3, (t4.value+0) AS data4 FROM ".db_prefix("module_userprefs")." AS t1 LEFT JOIN ".db_prefix("accounts")." ON ".db_prefix("accounts").".acctid=t1.userid LEFT JOIN ".db_prefix("module_userprefs")." AS t2 ON t1.userid=t2.userid LEFT JOIN ".db_prefix("module_userprefs")." AS t3 ON t1.userid=t3.userid LEFT JOIN ".db_prefix("module_userprefs")." AS t4 ON t1.userid=t4.userid WHERE locked=0 AND t1.modulename='wettkampf' AND t1.setting='$wettbewerb2' AND t1.value !=$zahl AND t1.value !=$zahl2 AND t2.modulename='wettkampf' AND t2.setting='$fw' AND t3.modulename='wettkampf' AND t3.setting='$dk' AND t4.modulename='wettkampf' AND t4.setting='$level' ORDER BY data1 $order, data2 $ow, data3 $ow, data4 $ow, acctid $ow LIMIT $limit";   	
	return $sql;
}

//Erstellt eine Sieger-/Rekordabfrage als array mit "acctid", "name", "result", "fw", "dk" und "level"
function sieger($wettbewerb, $best=false){
	global $session;
	$zahl=10000; $zahl2=0; $limit=1; $typ="w"; $true=true;
	if ($best == true) $typ="best";
	switch($wettbewerb) {
		case "bogen"      : $wettbewerb2="".$typ."bogen3"; $fertigkeit="bogen"; $fertigkeit2="".$typ."".$fertigkeit.""; break;
		case "klettern"   : $wettbewerb2="".$typ."klettern0"; $fertigkeit="klettern"; $fertigkeit2="".$typ."".$fertigkeit.""; break;
		case "kochen"     : $wettbewerb2="".$typ."kochen"; $fertigkeit="kochen"; $fertigkeit2="".$typ."".$fertigkeit.""; break;
		case "musik"	  : $wettbewerb2="".$typ."musik2"; $zahl=-1; $fertigkeit="musik"; $fertigkeit2="".$typ."".$fertigkeit.""; break;
		case "reiten"     : $wettbewerb2="".$typ."reiten2";	$fertigkeit="reiten"; $fertigkeit2="".$typ."".$fertigkeit.""; break;
		case "schleichen" : $wettbewerb2="".$typ."schleichen0"; $zahl2=9999; $fertigkeit="schleichen"; $fertigkeit2="".$typ."".$fertigkeit.""; $true=false; break;
		case "schwimmen"  : $wettbewerb2="".$typ."schwimm2"; $fertigkeit="schwimmen"; $fertigkeit2="".$typ."schwimm"; break;
	}
	$result = db_query(abfrage_wettbewerb($wettbewerb2, $fertigkeit, $fertigkeit2, $zahl, $zahl2, $true, 1)) or die(db_error(LINK));
	$werte = db_fetch_assoc($result);
	return array( "acctid"=>"$werte[acctid]", "name"=>"$werte[name]", "result"=>"$werte[data1]", "fw"=>"$werte[data2]", "dk"=>"$werte[data3]", "level"=>"$werte[data4]");
}

function fest_endet(){
	global $session; 
	
	$dauer0=get_module_setting("dauer0", "wettkampf");
	set_module_setting("fest", 0, "wettkampf");
	set_module_setting("tage", $dauer0, "wettkampf");
	
	$tage2=translate_inline("Tagen");
	if ($dauer0==1)$tage2=translate_inline("Tag");
		
	$festzahl=get_module_setting("festzahl", "wettkampf");
	$festzahlneu=$festzahl+1;
	set_module_setting("festzahl", $festzahlneu, "wettkampf");
	
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=0 WHERE modulename='wettkampf' AND setting='blumenniederlegen'";
	db_query($sql);
	
	set_module_setting("statueblumen", 0, "wettkampf");
	  
	//Auszeichnungen festlegen (�berarbeitet von Nico Lachmann)
	$rotation=e_rand(0,8);
	$p1=($rotation) % 8 + 1;
	$p2=(1+$rotation) % 8 + 1;
	$p3=(2+$rotation) % 8 + 1;
	$p4=(3+$rotation) % 8 + 1;
	$p5=(4+$rotation) % 8 + 1;
	$p6=(5+$rotation) % 8 + 1;
	$p7=(6+$rotation) % 8 + 1;
	$p8=(7+$rotation) % 8 + 1;
	
	$p1name=get_module_setting("gegenstand".$p1."", "wettkampf");
	$p2name=get_module_setting("gegenstand".$p2."", "wettkampf");
	$p3name=get_module_setting("gegenstand".$p3."", "wettkampf");
	$p4name=get_module_setting("gegenstand".$p4."", "wettkampf");
	$p5name=get_module_setting("gegenstand".$p5."", "wettkampf");
	$p6name=get_module_setting("gegenstand".$p6."", "wettkampf");
	$p7name=get_module_setting("gegenstand".$p7."", "wettkampf");
	$p8name=get_module_setting("gegenstand".$p8."", "wettkampf");
	
	$siegerbogen=sieger(bogen);  
	$siegerklettern=sieger(klettern);
	$siegerkochen=sieger(kochen);
	$siegermusik=sieger(musik);
	$siegerreiten=sieger(reiten);
	$siegerschleichen=sieger(schleichen);
	$siegerschwimmen=sieger(schwimmen);
	
	if (is_module_active('bioextension')){
		require_once("modules/bioextension.php");
		$bio=1;
	}
	
	$name=$siegerbogen[name];
	if ($name!=""){
		$id=$siegerbogen[acctid];
		
		if ($bio == 1){
			$ext="`^".$festzahl.".`@ Fest: Sieg beim Bogenschie�en!";
			bioextension($ext, $id);
		}
		
		addnews_for_user($id, "`@Den Wettbewerb im Bogenschie�en gewann %s`@!", $name);
		$mailmessage1 = array("`@Du hast den Wettbewerb im Bogenschie�en gewonnen!`n`nDaf�r hast Du %s verliehen bekommen!", $p1name);
		systemmail($id, array("`@Sieg beim Bogenschie�en!"),$mailmessage1);
		set_module_setting("bgegenstand".$p1."", $id, "wettkampf");
		set_module_setting("sbogen", $id, "wettkampf");
	}

	$name=$siegerklettern[name];
	if ($name!=""){
		$id=$siegerklettern[acctid];
		
		if ($bio == 1){
			$ext="`^".$festzahl.".`@ Fest: Sieg beim Klettern!";
			bioextension($ext, $id);
		}
		
		addnews_for_user($id, "`@Den Wettbewerb im Klettern im Tiefenschacht gewann %s`@!", $name);
		$mailmessage1 = array("`@Du hast den Wettbewerb im Klettern gewonnen!`n`nDaf�r hast Du %s verliehen bekommen!", $p2name);
		systemmail($id,array("`@Sieg beim Klettern!"),$mailmessage1);
		set_module_setting("bgegenstand".$p2."", $id, "wettkampf");
		set_module_setting("sklettern", $id, "wettkampf");
	}
	
	$name=$siegerkochen[name];
	if ($name!=""){
		$id=$siegerkochen[acctid];
		
		if ($bio == 1){
			$ext="`^".$festzahl.".`@ Fest: Sieg beim Kochen!";
			bioextension($ext, $id);
		}
		
		$speise=get_module_setting("siegspeise", "wettkampf");
		addnews_for_user($id, "`@Den Wettbewerb im Kochen gewann %s`@ f�r `2%s`@!", $name, $speise);
		$mailmessage1 = array("`@Du hast den Wettbewerb im Kochen gewonnen!`n`nDaf�r hast Du %s verliehen bekommen!", $p3name);
		systemmail($id,array("`@Sieg beim Kochen!"),$mailmessage1);
		set_module_setting("bgegenstand".$p3."", $id, "wettkampf");
		set_module_setting("skochen", $id, "wettkampf");
	}
	
	$name=$siegermusik[name];
	if ($name!=""){
		$id=$siegermusik[acctid];
		
		if ($bio == 1){
			$ext="`^".$festzahl.".`@ Fest: Sieg beim Musizieren und Singen!";
			bioextension($ext, $id);
		}
		
		addnews_for_user($id, "`@Den Wettbewerb im Musizieren und Singen gewann %s`@!", $name);
		$mailmessage1 = array("`@Du hast den Wettbewerb im Musizieren und Singen gewonnen!`n`nDaf�r hast Du %s verliehen bekommen!", $p7name);
		systemmail($id, array("`@Sieg beim Musizieren und Singen!"),$mailmessage1);
		set_module_setting("bgegenstand".$p7."", $id, "wettkampf");
		set_module_setting("smusik", $id, "wettkampf");
	}
	
	$name=$siegerreiten[name];
	if ($name!=""){
		$id=$siegerreiten[acctid];
		
		if ($bio == 1){
			$ext="`^".$festzahl.".`@ Fest: Sieg beim Reiten!";
			bioextension($ext, $id);
		}
		
		addnews_for_user($id, "`@Den Wettbewerb im Reiten gewann %s`@!", $name);
		$mailmessage1 = array("`@Du hast den Wettbewerb im Reiten gewonnen!`n`nDaf�r hast Du %s verliehen bekommen!", $p6name);
		systemmail($id, array("`@Sieg beim Reiten!"),$mailmessage1);
		set_module_setting("bgegenstand".$p6."", $id, "wettkampf");
		set_module_setting("sreiten", $id, "wettkampf");
	}
	
	$name=$siegerschleichen[name];
	if ($name!=""){
		$id=$siegerschleichen[acctid];
		
		if ($bio == 1){
			$ext="`^".$festzahl.".`@ Fest: Sieg beim Schleichen und Verstecken!";
			bioextension($ext, $id);
		}
		
		addnews_for_user($id, "`@Den Wettbewerb im Schleichen und Verstecken auf dem Platz der V�lker gewann %s`@!", $name);
		$mailmessage1 = array("`@Du hast den Wettbewerb im Schleichen und Verstecken gewonnen!`n`nDaf�r hast Du %s verliehen bekommen!", $p5name);
		systemmail($id,array("`@Sieg beim Schleichen und Verstecken!"),$mailmessage1);
		set_module_setting("bgegenstand".$p5."", $id, "wettkampf");
		set_module_setting("sschleichen", $id, "wettkampf");
	}
	
	$name=$siegerschwimmen[name];
	if ($name!=""){
		$id=$siegerschwimmen[acctid];
		
		if ($bio == 1){
			$ext="`^".$festzahl.".`@ Fest: Sieg beim Schwimmen und Tauchen!";
			bioextension($ext, $id);
		}
		
		addnews_for_user($id, "`@Den Wettbewerb im Schwimmen und Tauchen im Schlammt�mpel gewann %s`@!", $name);
		$mailmessage1 = array("`@Du hast den Wettbewerb im Schwimmen und Tauchen im Schlammt�mpel gewonnen!`n`nDaf�r hast Du %s verliehen bekommen!", $p4name);
		systemmail($id,array("`@Sieg beim Schwimmen und Tauchen!"),$mailmessage1);
		set_module_setting("bgegenstand".$p4."", $id, "wettkampf");
		set_module_setting("sschwimmen", $id, "wettkampf");
	}
	
	addnews("`@`bHeute endete das `^%s.`@ Fest der V�lker! Das n�chste Fest wird in `^%s`@ %s stattfinden!`b", $festzahl, $dauer0, $tage2, true);
}

function fest_beginnt(){
	global $session;
	
	$dauer1=get_module_setting("dauer1", "wettkampf");
	set_module_setting("fest", 1, "wettkampf");
	set_module_setting("tage", $dauer1, "wettkampf");
	
	$festzahl=get_module_setting("festzahl", "wettkampf");
	
	//Ergebnisse zur�cksetzen
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wbogen0'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wbogen1'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wbogen2'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wbogen3'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wschwimm0'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wschwimm1'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wschwimm2'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wkochen'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wklettern0'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wschleichen0'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wreiten0'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wreiten1'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=10000 WHERE modulename='wettkampf' AND setting='wreiten2'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=-1 WHERE modulename='wettkampf' AND setting='wmusik0'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=-1 WHERE modulename='wettkampf' AND setting='wmusik1'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=-1 WHERE modulename='wettkampf' AND setting='wmusik2'";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=2 WHERE modulename='wettkampf' AND setting='schleichenversuch'";
	db_query($sql);
	 
	//Gegenst�nde zur�cknehmen
	set_module_setting("bgegenstand1", "", "wettkampf"); 
	set_module_setting("bgegenstand2", "", "wettkampf"); 
	set_module_setting("bgegenstand3", "", "wettkampf"); 
	set_module_setting("bgegenstand4", "", "wettkampf"); 
	set_module_setting("bgegenstand5", "", "wettkampf"); 
	set_module_setting("bgegenstand6", "", "wettkampf"); 
	set_module_setting("bgegenstand7", "", "wettkampf"); 
	set_module_setting("bgegenstand8", "", "wettkampf"); 

	if ($dauer1!=1) addnews("`@`bW�hrend der kommenden `^%s`@ Tage findet das `^%s.`@ Fest der V�lker statt!`b", $dauer1, $festzahl, true);
	if ($dauer1==1) addnews("`@`bHeute findet das `^%s.`@ Fest der V�lker statt!`b", $festzahl, true);
}

//Steigerungsm�glichkeiten / Letzter Gewinner / Rekordhalter (verwendet bei jedem "aus-*")
function welche_steigerungen($fertigkeit){
	global $session;
	$fertigkeitlink=$fertigkeit;
	$fertigkeit=get_module_pref($fertigkeit, "fertigkeiten");
	$fertigkeit2=get_fertigkeit($fertigkeitlink);

	$steigerung=get_module_pref("usersteigerung", "fertigkeiten");
	$grund=get_module_setting("grund", "wettkampf");
	$dklimit=get_module_pref("userdklimit", "fertigkeiten");

	$steigeinstellung=get_module_setting("steigerung", "fertigkeiten");
	
	//Sperre, damit man den Maximalwert nicht austricksen kann
	$sperre=0;

	if ($fertigkeit==95) $sperre=1;
			
	//Bestenlistenabfrage
	if ($fertigkeitlink == "bogen"){
		$wbogen3=get_module_pref("wbogen3", "wettkampf");
		$bestbogen3=get_module_pref("bestbogen3", "wettkampf");
		$t1="bestbogen3";
		$t2="wbogen3";
		$rekord_text=translate_inline("W�hrend sie spricht, f�llt Dein Blick auf die B�ste des besten Sch�tzen aller Zeiten:"); 
		$sieger_text=translate_inline("Sie steht halb auf einer Zeichnung des letzten Gesamtsiegers:");
		$typ_text=translate_inline("Bogenschie�en");
	}else if ($fertigkeitlink == "schwimmen"){
		$wschwimm2=get_module_pref("wschwimm2", "wettkampf");
		$bestschwimm2=get_module_pref("bestschwimm2", "wettkampf");
		$t1="bestschwimm2";
		$t2="wschwimm2";
		$rekord_text=translate_inline("Bevor Du antwortest, f�llt Dein Blick noch auf die fast f�nf Fu� hohe Tonskulptur des besten Schlammschwimmers und -tauchers aller Zeiten:"); 
		$sieger_text=translate_inline("Daneben steht eine bedeutend kleinere des letzten Gesamtsiegers:");
		$typ_text=translate_inline("Schwimmen und Tauchen");
	}else if ($fertigkeitlink == "klettern"){
		$wklettern0=get_module_pref("wklettern0", "wettkampf");
		$bestklettern0=get_module_pref("bestklettern0", "wettkampf");
		$t1="bestklettern0";
		$t2="wklettern0";
		$rekord_text=translate_inline("W�hrend er spricht, f�llt Dein Blick auf die Mondsilberm�nzen auf dem Tresen vor Dir, die zu Ehren des besten Kletterers aller Zeiten gepr�gt wurden:"); 
		$sieger_text=translate_inline("Sie liegen auf einer Ehrentafel aus Silber, auf der die Sieger der Wettbewerbe verzeichnet sind. Den letzten Wettbewerb gewann");
		$typ_text=translate_inline("Klettern");
	}else if ($fertigkeitlink == "reiten"){
		$wreiten2=get_module_pref("wreiten2", "wettkampf");
		$bestreiten2=get_module_pref("bestreiten2", "wettkampf");
		$t1="bestreiten2";
		$t2="wreiten2";
		$rekord_text=translate_inline("W�hrend er spricht, siehst Du, dass er ein Amulett tr�gt, auf dem der beste Reiter aller Zeiten abgebildet ist:"); 
		$sieger_text=translate_inline("Hinter Hannes VI., an der Hauswand weht ein schlechtbefestigtes, gro�es Blatt, auf dem der Sieger des letzten Gesamtwettbewerbs verk�ndet wird:");
		$typ_text=translate_inline("Reiten");
	}else if ($fertigkeitlink == "schleichen"){
		$wschleichen0=get_module_pref("wschleichen0", "wettkampf");
		$bestschleichen0=get_module_pref("bestschleichen0", "wettkampf");
		$t1="bestschleichen0";
		$t2="wschleichen0";
		$rekord_text=translate_inline("In den Tisch eingeritzt und mit Gold ausgef�llt, steht der Name des besten Schleichers aller Zeiten:"); 
		$sieger_text=translate_inline("Auf dem Tisch steht der Name des letzten Gewinners geschrieben - mit Blut:");
		$typ_text=translate_inline("Schleichen und Verstecken");
	}else if ($fertigkeitlink == "kochen"){
		$wkochen=get_module_pref("wkochen", "wettkampf");
		$bestkochen=get_module_pref("bestkochen", "wettkampf");
		$t1="bestkochen";
		$t2="wkochen";
		$rekordspeise=get_module_setting("bestespeise", "wettkampf");
		$siegspeise=get_module_setting("siegspeise", "wettkampf");
		$typ_text=translate_inline("Kochen");
	}else if ($fertigkeitlink == "musik"){
		$t1="bestmusik2";
		$t2="wmusik2";
		$rekord_text=translate_inline("An der R�ckwand der B�hne prangt das gro�e Plakat des besten Interpreten aller Zeiten:"); 
		$sieger_text=translate_inline("�berall liegen Autogrammkarten, die vom letzten Gewinner �briggeblieben sind:");
		$typ_text=translate_inline("Musik und Gesang");
	}
			
	$modtext=""; 
	if ($session[user][race]==get_module_setting("bonus".$fertigkeitlink."", "fertigkeiten")) $modtext=translate_inline("`@ (einschlie�lich Rassenbonus)`@");
	else if ($session[user][race]==get_module_setting("malus".$fertigkeitlink."", "fertigkeiten"))$modtext=translate_inline("`\$ (einschlie�lich Rassenmalus)`@");
 
	//Rekord
	$rekord=sieger($fertigkeitlink, true);
	$rekord_text2=$rekord[name];
	if ($rekord[acctid] == $session[user][acctid]) $rekord_text2=translate_inline("Das bist ja Du");
		
	//Sieger
	$sieger=sieger($fertigkeitlink, false);
	$sieger_text2=$sieger[name];
	if ($sieger[acctid] == $session[user][acctid]) $sieger_text2=translate_inline("`bDu erkennst Dich selbst`b"); 
		
	//Ausgabe
	if ($fertigkeitlink != "kochen"){
		if ($rekord[acctid]!="") output("`@%s`b `^%s`@`b! ", $rekord_text, $rekord_text2);
		if ($sieger[acctid]!="") output("`@%s `^%s`@!", $sieger_text, $sieger_text2);
	}else{
		if ($rekord[acctid]!="") output("Du schaust Dich etwas um und erblickst das Kochbuch des besten Kochs aller Zeiten, der in der Lage ist, `2%s`@ zuzubereiten: `b`^%s`b`@!", $rekordspeise, $rekord_text2);
		if ($sieger[acctid]!="") output("Heraus schaut als Lesezeichen das Siegerrezept des letzten Gewinners, in dem steht wie man `2%s`@ zubereitet: `^%s`@!", $siegspeise, $sieger_text2);
	}

	//Momentaner FW
	output ("`@`n`nDein momentaner Fertigkeitswert im %s betr�gt `^%s/100`@ Punkten! %s", $typ_text, $fertigkeit2, $modtext);
		
	if ($sperre==1){
		output("`@`n`nDu hast bereits die Meisterschaft erlangt und somit alles gelernt, was man lernen kann.");
	}else if ($steigerung!=0 && $dklimit>0){
		if ($fertigkeit<85 && $steigerung!=0) output("`n`n`@Welche Art von Unterricht m�chtest Du bekommen?");
		
		$gespr�ch = round($session['user']['level']*$grund*0.66);
		$normal = round($session['user']['level']*$grund*1);
		$intensiv = round($session['user']['level']*$grund*1.66);

		if ($fertigkeit>=25 && $fertigkeit<50){
			output("`@Da Du bereits %s bist, wird ein h�herer Preis verlangt.", ($session[user][sex]?"Sch�lerin":"Sch�ler"));
			$gespr�ch = round($session['user']['level']*$grund);
			$normal = round($session['user']['level']*$grund*1.33);
			$intensiv = round($session['user']['level']*$grund*2);
		}else if ($fertigkeit>=50 && $fertigkeit<75){
			output("`@Da Du bereits %s bist, wird ein h�herer Preis verlangt. Au�erdem w�rden Dich reine Gespr�che nun nicht mehr weiterbringen.", ($session[user][sex]?"Fortgeschrittene":"Fortgeschrittener"));
			$normal = round($session['user']['level']*$grund*2);
			$intensiv = round($session['user']['level']*$grund*2.66);
		}else if ($fertigkeit>=75 && $fertigkeit<85){
			output("`@Da Du bereits �ber sehr gute F�higkeiten verf�gst, wird ein h�herer Preis verlangt. Au�erdem w�rden Dich reine Gespr�che nun nicht mehr weiterbringen.");
			$normal = round($session['user']['level']*$grund*2.66);
			$intensiv = round($session['user']['level']*$grund*3.33);
		}else if ($fertigkeit>=85){
			if ($fertigkeit <= 95) output("`@`n`nDa Du Dich bereits in der N�he der absoluten Meisterschaft, befindest, wird ein h�herer Preis verlangt. Au�erdem w�rden Dich reine Gespr�che und normale �bungsstunden nun nicht mehr weiterbringen.");
			else output("`@`n`nDa Du zu den absoluten Gro�meistern dieser Disziplin geh�rst, wird ein h�herer Preis verlangt. Au�erdem w�rden Dich reine Gespr�che und normale �bungsstunden nun nicht mehr weiterbringen.");
			$intensiv = round($session['user']['level']*$grund*4.66);
		}

		set_module_pref("preis0", $gespr�ch, "wettkampf");
		set_module_pref("preis1", $normal, "wettkampf");
		set_module_pref("preis2", $intensiv, "wettkampf");
	}
	
	if ($steigerung==0 && $fertigkeit<85 && $dklimit>0){
    	output("`@`n`nF�r heute hast Du bereits so viel versucht dazuzulernen, dass Du schon beim schieren Gedanken an weiteren Unterricht Kopfschmerzen bekommst.");
	}
	if ($dklimit<=0) output("`n`nDu hast das sichere Gef�hl, dass Dein Kopf platzen wird, wenn Du vor der n�chsten Drachenjagd noch weitere �bungsstunden nimmst.");

	if ($steigerung!=$steigeinstellung && $fertigkeit>=85 && $dklimit>0 && $sperre==0){
    	output("`@`n`nIn dieser Fertigkeit bist Du bereits so gut, dass Du nur noch etwas hinzulernen kannst, wenn Du die gesamte Zeit, die Du am Tag zum �ben zur Verf�gung hast, dieser einen Disziplin widmest. Komm morgen wieder.");
	}
    if ($fertigkeit<25 && $steigerung!=0 && $dklimit >0) output("`n`n`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."0&subop=0'>Ich m�chte mich nur eine Viertelstunde lang dar�ber unterhalten und hoffe, dabei doch das ein oder andere zu lernen. Das kostet mich %s Goldst�cke.</a>", $gespr�ch, true);
    if ($fertigkeit<25 && $steigerung!=0 && $dklimit >0) output("`@`n`n<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."1&subop=0'>Ich m�chte eine ganz normale �bungsstunde. Das kostet mich %s Goldst�cke.</a>", $normal, true);
    if ($fertigkeit<25 && $steigerung!=0 && $dklimit >0) output("`@`n`n<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."2&subop=1'>Ich m�chte eine intensive Doppelstunde. Das kostet mich %s Goldst�cke und einen Edelstein.</a>", $intensiv, true);
	if ($fertigkeit<50 && $fertigkeit>=25 && $steigerung!=0 && $dklimit >0) output("`n`n`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."0&subop=0'>Ich m�chte mich nur eine Viertelstunde lang dar�ber unterhalten und hoffe, dabei doch das ein oder andere zu lernen. Das kostet mich %s Goldst�cke.</a>", $gespr�ch, true);
    if ($fertigkeit<85 && $fertigkeit>=25 && $steigerung!=0 && $dklimit >0) output("`@`n`n<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."1&subop=1'>Ich m�chte eine ganz normale �bungsstunde. Das kostet mich %s Goldst�cke und einen Edelstein.</a>", $normal, true);
    if ($fertigkeit<85 && $fertigkeit>=25 && $steigerung!=0 && $dklimit >0)	output("`@`n`n<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."2&subop=2'>Ich m�chte eine intensive Doppelstunde. Das kostet mich %s Goldst�cke und zwei Edelsteine.</a>", $intensiv, true);
    if ($fertigkeit>=85 && $steigerung==$steigeinstellung && $dklimit >0  && $sperre!=1) output("`@`n`n<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."2&subop=2'>Ich m�chte eine intensive Doppelstunde. Das kostet mich %s Goldst�cke und zwei Edelsteine.</a>", $intensiv, true);
    output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Zur�ck.</a>", true);

    
    if ($fertigkeit<25 && $steigerung!=0 && $dklimit >0) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."0&subop=0");
    if ($fertigkeit<25 && $steigerung!=0 && $dklimit >0) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."1&subop=0");
    if ($fertigkeit<25 && $steigerung!=0 && $dklimit >0) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."2&subop=1");
    if ($fertigkeit<50 && $fertigkeit>=25 && $steigerung!=0 && $dklimit >0) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."0&subop=0");
    if ($fertigkeit<85 && $fertigkeit>=25 && $steigerung!=0 && $dklimit >0) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."1&subop=1");
    if ($fertigkeit<85 && $fertigkeit>=25 && $steigerung!=0 && $dklimit >0) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."2&subop=2");
    if ($fertigkeit>=85 && $steigerung==$steigeinstellung && $dklimit >0 && $sperre!=1) addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."2&subop=2");
    addnav("","runmodule.php?module=wettkampf&op1=");	
    addnav("�bungen");
    
    if ($fertigkeit<25 && $steigerung!=0 && $dklimit >0) addnav("Unterhalten","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."0&subop=0");
    if ($fertigkeit<25 && $steigerung!=0 && $dklimit >0) addnav("Normale �bungsstunde","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."1&subop=0");
    if ($fertigkeit<25 && $steigerung!=0 && $dklimit >0) addnav("Intensive Doppelstunde","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."2&subop=1");
    if ($fertigkeit<50 && $fertigkeit>=25 && $steigerung!=0 && $dklimit >0) addnav("Unterhalten","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."0&subop=0");
    if ($fertigkeit<85 && $fertigkeit>=25 && $steigerung!=0 && $dklimit >0) addnav("Normale �bungsstunde","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."1&subop=1");
    if ($fertigkeit<85 && $fertigkeit>=25 && $steigerung!=0 && $dklimit >0) addnav("Intensive Doppelstunde","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."2&subop=2");
    if ($fertigkeit>=85 && $steigerung==$steigeinstellung && $dklimit >0 && $sperre!=1) addnav("Intensive Doppelstunde","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeitlink."&subop2=".$fertigkeitlink."2&subop=2");
    addnav("Zur�ck","runmodule.php?module=wettkampf&op1=");
}

//Steigerungen
function steigerung($fertigkeit, $typ, $gems){
	global $session;
	$fertigkeitvar="\$".$fertigkeit."";
	$fw=get_module_pref($fertigkeit, "fertigkeiten");
	if ($typ == "gespr�ch")	$preis=get_module_pref("preis0", "wettkampf");
	else if ($typ == "normal") $preis=get_module_pref("preis1", "wettkampf");
	else $preis=get_module_pref("preis2", "wettkampf");
	
	if ($fertigkeit == "bogen") $text=translate_inline("Es gibt einige Dinge, die die Elfen bei den Zwergen gelernt haben, z.B. erst sicherzustellen, dass der Kunde zahlungskr�ftig ist. Und Du geh�rst offenbar nicht dazu!");
	else if ($fertigkeit == "reiten") $text=translate_inline("Hannes VI. runzelt die Stirn und scheint auf irgendetwas zu warten ... es k�nnte Geld sein.");
	else if ($fertigkeit == "schleichen") $text=translate_inline("Kalyth l�chelt Dich an. `#'Kein Geld dabei? Vielleicht sollte ich Dein Leben in Zahlung nehmen ...'");
	else if ($fertigkeit == "klettern") $text=translate_inline("Regon r�mpft die Nase. `#'Aber nicht umsonst! Rabatt gibt es auch nicht ...'");
	else if ($fertigkeit == "kochen") $text=translate_inline("Ag'nsra beginnt zu lachen. `#'Ihr habt Euer Geld wohl zu Hause vergessen!'");
	else if ($fertigkeit == "musik") $text=translate_inline("Ra'esha sch�ttelt den Kopf: `#'Nur der Tod ist umsonst - zum Gl�ck, w�re auch schade drum.'");
	else if ($fertigkeit == "schwimmen") $text=translate_inline("Der Troll schaut Dich grimmig an. `#'Ich lehre nur, wenn Ihr Euren Geldbeutel leert - und genug dabei herauskommt.'");
	
	if ($session[user][gold] < $preis || $typ == "normal" && $session[user][gems] < $gems || $typ == "intensiv" && $session[user][gems] < $gems){
		output("`@%s`@", $text);
	}else {
		$steigerung=get_module_pref("usersteigerung", "fertigkeiten");
		$session[user][gold]-=$preis;
	
		if ($typ == "gespr�ch"){
			$neu=$steigerung-=1;
			set_module_pref("usersteigerung",$neu, "fertigkeiten");
			switch(e_rand(1,10)){ 
				case 1: 
				case 2: 
				case 3:
				case 4:
				case 5:
					output("`@Es gelingt Dir, aus dem Gespr�ch neue Erkenntnisse zu ziehen. Du verbesserst Deine F�higkeit um einen Punkt!");			
					$wert=get_module_pref("$fertigkeit", "fertigkeiten");
					debuglog("PdV: Steigerung von ".$fertigkeit." (".$wert.") um 1 (Gespr�ch).");
					set_module_pref("$fertigkeit", $wert+1, "fertigkeiten");
					$dklimit=get_module_pref("userdklimit", "fertigkeiten");
					$dklimitneu=$dklimit-1;
					set_module_pref("userdklimit", $dklimitneu, "fertigkeiten");
					break;
				case 6:
				case 7:
				case 8:
				case 9:
				case 10:															
					output("`@Es gelingt Dir nicht, aus dem Gespr�ch neue Erkenntnisse zu ziehen.");
					debuglog("PdV: Steigerung von ".$fertigkeit." (".$wert.") misslungen (Gespr�ch).");
					break;				
			}
		}else if ($typ == "normal"){
			$session[user][gems]-=$gems;
			$neu=$steigerung-=1;
			set_module_pref("usersteigerung",$neu, "fertigkeiten");
			output("`@Nach einer �bungsstunde hat sich Dein Wert um einen Punkt verbessert!");			
			$wert=get_module_pref("$fertigkeit", "fertigkeiten");
			debuglog("PdV: Steigerung von ".$fertigkeit." (".$wert.") um 1 (Normal).");
			set_module_pref("$fertigkeit", $wert+1, "fertigkeiten");
		}else if ($typ == "intensiv"){
			$session[user][gems]-=$gems;
			if ($fw >= 85 && $fw < 95){
				set_module_pref("usersteigerung", 0, "fertigkeiten");
				output("`@Du bist hochaufmerksam, aber da Du bereits so gut bist, f�llt es Dir schwer Neues zu lernen. Du verbesserst Deinen Wert um einen Punkt und bist f�r heute v�llig erledigt.");
				$wert=get_module_pref("$fertigkeit", "fertigkeiten");
				debuglog("PdV: Steigerung von ".$fertigkeit." (".$wert.") um 1 (Intensiv).");
				set_module_pref("$fertigkeit", $wert+1, "fertigkeiten");
				$dklimit=get_module_pref("userdklimit", "fertigkeiten");
				$dklimitneu=$dklimit-1;
				set_module_pref("userdklimit", $dklimitneu, "fertigkeiten");
			}else if ($fw < 85){
				$neu=$steigerung-=1;
				set_module_pref("usersteigerung", $neu, "fertigkeiten");
				
				switch(e_rand(1,10)){ 
					case 1: 
					case 2: 
					case 3:
					case 4:
					case 5:
						output("`@Du bist hochaufmerksam und erlangst Einblicke, von denen Du nicht h�ttest zu tr�umen gewagt. Du verbesserst Deinen Wert um zwei Punkte!");
						$wert=get_module_pref("$fertigkeit", "fertigkeiten");
						debuglog("PdV: Steigerung von ".$fertigkeit." (".$wert.") um 2 (Intensiv).");
						set_module_pref("$fertigkeit", $wert+2, "fertigkeiten");
						$dklimit=get_module_pref("userdklimit", "fertigkeiten");
						$dklimitneu=$dklimit-2;
						set_module_pref("userdklimit", $dklimitneu, "fertigkeiten");			
					break;
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:															
						output("`@Du bist hochaufmerksam und erlangst Einblicke, von denen Du nicht h�ttest zu tr�umen gewagt. Doch insgesamt war das zu viel auf einmal, weshalb sich Dein Wert nur um einen Punkt erh�ht.");
						debuglog("PdV: Steigerung von ".$fertigkeit." um 1 (Intensiv).");
						$wert=get_module_pref("$fertigkeit", "fertigkeiten");
						set_module_pref("$fertigkeit", $wert+1, "fertigkeiten");
						$dklimit=get_module_pref("userdklimit", "fertigkeiten");
						$dklimitneu=$dklimit-1;
						set_module_pref("userdklimit", $dklimitneu, "fertigkeiten");			
					break;			
				}
			}
		}
	}
	addnav("Zur�ck","runmodule.php?module=wettkampf&op1=aufruf&subop1=a".$fertigkeit."&subop2=aus-".$fertigkeit."");
}

function set_modtext($fertigkeit){
	$modbogentext="";
    $modschwimmentext="";
	$modkochentext="";
	$modkletterntext="";
	$modmusiktext="";
	$modschleichentext="";
	$modreitentext="";
	
	$mod=get_mod($fertigkeit);
	
	if ($fertigkeit=="bogen"){
		if ($mod == -5) $modbogentext=translate_inline("`\$ (einschlie�lich Rassenmalus)`@");
		if ($mod == 5) $modbogentext=translate_inline("`@ (einschlie�lich Rassenbonus)`@"); 
		return $modbogentext;
	}else if ($fertigkeit=="schwimmen"){
		if ($mod == -5) $modschwimmentext=translate_inline("`\$ (einschlie�lich Rassenmalus)`@");
		if ($mod == 5) $modschwimmentext=translate_inline("`@ (einschlie�lich Rassenbonus)`@"); 
		return $modschwimmentext;
	}else if ($fertigkeit=="kochen"){
		if ($mod == -5) $modkochentext=translate_inline("`\$ (einschlie�lich Rassenmalus)`@");
		if ($mod == 5) $modkochentext=translate_inline("`@ (einschlie�lich Rassenbonus)`@"); 
		return $modkochentext;
	}else if ($fertigkeit=="klettern"){
		if ($mod == -5) $modkletterntext=translate_inline("`\$ (einschlie�lich Rassenmalus)`@");
		if ($mod == 5) $modkletterntext=translate_inline("`@ (einschlie�lich Rassenbonus)`@"); 
		return $modkletterntext;
	}else if ($fertigkeit=="musik"){
		if ($mod == -5) $modmusiktext=translate_inline("`\$ (einschlie�lich Rassenmalus)`@");
		if ($mod == 5) $modmusiktext=translate_inline("`@ (einschlie�lich Rassenbonus)`@"); 
		return $modmusiktext;
	}else if ($fertigkeit=="reiten"){
		if ($mod == -5) $modreitentext=translate_inline("`\$ (einschlie�lich Rassenmalus)`@");
		if ($mod == 5) $modreitentext=translate_inline("`@ (einschlie�lich Rassenbonus)`@"); 
		return $modreitentext;
	}else if ($fertigkeit=="schleichen"){
		if ($mod == -5) $modschleichentext=translate_inline("`\$ (einschlie�lich Rassenmalus)`@");
		if ($mod == 5) $modschleichentext=translate_inline("`@ (einschlie�lich Rassenbonus)`@");
		return $modschleichentext;
	}	
}
?>