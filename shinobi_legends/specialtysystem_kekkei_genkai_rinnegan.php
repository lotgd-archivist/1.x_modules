<?php

function specialtysystem_kekkei_genkai_rinnegan_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - Rinnegan",
		"author" => "`LShinobiIceSlayer `~ based on work by `2Oliver Brendel`0",
		"version" => "1.0",
		"download" => "",
		"category" => "Specialty System",
		"requires"=> array(
			"specialtysystem"=>"1.0|Specialty System by `2Oliver Brendel",
			),
		"prefs"=> array(
			"deva_1_marker"=>"Marker for deva_1 temp rebuild of fightnav(),bool",
			"deva_2_marker"=>"Marker for deva_2 temp rebuild of fightnav(),bool",
			),
	);
	return $info;
}

function specialtysystem_kekkei_genkai_rinnegan_install(){
	module_addhook("specialtysystem-register");
	module_addhook("battle");
	return true;
}

function specialtysystem_kekkei_genkai_rinneganspecialtysystem_kekkei_genkai_rinnegan_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_kekkei_genkai_rinnegan");
	return true;
}

function specialtysystem_kekkei_genkai_rinnegan_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses();
	$pers=get_module_pref("stack","circulum_rinnegan");
	$name=translate_inline('`xKekkei Genkai `%R`Vinnegan');
	tlschema('module-specialtysystem_kekkei_genkai_rinnegan');
	if ($uses > 0 && $pers>0) {
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		
		if ($uses > 1) specialtysystem_addfightnav("`xKokushintō","needle&cost=1&path=",1);
		
		require_once("modules/rinnegan/functions.php");
		$paths = check_paths();
		if (!has_buff('deva_2')){
			if ($pers > 0) {
				require_once("modules/rinnegan/paths/deva_functions.php");
				get_deva_fightnavs($uses,$paths['deva_path']);
			}
			if ($pers > 1)  {
				require_once("modules/rinnegan/paths/naraka_functions.php");
				get_naraka_fightnavs($uses,$paths['naraka_path']);
			}
			if ($pers > 2)  {
				require_once("modules/rinnegan/paths/animal_functions.php");
				get_animal_fightnavs($uses,$paths['animal_path']);
			}
			if ($pers > 3) {
				require_once("modules/rinnegan/paths/preta_functions.php");
				get_preta_fightnavs($uses,$paths['preta_path']);	
			}
			if ($pers > 4)  {	
				require_once("modules/rinnegan/paths/asura_functions.php");
				get_asura_fightnavs($uses,$paths['asura_path']);	
			}	
			if ($pers > 5)  {
				require_once("modules/rinnegan/paths/human_functions.php");
				get_human_fightnavs($uses,$paths['human_path']);
			}
		}
	} 
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_kekkei_genkai_rinnegan_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$cost=httpget('cost');
	$path=httpget('path');
	$success=true;
		
	switch($path){
		case "deva":
			require_once("modules/rinnegan/paths/deva_functions.php");
			$success=deva_path_apply($skillname);
			break;
		case "naraka":
			require_once("modules/rinnegan/paths/naraka_functions.php");
			$success=naraka_path_apply($skillname);
			break;
		case "animal":
			require_once("modules/rinnegan/paths/animal_functions.php");
			$success=animal_path_apply($skillname);
			break;
		case "preta":
			require_once("modules/rinnegan/paths/preta_functions.php");
			$success=preta_path_apply($skillname);
			break;
		case "asura":
			require_once("modules/rinnegan/paths/asura_functions.php");
			$success=asura_path_apply($skillname);
			break;
		case "human":
			require_once("modules/rinnegan/paths/human_functions.php");
			$success=human_path_apply($skillname);
			break;
		default:
			if ($skillname=="needle"){
				apply_buff('needle',array(
				"startmsg"=>"`xYou take a black chakra rod, like those used to control your Paths, and stab it into, {badguy}.",
				"name"=>"`xKokushintō",
				"rounds"=>3,
				"roundmsg"=>"`x{badguy} is haunted by images of your `%R`Vinnegan `xin his mind, as you fight to over ride his body through the Chakra Rod.",
				"wearoff"=>"`x{badguy} pulls out the Chakra Rod.",
				"badguyatkmod"=>0.9,
				"badguydefmod"=>0.9,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			}
			break;	
	}
	if (!$success) $cost=0;
	specialtysystem_incrementuses("specialtysystem_kekkei_genkai_rinnegan",$cost);
	return;
}

function specialtysystem_kekkei_genkai_rinnegan_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Kekkei Genkai Rinnegan',
			"spec_colour"=>'`V',
			"spec_shortdescription"=>'-internal-',
			"spec_longdescription"=>'-internal-',
			"modulename"=>'specialtysystem_kekkei_genkai_rinnegan',
			"fightnav_active"=>'1',
			"newday_active"=>'0',
			"dragonkill-active"=>'0',
			"noaddskillpoints"=>1,
			"dragonkill_minimum_requirement"=>-1
			);
		break;
	case "battle": //yes, not really nice, but no other way for expiration each round
		if (has_buff('deva_1')) {
			//we need to track the expiration of that one
			set_module_pref('deva_1_marker',1);
		} elseif (get_module_pref('deva_1_marker')==1) {
			//we had a buff and need to rebuild fightnav now, i.e. clear the cache
			set_module_pref("cache","","specialtysystem");
			set_module_pref('deva_1_marker',0);
		}
		if (has_buff('deva_2')) {
			//we need to track the expiration of that one
			set_module_pref('deva_2_marker',1);
		} elseif (get_module_pref('deva_2_marker')==1) {
			//we had a buff and need to rebuild fightnav now, i.e. clear the cache
			set_module_pref("cache","","specialtysystem");
			set_module_pref('deva_1_marker',0);
		}
		break;
	}
	return $args;
}

function specialtysystem_kekkei_genkai_rinnegan_run(){
}

?>
