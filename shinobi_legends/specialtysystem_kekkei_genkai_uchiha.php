<?php

function specialtysystem_kekkei_genkai_uchiha_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - Uchiha",
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

function specialtysystem_kekkei_genkai_uchiha_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_kekkei_genkai_uchihaspecialtysystem_kekkei_genkai_uchiha_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_kekkei_genkai_uchiha");
	return true;
}

function specialtysystem_kekkei_genkai_uchiha_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses();
	$pers=get_module_pref("stack","circulum_uchiha");
	$name=translate_inline('`$Kekkei Genkai `4U`$chiha');
	tlschema('module-specialtysystem_kekkei_genkai_uchiha');
	$su=$session['user']['dragonkills'];
	if ($uses > 0 && $pers>0) {
		$buff_uchiha_1 = has_buff('kekkei_genkai_uchiha_1');
		$buff_uchiha_2 = has_buff('kekkei_genkai_uchiha_2');
		$buff_susanoo_1 = has_buff('susanoo_1');
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_uchiha"));
		if ($buff_susanoo_1 || $buff_uchiha_2 || $buff_uchiha_1) specialtysystem_addfightnav("`gDeactivate `\$S`4haringan","deactivatesharingan&cost=0",0);
		if ($uses > 0 && !$buff_uchiha_2 && !$buff_susanoo_1) specialtysystem_addfightnav(array("`\$S`4haringan (%s Tomoe)",min(3,$su+1)),"sharingan&cost=1",1);
		if ($uses > 2 && $pers>1 && $buff_uchiha_1 && get_module_pref("hasseal","susanoo")<1 && $su>10) {
			specialtysystem_addfightnav("`4万華鏡写輪眼, `\$M`4angekyō `\$S`4haringan","mangekyou&cost=3",3);
		}
		// susanoo
		$susa_level = get_module_pref("hasseal","susanoo");
		$sus_buff = $buff_susanoo_1;
		if ($uses > 0 && $pers>0 && $susa_level > 0) {
			if ($uses > 2 && $pers>1 && $buff_uchiha_1 && $su>10) {
				specialtysystem_addfightnav("`4万華鏡写輪眼, `\$E`4ternal `\$M`4angekyō `\$S`4haringan","susanoo&cost=3",3);
			}
			if ($uses > 2 && $pers>1 && $sus_buff && $su>10) {
				specialtysystem_addfightnav("`4Skeletal Susanoo","susanoo_3&cost=3",3);
			}
			if ($uses > 5 && $pers>1 && $sus_buff && $su>10 && $susa_level>1) {
				specialtysystem_addfightnav("`4Humanoid Susanoo","susanoo_6&cost=6",6);
			}
			if ($uses > 8 && $pers>1 && $sus_buff && $su>10 && $susa_level>2) {
				specialtysystem_addfightnav("`4Armored Susanoo","susanoo_9&cost=9",9);
			}					
			if ($uses > 14 && $pers>1 && $sus_buff && $su>10 && $susa_level>3) {
				specialtysystem_addfightnav("`4Perfect Susanoo","susanoo_15&cost=15",15);
			}			
		}
		require_once("lib/buffs.php");
		if ($uses>3 && $su>1 && ($buff_susanoo_1 || $buff_uchiha_1 || $buff_uchiha_2)) specialtysystem_addfightnav("`\$S`4ofūshasen no Tachi","windmill&cost=4",4);
		if ($uses>9 && ($buff_susanoo_1 || $buff_uchiha_2) && $su>10) specialtysystem_addfightnav("`\$A`4materasu","amaterasu&cost=10",10);
		if ($uses>9 && ($buff_susanoo_1 || $buff_uchiha_2) && $su>8) specialtysystem_addfightnav("`\$T`4sukuyomi","tsukuyomi&cost=10",10);
	} elseif ($uses==0 && $pers>0) {
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_uchiha"));
		if ($buff_susanoo_1 || $buff_uchiha_2 || $buff_uchiha_1) specialtysystem_addfightnav("`gDeactivate `\$S`4haringan","deactivatesharingan&cost=0",0);
	}
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_kekkei_genkai_uchiha_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$cost=httpget('cost');
	$pers=get_module_pref("stack","circulum_uchiha");
	$su=$session['user']['dragonkills'];
