<?php

function get_asura_fightnavs($points,$path){
	
	$name = translate_inline("`7修羅道, Shuradō (Asura Path)");
	tlschema('module-specialtysystem_kekkei_genkai_rinnegan_asura');
	
	$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));
	
	if($path==false && $summons['asura_path']==false){
		if ($points > 0) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if ($points > 0) specialtysystem_addfightnav("`7Summon Asura Path","summon_asura_path&cost=1&path=asura",1);
		
	} elseif($path) {
		if ($points > 2) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if ($points > 2) specialtysystem_addfightnav("`7Chakra Propulsion Boots","boots&cost=3&path=asura",3);
		
		if ($points >4) specialtysystem_addfightnav("`7Kaiwan no Hiya","fist&cost=5&path=asura",5);
		
		if ($points > 7) specialtysystem_addfightnav("`7Missle Barrage","missle&cost=8&path=asura",8);
		
		if ($points > 9) specialtysystem_addfightnav("`7Laser Explosion","laser&cost=10&path=asura",10);
	}
	
	return;
}

function asura_path_apply($skill) {

	global $session;
	$pers=get_module_pref("stack","circulum_rinnegan");
	
	require_once("modules/rinnegan/functions.php");
	$companions=check_paths();
	if ($companions['asura_path']==false&&$skill!='summon_asura_path') {
		output("`\$Can't do that, you don't have body anymore!");
		return false;
	}

	switch ($skill) {
	
		case "boots":
			apply_buff('boots',array(
				"startmsg"=>"`7`b`iChakra Propulsion Boots!`b`i - `xAsura Path fires chakra from it's feet, sending itself flying at {badguy}.",
				"name"=>"`7Chakra Propulsion Boots",
				"rounds"=>1,
				"effectmsg"=>"`7{badguy} is struck for {damage} damage.",
				"minbadguydamage"=>min($session['user']['dragonkills'],15),
				"maxbadguydamage"=>min(($session['user']['dragonkills']+15),30),
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			break;
		case "fist":
			apply_buff('fist',array(
				"startmsg"=>"`7`b`iKaiwan no Hiya!`b`i - `xAsura Path fires it's fist like a rocket at {badguy}.",
				"name"=>"`7Kaiwan no Hiya",
				"rounds"=>1,
				"effectmsg"=>"`7{badguy} suffers {damage} damage from the strike.",
				"minbadguydamage"=>max($session['user']['dragonkills'],15)+$session['user']['level'],
				"maxbadguydamage"=>max(($session['user']['dragonkills']+10),30)+$session['user']['level']*2,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			break;
		case "missle":
			apply_buff('missle',array(
					"startmsg"=>"`7`b`iMissle Barrage!`b`i - `xAsura Path reveals hidden missle in it's arm, which all start to fire.",
					"name"=>"`7Missle Barrage",
					"rounds"=>1,
					"effectmsg"=>"`7{badguy} is struck by a missle, causing {damage} damage.",
					"minbadguydamage"=>floor($session['user']['level']/2),
					"maxbadguydamage"=>$session['user']['level'],
					"areadamage"=>true,
					"minioncount"=>floor($session['user']['level']/2),
					"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
				));
			break;
		case "laser":
			apply_buff('laser',array(
				"startmsg"=>"`7`b`iLaser Explosion!`b`i - `xAsura Path opens it's head, revealing a cannon which starts charging Chakra to fire at {badguy}.",
				"name"=>"`7Laser Explosion",
				"rounds"=>1,
				"effectmsg"=>"`7{badguy} is blasted by the beam for {damage} damage.",
				"areadamage"=>true,
				"minbadguydamage"=>$session['user']['dragonkills']*$pers-$session['user']['level'],
				"maxbadguydamage"=>$session['user']['dragonkills']*$pers+$session['user']['level'],
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			break;
		case "summon_asura_path":
			apply_companion('asura_path', array(
							"name"=>"`7Asura Path",
							"jointext"=>"`xYou summon the `7Asura Path`x!",
							"hitpoints"=>round($session['user']['maxhitpoints']*0.8,0)+10,
							"maxhitpoints"=>round($session['user']['maxhitpoints']*0.8,0)+10,
							"attack"=>round($session['user']['attack']*0.7,0),
							"defense"=>round($session['user']['defense']*0.8,0),
							"abilities"=>array(
								"fight"=>1,
								"heal"=>0,
								"magic"=>0,
								"defend"=>1,
							),
							"ignorelimit"=>true, 
						), true);
			output_notl("`xYou summon the `7Asura Path`x!");
			$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));
			$summons['asura_path']=true;
			set_module_pref("pathsused",serialize($summons),"circulum_rinnegan");
			break;
	}
	return true;
}

?>
