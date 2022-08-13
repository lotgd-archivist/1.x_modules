 <?php

function specialtysystem_toad_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - toad",
		"author" => "`2Oliver Brendel`0",
		"version" => "1.0",
		"download" => "",
		"category" => "Specialty System",
		"requires"=> array(
			"specialtysystem"=>"1.0|Specialty System by `2Oliver Brendel",
			),
	);
	return $info;
}

function specialtysystem_toad_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_toad_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_toad");
	return true;
}

function specialtysystem_toad_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	if ($session['user']['acctid']!=7) return specialtysystem_getfightnav();
	$uses=specialtysystem_availableuses();
	$pers=specialtysystem_availableuses("specialtysystem_toad");
	$name=translate_inline('Animal Summonings');
	tlschema('module-specialtysystem_toad');
	if ($uses > 4) {
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_toad"));
		specialtysystem_addfightnav("`gG`4amakichi","gamakichi&cost=1",1);
		specialtysystem_addfightnav("`gG`4amatatsu","gamatatsu&cost=1",1);
		specialtysystem_addfightnav("`gG`4amabushi","gamabushi&cost=3",3);
		specialtysystem_addfightnav("`gG`4amabunta","gamabunta&cost=15",15);
	}
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_toad_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	require_once("lib/buffs.php");
	$basehp=$session['user']['level']*10+$session['user']['dragonkills']*3;
	switch($skillname){
		case "gamakichi":
			$comp=array(
				"name"=>"`gG`4amakichi",
				"attack"=>$session['user']['level'],
				"defense"=>$session['user']['level'],
				"hitpoints"=>$basehp,
				"maxhitpoints"=>$basehp,
				"companionactive"=>1,
				"jointext"=>'`$You called, boss?`n',
				"cannotdie"=>0,
				"cannotbehealed"=>1,
				"dyingtext"=>"`gG`4amakichi: `1See you around, gotta go.`n",
				"expireafterfight"=>1,
				"abilities"=>serialize(array(
					"fight"=>1,
					"heal"=>0,
					"magic"=>0,
					"defend"=>0,
					)),
				"schema"=>"module-specialtysystem_toad"
			);
			break;
			
	}
	apply_companion(sanitize($comp['name']),$comp);
	output($comp['jointext']);
	specialtysystem_incrementuses("specialtysystem_toad",httpget('cost'));
	return;
}

function specialtysystem_toad_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Toad Contracts',
			"spec_colour"=>'`x',
			"spec_shortdescription"=>'-internal-',
			"spec_longdescription"=>'-internal-',
			"modulename"=>'specialtysystem_toad',
			"fightnav_active"=>'1',
			"newday_active"=>'0',
			"dragonkill-active"=>'0',
			"noaddskillpoints"=>1,
			"dragonkill_minimum_requirement"=>-1
			);
		break;
	}
	return $args;
}

function specialtysystem_toad_run(){
}
?>
