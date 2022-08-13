<?php

//taken a deep look into City - Amwayr from Billie Kennedy

function hokagevillage_getmoduleinfo(){
	$info = array(
		"name"=>"Hokage City",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Cities",
		"download"=>"",
		"requires"=>array(
			"cities"=>"1.0|Eric Stevens, part of the core download",
		),
		"settings"=>array(
			"Hokage Village Settings,title",
			"villagename"=>"Name for the village|`vHi`!dd`ven `lStone `%Village",
			"showforest"=>"Is the forest available from here?,bool|0",
			"travelfrom"=>"Where can you travel from,location|".getsetting("villagename", LOCATION_FIELDS),
			"travelto"=>"Where can you travel to,location|".getsetting("villagename", LOCATION_FIELDS),
			"mindk"=>"How many dks does a player have to have for access?,int|50",
		),
		"prefs"=>array(
			"Hokage Village Prefs,title",
			"hasmap"=>"Has this user a map to this village?,bool|0",
			),
	);
	return $info;
}

function hokagevillage_install(){
	module_addhook("villagetext");
	module_addhook("village");
	module_addhook("travel");
	module_addhook("validlocation");
	module_addhook("moderate");
	module_addhook("changesetting");
	module_addhook("mountfeatures");
	module_addhook("scrylocation");
	return true;
}

function hokagevillage_uninstall(){
	global $session;
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$gname = get_module_setting("villagename");
	$sql = "UPDATE " . db_prefix("accounts") . " SET location='$vname' WHERE location = '$gname'";
	db_query($sql);
	if ($session['user']['location'] == $gname)
		$session['user']['location'] = $vname;
	return true;
}

