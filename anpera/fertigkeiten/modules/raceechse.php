<?php
// translator ready
// addnews ready

/*
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

function raceechse_getmoduleinfo(){
	$info = array(
		"name"=>"Rasse - Echse",
		"version"=>"1.0",
		"author"=>"Oliver Wellinghoff",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/users/Harassim/fertigkeiten.zip",
		"settings"=>array(
			"Einstellungen - Echsen,title",
			"minedeathchance"=>"Prozentuale Chance für eine Echse in der Mine zu sterben,range,0,100,1|50",
			"chance-halb"=>"Prozentuale Chance nach einem Kampf 50% zu regenerieren wenn HP<=0.75%? ,range,5,10,1|5",
			"chance-ganz"=>"Prozentuale Chance nach einem Kampf ganz zu regenerieren wenn HP<=0.75%? ,range,0,5,1|3",						
		),
	);
	return $info;
}

function raceechse_install(){
	// Echsen starten bei den Menschen.
	if (!is_module_installed("racehuman")) {
		output("Die Echsen leben bei den Menschen. Du musst das entsprechende Modul installieren.");
		return false;
	}
	module_addhook("chooserace");
	module_addhook("battle-victory");
	module_addhook("setrace");
	module_addhook("charstats");
	module_addhook("raceminedeath");
	return true;
}

function raceechse_uninstall(){
	global $session;
	// Force anyone who was an "Echse" to rechoose race
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Echse'";
	db_query($sql);
	if ($session['user']['race'] == 'Echse')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function raceechse_dohook($hookname,$args){
	global $session;

	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Echse";
	switch($hookname){
    case "battle-victory":
if ($args['type'] != "forest") break;
if ($session['user']['race']==$race){
if ($session['user']['hitpoints']<=$session['user']['maxhitpoints']*0.75){
   $Vorteil = (e_rand(0,100));
   $halb=get_module_setting("chance-halb");
   $chanceganz=get_module_setting("chance-ganz");
   $ganz=$chanceganz+$halb;
   if ($Vorteil <= $halb){
	output("`5`n`bNach dem Kampf regenerierst Du einen Teil Deiner Verletzungen, indem Du Dich häutest!`b`n`n");
    $session['user']['hitpoints']+=($session['user']['maxhitpoints']-$session['user']['hitpoints'])*0.5;
	break;
        }
   if ($Vorteil > $halb && $Vorteil <= $ganz){
	output("`5`n`bNach dem Kampf regenerierst Du all Deine Verletzungen, indem Du Dich häutest!`b`n`n");
    $session['user']['hitpoints']=$session['user']['maxhitpoints'];
	break;
        }
}}
break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Du kannst gerade noch entkommen.`n";
			$args['schema']="module-raceechse";
		}
		break;
	case "charstats":
		if ($session['user']['race']==$race){
			addcharstat("Vital Info");
			addcharstat("Race", translate_inline($race));
		}
		break;
	case "chooserace":
		output("<a href='newday.php?setrace=Echse$resline'>Die Echsen `5sind eines der großen Völker und leben sowohl in den Sümpfen im Osten als auch auf den Hochebenen Chrizzaks nördlich des Drassoria-Gebirges.`n`n",$city, true);
		addnav("`5Echse`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "setrace":
		if ($session['user']['race']==$race){ // it helps if you capitalize correctly
			output("`5Durch die Häutungen können Echsen Verletzungen leichter regenerieren als andere Rassen ...`n");
			if (is_module_active("cities")) {
				if ($session['user']['dragonkills']==0 &&
						$session['user']['age']==0){
					//new farmthing, set them to wandering around this city.
					set_module_setting("newest-$city",
							$session['user']['acctid'],"cities");
				}
				set_module_pref("homecity",$city,"cities");
				$session['user']['location']=$city;
			}
		}
		break;
function raceechse_checkcity(){
	global $session;
	$race="Echse";
	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	
	if ($session['user']['race']==$race && is_module_active("cities")){
		//if they're this race and their home city isn't right, set it up.
		if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
			set_module_pref("homecity",$city,"cities");
		}
	}	
	
}
}
    return $args;
}
function raceechse_run(){
}
?>
