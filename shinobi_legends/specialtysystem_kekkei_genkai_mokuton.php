<?php

function specialtysystem_kekkei_genkai_mokuton_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - Mokuton",
		"author" => "`LShinobiIceSlayer `~based on work by `2Oliver Brendel`0",
		"version" => "1.0",
		"download" => "",
		"category" => "Specialty System",
		"requires"=> array(
			"specialtysystem"=>"1.0|Specialty System by `2Oliver Brendel",
			),
	);
	return $info;
}

function specialtysystem_kekkei_genkai_mokuton_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_kekkei_genkai_mokutonspecialtysystem_kekkei_genkai_mokuton_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_kekkei_genkai_mokuton");
	return true;
}

function specialtysystem_kekkei_genkai_mokuton_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses();
	$pers=get_module_pref("stack","circulum_senju");
	$name=translate_inline('`TM`@okuton');
	tlschema('module-specialtysystem_kekkei_genkai_mokuton');
	$su=$session['user']['dragonkills'];
	if ($uses > 0 && $pers>0) {
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_mokuton"));
		require_once("lib/buffs.php");
		if (has_buff('jukai_kotan')) {
			$active=2;
		} else {
			$active=1;
		}	
		
		if ($pers>0) {
				if ($uses>floor(1/$active)) {
					$basecost = 2;
					if ($pers>2) $basecost--;
					$cost = floor($basecost/$active);
					if ($cost<1) $cost=1;
					specialtysystem_addfightnav("`@??, Moku Henge","henge&cost=$cost&active=$active",$cost);
				}
				if ($uses>floor(4/$active)) {
					$basecost = 4;
					if ($pers>3) $basecost--;
					$cost = floor($basecost/$active);
					if ($cost<1) $cost=1;
					specialtysystem_addfightnav("`@??, Moku Joheki","joheki&cost=$cost&active=$active",$cost);
				}
				if ($uses>floor(6/$active)) {
					$basecost = 6;
					if ($pers>3) $basecost--;
					$cost = floor($basecost/$active);
					if ($cost<1) $cost=1;
					specialtysystem_addfightnav("`@??, Shichuro","shichuro&cost=$cost&active=$active",$cost);
				}	
				if ($uses>floor(9/$active)) {	
					$basecost = 9;
					if ($pers>3) $basecost--;
					$cost = floor($basecost/$active);
					if ($cost<1) $cost=1;
					specialtysystem_addfightnav("`@??, Moku Bunshin","bunshin&cost=$cost&active=$active",$cost);
				}	
		}	
		if ($pers>1) {
			if ($active != 2) {
				if ($uses>9) specialtysystem_addfightnav("`@???, Mokuton Hijutsu: Jukai Kotan","jukai_kotan&cost=10active=$active",10);
			}
			if ($active == 2) {
				if ($uses>4) specialtysystem_addfightnav("`@???, Jubaku Eisou","jubaku_eisou&cost=5",5);
			}
		}
	}
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_kekkei_genkai_mokuton_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$cost=httpget('cost');
	$active=httpget('active');
	$pers=get_module_pref("stack","circulum_senju");
	require_once("lib/buffs.php");
	if (!has_buff('jukai_kotan') && $active==2) {
		output("`@You have lost the strength of the forest.`n");
		return;
	}
	switch($skillname){
		case "henge":
			apply_buff('henge',array(
				"name"=>"`@Moku Henge",
				"startmsg"=>"`@You cover you body in `Twood `@and take on the appearance of another, confusing {badguy}.",
				"rounds"=>2,
				"wearoff"=>"`@The `TWood `#falls off your body, revealing you once again.",
				"badguyatkmod"=>.7,
				"schema"=>"module-specialtysystem_kekkei_genkai_mokuton"
			));
			break;
		case "joheki":
			apply_buff('joheki',array(
				"name"=>"`@Moku Joheki",
				"startmsg"=>"`@You create large branches of `Twood `@which form a large dome around you.",
				"roundmsg"=>"`@You hide behind the dome",
				"rounds"=>2,
				"defmod"=>1.3,
				"schema"=>"module-specialtysystem_kekkei_genkai_mokuton"
			));
			break;				
		case "shichuro":
			apply_buff('shichuro',array(
				"name"=>"`@Shichuro",
				"startmsg"=>"`@You cause large pillars of `Twood `@to form around {badguy}.",
				"roundmsg"=>"`@{badguy} is trapped in the prison.",
				"rounds"=>3,
				"badguyatkmod"=>0.7,
				"badguydefmod"=>1.1,
				"schema"=>"module-specialtysystem_kekkei_genkai_mokuton"
			));
			break;
		case "bunshin":
			$name = "`@Moku Bunshin";
			apply_companion($name , array(
				"name"=>"$name",
				"hitpoints"=>$session['user']['hitpoints'],
				"maxhitpoints"=>$session['user']['maxhitpoints'],
				"attack"=>floor($session['user']['attack']/2),
				"defense"=>floor($session['user']['defense']/2),
				"dyingtext"=>"`@The clone returns to wood.",
				"cannotbehealed"=>true,
				"expireafterfight"=>1,
				"abilities"=>array(
					"fight"=>true,
				),							
			), true);			
			break;
		case "jukai_kotan":
			apply_buff('jukai_kotan',array(
				"name"=>"`@Mokuton Hijutsu: Jukai Kotan",
				"startmsg"=>"`@You Create a Large Forest around you.",
				"rounds"=>15*$pers,
				"roundmsg"=>"You are surrounded by the trees!",
				"wearoff"=>"The trees return to the ground.",
				"defmod"=>1.5,
				"badguyatkmod"=>1.15,
				"schema"=>"module-specialtysystem_kekkei_genkai_mokuton"
			));
			break;
		case "jubaku_eisou":
			apply_buff('jubaku_eisou',array(
				"name"=>"`@Jubaku Eisou",
				"startmsg"=>"`@You cause the `Tbranches `@of a near by `Ttree `@entangle {badguy}.",
				"rounds"=>-1,
				"roundmsg"=>"{badguy} is entangled in the `Ttree`@.",
				"badguydefmod"=>0.5,
				"badguyatkmod"=>0.90,
				"expireafterfight"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_mokuton"
			));
			break;		
	}
	specialtysystem_incrementuses("specialtysystem_kekkei_genkai_mokuton",$cost);
	return;
}

function specialtysystem_kekkei_genkai_mokuton_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Mokuton',
			"spec_colour"=>'`@',
			"spec_shortdescription"=>'-internal-',
			"spec_longdescription"=>'-internal-',
			"modulename"=>'specialtysystem_kekkei_genkai_mokuton',
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

function specialtysystem_kekkei_genkai_mokuton_run(){
}
?>
