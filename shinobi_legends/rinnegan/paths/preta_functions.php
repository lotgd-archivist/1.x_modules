<?php

function get_preta_fightnavs($points,$path){
	
	$name = translate_inline("`@餓鬼道, Gakidō (Preta Path)");
	tlschema('module-specialtysystem_kekkei_genkai_rinnegan');

	$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));
	$buffs=$session['bufflist'];
	if (isset($buffs['jutsucreatures'])) $buffs=$buffs['jutsucreatures'];

	require_once("lib/buffs.php");
	
	if($path==false && $summons['preta_path']==false){
		if ($points > 0) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if ($points > 0) specialtysystem_addfightnav("`@Summon Preta Path","summon_preta_path&cost=1&path=preta",1);
		
	} elseif($path) {
		if ($points > 2) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		//if (has_buff('jutsucreatures') && $points > 2) specialtysystem_addfightnav(array("`@Fūjutsu Kyūin(%s)",$buffs['name']),"fujutsu&cost=3&path=preta",3);
		if ($points > 2) specialtysystem_addfightnav(array("`@Fūjutsu Kyūin(%s)",$buffs['name']),"fujutsu&cost=3&path=preta",3);
		
		if ($points >4) specialtysystem_addfightnav("`@Chakura Kyūin Jutsu","chakura&cost=5&path=preta",5);
	}
	
	return;
}

function preta_path_apply($skill) {

	global $session;
	
	require_once("modules/rinnegan/functions.php");
	$companions=check_paths();
	if ($companions['preta_path']==false&&$skill!='summon_preta_path') {
		output("`\$Can't do that, you don't have the body anymore!");
		return false;
	}

	switch ($skill) {
		
		case "fujutsu":
			require_once("lib/buffs.php");
			output("`\$You try to remove jutsus the enemy uses!`n");
			strip_buff('jutsucreatures'); //Can only remove multi round buffs, not perfect bit it will do.
			break;
		case "chakura":
			apply_buff('chakura',array(
				"startmsg"=>"`@`b`iChakura Kyūin Jutsu!`b`i - `xPreta Path grabs on to {badguy}, and starts draining their Chakra.",
				"name"=>"`!Chakura Kyūin Jutsu",
				"rounds"=>3,
				"effectmsg"=>"`@{badguy} is drained of {damage} healthpoints.",
				"minbadguydamage"=>$session['user']['level']*2,
				"maxbadguydamage"=>max($session['user']['dragonkills'],16)+$session['user']['level'],
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			break;
		case "summon_preta_path":
			apply_companion('preta_path', array(
							"name"=>"`@Preta Path",
							"jointext"=>"`xYou summon the `@Preta Path`x!",
							"hitpoints"=>round($session['user']['maxhitpoints']*0.45,0),
							"maxhitpoints"=>round($session['user']['maxhitpoints']*0.45,0),
							"attack"=>round($session['user']['attack']*0.45,0),
							"defense"=>round($session['user']['defense']*0.45,0),
							"survivenewday"=>false,
							"abilities"=>array(
								"fight"=>0,
								"heal"=>0,
								"magic"=>0,
								"defend"=>1,
							),
							"ignorelimit"=>true, 
						), true);
			$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));
			$summons['preta_path']=true;
			set_module_pref("pathsused",serialize($summons),"circulum_rinnegan");
			break;
	}
	return true;
}

?>
