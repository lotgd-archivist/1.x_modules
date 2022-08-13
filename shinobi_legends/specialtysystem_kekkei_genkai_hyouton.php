<?php

function specialtysystem_kekkei_genkai_hyouton_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - Hyouton Kekkei Genkai",
		"author" => "`LShinobiIceSlayer`~ based on work by `2Oliver Brendel`~.`0",
		"version" => "1.0",
		"download" => "",
		"category" => "Specialty System",
		"requires"=> array(
			"specialtysystem"=>"1.0|Specialty System by `2Oliver Brendel",
			),
	);
	return $info;
}

function specialtysystem_kekkei_genkai_hyouton_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_kekkei_genkai_hyoutonspecialtysystem_kekkei_genkai_hyouton_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_kekkei_genkai_hyouton");
	return true;
}

function specialtysystem_kekkei_genkai_hyouton_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses();
	$pers=get_module_pref("stack","circulum_hyouton");
	$name=translate_inline('`LKekkei Genkai `lH`Lyouton');
	tlschema('module-specialtysystem_kekkei_genkai_hyouton');
	$su=$session['user']['dragonkills'];

	if ($uses > 0 && $pers>0) {
		$buffs=$session['bufflist'];
		if (isset($buffs['kekkei_hyouton'])) $buffs=$buffs['kekkei_hyouton'];
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_hyouton"));
		require_once("lib/buffs.php");
		if (!has_buff('kekkei_hyouton')) {
			$mirrors=0;
		} else {
			$mirrors=(int)$buffs['mirrors'];
		}
		$inmirror=has_buff('hyosho');
		
		if ($uses>2) specialtysystem_addfightnav("`LSensatsu Suishō","sensatsu&cost=3",3);
		
		if ($uses>1 && $mirrors<21) specialtysystem_addfightnav("`LMakyō Hyōshō (7)","hyosho7&cost=2",2);
		
		if ($inmirror==false && $mirrors>0 && $uses>0) specialtysystem_addfightnav("`LEnter Mirrors","enter&cost=1",1);
		
		if ($inmirror==true){
			
			if ($mirrors>0) specialtysystem_addfightnav("`LLeave Mirrors","leave&cost=0",0);
			
			if ($uses>0 && $mirrors>0) specialtysystem_addfightnav("`LMirror Shuffle","shuffle&cost=1",1);
			
			if ($uses>0 && $mirrors>0) specialtysystem_addfightnav("`LSneak Attack","sneak&cost=1",1);
			
			if ($uses>2 && $mirrors>0) specialtysystem_addfightnav("`LSenbon Strike","senbon&cost=3",3);
		}
		
		if ($pers>1) {
			
			if ($uses>6 && $su>5 && $mirrors==0) specialtysystem_addfightnav("`LMakyō Hyōshō (21)","hyosho21&cost=7",7);			
			
			if ($uses>4 && $mirrors==21 && $inmirror==true) specialtysystem_addfightnav("`LRapid Senbon Strike","rapid&cost=5",5);
			
		}		
	}

	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_kekkei_genkai_hyouton_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$pers=get_module_pref("stack","circulum_hyouton");
	$cost=httpget('cost');	
	$buffs=$session['bufflist'];
	if (isset($buffs['kekkei_hyouton'])) $buffs=$buffs['kekkei_hyouton'];
	require_once("lib/buffs.php");
	if (!has_buff('kekkei_hyouton')) {
			$mirrors=0;
		} else {
			$mirrors=(int)$buffs['mirrors'];
		}
	$inmirror=has_buff('hyosho');
	$active=true;
	if ($mirrors<1 || $inmirror==false) $active=false;
	switch($skillname){
		
		case "sensatsu":
			apply_buff('sensatsu',array(
				"startmsg"=>"`LSensatsu Suishō `l- You freeze the air around {badguy} into hundreds of Ice Needles.",
				"name"=>"`LSensatsu Suishō",
				"rounds"=>1,
				"minbadguydamage"=>floor($session['user']['level']/2),
				"maxbadguydamage"=>$session['user']['level'],
				"minioncount"=>10*$pers,
				"effectmsg"=>"{badguy} suffers {damage} damage from the sharp needles!",
				"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
			));
		break;
		
		case "hyosho7":
			if($mirrors>0) $msg="add seven more Ice mirrors to those already surrounding";
			else $msg="create seven Ice mirrors, which incircle ";
			$mirrors=$mirrors+7;
			apply_buff('kekkei_hyouton',array(
				"startmsg"=>"`LMakyō Hyōshō! `l- You $msg {badguy}.",
				"name"=>"`LIce Mirrors - $mirrors",
				"mirrors"=>$mirrors,
				"rounds"=>-1,
				"expireafterfight"=>true,
				"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
			));
			apply_buff('hyosho',array(
				"startmsg"=>"`LYou walk into the nearest mirror, becoming an image on it.",
				"name"=>"`LMakyō Hyōshō",
				"badguyatkmod"=>0.9-((($mirrors+7)/7)/10),
				"badguydefmod"=>0.9-((($mirrors+7)/7)/10),
				"expireafterfight"=>true,
				"rounds"=>-1,
				"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
			));
		break;
		
		case "enter":
			if ($mirrors==0) {
				output("`\$You need to activate your Mirrors to do that.`n`0");
				return;
			}else {
				apply_buff('hyosho',array(
						"startmsg"=>"`LYou walk into the nearest mirror, becoming an image on it.",
						"name"=>"`LMakyō Hyōshō",
						"badguyatkmod"=>0.9-((($mirrors+7)/7)/10),
						"badguydefmod"=>0.9-((($mirrors+7)/7)/10),
						"expireafterfight"=>true,
						"rounds"=>-1,
						"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
					));
			}
		break;
		
		case "leave":
			if (!$active) {
				output("`\$You need to activate your Mirrors to do that.`n`0");
				return;
			}else {
				strip_buff('hyosho');
				output("`LYou leave the saftey of your Mirrors, stepping out to fight the old fashion way.`n");
			}
		break;
		
		case "shuffle":
			if (!$active) {
				output("`\$You need to activate your Mirrors to do that.`n`0");
				return;
			}else {
				apply_buff('shuffle',array(
						"startmsg"=>"`LYou move rapidly between mirrors, making it impossible to hit you.",
						"name"=>"`LMirror Shuffle",
						"atkmod"=>0.5,
						"invulnerable"=>1,
						"rounds"=>1,
						"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
					));
			}
		break;
		
		case "sneak":
			if (!$active) {
				output("`\$You need to activate your Mirrors to do that.`n`0");
				return;
			}else {
				strip_buff('hyosho');
				apply_buff('sneak',array(
						"startmsg"=>"`LYou surprise {badguy} by jumping out of a random Mirror.",
						"name"=>"`LSneak Attack",
						"minbadguydamage"=>$session['user']['level'],
						"maxbadguydamage"=>$session['user']['level']*2,
						"minioncount"=>1,
						"effectmsg"=>"`LYou hit {badguy} for {damage} damage!",
						"atkmod"=>1.2,
						"badguydefmod"=>0.8,
						"rounds"=>1,
						"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
					));
			}
		break;
		
		case "senbon":
			if (!$active) {
				output("`\$You need to activate your Mirrors to do that.`n`0");
				return;
			}else {
				apply_buff('senbon',array(
						"startmsg"=>"`LYou rush between mirrors, flicking senbon at {badguy}.",
						"name"=>"`LSenbon Strike",
						"minbadguydamage"=>floor($session['user']['level']/2),
						"maxbadguydamage"=>$session['user']['level'],
						"minioncount"=>floor($mirrors/2),
						"areadamage"=>true,
						"effectmsg"=>"{badguy} suffers {damage} damage from the senbon!",
						"rounds"=>1,
						"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
					));
			}
		break;
		
		case "hyosho21":
			apply_buff('kekkei_hyouton',array(
				"startmsg"=>"`LMakyō Hyōshō! `l- You create a dome of 21 Ice Mirrors around {badguy}.",
				"name"=>"`LIce Mirrors - 21",
				"mirrors"=>21,
				"rounds"=>-1,
				"expireafterfight"=>true,
				"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
			));
			apply_buff('hyosho',array(
				"startmsg"=>"`LYou walk into the nearest mirror, becoming an image on it.",
				"name"=>"`LMakyō Hyōshō",
				"badguyatkmod"=>0.6,
				"badguydefmod"=>0.6,
				"expireafterfight"=>true,
				"rounds"=>-1,
				"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
			));
		break;
		
		case "rapid":
			if ($active==false) {
				output("`\$You need to activate your Mirrors to do that.`n`0");
				return;
			}else {
				apply_buff('senbon',array(
						"startmsg"=>"`LYou move so fast that you appear in all the mirrors, flicking senbon at {badguy}.",
						"name"=>"`LRapid Senbon Strike",
						"minbadguydamage"=>floor($session['user']['level']/2),
						"maxbadguydamage"=>$session['user']['level'],
						"minioncount"=>21,
						"areadamage"=>true,
						"effectmsg"=>"{badguy} suffers {damage} damage from the senbon!",
						"rounds"=>1,
						"schema"=>"module-specialtysystem_kekkei_genkai_hyouton"
					));
			}
		break;
		
	}
	specialtysystem_incrementuses("specialtysystem_kekkei_genkai_hyouton",$cost);
	return;
}

function specialtysystem_kekkei_genkai_hyouton_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Kekkei Genkai Hyouton',
			"spec_colour"=>'`x',
			"spec_shortdescription"=>'-internal-',
			"spec_longdescription"=>'-internal-',
			"modulename"=>'specialtysystem_kekkei_genkai_hyouton',
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

function specialtysystem_kekkei_genkai_hyouton_run(){
}
?>
