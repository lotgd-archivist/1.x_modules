<?php

function specialtysystem_kekkei_genkai_sage_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - Sage Mode",
		"author" => "`LShinobIceSlayer `~based on work by `2Oliver Brendel`0",
		"version" => "1.0",
		"download" => "",
		"category" => "Specialty System",
		"requires"=> array(
			"specialtysystem"=>"1.0|Specialty System by `2Oliver Brendel",
			),
	);
	return $info;
}

function specialtysystem_kekkei_genkai_sage_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_kekkei_genkai_sagespecialtysystem_kekkei_genkai_sage_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_kekkei_genkai_sage");
	return true;
}

function specialtysystem_kekkei_genkai_sage_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses();
	$pers=get_module_pref("stack","circulum_sage");
	$name1=translate_inline('`jKuchiyose no Jutsu');
	$name2=translate_inline('`@Senjutsu');
	tlschema('module-specialtysystem_kekkei_genkai_sage');
	$su=$session['user']['dragonkills'];
	require_once("modules/sage/functions.php");
	$toads=check_toads();
	if ($uses > 0){
		if($pers>0) {
			specialtysystem_addfightheadline($name1, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_sage"));
			if ($uses > 0 &&  !$toads['toad_gamatatsu']) specialtysystem_addfightnav("`tG`qa`tma`qt`tat`qs`tu","summon&toad=gamatatsu&cost=1",1);
			if ($uses > 1 &&  !$toads['toad_gamakichi']) specialtysystem_addfightnav("`qG`5a`qm`5ak`qic`5h`qi","summon&toad=gamakichi&cost=2",2);
			if ($uses > 5 &&  !$toads['toad_gamahiro'] && $su>4) specialtysystem_addfightnav("`kG`3a`km`3a`khi`3r`ko","summon&toad=gamahiro&cost=6",6);
			if ($uses > 5 &&  !$toads['toad_gamaken'] && $su>4) specialtysystem_addfightnav("`4G`~a`4mak`~e`4n ","summon&toad=gamaken&cost=6",6);
			if ($uses > 9 &&  !$toads['toad_gamabunta'] && $su>9) specialtysystem_addfightnav("`qG`\$a`qm`\$ab`qun`\$t`qa","summon&toad=gamabunta&cost=10",10);
		}
		if($pers>1) {
			if ($uses > 9 &&  !$toads['toad_gamahiro'] && $su>4) specialtysystem_addfightnav("`jKuchiyose: Yatai Kuzushi no Jutsu","foodcart&cost=10",10);
			if ($uses > 3 &&  !$toads['toad_shima']&& $su>5) specialtysystem_addfightnav("`KS`%him`Ka","summon&toad=shima&cost=4",4);
			if ($uses > 3 &&  !$toads['toad_fukasaku']&& $su>5) specialtysystem_addfightnav("`YF`ju`@k`jas`@a`jk`Yu","summon&toad=fukasaku&cost=4",4);
			if ($uses > 6 &&  !$toads['toad_shima'] && !$toads['toad_fukasaku']&& $su>9) specialtysystem_addfightnav("`KS`%him`Ka and `YF`ju`@k`jas`@a`jk`Yu","summon&toad=elders&cost=7",7);			
			
			if (($uses > 4 && $pers==2) || ($uses > 0 && $pers > 2)) specialtysystem_addfightheadline($name2, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_sage"));
			
			if ($uses > 4 &&  $toads['toad_gamatatsu'] && $toads['toad_gamakichi']) specialtysystem_addfightnav("`@Fūton: `TGamayu `\$Endan","combo_endan&cost=5",5);
			if ($uses > 9 &&  $toads['toad_gamabunta']) specialtysystem_addfightnav("`\$Katon: `TGamayu `QEndan","bunta_endan&cost=10",10);
			if ($uses > 4 &&  ($toads['toad_shima'] || $session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])) specialtysystem_addfightnav("`RZessenbaku","zessenbaku&cost=5",5);
			if ($uses > 4 &&  ($toads['toad_fukasaku'] || $session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])) specialtysystem_addfightnav("`LZessenzan","zessenzan&cost=5",5);
			if ($uses > 6 && (($toads['toad_shima'] &&  $toads['toad_fukasaku']) || $session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])) specialtysystem_addfightnav("`@Senpō: Kawazu Naki","kawazu&cost=7",7);
			
		}
		if($pers>2) {
			require_once("lib/buffs.php");
			if (get_module_pref('clone','circulum_sage') && !isset($session['bufflist']['kekkei_genkai_sage_clone'])){
				specialtysystem_addfightnav("`@Disperse Clone","enter_sage_mode&method=clone&cost=0",0);
			}
			if (!has_buff('kekkei_genkai_sage_mode')){
				if (!has_buff('kekkei_genkai_sage_gather'))specialtysystem_addfightnav("`@Gather Senjutsu Chakra","gather&cost=0",0);
				if ($toads['toad_shima'] &&  $toads['toad_fukasaku']) specialtysystem_addfightnav("`@Senpō: Ryōsei no Jutsu","enter_sage_mode&method=ryosei&cost=0",0);	
				if (has_buff('kekkei_genkai_sage_gather')) specialtysystem_addfightnav("`@Enter Sage Mode","enter_sage_mode&method=gather&cost=0",0);
			} elseif (has_buff('kekkei_genkai_sage_mode')){
				if ($uses > 14 && (($toads['toad_shima'] &&  $toads['toad_fukasaku']) || $session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])) specialtysystem_addfightnav("`@Magen: `2Gama`@rin`Yshō","gamarinsho&cost=15",15);
				if ($uses > 19 && (($toads['toad_shima'] &&  $toads['toad_fukasaku']) || $session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])) specialtysystem_addfightnav("`@Senpō: Go`Tem`\$on","goemon&cost=20",20);
				specialtysystem_addfightnav("`@Exit Sage Mode","exit&cost=0",0);
			}
		}
	}
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_kekkei_genkai_sage_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	require_once("modules/sage/functions.php");
	$cost=httpget('cost');
	$pers=get_module_pref("stack","circulum_sage");
	$su=$session['user']['dragonkills'];
	$toads=check_toads();
	switch($skillname){		
		case "summon":
			$toad=httpget('toad');			
			$check = summon_toad($toad);
			if ($check==false) $cost = 1; //They summoned Gamariki!
			break;
		case "foodcart":
			apply_buff('foodcart',array(
				"startmsg"=>"`j`b`iKuchiyose: Yatai Kuzushi no Jutsu`i`b `2- `@You summon the large toad, `kG`3a`km`3a`khi`3r`ko`@, right on top of {badguy}.",
				"name"=>"`jKuchiyose: Yatai Kuzushi no Jutsu",
				"rounds"=>1,
				"effectmsg"=>"`kG`3a`km`3a`khi`3r`ko `@crushes, {badguy}, causing them {damage} damage.",
				"minbadguydamage"=>$session['user']['dragonkills']*2+1,
				"maxbadguydamage"=>$session['user']['dragonkills']*2+$session['user']['level'],
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			));
			apply_companion("toad_gamahiro",array(
				"name"=>"`kG`3a`km`3a`khi`3r`ko",
				"attack"=>$session['user']['level']+8+min($pers,3),
				"defense"=>$session['user']['level']+5+min($pers,3),
				"hitpoints"=>$session['user']['level']*10+$session['user']['dragonkills']*min($pers,3),
				"maxhitpoints"=>$session['user']['level']*10+$session['user']['dragonkills']*min($pers,3),
				"companionactive"=>1,
				"jointext"=>'',
				"cannotdie"=>0,
				"cannotbehealed"=>1,
				"dyingtext"=>"`kG`3a`km`3a`khi`3r`ko`@, disappears in a puff of smoke. `j*Poof*",
				"expireafterfight"=>0,
				"ignorelimit"=>true,
				"abilities"=>array(
					"fight"=>1,
					"heal"=>0,
					"magic"=>0,
					"defend"=>1,
					),
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			),true);
			break;
		case "combo_endan":
			if ($toads['toad_gamatatsu'] && $toads['toad_gamakichi']){
				apply_buff('combo_endan',array(
					"startmsg"=>"`b`i`@Fūton: `TGamayu `\$Endan`i`b `2- `@Gamatatsu, Gamakichi, and you combine Katon, Fūton, and Toad Oil, to create a powerful wave of fire.",
					"name"=>"`@Fūton: `TGamayu `\$Endan",
					"rounds"=>1,
					"areadamage"=>true,
					"minbadguydamage"=>2+$session['user']['dragonkills'],
					"maxbadguydamage"=>1+$session['user']['level']*2+$session['user']['dragonkills'],
					"minioncount"=>2,
					"effectmsg"=>"`q{badguy}`q suffers {damage} damage from burns!",
					"schema"=>"module-specialtysystem_kekkei_genkai_sage"
				));
			} else {
				output("`\$You don't have Gamatatsu and Gamakichi no more!");
				return;
			}
			break;				
		case "bunta_endan":
			if ($toads['toad_gamabunta']){
				apply_buff('bunta_endan',array(
					"startmsg"=>"`b`i`\$Katon: `TGamayu `QEndan`i`b `2- `@You send a `\$Katon `@jutsu from your mouth to Gamabunta's oil-blast... and engulf {badguy} in flames!",
					"name"=>"`\$Katon: `TGamayu `QEndan",
					"rounds"=>2,
					"wearoff"=>"`@The fire extinguishes and only the ash remains.",
					"areadamage"=>true,
					"minbadguydamage"=>5+$session['user']['dragonkills'],
					"maxbadguydamage"=>5+$session['user']['level']*3+$session['user']['dragonkills'],
					"minioncount"=>4,
					"effectmsg"=>"`q{badguy}`q suffers {damage} damage from burns!",
					"schema"=>"module-specialtysystem_kekkei_genkai_sage"
				));
			} else {
				output("`\$You don't have Gamabunta no more!");
				return;
			}
			break;	
		case "zessenbaku":
			if (has_buff('kekkei_genkai_sage_mode')&&(($toads['toad_shima']&&$toads['toad_fukasaku'])||$session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])){
				apply_buff('zessenbaku',array(
					"startmsg"=>"`b`i`RZessenbaku`i`b `2- `@Shima's tonuge rapidly extends, and seeks out {badguy}.!",
					"name"=>"`RZessenbaku",
					"rounds"=>3,
					"roundmsg"=>"`RShima's tonuge ensnares {badguy}!",
					"wearoff"=>"`@`RShima's tonuge releases it's prey, returning to normal.",
					"badguyatkmod"=>1-(($pers/2)*0.1),
					"badguydefmod"=>1-(($pers/2)*0.1),
					"minioncount"=>1,
					"schema"=>"module-specialtysystem_kekkei_genkai_sage"
				));
			} else {
				output("`\$You can't use this jutsu right now!!");
				return;
			}
			break;	
		case "zessenzan":
			if (has_buff('kekkei_genkai_sage_mode')&&(($toads['toad_shima']&&$toads['toad_fukasaku'])||$session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])){
				apply_buff('zessenzan',array(
					"startmsg"=>"`b`i`LZessenzan`i`b `2- `@Fukasaku fires a highly pressurized stream of water from his mouth at {badguy}!",
					"name"=>"`LZessenzan",
					"rounds"=>1,
					"effectmsg"=>"`@{badguy} is sliced by the stream, causing {damage} damage!",
					"minbadguydamage"=>$session['user']['dragonkills']+5*$pers,
					"maxbadguydamage"=>$session['user']['dragonkills']+10*$pers,
					"minioncount"=>1,
					"schema"=>"module-specialtysystem_kekkei_genkai_sage"
				));
			} else {
				output("`\$You can't use this jutsu right now!!");
				return;
			}
			break;	
		case "kawazu":
			if (has_buff('kekkei_genkai_sage_mode')&&(($toads['toad_shima']&&$toads['toad_fukasaku'])||$session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])){
				apply_buff('kawazu',array(
					"startmsg"=>"`b`i`@Senpō: Kawazu Naki`i`b `2- `@Shima and Fukasaku sing together, creating a small Genjutsu!",
					"name"=>"`@Senpō: Kawazu Naki",
					"rounds"=>5,
					"roundmsg"=>"`@{badguy} is distracted by the Genjutsu.",
					"badguyatkmod"=>1-($pers*0.1),
					"badguydefmod"=>1-($pers*0.1),
					"minioncount"=>1,
					"schema"=>"module-specialtysystem_kekkei_genkai_sage"
				));
			} else {
				output("`\$You can't use this jutsu right now!!");
				return;
			}
			break;	
		case "gather":
			apply_buff('kekkei_genkai_sage_gather',array(
				"startmsg"=>"`@You stop and stand still, gathering Natural Chakra.",
				"name"=>"`@Natural Chakra Gathering",
				"rounds"=>30,
				"roundmsg"=>"`@You are completely still, and open to attack.",
				"atkmod"=>0.1,
				"defmod"=>0.1,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			));
			break;	
		case "enter_sage_mode":
			$method=httpget('method');
			$check=sage_mode($method);
			if ($check!=''){
				output($check);
				return;
			}
			break;	
		case "gamarinsho":
			if (has_buff('kekkei_genkai_sage_mode')&&(($toads['toad_shima']&&$toads['toad_fukasaku'])||$session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])){
				apply_buff('gamarinsho',array(
					"startmsg"=>"`b`i`@Magen: `2Gama`@rin`Yshō`i`b `2- `@Shima and Fukasaku perform a powerful Genjutsu, by singing together they trap all that hear their song.",
					"name"=>"`@Magen: `2Gama`@rin`Yshō",
					"rounds"=>5,
					"roundmsg"=>"`@{badguy} is trapped in a Powerful Genjutsu, surrounded by Toad Samurai.",
					"badguyatkmod"=>0.4*(1/sqrt($pers)),
					"badguydefmod"=>0.4*(1/sqrt($pers)),
					"minioncount"=>1,
					"schema"=>"module-specialtysystem_kekkei_genkai_sage"
				));
			} else {
				output("`\$You can't use this jutsu right now!!");
				return;
			}
			break;	
		case "goemon":
			if (has_buff('kekkei_genkai_sage_mode')&&(($toads['toad_shima']&&$toads['toad_fukasaku'])||$session['bufflist']['kekkei_genkai_sage_mode']['ryosei'])){
				apply_buff('goemon',array(
					"startmsg"=>"`b`i`@Senpō: Go`Tem`\$on`i`b `2- `@Shima, Fukasaku and you combine streams of Toad Oil, Wind and Fire, to make a wave of intense fire!",
					"name"=>"`@Senpō: Go`Tem`\$on",
					"rounds"=>3,
					"effectmsg"=>"`@{badguy} suffers burns, causing {damage} damage.",
					"minbadguydamage"=>$session['user']['dragonkills']*$pers+1,
					"maxbadguydamage"=>$session['user']['dragonkills']*$pers+$session['user']['level'],
					"areadamage"=>true,
					"minioncount"=>2,
					"schema"=>"module-specialtysystem_kekkei_genkai_sage"
				));
			} else {
				output("`\$You can't use this jutsu right now!!");
				return;
			}
			break;	
		case "exit":
			require_once("lib/buffs.php");
			strip_buff('kekkei_genkai_sage_mode');
			strip_buff('kekkei_genkai_sage_katas');
			output("`@You dispel the Natural Chakra from your body.`n");
			break;
	}
	specialtysystem_incrementuses("specialtysystem_kekkei_genkai_sage",$cost);
	return;
}

function specialtysystem_kekkei_genkai_sage_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Kekkei Genkai Sage Mode',
			"spec_colour"=>'`4',
			"spec_shortdescription"=>'-internal-',
			"spec_longdescription"=>'-internal-',
			"modulename"=>'specialtysystem_kekkei_genkai_sage',
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

function specialtysystem_kekkei_genkai_sage_run(){
}

?>