function hokagevillage_dohook($hookname,$args){
	global $session,$resline;
	$city = get_module_setting("villagename");
	switch($hookname){
	case "scrylocation":
		//you cannot scry to this one (update 14/06/29 now you can)
		$args[sanitize($city)]="hokagevillage";
	//	if (array_key_exists(sanitize($city),$args)) {
	//		$args=array_diff($args,array(sanitize($city)=>$args[sanitize($city)]));
	//	}
		break;
	case "travel":
		$args2 = modulehook("count-travels", array('available'=>0,'used'=>0));
		$free = max(0, $args2['available'] - $args2['used']);
		$tfree=$free+$session['user']['turns'];
		$capital = getsetting("villagename", LOCATION_FIELDS);
		$hotkey = substr(sanitize($city), 0, 1);
		$scity = htmlentities(sanitize($city),ENT_COMPAT,getsetting('charset','ISO-8859-1'));
		tlschema("module-cities");
//		if ($session['user']['dragonkills'] < get_module_setting("mindk")) 
//			break;
// Currently no map required
//		if (get_module_pref("hasmap")!=1)
//			break;
//sanitize colors!
		if ($session['user']['location']!=sanitize($city)){
			addnav("More Dangerous Travel");
			// Actually make the travel dangerous
			$cost=5;
				addnav(array("Go to %s (%s points)", ($tfree>=$cost?$city:sanitize($city)),$cost),
					($tfree>=$cost?"runmodule.php?op=travel&module=cities&cost=5&city=$scity&d=1":""));
/*
			if($session['user']['location'] == get_module_setting("travelfrom")){
				addnav(array("Go to %s (%s points)", ($tfree>=$cost?$city:sanitize($city)),$cost),
					($tfree>=$cost?"runmodule.php?op=travel&module=cities&cost=5&city=$scity&d=1":""));
			}
			if($session['user']['location'] == get_module_setting("travelto") && $session['user']['location'] != get_module_setting("travelfrom")){
				addnav(array("Go to %s (%s points)", ($tfree>=$cost?$city:sanitize($city)),$cost),
					($tfree>=$cost?"runmodule.php?op=travel&module=cities&cost=5&city=$scity&d=1":""));
			}
*/
		}
		if ($session['user']['superuser'] & SU_EDIT_USERS){
			addnav("Superuser");
			addnav(array("Go to %s (free)", $city),
					"runmodule.php?op=travel&module=cities&cost=5&city=$scity&su=1");
		}
		tlschema();
		break;
	case "changesetting":
		// Ignore anything other than villagename setting changes
		if ($args['setting']=="villagename" && $args['module']=="hokagevillage") {
			if ($session['user']['location'] == $args['old']) {
				$session['user']['location'] = $args['new'];
			}
			$sql = "UPDATE " . db_prefix("accounts") . " SET location='" .
				$args['new'] . "' WHERE location='" . $args['old'] . "'";
			db_query($sql);
		}
		break;
	case "validlocation":
		if (is_module_active("cities"))
			$args[sanitize($city)]="village-hokagevillage";
		break;
	case "moderate":
		if (is_module_active("cities")) {
			tlschema("commentary");
			$args["hokagevillage"]=sprintf_translate("%s", $city);
			tlschema();
		}
		break;
	case "villagetext":

		if ($session['user']['location'] == sanitize($city)){
			$args['text']="`\$`c`@`bYou enter $city, Secret Home of the Whirlpools.`b`@`c`n`n`2
You step off the boat and land on the docks as the mountainous Uzushiogakure rises about you. `n`n`vA pathway`2 leads to the grand entrance as you are welcomed by the people roaming about.`nThe `vexquisite scenery`2 around is exuberant to the sight; accompanied by `xflowing trees`2 and greenery with a `vconstant stream`2 that separates the village with bridges and ducts as you pass by.`n`n`v The nature of the village surely creeps on you the further you step in.`2
With the uplifting aura of the isle and fearsome guardsmen posted at each corner, you get the feeling you're surrounded by powerful jutsus and ninja.`n`n";
            $args['schemas']['text'] = "module-hokagevillage";
			$args['clock']="`n`7A large clock tower at the center of the village reads `&%s`7.`n";
            $args['schemas']['clock'] = "module-hokagevillage";
			if (is_module_active("calendar")) {
				$args['calendar'] = "`n`2The local paper shows that it is `&%s`2, `&%s %s %s`2.`n";
				$args['schemas']['calendar'] = "module-hokagevillage";
			}
			$args['title']=array("%s", sanitize($city));
			$args['schemas']['title'] = "module-hokagevillage";
			$args['sayline']="speaks";
			$args['schemas']['sayline'] = "module-hokagevillage";
			$args['talk']="`n`&You sense:`n";
			$args['schemas']['talk'] = "module-hokagevillage";
			$args['newest'] = "";

			//block all the multicity navs and modules. configure as needed for your server

			
			//blocknav("lodge.php");
			blocknav("weapons.php");
			//blocknav("armor.php");
			//blocknav("clan.php");
			blocknav("pvp.php");
			//blocknav("runmodule.php?module=cities&op=travel");
			//blocknav("list.php");

			if (!get_module_setting("showforest"))
				blocknav("forest.php");



			blocknav("bank.php");
			//blockmodule("cities");
			blockmodule("questbasics");
			blockmodule("house");
			blockmodule("klutz");
			blockmodule("abigail");
			blockmodule("crazyaudrey");
			blockmodule("zoo");
			blockmodule("battlearena");
			blockmodule("beggarslane");
			



			$args['schemas']['newest'] = "module-hokagevillage";
			$args['gatenav']="Village Gates";
			$args['schemas']['gatenav'] = "module-hokagevillage";
			$args['fightnav']="Nearby Forest";
			$args['schemas']['fightnav'] = "module-hokagevillage";
			$args['marketnav']="Market Square";
			$args['schemas']['marketnav'] = "module-hokagevillage";
			$args['tavernnav']="Drunkard's Lane";
			$args['schemas']['tavernnav'] = "module-hokagevillage";
			$args['section']="hokagevillage";
			$args['infonav']="Village Council";
			$args['schemas']['infonav'] = "module-hokagevillage";
		}
		break;

	case "village":
		$from = get_module_setting("travelfrom");
		$to = get_module_setting("travelto");
		$city = sanitize($city);
		if ($session['user']['location']==$city){
			tlschema($args['schemas']['gatenav']);
			addnav($args['gatenav']);
			tlschema();
			addnav("Visit the Hospital","healer.php?return=village.php");
			modulehook("eliteforest");
		}
//		if ($session['user']['acctid']==7) {
//		}
		break;
	}
	return $args;
}

function hokagevillage_run(){
}

function hokagevillage_freetravel() {
	$args = modulehook("count-travels", array('available'=>0,'used'=>0));
	$free = max(0, $args['available'] - $args['used']);
	return max(0,$free);
}
?>
