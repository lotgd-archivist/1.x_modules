<?php

function specialtysystem_kekkei_genkai_hyuuga_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - Hyuuga Kekkei Genkai",
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

function specialtysystem_kekkei_genkai_hyuuga_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_kekkei_genkai_hyuugaspecialtysystem_kekkei_genkai_hyuuga_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_kekkei_genkai_hyuuga");
	return true;
}

function specialtysystem_kekkei_genkai_hyuuga_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses();
	$pers=get_module_pref("stack","circulum_hyuuga");
	$name=translate_inline('`$Kekkei Genkai `%H`Ryūga');
	tlschema('module-specialtysystem_kekkei_genkai_hyuuga');
	if ($uses > 0 && $pers>0) {
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_hyuuga"));
		if ($uses > 0)specialtysystem_addfightnav("`%B`Ryakugan","byakugan&cost=1",1);
		require_once("lib/buffs.php");
		if (has_buff('kekkei_genkai_hyuuga_1')) specialtysystem_addfightnav("`xJyūken","jyuuken&cost=1",1);
		if (has_buff('kekkei_genkai_hyuuga_1')) specialtysystem_addfightnav("`xHakke Rokujyūyonshō","hakke&cost=1",1);
		if (has_buff('kekkei_genkai_hyuuga_1')) specialtysystem_addfightnav("`xHakkeshō Kaiten","kaiten&cost=1",1);
		if (has_buff('kekkei_genkai_hyuuga_1') && $uses>2) specialtysystem_addfightnav("`xHakke Hyakunijyūhasshō","128hakke&cost=3",3);		
	}
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_kekkei_genkai_hyuuga_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$cost=httpget('cost');
	$pers=get_module_pref("stack","circulum_hyuuga");
	switch($skillname){
		case "byakugan":
			apply_buff('kekkei_genkai_hyuuga_1',array(
				"startmsg"=>"`x`b`i`%B`Ryakugan`i`b `4- `yYour dōjutsu awakens your unbelievable insight abilites.",
				"name"=>"`%B`Ryakugan",
				"rounds"=>20,
				"effectmsg"=>"You analyze {badguy} and predict his moves!",
				"badguyatkmod"=>0.8*(1/$pers),
				"badguydefmod"=>0.8*(1/$pers),
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_hyuuga"
			));
			break;
		case "jyuuken":
			require_once("lib/buffs.php");
			if (!has_buff('kekkei_genkai_hyuuga_1')) {
				$cost=0;
				output("`\$You have no ``%B`Ryakugan`0 active! Activate it first to use Jyūken!`n");
				break;
			}
			apply_buff('kekkei_genkai_hyuuga_2',array(
				"startmsg"=>"`x`b`iJyūken!`i`b `4- `yYou deal tremendous damage to the enemies chakra system!",
				"name"=>"`xJyūken",
				"rounds"=>1,
				"effectmsg"=>"You hit {badguy} for {damage} damage with your empowered Jyūken Fist!",
				"minbadguydamage"=>$session['user']['dragonkills']*$pers+1,
				"maxbadguydamage"=>$session['user']['dragonkills']*$pers+$session['user']['level'],
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_hyuuga"
			));
			break;
		case "hakke":
			require_once("lib/buffs.php");
			if (!has_buff('kekkei_genkai_hyuuga_1')) {
				$cost=0;
				output("`\$You have no `%B`Ryakugan`0 active! Activate it first to use Jyūken!`n");
				break;
			}
			apply_buff('kekkei_genkai_hyuuga_3',array(
				"startmsg"=>"`x`b`iHakke Rokujyūyonshō!`i`b `4- `yYou deal tremendous damage to the enemies chakra system by performing an insane number of hits!",
				"name"=>"`xHakke Rokujyūyonshō",
				"rounds"=>1,
				"effectmsg"=>"You hit {badguy} for {damage} damage with your empowered Jyūken Fist!",
				"minbadguydamage"=>$session['user']['dragonkills']*$pers/25+5,
				"maxbadguydamage"=>max(2,$session['user']['dragonkills']*$pers/25+$session['user']['level']/3),
				"minioncount"=>64,
				"schema"=>"module-specialtysystem_kekkei_genkai_hyuuga"
			));
			break;
		case "kaiten":
			require_once("lib/buffs.php");
			if (!has_buff('kekkei_genkai_hyuuga_1')) {
				$cost=0;
				output("`\$You have no `%B`Ryakugan`0 active! Activate it first to use Jyūken!`n");
				break;
			}
			apply_buff('kekkei_genkai_hyuuga_4',array(
				"startmsg"=>"`x`b`iKaiten!`i`b `4- `yYou deal some damage to all enemies!",
				"name"=>"`xKaiten",
				"rounds"=>10,
				"effectmsg"=>"You hit {badguy} for {damage} damage with your `iKaiten`i!",
				"areadamage"=>1,
				"minbadguydamage"=>$session['user']['level']*$pers+1,
				"maxbadguydamage"=>$session['user']['level']*$pers+$session['user']['level'],
				"minioncount"=>max($session['user']['level'],3), //was 3
				"schema"=>"module-specialtysystem_kekkei_genkai_hyuuga"
			));			
			break;
		case "128hakke":
			require_once("lib/buffs.php");
			if (!has_buff('kekkei_genkai_hyuuga_1')) {
				$cost=0;
				output("`\$You have no `%B`Ryakugan`0 active! Activate it first to use Jyūken!`n");
				break;
			}
			apply_buff('kekkei_genkai_hyuuga_3',array(
				"startmsg"=>"`y`b`iHakke `lHyakunijyūhasshō!`i`b `4- `yYou execute a lethal maneuver of hits!",
				"name"=>"`yHakke `lHyakunijyūhasshō",
				"rounds"=>1,
				"effectmsg"=>"You hit {badguy} for {damage} damage with your empowered Jyūken Fist!",
				"minbadguydamage"=>$session['user']['dragonkills']*$pers/25+5,
				"maxbadguydamage"=>max(2,$session['user']['dragonkills']*$pers/25+$session['user']['level']/3),
				"minioncount"=>128,
				"schema"=>"module-specialtysystem_kekkei_genkai_hyuuga"
			));
			break;			
	}
	specialtysystem_incrementuses("specialtysystem_kekkei_genkai_hyuuga",$cost);
	return;
}

function specialtysystem_kekkei_genkai_hyuuga_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Kekkei Genkai Hyuuga',
			"spec_colour"=>'`x',
			"spec_shortdescription"=>'-internal-',
			"spec_longdescription"=>'-internal-',
			"modulename"=>'specialtysystem_kekkei_genkai_hyuuga',
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

function specialtysystem_kekkei_genkai_hyuuga_run(){
}
?>
