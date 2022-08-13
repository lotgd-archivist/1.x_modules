<?php

function specialtysystem_taijutsu_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - Taijutsu",
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

function specialtysystem_taijutsu_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_taijutsu_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_taijutsu");
	return true;
}

function specialtysystem_taijutsu_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses("specialtysystem_taijutsu");
	$name=translate_inline('Taijutsu');
	tlschema('module-specialtysystem_taijutsu');
	if ($uses > 0) {
		specialtysystem_addfightheadline($name, $uses,specialtysystem_getskillpoints("specialtysystem_taijutsu"));
		specialtysystem_addfightnav("Kousa Ho","taijutsu1&cost=1",1);
	}
	if ($uses > 2) {
		specialtysystem_addfightnav("Gouken","taijutsu2&cost=3",3);
	}
	if ($uses > 4) {
		specialtysystem_addfightnav("Kage Buyou","taijutsu3&cost=5",5);
	}
	if ($uses > 5) {
		specialtysystem_addfightnav("Konoha Senpuu","taijutsu4&cost=6",6);
	}
	if ($uses > 7) {
		specialtysystem_addfightnav("Konoha Daisenpuu","taijutsu5&cost=8",8);
	}
	if ($uses > 9) {
		specialtysystem_addfightnav("Konoha Gouriki Senpuu","taijutsu6&cost=10",10);
	}
	require_once("lib/buffs.php");
	if ($uses > 11 && has_buff('taijutsu3')) specialtysystem_addfightnav("Shishi Rendan","taijutsu7&cost=12",12);
	if ($uses > 14 && has_buff('taijutsu3')) specialtysystem_addfightnav("Hayabusa Otoshi","taijutsu8&cost=15",15);
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_taijutsu_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$strengthboon=$session['user']['strength']-10;
	switch($skillname){
		case "taijutsu1":
			apply_buff('taijutsu1',array(
				"startmsg"=>"`@You intercept and counter an oncoming attack from {badguy}.",
				"name"=>"`VKousa Ho",
				"rounds"=>1,
//				"invulnerable"=>true,
				"badguyatkmod"=>0,
				"minbadguydamage"=>$session['user']['level'],
				"maxbadguydamage"=>$session['user']['level']*2,
				"minioncount"=>1,
				"effectmsg"=>"{badguy} suffers {damage} damage from your counter attack!",
				"schema"=>"module-specialtysystem_taijutsu"
			));
			break;
		case "taijutsu2":
			apply_buff('taijutsu2',array(
				"startmsg"=>"`@`iGouken!`i`n`@You `2focus your strength on your fist.",
				"name"=>"`@Gouken",
				"rounds"=>5,
				"wearoff"=>"You feel your strength leaving.",
				"atkmod"=>1.30,
				"minioncount"=>1,
				"effectnodmgmsg"=>"You attack {badguy} with fist of steel!",
				"schema"=>"module-specialtysystem_taijutsu"
			));
			break;
		case "taijutsu3":
			apply_buff('taijutsu3',array(
				"startmsg"=>"`@You kick {badguy} into the air and then appear behind him like a `7shadow.",
				"name"=>"`#Kage Buyou",
				"rounds"=>2,
				"wearoff"=>"{badguy} recovers from the vulnerable position.",
				"invulnerable"=>true,
				"roundmsg"=>"{badguy} is in a vulnerable position and is unable to attack you!",
				"schema"=>"module-specialtysystem_taijutsu"
			));
			break;
		case "taijutsu4":
			apply_buff('taijutsu4',array(
				"startmsg"=>"`@`iKonoha Senpuu!`i`n`@You `2duck and deliver a powerful upward kick.",
				"name"=>"`@Konoha Senpuu",
				"rounds"=>1,
				"minbadguydamage"=>20+$strengthboon,
				"maxbadguydamage"=>40+$strengthboon,
				"minioncount"=>1,
				"effectmsg"=>"{badguy} suffers {damage} damage from the powerful kick!",
				"schema"=>"module-specialtysystem_taijutsu"
			));
			break;
		case "taijutsu5":
			apply_buff('taijutsu5',array(
				"startmsg"=>"`@`iKonoha `2Dai`@senpuu!`i`n`@You `2perform a powerful leap forward followed by a spinning kick with both legs in succession.",
				"name"=>"`@Konoha `2Dai`@senpuu",
				"rounds"=>1,
				"areadamage"=>true,
				"minbadguydamage"=>15+$strengthboon,
				"maxbadguydamage"=>30+$strengthboon,
				"minioncount"=>4,
				"effectmsg"=>"{badguy} suffers {damage} damage from the spinning kick!",
				"effectnodmgmsg"=>"{badguy} manages to dodge out of the way.",
				"schema"=>"module-specialtysystem_taijutsu"
			));
			break;
		case "taijutsu6":
			apply_buff('taijutsu6',array(
				"startmsg"=>"`@`iKonoha `2Goriki `@Senpuu!`i`n`@You `2perform a powerful leap forward followed by a powerful spinning kick with both legs in succession.",
				"name"=>"`@Konoha `2Goriki `@Senpuu",
				"rounds"=>1,
				"areadamage"=>true,
				"minbadguydamage"=>30+$strengthboon,
			    "maxbadguydamage"=>40+$strengthboon,
				"minioncount"=>4,
				"effectmsg"=>"{badguy} suffers {damage} damage from the powerful spinning kick!",
				"effectnodmgmsg"=>"{badguy} manages to dodge out of the way.",
				"schema"=>"module-specialtysystem_taijutsu"
			));
			break;
		case "taijutsu7":
			require_once("lib/buffs.php");
			if (!has_buff('taijutsu3')) {
				$cost=0;
				output("`\$Combo was unsuccessful! Perform `#Kage Buyou `\$first to use Shishi Rendan!`n");
				break;
			}
			apply_buff('taijutsu7',array(
				"startmsg"=>"`@With a combination of attacks while in midair, you send {badguy} towards the ground and finishes with a spinning kick to the chest - `1Shi`!shi `tRendan`@!",
				"name"=>"`1Shi`!shi `tRendan",
				"rounds"=>1,
				"effectmsg"=>"You hit {badguy} for {damage} damage!",
				"minbadguydamage"=>60+$strengthboon,
			    "maxbadguydamage"=>90+$strengthboon,
				"minioncount"=>4,
				"schema"=>"module-specialtysystem_taijutsu"
			));
			strip_buff('taijutsu3');
			break;
		case "taijutsu8":
			require_once("lib/buffs.php");
			if (!has_buff('taijutsu3')) {
				$cost=0;
				output("`\$Attack was unsuccessful! Perform `#Kage Buyou `\$first to use Hayabusa Otoshi!`n");
				break;
			}
			apply_buff('taijutsu8',array(
				"startmsg"=>"`@You grab the falling opponent by their ankles, wraps your legs around their waist, and drives {badguy} head first into the ground - `1Haya`7busa `!Otoshi`@!",
				"name"=>"`1Haya`7busa `!Otoshi",
				"rounds"=>1,
				"effectmsg"=>"{badguy} suffers {damage} damage from the crashing dive!",
				"minbadguydamage"=>100+$strengthboon*2,
			    "maxbadguydamage"=>150+$strengthboon*2,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_taijutsu"
			));
			strip_buff('taijutsu3');
			break;

	}
	specialtysystem_incrementuses("specialtysystem_taijutsu",httpget('cost'));
	return;
}

function specialtysystem_taijutsu_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Taijutsu',
			"spec_colour"=>'`@',
			"spec_shortdescription"=>'`@Power of the body!',
			"spec_longdescription"=>'`5Growing up, you always loved punching and kicking more than anything else ... so you studied taijutsu, facing your enemies head-on with hand-to-hand combat.',
			"modulename"=>'specialtysystem_taijutsu',
			"fightnav_active"=>'1',
			"newday_active"=>'0',
			"dragonkill-active"=>'0',
			"dragonkill_minimum_requirement"=>'0'
			);
		break;
	}
	return $args;
}

function specialtysystem_taijutsu_run(){
}
?>
