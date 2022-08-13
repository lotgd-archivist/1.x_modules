<?php
/*
 * Copyright (C) 2006 the_Cr0w (aka Vancha March)
 * Email: c.herold@inode.at
 * Homepage: http://www.logd-diablo.at
 *
 * Plattform: LOTGD - 1.1.0 DragonPrime Edition
 * racevampir.php
 */

function racevampir_getmoduleinfo(){
	$info = array(
		"name"=>"Rasse - Vampir",
		"version"=>"1.2",
		"author"=>"`@Vancha March",
		"category"=>"Races",
		"download"=>"http://vancha-march.logd-diablo.at/index.php?mod=files&action=view&where=1",
		"settings"=>array(
			"Vampirrasse Einstellungen,title",
			"villagename"=>"Name f�r den Berg der Vampire|Mt.Transylvania",
			"minedeathchance"=>"Wahrscheinlichkeit f�r Vampire in der Mine zu sterben,range,0,100,1|25",
			"mindk"=>"Wie viele Drachenkills braucht man damit die Rasse verf�gbar ist?,int|10",
			"atcreation"=>"K�nnen Spieler bei der Charaktererstellung Vampir werden?,bool|0",
			"hint1"=>"Spieler mit einer anderen Rasse haben die M�glichkeit sp�ter zu Vampiren zu werden,note",
			
			"F�rstenhalle,title",
			"hint2"=>"Nur verf�gbar wenn Cities von Eric Stevens installiert ist,note",
			"openhall"=>"Soll die F�rstenhalle ge�ffnet werden?,enum,yes,Ja,no,Nein|yes",
			"openhallfornotvampires"=>"D�rfen auch Nichtvampire die Halle betreten?,enum,yes,Ja,no,Nein|no",
			"namefuerst1"=>"Name des ersten F�rtsen|Vancha March",
			"namefuerst2"=>"Name des zweiten F�rtsen|Mika Ver Leth",
			"namefuerst3"=>"Name des dritten F�rtsen|Pfeilspitze",
			"discuss"=>"Thema der heutigen Diskussion|der Speiseplan f�r Mittwoch",
			
			"Vampir werden,title",
			"hint3"=>"Nur verf�gbar wenn Cities von Eric Stevens installiert ist,note",
			"racechangetovampire"=>"K�nnen andere Rassen zu Vampiren werden?,bool|1",
			"changeracedk"=>"Ab wie vielen Drackenkills k�nnen andere Rassen zu Vampiren werden?,range,0,31,1|10",
			
			"Bluttrinken,title",
			"hint4"=>"Ich empfehle 'Ja' da Vampire sonst zu stark sind,note",
			"needbloodall"=>"Brauchen Vampire blut?,bool|1",
			"bluttageall"=>"Tage bis ein Vampir wieder Blut ben�tigt,range,1,6,1|3",
		),
		"prefs"=>array(
			"bluttage"=>"Tage bis dieser Vampir wieder Blut ben�tigt,int|3"/*.get_module_setting("bluttageall")*/,
		),
		
	);
	return $info;
}

function racevampir_install(){
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("creatureencounter");
	module_addhook("villagetext");
	module_addhook("travel");
	module_addhook("charstats");
	module_addhook("village");
	module_addhook("validlocation");
	module_addhook("validforestloc");
	module_addhook("moderate");
	module_addhook("changesetting");
	module_addhook("raceminedeath");
	module_addhook("racenames");
	module_addhook("newday");
	return true;
}

function racevampir_uninstall(){
	global $session;
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$gname = get_module_setting("villagename");
	$sql = "UPDATE " . db_prefix("accounts") . " SET location='$vname' WHERE location = '$gname'";
	db_query($sql);
	if ($session['user']['location'] == $gname)
		$session['user']['location'] = $vname;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Vampir'";
	db_query($sql);
	if ($session['user']['race'] == 'Vampir')
		$session['user']['race'] = RACE_UNKNOWN;
	
	return true;
}

