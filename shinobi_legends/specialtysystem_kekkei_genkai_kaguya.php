<?php

function specialtysystem_kekkei_genkai_kaguya_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - Kaguya Kekkei Genkai",
		"author" => "`4Gyururu, based on work by `2Oliver Brendel",
		"version" => "1.0",
		"download" => "",
		"category" => "Specialty System",
		"requires"=> array(
			"specialtysystem"=>"1.0|Specialty System by `2Oliver Brendel",
			),
	);
	return $info;
}

function specialtysystem_kekkei_genkai_kaguya_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_kekkei_genkai_kaguya_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_kekkei_genkai_kaguya");
	return true;
}

function specialtysystem_kekkei_genkai_kaguya_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses();
	$pers=get_module_pref("stack","circulum_kaguya");
	$hyuuga=(int)get_module_pref("stack","circulum_hyuuga");
	$uchiha=(int)get_module_pref("stack","circulum_uchiha");
	$name=translate_inline('`%Shi`7ko`%tsu`7mya`%ku');
	tlschema('module-specialtysystem_kekkei_genkai_kaguya');
	if ($uses > 0 && $pers>0) {
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_kaguya"));
		require_once("lib/buffs.php");
		if (has_buff('shikotsumyaku5')) {
			$active=2;
		} else {
			$active=1;
		}
		// 1 stack jutsus
		if (has_buff('shikotsumyaku1' || 'shikotsumyaku2') && $uses >=0) specialtysystem_addfightnav("`gWithdraw your `vBones","withdrawbones&cost=0",0);
		if ($uses > 2) specialtysystem_addfightnav("`VT`%subaki `Xno `vmai","tsubaki&cost=3",3);
		if ($uses > 2) specialtysystem_addfightnav("`VK`%aramatsu `Xno `vmai","karamatsu&cost=3",3);
		if ($uses > 5) specialtysystem_addfightnav("`TT`ees`\$hi `MS`xe`mnd`Xan","sendan&cost=6",6);
		if ($uses > 6) specialtysystem_addfightnav("`)Y`7a`5na`7g`)i `Xno `vMai","yanagi&cost=7",7);
		// combo jutsus
			if ($uses>4 && $hyuuga>0 && has_buff("kekkei_genkai_hyuuga_1")) {
				specialtysystem_addfightnav("`)Ho`7n`je `jKa`vit`Le`ln","honekaiten&cost=5",5);
			}
			if ($uses>7 && $uchiha>0 && (has_buff("kekkei_genkai_uchiha_1") || has_buff("kekkai_genkai_uchiha_2") || has_buff("susanoo_1"))) {
				specialtysystem_addfightnav("`7S`4ha`\$rin`Pga`Xn `vn`Xo `vM`Xai","sharinganmai&cost=8",8);
			}
		// 2 stack jutsus
		if ($pers>1) {
			if ($active != 2) {
				if ($uses > 9) specialtysystem_addfightnav("`lT`5es`)se`5nk`la `Xno `vmai: `VT`%suru","tsuru&cost=10",10);
			}
			if ($active == 2) { 
				if ($uses > 4) specialtysystem_addfightnav("`lT`5es`)se`5nk`la `Xno `vmai: `VH`%ana","hana&cost=5",5);
			}
			if ($uses > 14) specialtysystem_addfightnav("`)S`5aw`7ar`5ab`)i `Xno `vMai","sawarabi&cost=15",15);
		}
	}
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_kekkei_genkai_kaguya_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$cost=httpget('cost');
	$pers=(int)get_module_pref("stack","circulum_kaguya");
	$hyuuga=(int)get_module_pref("stack","circulum_hyuuga");
	$uchiha=(int)get_module_pref("stack","circulum_uchiha");
	
	switch($skillname){
		case "sharinganmai": // overall improved, counter-balanced by slightly random hit number
			apply_buff('sharinganmai',array(
				"startmsg"=>"`7S`4ha`\$ringa`7n `vn`Xo `vM`Xai - `~With your sharingan active you dash into melee, shooting bones at nearby enemies like a death blossom!",
				"name"=>"`7S`4ha`\$ringa`7n `vn`Xo `vM`Xai",
				"rounds"=>10,
				"wearoff"=>"You require a moment to recuperate.",
				"atkmod"=>1.85,
				"defmod"=>1.2,
				"areadamage"=>1,
				"minbadguydamage"=>$session['user']['dragonkills']+60+(15*($pers+$uchiha)),
				"maxbadguydamage"=>$session['user']['dragonkills']+80+(15*($pers+$uchiha)),
				"minioncount"=>e_rand(2,4),
				"effectmsg"=>"{badguy}`7 is pierced by flying bones for {damage} damage!",
				"schema"=>"module-specialtysystem_kekkei_genkai_kaguya"
			));		
			break;
		case "honekaiten": // redesigned, brief invulnerable with some damage, no buff 
			apply_buff('honekaiten',array(
				"startmsg"=>"`)Ho`7n`je `jKa`vit`Le`ln - `~You execute a kaiten, expelling bone shrapnel with its momentum!",
				"name"=>"`)Ho`7n`je `jKa`vit`Le`ln",
				"rounds"=>3,
				"wearoff"=>"You stop spinning to let your body recover.",
				"areadamage"=>1,
				"invulnerable"=>1,
				"minbadguydamage"=>$session['user']['level']+(2*($pers+$hyuuga)),
				"maxbadguydamage"=>$session['user']['level']+(5*($pers+$hyuuga)),		
				"minioncount"=>3,
				"effectmsg"=>"{badguy}`7 is shredded for {damage} damage while you defend yourself!",
				"schema"=>"module-specialtysystem_kekkei_genkai_kaguya"
			));			
			break;
			case "withdrawbones":
			require_once("lib/buffs.php");
			strip_buff('shikotsumyaku1' && 'shikotsumyaku2');
			output("`@Your muscles mend and skin closes as your bones retract.`n");
			break;
		case "tsubaki": // redesigned, perma duration with minor damage, cant be active with karamatsu
			strip_buff('shikotsumyaku2');
			apply_buff('shikotsumyaku1',array(
				"startmsg"=>"`VT`%subaki `Xno `vmai - `~You extract your humerus to shape into a sword.",
				"name"=>"`VT`%subaki `Xno `vmai",
				"rounds"=>-1,
				"wearoff"=>"You dismantle your weapon.",
				"atkmod"=>1.35,
				"minbadguydamage"=>"<level>*$pers",
				"maxbadguydamage"=>"<level>*$pers+4",
				"minioncount"=>1,
				"effectmsg"=>"You stab and slash {badguy} for {damage} damage!",
				"schema"=>"module-specialtysystem_kekkei_genkai_kaguya"
			));
			break;
		case "karamatsu": // redesigned, perma duration with minor regen and dmgshield, cant be active with tsubaki
			strip_buff('shikotsumyaku1');
			apply_buff('shikotsumyaku2',array(
				"startmsg"=>"`VK`%aramatsu `Xno `vmai - `~You become a human porcupine as your recovery accelerates.",
				"name"=>"`VK`%aramatsu `Xno `vmai",
				"rounds"=>-1,
				"wearoff"=>"Your recovery rate slows as your bones retract.",
				"defmod"=>1.35,
				"dmgshield"=>0.3,
				"regen"=>"<level>+2*$pers",
				"minioncount"=>1,
				"effectmsg"=>"Your bones protect you as you regenerate!",
				"schema"=>"module-specialtysystem_kekkei_genkai_kaguya"
			));
			break;
		case "sendan": // renamed and buffed, 2nd kaguya stack has more impact
			apply_buff('shikotsumyaku3',array(
				"startmsg"=>"`TT`ees`\$hi `MS`xe`mnd`Xan - `7You begin ejecting your fingerbones, firing them out like bullets with swings of your hands!",
				"name"=>"`TT`ees`\$hi `MS`xe`mnd`Xan",
				"rounds"=>8,
				"wearoff"=>"Your fingertips mend shut as you stop.",
				"minbadguydamage"=>$session['user']['dragonkills']+40+(10*$pers),
				"maxbadguydamage"=>$session['user']['dragonkills']+60+(10*$pers),
				"minioncount"=>5,
				"effectmsg"=>"{badguy}`7 suffers {damage} damage from your volley!",
				"effectnodmgmsg"=>"You missed!",
				"schema"=>"module-specialtysystem_kekkei_genkai_kaguya"
			));
			break;
		case "yanagi": // buffed, 2nd kaguya stack has more impact
			apply_buff('shikotsumyaku4',array(
				"startmsg"=>"`)Y`7a`5na`7g`)i `Xno `vMai - `~You extend multiple bone blades out of your body and dance into combat!",
				"name"=>"`)Y`7a`5na`7g`)i `Xno `vMai",
				"rounds"=>8,
				"wearoff"=>"The bones retract as you become too light-headed to continue.",
				"areadamage"=>1,
				"minbadguydamage"=>$session['user']['dragonkills']+40+(10*$pers),
				"maxbadguydamage"=>$session['user']['dragonkills']+60+(10*$pers),
				"minioncount"=>3,
				"effectmsg"=>"{badguy}`7 suffers {damage} damage from your acrobatics!",
				"schema"=>"module-specialtysystem_kekkei_genkai_kaguya"
			));			
			break;
		case "tsuru": // now acts as requirement for using bone drill
			apply_buff('shikotsumyaku5',array(
				"startmsg"=>"`lT`5es`7se`5nk`la `Xno `vmai: `)T`Xsuru - `~You expell a copy of your spinal cord and bind the enemy!",
				"name"=>"`lT`5es`~se`5nk`la `Xno `vmai: `VT`%suru",
				"rounds"=>6,
				"wearoff"=>"Your vertebrae whip falls apart.",
				"invulnerable"=>1,
				"minioncount"=>1,
				"effectnodmgmsg"=>"{badguy}`7 is unable to attack you!",
				"schema"=>"module-specialtysystem_kekkei_genkai_kaguya"
			));			
			break;
		case "hana": // redesigned into a single-shot, needs tsuru active and breaks it after use to prevent spam
		    strip_buff('shikotsumyaku5');
			apply_buff('shikotsumyaku6',array(
				"startmsg"=>"`lT`5es`7se`5nk`la `Xno `vmai: `7H`Xana - `~Shaping bones about your arm into a giant drill-like spear, you charge at your whip-bound foe!",
				"name"=>"`lT`5es`~se`5nk`la `Xno `vmai: `VH`%ana",
				"rounds"=>1,
				"minbadguydamage"=>$session['user']['dragonkills']+1500,
				"maxbadguydamage"=>$session['user']['dragonkills']+2500,
				"minioncount"=>1,
				"effectmsg"=>"You skewer {badguy}`7 into a nigh unrecognizable mess!",
				"schema"=>"module-specialtysystem_kekkei_genkai_kaguya"
			));			
			break;
		case "sawarabi": // buffed, random hit number to counter-balance
			apply_buff('shikotsumyaku7',array(
				"startmsg"=>"`)S`5aw`7ar`5ab`)i `Xno `vMai - `~Spreading mass amounts of bone underground, it pierces to the surface like a forest of death!",
				"name"=>"`)S`5aw`7ar`5ab`)i `Xno `vMai",
				"rounds"=>5,
				"wearoff"=>"The spread of carnage ceases.",
				"areadamage"=>1,
				"minbadguydamage"=>$session['user']['dragonkills']+100,
				"maxbadguydamage"=>$session['user']['dragonkills']+150,
				"minioncount"=>e_rand(7,10),
				"effectmsg"=>"{badguy}`7 suffers {damage} damage from the erupting bone spikes!",
				"schema"=>"module-specialtysystem_kekkei_genkai_kaguya"
			));			
			break;
	}
	specialtysystem_incrementuses("specialtysystem_kekkei_genkai_kaguya",$cost);
	return;
}

function specialtysystem_kekkei_genkai_kaguya_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Shikotsumyaku',
			"spec_colour"=>'`7',
			"spec_shortdescription"=>'-internal-',
			"spec_longdescription"=>'-internal-',
			"modulename"=>'specialtysystem_kekkei_genkai_kaguya',
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

function specialtysystem_kekkei_genkai_kaguya_run(){
}
?>
