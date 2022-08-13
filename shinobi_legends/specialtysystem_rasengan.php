<?php

function specialtysystem_rasengan_getmoduleinfo(){
	$info = array(
			"name" => "Specialty System - Rasengan",
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

function specialtysystem_rasengan_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_rasengan_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_rasengan");
	return true;
}

function specialtysystem_rasengan_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses();
	$pers=specialtysystem_availableuses("specialtysystem_rasengan");
	$name=translate_inline('Extraordinary Jutsus');
	tlschema('module-specialtysystem_rasengan');
	if ($uses > 4 && $pers>0) {
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_rasengan"));
		specialtysystem_addfightnav("`gRa`\$sen`ggan","rasengan&cost=5",5);
	} 
	if ($uses > 10 && $pers>0 && $session['user']['dragonkills']>50) {
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_rasengan"));
		specialtysystem_addfightnav("`gŌdama-Ra`\$sen`ggan","dairasengan&cost=11",11);
	}
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_rasengan_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$u=&$session['user'];
	switch($skillname){
		case "rasengan":
			apply_buff('rasengan',array(
						"startmsg"=>"`v`i`gRa`\$sen`gGan!`i`n`tYou `qform chakra in your hand, bring it into rotation and jump at your enemy...this form is not all a true rasengan can do.",
						"name"=>"`gRa`\$sen`gGan",
						"rounds"=>1,
						"effectmsg"=>"{badguy} takes some serious damage...!",
						"minbadguydamage"=>80+($u['strength']+$u['intelligence'])*3.5,
						"maxbadguydamage"=>100+($u['strength']+$u['intelligence'])*3.5,
						"minioncount"=>1,
						"atkmod"=>0.8,
						"schema"=>"module-specialtysystem_rasengan"
						));
			break;
			//more to come with a bunshin
		case "dairasengan":
			apply_buff('rasengan',array(
						"startmsg"=>"`v`i`\$Ōdama `gRa`\$sen`gGan!`i`n`tYou `qform a huge ball of chakra with both your hands, bring it into multiple inner spin rotations and jump at your enemy.",
						"name"=>"`\$Ōdama `gRa`\$sen`gGan",
						"rounds"=>1,
						"effectmsg"=>"{badguy} is devastated...!",
						"minbadguydamage"=>120+($u['strength']+$u['intelligence'])*5.5,
						"maxbadguydamage"=>200+($u['strength']+$u['intelligence'])*5.5,
						"minioncount"=>1,
						"atkmod"=>0.7,
						"schema"=>"module-specialtysystem_rasengan"
						));
			break;
	}
	specialtysystem_incrementuses("specialtysystem_rasengan",httpget('cost'));
	return;
}

function specialtysystem_rasengan_dohook($hookname,$args){
	switch ($hookname) {
		case "specialtysystem-register":
			$args[]=array(
					"spec_name"=>'`gRa`$sen`ggan',
					"spec_colour"=>'`g',
					"spec_shortdescription"=>'-internal-',
					"spec_longdescription"=>'-internal-',
					"modulename"=>'specialtysystem_rasengan',
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

function specialtysystem_rasengan_run(){
}
?>