function racevampir_dohook($hookname,$args){
	global $session,$resline;
	$city = get_module_setting("villagename");
	$race = "Vampir";
	switch($hookname){
	case "racenames":
		$args[$race] = $race;
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Deine Vampirkr�fte lassen dich unbeschadet davon kommen.`n";
			$args['schema'] = "module-racevampir";
		}
		break;
	case "changesetting":
		if ($args['setting'] == "villagename" && $args['module']=="racevampir") {
			if ($session['user']['location'] == $args['old'])
				$session['user']['location'] = $args['new'];
			$sql = "UPDATE " . db_prefix("accounts") .
				" SET location='" . addslashes($args['new']) .
				"' WHERE location='" . addslashes($args['old']) . "'";
			db_query($sql);
			if (is_module_active("cities")) {
				$sql = "UPDATE " . db_prefix("module_userprefs") .
					" SET value='" . addslashes($args['new']) .
					"' WHERE modulename='cities' AND setting='homecity'" .
					"AND value='" . addslashes($args['old']) . "'";
				db_query($sql);
			}
		}
		break;
	case "charstats":
		if ($session['user']['race']==$race){
			addcharstat("Vital Info");
			addcharstat("Race", translate_inline($race));
			if(get_module_setting("needbloodall") == 0)
			break;
			addcharstat("Tage ohne Blut", get_module_pref("bluttage"));
			
		}
		
		break;
	case "chooserace":
		if (get_module_setting("atcreation") == 0)
		break;
		output("<a href='newday.php?setrace=$race$resline'>In %s</a>", $city, true);
		addnav("`4Vampir`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		modulehook("racevampir-chooserace");
		break;
	case "setrace":
		if ($session['user']['race']==$race){
			output("`#Als Vampir bist du st�rker als andere Rassen und kannst dich selbst heilen.`n");
			output("`^Du erh�ltst 2 Punkte auf Angriff und Verteidigung und die F�higkeit dich selbst zu heilen.");
			if (is_module_active("cities")) {
				if ($session['user']['dragonkills']==0 &&
						$session['user']['age']==0){
					set_module_setting("newest-$city",
							$session['user']['acctid'],"cities");
				}
				#set_module_pref("homecity",$city,"cities");
				if ($session['user']['age'] == 0)
					$session['user']['location']=$city;
			}
		}
		break;
	case "validforestloc":
	case "validlocation":
		if (is_module_active("cities"))
			$args[$city] = "village-$race";
		break;
	case "moderate":
		if (is_module_active("cities")) {
			tlschema("commentary");
			$args["village-$race"]=sprintf_translate("Stadt %s", $city);
			tlschema();
		}
		tlschema("commentary");
		$args["fuerstenhalle"]=sprintf_translate("Die F�rstenhalle");
		tlschema();
		break;
	case "creatureencounter":
		if ($session['user']['race']==$race){
			racevampir_checkcity();
			$args['creaturegold']=round($args['creaturegold']*1.2,0);
		}
		break;
	case "travel":
		$capital = getsetting("villagename", LOCATION_FIELDS);
		$hotkey = substr($city, 0, 1);
		tlschema("module-cities");
		if ($session['user']['location']==$capital){
			addnav("Sicherere Reise");
			addnav(array("%s?Go to %s", $hotkey, $city),"runmodule.php?module=cities&op=travel&city=$city");
		}elseif ($session['user']['location']!=$city){
			addnav("Gef�hrlichere Reisen");
			addnav(array("%s?Go to %s", $hotkey, $city),"runmodule.php?module=cities&op=travel&city=$city&d=1");
		}
		if ($session['user']['superuser'] & SU_EDIT_USERS){
			addnav("Superuser");
			addnav(array("%s?Go to %s", $hotkey, $city),"runmodule.php?module=cities&op=travel&city=$city&su=1");
		}
		tlschema();
		break;	
	case "villagetext":
		racevampir_checkcity();
		if ($session['user']['location'] == $city){
			$args['text']=array("`#`c`bDie Hallen von %s, Zuflucht und Heimat f�r viele Vampire.`b`c`n", $city);
			$args['schemas']['text'] = "module-racevampir";
			$args['clock']="`n`3Du sch�tzt, dass es `#%s`3 ist.`n";
			$args['schemas']['clock'] = "module-racevampir";
			if (is_module_active("calendar")) {
				$args['calendar'] = "`n`3Es ist das `#Jahr %4\$s`3, `#%3\$s %2\$s`3.`nDen Wochentag kannst du nur sch�tzen: `#%1\$s`3.`n";
				$args['schemas']['calendar'] = "module-racevampir";
			}
			$args['title']= array("Die Hallen von %s", $city);
			$args['schemas']['title'] = "module-racevampir";
			$args['sayline']="sagt";
			$args['schemas']['sayline'] = "module-racevampir";
			$args['talk']="`n`#In der N�he reden ein paar Bewohner:`n";
			$args['schemas']['talk'] = "module-racevampir";
			$new = get_module_setting("newest-$city", "cities");
			if ($new != 0) {
				$sql =  "SELECT name FROM " . db_prefix("accounts") .
					" WHERE acctid='$new'";
				$result = db_query_cached($sql, "newest-$city");
				$row = db_fetch_assoc($result);
				$args['newestplayer'] = $row['name'];
				$args['newestid']=$new;
			} else {
				$args['newestplayer'] = $new;
				$args['newestid']="";
			}
			if ($new == $session['user']['acctid']) {
				$args['newest']="`n`3Du bist das neuste Mitglied in der Gemeinschaft der Vampire";
			} else {
				$args['newest']="`n`3Das neuste Mitglied in der Gemeinschaft der Vampire ist `#%s`3.";
			}
			$args['schemas']['newest'] = "module-racevampir";
			$args['gatenav']="H�hleneing�nge";
			$args['schemas']['gatenav'] = "module-racevampir";
			$args['fightnav']="";
			$args['schemas']['fightnav'] = "module-racevampir";
			$args['marketnav']="";
			$args['schemas']['marketnav'] = "module-racevampir";
			$args['tavernnav']="Die Hallen";
			$args['schemas']['tavernnav'] = "module-racevampir";
			$args['section']="village-$race";
			
			blocknav("lodge.php");
			blocknav("bank.php");
			blocknav("weapons.php");
			blocknav("armor.php");
			blocknav("gypsy.php");
			blocknav("gardens.php");
			blocknav("pvp.php");
			blocknav("forest.php");
			blockmodule("crazyaudrey");
		}
		break;
	case "village":
		if ($session['user']['location'] == $city) {
			tlschema($args['schemas']['tavernnav']);
			addnav($args['tavernnav']);
			tlschema();
			addnav("F�rtsenhalle","runmodule.php?module=racevampir&op=vorfuerstenhalle");
			addnav("Kamparenen","runmodule.php?module=racevampir&op=arena");
			 if (get_module_setting("racechangetovampire") == "1")
			 {
			  addnav("Vampir werden","runmodule.php?module=racevampir&op=vampirwerden");
			 }
		}
		if ($session['user']['race']==$race && get_module_setting("needbloodall") == 1){
		tlschema($args['schemas']['gatenav']);
		addnav($args['gatenav']);
		tlschema();
		addnav("Opfer suchen","runmodule.php?module=racevampir&op=opfersuchen");
		}
		break;
	 case "newday":
     	 if ($session['user']['race']==$race){
         	 racevampir_checkcity();
		 	 apply_buff("racialbenefit",array(
		 	 "name"=>"`@Vampirkr�fte`0",
		 	 "regen"=>"(<defense>?(1+((3+floor(<level>/5))/<defense>)):0)",
			 "atkmod"=>"(<attack>?(1+((2+floor(<level>/5))/<attack>)):0)",
			 "defmod"=>"(<defense>?(1+((2+floor(<level>/5))/<defense>)):0)",
			 "badguydmgmod"=>1.05,
			 "allowinpvp"=>1,
 			 "allowintrain"=>1,
			 "rounds"=>-1,
 			 "schema"=>"module-racevampir",
             )
			 );

		  if(get_module_setting("needbloodall") == 0)
		  break;
		  $bluttage = get_module_pref("bluttage");
		  $bluttageall = get_module_setting("bluttageall");
		  $bluttage--;
		  if($bluttage <= 0)
		  {
		   $session['user']['alive']=false;
		   $session['user']['hitpoints']=0;
		   output("`n`n`4Du bist tot. H�ttest du mehr Blut getrunken w�re das nicht passiert.`n`n");
		   addnav("T�gliche News","news.php");
		   addnews("%s `3ist gestorben, weil er/sie nicht genug `4Blut`3 getrunken hat",$session['user']['name']);
		   set_module_pref("bluttage", $bluttageall);
		  }else{
		   output("`n`n`3Noch`4 %s`3 Tage bis du wieder `4Blut `3trinken musst`n`n", $bluttage);
		   set_module_pref("bluttage", $bluttage);
		  }
		 }
		 break;
	
	}
	return $args;
}