//	$factor = 1/sqrt($pers); // old factor
	$factor = 1/ (1+log10($pers));
	switch($skillname){
		case "deactivatesharingan":
			require_once("lib/buffs.php");
			strip_buff('kekkei_genkai_uchiha_1');
			strip_buff('kekkei_genkai_uchiha_2');
			strip_buff('susanoo_1');
			output("`4Your dōjutsu has been sealed again.`n");
			break;
		case "sharingan":
			apply_buff('kekkei_genkai_uchiha_1',array(
				"startmsg"=>"`x`b`i`\$S`4haringan`i`b `4- `yYour dōjutsu awakens your unbelievable abilities.",
				"name"=>array("`\$S`4haringan (%s Tomoe)",min(3,$su+1)),
				"rounds"=>-1,
				"effectmsg"=>"You analyze {badguy} and predict his moves!",
				"badguyatkmod"=>0.9*($factor),
				"badguydefmod"=>0.9*($factor),
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
			));
			break;
		case "mangekyou":
			require_once("lib/buffs.php");
			strip_buff('kekkei_genkai_uchiha_1');
			apply_buff('kekkei_genkai_uchiha_2',array(
				"startmsg"=>"`x`b`i`4万華鏡写輪眼, `\$M`4angekyō `\$S`4haringan`i`b `4- `yYour dōjutsu awakens your unbelievable abilities.",
				"name"=>"`4万華鏡写輪眼, `\$M`4angekyō `\$S`4haringan",
				"rounds"=>-1,
				"effectmsg"=>"You analyze {badguy} and predict his moves!",
				"badguyatkmod"=>0.7*($factor),
				"badguydefmod"=>0.7*($factor),
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
			));
			break;
		case "windmill":
			apply_buff('kekkei_genkai_uchiha_5',array(
				"startmsg"=>"`x`b`i`4`\$S`4ofūshasen no Tachi`i`b `4- `yYou pull out wired shuriken to limit any enemy movement!`y!",
				"name"=>"`\$S`4ofūshasen no Tachi",
				"rounds"=>1,
				"effectmsg"=>"`4Your enemy is almost defenseless!",
				"badguyatkmod"=>0.7*($factor),
				"badguydefmod"=>0.1*($factor),
				"minioncount"=>2,
				"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
			));
			break;				
		case "amaterasu":
			apply_buff('kekkei_genkai_uchiha_3',array(
				"startmsg"=>"`x`b`i`4`\$A`4materasu`i`b `4- `yYour dōjutsu burns your enemy with `~black`\$ flames`y!",
				"name"=>"`\$A`4materasu",
				"rounds"=>2,
				"effectmsg"=>"`4You burn your enemy for {damage} damage points!",
				"minbadguydamage"=>$session['user']['dragonkills']*$pers*2+1,
				"maxbadguydamage"=>$session['user']['dragonkills']*$pers*2+$session['user']['level'],
				"minioncount"=>2,
				"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
			));
			break;	
		case "tsukuyomi":
			apply_buff('kekkei_genkai_uchiha_4',array(
				"startmsg"=>"`x`b`i`4`\$T`4sukuyomi`i`b `4- `yYour dōjutsu traps the enemy!",
				"name"=>"`\$T`4sukuyomi",
				"rounds"=>15,
				"effectmsg"=>"`4Your enemy is trapped and suffers great damage while being almost paralyzed!",
				"minbadguydamage"=>$session['user']['dragonkills']*$pers*2+1,
				"maxbadguydamage"=>$session['user']['dragonkills']*$pers*2+$session['user']['level'],
				"badguyatkmod"=>0.5*($factor),
				"badguydefmod"=>0.5*($factor),
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
			));
			break;	

					case "susanoo":
						require_once("lib/buffs.php");
						strip_buff('kekkei_genkai_uchiha_1');
				/*		apply_buff('kekkei_genkai_uchiha_2',array(
							"name"=>"`\$E`4ternal `\$M`4angekyō `\$S`4haringan",
							"rounds"=>-1,
							"badguyatkmod"=>1,
							"minioncount"=>1,
							"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
							));*/
						apply_buff('susanoo_1',array(
							"startmsg"=>"`xE`lternal `xM`langekyō `xS`lharingan`J (永遠の万華鏡写輪眼) `y - Your dōjutsu awakens your absorbed abilities.",
							"name"=>"`\$E`4ternal `\$M`4angekyō `\$S`4haringan",
							"rounds"=>-1,
							"effectmsg"=>"You analyze {badguy} and predict his moves!",
							"badguyatkmod"=>0.7*(1/$factor),
							"badguydefmod"=>0.7*(1/$factor),
							"minioncount"=>1,
							"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
						));
						break;	
					case "susanoo_3":
						apply_buff('susanoo_3',array(
								"startmsg"=>"`\$S`4usanō!`i`b `4- `yThe skeletal form of Susanō manifests itself.",
								"name"=>"`xS`lkeletal `xS`lusanō",
								"rounds"=>15,
								"effectmsg"=>"Susanō lashes out at {badguy}, dealing {damage} damage!",
								"atkmod"=>1.5*(1/$factor),
								"defmod"=>1.5*(1/$factor),
								"areadamage"=>true,
								"minbadguydamage"=>2+$session['user']['level'],
								"maxbadguydamage"=>1,
								"minioncount"=>3,
								"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
							));
							break;	
					case "susanoo_6":
						apply_buff('susanoo_6',array(
								"startmsg"=>"`\$S`4usanō!`i`b `4- `yThe humanoid form of Susanō manifests itself.",
								"name"=>"`xH`lumanoid `xS`lusanō",
								"rounds"=>15,
								"effectmsg"=>"Susanō lashes out at {badguy}, dealing {damage} damage!",
								"atkmod"=>1.8*(1/$factor),
								"defmod"=>1.8*(1/$factor),
								"areadamage"=>true,
								"minbadguydamage"=>5+$session['user']['level'],
								"maxbadguydamage"=>3,
								"minioncount"=>3,
								"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
							));
							break;						
					case "susanoo_9":
						apply_buff('susanoo_9',array(
								"startmsg"=>"`\$S`4usanō!`i`b `4- `yThe armored form of Susanō manifests itself.",
								"name"=>"`xA`lrmored `xS`lusanō",
								"rounds"=>15,
								"effectmsg"=>"Susanō lashes out at {badguy}, dealing {damage} damage!",
								"atkmod"=>2*(1/$factor),
								"defmod"=>2*(1/$factor),
								"areadamage"=>true,
								"minbadguydamage"=>5+$session['user']['level'],
								"maxbadguydamage"=>6,
								"minioncount"=>3,
								"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
							));
							break;						
					case "susanoo_15":
						apply_buff('susanoo_15',array(
								"startmsg"=>"`\$S`4usanō!`i`b `4- `yThe perfect form of Susanō manifests itself.",
								"name"=>"`xP`lerfect `xS`lusanō",
								"rounds"=>15,
								"effectmsg"=>"Susanō lashes out at {badguy}, dealing {damage} damage!",
								"atkmod"=>2*(1/$factor),
								"defmod"=>2*(1/$factor),
								"areadamage"=>true,
								"minbadguydamage"=>2+$session['user']['level']*2,
								"maxbadguydamage"=>8,
								"minioncount"=>3,
								"schema"=>"module-specialtysystem_kekkei_genkai_uchiha"
							));
							break;	
			
	}
	specialtysystem_incrementuses("specialtysystem_kekkei_genkai_uchiha",$cost);
	return;
}

function specialtysystem_kekkei_genkai_uchiha_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Kekkei Genkai Uchiha',
			"spec_colour"=>'`4',
			"spec_shortdescription"=>'-internal-',
			"spec_longdescription"=>'-internal-',
			"modulename"=>'specialtysystem_kekkei_genkai_uchiha',
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

function specialtysystem_kekkei_genkai_uchiha_run(){
}
?>
