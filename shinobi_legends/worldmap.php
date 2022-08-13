<?php
/*

notes to self:

*php5-gd must be installed, without that library, php will not recognize the functions
*modules need to have a different link, runmodule.php?module=cities is blocked, use runmodule.php?op=travel to work around (for cities you have special requirements or whatnot and handle them yourself. Add a "cost" to make it available via HTTPGET, else the cost==1
*new cities have to be done manually all the way. see subfolder, places is to be edited.

*/
function worldmap_getmoduleinfo(){
	$info = array(
		"name"=>"World Map System",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"World Map",
		"download"=>"",
		"settings"=>array(
			"World Map Settings,title",
			"dailytravels"=>"Basic Travel Points for a day,int|15",
			
			),
		"prefs"=>array(
			"World Map Prefs,title",
			"traveltoday"=>"Travels used today,int",
		),
	);
	return $info;
}

function worldmap_install(){
	module_addhook("travel");
	module_addhook("count-travels");
	module_addhook("travel-cost");
	module_addhook("validlocation");
	module_addhook_priority("newday",1);
	return true;
}

function worldmap_uninstall(){
	return true;
}

function worldmap_dohook($hookname,$args){
	global $session	;
	switch($hookname){
	
		case "travel-cost":
			require("modules/worldmap/places.php");
			$from=$args['from'];
			$to=$args['to'];
			$num=array_keys($places);
			$location=array_search($from,$num);
			$location2=array_search($to,$num);
			if ($location===false || $location2==false) {
				break; //nothing to do
			}
			$args['cost']=$matrix[$location][$location2];
			break;
	
		case "travel":
			rawoutput("<center><img src='modules/worldmap/draw_map.php?loc=".htmlentities($session['user']['location'],ENT_COMPAT,getsetting('charset','ISO-8859-1'))."'></center><br><br>");
			require("modules/worldmap/places.php");
			$num=array_keys($places);
			$location=$session['user']['location'];
			$from=array_search($location,$num);
			if ($from===false) break; //nothing to do for us here
			blocknav("runmodule.php?module=cities",true);
			$capital=getsetting('villagename', LOCATION_FIELDS);
			// debug($matrix);
			$args = modulehook("count-travels", array('available'=>0,'used'=>0));
			$free = max(0, $args['available'] - $args['used']);
			$tfree=$free+$session['user']['turns'];
			if ($tfree==0) {
				output("You are too tired to travel all by yourself today!`n`n");
			}			
			
			foreach ($places as $name=>$place_array) {
				$to=array_search($name,$num);
				$place = $place_array['name'];
				// debug($name);
				// debug($from);
				// debug($to);
				if ($session['user']['superuser'] & SU_EDIT_USERS){
					addnav("Heavenly Travel On Safe Wings");
					addnav(array("Go to %s`0 (free)", $place),"runmodule.php?op=travel&module=cities&su=1&cost=".$cost."&city=".$name);
				}
				$cost=(int)$matrix[$from][$to];

				if ($cost==0) continue;
				// debug($cost);
				if ($location==$capital || $capital==$name) {
					addnav("Safer Travels");
					addnav(array("Go to %s`0 (%s points)", $place,$cost),($tfree>=$cost?"runmodule.php?op=travel&module=cities&city=".$name."&cost=".$cost:""));
				} elseif ($location!=$name) {
					addnav("Hazardous Travel");
					addnav(array("Go to %s`0 (%s points)", $place,$cost),($tfree>=$cost?"runmodule.php?op=travel&module=cities&city=".$name."&cost=".$cost."&d=1":""));
				}
			}
			break;
		case "count-travels":
			global $playermount;
			$args['available']+=get_module_setting('dailytravels');
			if ($playermount && isset($playermount['mountid'])) {
				$id=$playermount['mountid'];
				$extra=get_module_objpref("mounts", $id, "extratravel");
				$args['available']+=$extra;
			}
			//$args['used'] += get_module_pref("traveltoday");
		break;
		
		case "newday":
			set_module_pref("traveltoday",0);
			break;
		
		default:
		break;		
	}
	return $args;
}

function worldmap_run(){
	$op = httpget('op');
	switch($op){
		default:

		break;
	}
}

?>