function racevampir_checkcity(){
	global $session;
	$race="Vampir";
	$city= get_module_setting("villagename");
	
	#if ($session['user']['race']==$race && is_module_active("cities")){
		//if they're this race and their home city isn't right, set it up.
		#if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
			#set_module_pref("homecity",$city,"cities");
		#}
	#}	
	return true;
}

function racevampir_run(){
	$op = httpget("op");
	switch($op){
	###F�RSTENHALLE###
	case "vorfuerstenhalle":
		require_once("lib/villagenav.php");
		$openforall = get_module_setting("openhallfornotvampires");
		page_header("Die F�rstenhalle");
		output("`3Du betrittst die Halle vor der F�rstenhalle. An der gegen�ber liegenden H�hlenwand siehst du eine gro�e Holzt�r.");
		if($openforall == "no")
		{
		 if($session['user']['race'] != "Vampir")
		 {
		 output("`n`n`3Doch bevor du dich weiter n�hern kannst versperrt dir eine Wache den Weg und erkl�rt dir, dass nur Vampire die F�rstenhalle betreten d�rfen.");
		 villagenav();
		 }
		 elseif(get_module_setting("openhall") == "yes")
		 {
		  output("`n`n`3Eine der Wachen kommt auf dich zu und sagt dir, dass du die Halle betreten darfst. Willst du reingehn?");
		  addnav("F�rsetnhalle betreten","runmodule.php?module=racevampir&op=fuerstenhalle");
		  villagenav();
		 }else{
		  output("`n`n`4Da du von den F�rsten nicht gerufen wurdest darfst du die F�rstenhalle nicht betreten.");
		  villagenav();
		 }
		}else{
		 output("`n`n`3Eine der Wachen kommt auf dich zu und sagt dir, dass du die Halle betreten darfst. Willst du reingehn?");
		 addnav("F�rsetnhalle betreten","runmodule.php?module=racevampir&op=fuerstenhalle");
		 villagenav();
		}
		page_footer();
		break;
	case "fuerstenhalle":
		require_once("lib/villagenav.php");
		require_once("lib/commentary.php");
		$discuss = get_module_setting("discuss");
		$fuerst1 = get_module_setting("namefuerst1");
		$fuerst2 = get_module_setting("namefuerst2");
		$fuerst3 = get_module_setting("namefuerst3");
		page_header("Die F�rstenhalle");
		output("`3Du betrittst die F�rstenhalle. Im inneren tummeln sich unz�hlige Vampire. Die meisten br�llen Kommentare zum aktuellen Thema.
				Du gehst zu einer der mittleren Holzb�nke, die in der ganzen Halle verteilt sind und setzt dich auf einen freihen Platz.`n");
		output("`n`3Dein Blick wandert vor zum Podium wo du `2%s `3, `1%s `3 und `4%s `3, die 3 Vampirf�rsten siehst. Sie h�ren dem Gebr�ll der anderen
				Vampire zu. Hin und wieder sagt einer von ihnen etwas dazu oder fl�stert einem der beiden anderen F�rsten etwas ins Ohr.", $fuerst1, $fuerst2, $fuerst3);
		output("`n`n`2Das Thema der heutigen Diskussion scheint heute `5%s `2zu sein.`n`n", $discuss);
		addcommentary();
		commentdisplay("Die Vampire br�llen:`n`n",
				"fuerstenhalle","Etwas br�llen:",30,"br�llt");
		villagenav();
		page_footer();
		break;
		
	###KAMPFARENEN###
	case "arena":
		page_header("Die Kampfarenen");
		output("`3Du betrittst die Kampfarenen. �berall stehen Vampire und feuern die K�mpfer an. Ein Vampir wird, an dir vorbei, 
		        aus dem H�hle getragen.`nEine der Arenen steht leer. Vanez Blane, der Arenaaufseher, kommt auf dich zu und fragt:\"`^Willst
				du k�mpfen?`3\".");
		addnav("Ja","pvp.php");
		addnav("Nein","runmodule.php?module=racevampir&op=pvpno");
		page_footer();
		break;
	case "pvpno":
		require_once("lib/villagenav.php");
		page_header("Die Kampfarenen");
		output("`3Du hast beschlossen nicht zu k�mpfen und verl�sst die Kampfarena wieder");
		villagenav();
		page_footer();
		break;
		
	###VAMPIR WERDEN###
	case "vampirwerden":
	    global $session;
		require_once("lib/villagenav.php");
		page_header("Vampir werden");
		if($session['user']['race'] == "Vampir")
		{
		 output("`3Du bist bereits Vampir. Das w�rde dir also nichts bringen.");
		 villagenav();
		}else{
		output("`3Du hast dich dazu entschlossen Vampir zu werden. Auf der Suche nach einem Vampir, der dich anzapfen w�rde denkst du nochmal dar�ber nach.
				Dir wird klar, dass du nie wieder etwas anderes sein kannst.`n`n");
		output("Als du durch die G�nge wanderst, h�lt dich einer der Vampire auf und sagt dir, dass er von deinem Entschluss geh�rt hat und dir gern helfen w�rde.`n
				`n`4Willst du wirklich Vampir werden? Du kannst sp�ter nicht mehr zu deiner �rspr�nglichen Rasse zur�ckkehern.");
		addnav("Vampir werden","runmodule.php?module=racevampir&op=vampirwerdenja");
		villagenav();
		}
		page_footer();
		break;
	case "vampirwerdenja":
	    global $session;
		require_once("lib/villagenav.php");
		page_header("Vampir werden");
		output("`3Der Vampir sieht dich an und nimmt dann eine Probe deines Blutes. Als er sie untersucht hat sagt er:\" ");
		if($session['user']['dragonkills'] >= get_module_setting("changeracedk"))
		{
		 output("`2Na gut. Das wird jetzt ein bisschen weh tun, aber sobald es vorbei ist sind die Schmerzen wieder weg.`3\"");
		 output("`n`3Der Vampir nimmt deine H�nde und ritzt die Fingerkuppen mit seinen Fingern�geln an, so dass das Blut heraus rinnt. Das selbe macht er bei sic selbst.
		 		 Dann presst er seine Fingerkuppen auf deine und du sp�rst wie sein Blut in deinen K�rper �bergeht. Pl�tzlich wird dir schwarz vor Augen und du f�llst um.");
		 output("`n`nAls du wieder aufwachst ist der Vampir verschwunden. Du sp�rst, dass du kr�ftiger als fr�her bist. Deine vollen Vampirkr�fte wirst du aber erst am n�chsten Tag nutzen k�nnen.");
		 $session['user']['race'] = "Vampir";
		}else{
		 output("`2Du bist noch nicht bereit ein Vampir zu werden. Bleib lieber noch eine Weile bei deiner alten Rasse.`3\"");
		}
		 villagenav();
		 page_footer();
		break;
		
	###BLUT TRINKEN###
	case "opfersuchen":
		global $session;
		require_once("lib/villagenav.php");
		$blooddays = get_module_pref("bluttage");
		$blooddaysall = get_module_setting("bluttageall");
		page_header("Opfer suchen");
		if($blooddays >= $blooddaysall)
		{
		 output("`3F�r heute hast du genug `4Blut`3 getrunken");
		 villagenav();
		}else{
		 output("`3Du begibst dich auf die Suche nach einem geeigneten Opfer.");
		 srand(microtime()*1000000);
  		 $find = rand(1,5);
		 if($find == 3)
		 {
		  output("`3`n`nNach einiger Zeit findest du einen Ahnungslosen Bauern.");
		  output("`3`nEs ging schnell und schmerzlos und hast genug `4Blut`3 getrunken um einen weiteren Tag zu �berleben.");
		  $blooddays++;
		  set_module_pref("bluttage", $blooddays);
		 }else{
		  output("`3Kannst aber nichts finden.");
		 }
		 output("`n`n`2Du verlierst einen Waldkampf.");
		 $session['user']['turns']--;
		 output("`n`n`3Willst du weitersuchen?");
		 addnav("Weitersuchen","runmodule.php?module=racevampir&op=opfersuchen");
		 villagenav();
		}
		 page_footer();
		break;
	}
}
?>