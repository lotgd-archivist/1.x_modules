<?php

function get_deva_fightnavs($points,$path){
	
	$name = translate_inline("`!天道, Tendō (Deva Path)");
	tlschema('module-specialtysystem_kekkei_genkai_rinnegan_deva');

	$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));

	require_once("lib/buffs.php");
	
	if($path==false && $summons['deva_path']==false){
		if ($points > 0) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if ($points > 0) specialtysystem_addfightnav("`!Summon Deva Path","summon_deva_path&cost=1&path=deva",1);
		
	} elseif(!has_buff('deva_1') && $path) {
		if ($points > 2) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if ($points > 2) specialtysystem_addfightnav("`!Bansho Ten'in","bansho&cost=3&path=deva",3);
		
		if ($points >2) specialtysystem_addfightnav("`!Shinra Tensei(Attack)","attack&cost=3&path=deva",3);
		
		if ($points > 3) specialtysystem_addfightnav("`!Shinra Tensei(Defense)","defense&cost=4&path=deva",4);
		
		if ($points > 12) specialtysystem_addfightnav("`LS`!hinra `LT`!ensei(Grand)","grand&cost=13&path=deva",13);
		
		if ($points > 14) specialtysystem_addfightnav("`!C`Lhibaku `!T`Lensei","chibaku&cost=15&path=deva",15);
	}
	
	return;
}

function deva_path_apply($skill) {

	global $session;
	$pers=get_module_pref("stack","circulum_rinnegan");
	
	require_once("modules/rinnegan/functions.php");
	$companions=check_paths();
	if ($companions['deva_path']==false&&$skill!='summon_deva_path') {
		output("`\$Can't do that, you don't have body anymore!");
		return false;
	}
	
	switch ($skill) {
	
		case "bansho":
			apply_buff('deva_1',array(
				"startmsg"=>"`!`b`iBansho Ten'in!`b`i - `xDeva Path draws {badguy} towards it with it's Gravity Manipulation.",
				"name"=>"`!Bansho Ten'in",
				"rounds"=>3,
				"roundmsg"=>"`!{badguy} struggles to defend.",
				"badguydefmod"=>0.7,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			break;
		case "attack":
			apply_buff('Shinra',array(
				"startmsg"=>"`!`b`iShinra Tensei!`b`i - `xDeva Path uses it's Gravity Manipulation to send {badguy} flying.",
				"name"=>"`!Shinra Tensei",
				"rounds"=>1,
				"effectmsg"=>"`!{badguy} suffers {damage} damage from the attack.",
				"minbadguydamage"=>max($session['user']['dragonkills'],15)+$session['user']['level'],
				"maxbadguydamage"=>max(($session['user']['dragonkills']+10),40)+$session['user']['level']*2,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			apply_buff('deva_1',array(
				"name"=>"`!Shinra Tensei",
				"rounds"=>3,
				"roundmsg"=>"`!Deva Path needs to recharge from your use of Shinra Tensei.",
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			break;
		case "defense":
			apply_buff('deva_1',array(
					"startmsg"=>"`!`b`iShinra Tensei!`b`i - `xDeva Path creates a barrier using it's Gravity Manipulation, stopping anything from attacking you.",
					"name"=>"`!Shinra Tensei",
					"rounds"=>3,
					"roundmsg"=>"`!You are protected by the barrier.",
					"invulnerable"=>1,
					"minioncount"=>1,
					"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
				));
			break;
		case "grand":
			apply_buff('grand',array(
				"startmsg"=>"`!`b`iShinra Tensei!`b`i - `xChanneling all your power into Deva Path, it repels anything near you, unleashing a wave of destruction.",
				"name"=>"`!Shinra Tensei",
				"rounds"=>1,
				"effectmsg"=>"`!{badguy} suffers {damage} damage from the attack.",
				"areadamage"=>true,
				"minbadguydamage"=>$session['user']['dragonkills']*$pers+1,
				"maxbadguydamage"=>$session['user']['dragonkills']*$pers+$session['user']['level'],
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			apply_buff('deva_2',array(
				"name"=>"`!Shinra Tensei",
				"rounds"=>5,
				"roundmsg"=>"`!You need to recharge from your use of Shinra Tensei.",
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			break;
		case "chibaku":
			apply_buff('deva_2',array(
				"startmsg"=>"`!`b`iChibaku Tensei!`b`i - `xChanneling all your power to Deva Path it uses Gravity Manipulation, to attract everything nearby into a huge sphere in the sky!",
				"name"=>"`!Chibaku Tensei",
				"rounds"=>5,
				"effectmsg"=>"`!{badguy} is crushed, unable to attack.",
				"minbadguydamage"=>($session['user']['dragonkills']*$pers)/2,
				"maxbadguydamage"=>$session['user']['dragonkills']*$pers,
				"badguyatkmod"=>0,
				"areadamage"=>true,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			break;
		case "summon_deva_path":
			apply_companion('deva_path', array(
							"name"=>"`!Deva Path",
							"jointext"=>"`xYou summon the `!Deva Path`x!",
							"hitpoints"=>round($session['user']['maxhitpoints']/2,0)+20,
							"maxhitpoints"=>round($session['user']['maxhitpoints']/2,0)+20,
							"attack"=>round(max($session['user']['attack']/2,4),0),
							"defense"=>round(max($session['user']['defense']/2,4),0),
							"abilities"=>array(
								"fight"=>1,
								"heal"=>0,
								"magic"=>0,
								"defend"=>1,
							),
							"ignorelimit"=>true, 
						), true);
			$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));
			$summons['deva_path']=true;
			set_module_pref("pathsused",serialize($summons),"circulum_rinnegan");
			break;
	}
	return true;
}

?>
