<?php

function get_human_fightnavs($points,$path){
	
	$name = translate_inline("`T人間道, Ningendō (Human Path)");
	tlschema('module-specialtysystem_kekkei_genkai_rinnegan');

	$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));

	require_once("lib/buffs.php");
	
	if($path==false && $summons['human_path']==false){
		if ($points > 0) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if ($points > 0) specialtysystem_addfightnav("`TSummon Human Path","summon_human_path&cost=1&path=human",1);
		
	} 
	
	return;
}

function human_path_apply($skill) {

	global $session;

	switch ($skill) {
	
		case "summon_human_path":
			apply_companion('human_path', array(
							"name"=>"`THuman Path",
							"jointext"=>"`xYou summon the `THuman Path`x!",
							"hitpoints"=>round($session['user']['maxhitpoints']/2,0)+10,
							"maxhitpoints"=>round($session['user']['maxhitpoints']/2,0)+10,
							"attack"=>round($session['user']['attack']/2,0),
							"defense"=>round($session['user']['defense']/2,0),
							"survivenewday"=>false,
							"abilities"=>array(
								"fight"=>1,
								"heal"=>0,
								"magic"=>0,
								"defend"=>1,
							),
							"ignorelimit"=>true, 
						), true);
			$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));
			$summons['human_path']=true;
			set_module_pref("pathsused",serialize($summons),"circulum_rinnegan");
			break;
	}
	return true;
}

?>