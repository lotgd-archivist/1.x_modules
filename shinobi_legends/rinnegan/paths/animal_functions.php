<?php

function get_animal_fightnavs($points,$path){
	
	global $companions;
	$name = translate_inline("`Q畜生道, Chikushōdō (Animal Path)");
	tlschema('module-specialtysystem_kekkei_genkai_rinnegan_animal');
	
	$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));
		
	$creatures = array("animal_attack","animal_defend","animal_zofuku");
	$summoned = array("animal_attack"=>false,
				"animal_defend"=>false,
				"animal_zofuku"=>false,
				);
	foreach ($companions as $creature=>$companion) {
		
		if (in_array($creature,$creatures)) {
			$summoned[$creature]=true;
		}
		
	}
	
	if($path==false && $summons['animal_path']==false){
		if ($points > 0) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if ($points > 0) specialtysystem_addfightnav("`QSummon Animal Path","summon_animal_path&cost=1&path=animal",1);
		
	} elseif($path) {
		if ($points > 6) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if(!$summoned['animal_attack']){
			if ($points > 6) specialtysystem_addfightnav("`QKuchiyose no Jutsu(Attack)","attack&cost=7&path=animal",7);
		}
		
		if(!$summoned['animal_defend']){
			if ($points > 6) specialtysystem_addfightnav("`QKuchiyose no Jutsu(Defense)","defense&cost=7&path=animal",7);
		}
		
		if(!$summoned['animal_zofuku']){
			if ($points > 13) specialtysystem_addfightnav("`QZōfuku Kuchiyose no Jutsu","zofuku&cost=14&path=animal",14);
		}
		
	}
	
	return;
}

function animal_path_apply($skill) {

	global $session;
	
	require_once("modules/rinnegan/functions.php");
	$companions=check_paths();
	if ($companions['animal_path']==false&&$skill!='summon_animal_path') {
		output("`\$Can't do that, you don't have body anymore!");
		return false;
	}
	
	switch ($skill) {
	
		case "attack":
			$attackers=array("bird"=>array("name"=>"`QGiant Drill Beaked Bird", "jointext"=>"`Q`b`iKuchiyose no Jutsu!`b`i -`x You summon a Giant Bird with a huge drill like beak, and `%R`Vinnegan `xeyes."),
							"ox"=>array("name"=>"`QGiant Ox", "jointext"=>"`Q`b`iKuchiyose no Jutsu!`b`i -`x Animal Path summons a Giant Ox like creature, with huge horns and `%R`Vinnegan `xeyes."), 
							"rhino"=>array("name"=>"`QGiant Rhino", "jointext"=>"`Q`b`iKuchiyose no Jutsu!`b`i -`x Animal Path summons a Giant Rhino, with a large horn and `%R`Vinnegan `xeyes."), 
							"crustacean"=>array("name"=>"`QGiant Crustacean", "jointext"=>"`Q`b`iKuchiyose no Jutsu!`b`i -`x Animal Path summons a large Crustacean, with huge claws and `%R`Vinnegan `xeyes."));
			$attacker=$attackers[array_rand($attackers)];
			apply_companion('animal_attack', array(
							"name"=>$attacker['name'],
							"jointext"=>$attacker['jointext'],
							"hitpoints"=>round($session['user']['maxhitpoints']*0.55,0),
							"maxhitpoints"=>round($session['user']['maxhitpoints']*0.55,0),
							"attack"=>round($session['user']['attack']*0.75,0),
							"defense"=>round($session['user']['defense']/3,0),
							"abilities"=>array(
								"fight"=>1,
								"heal"=>0,
								"magic"=>0,
								"defend"=>0,
							),
							"ignorelimit"=>true, 
						), true);
			break;
		case "defense":
			$defenders=array("chameleon"=>array("name"=>"`QGiant Snake-Tailed Chameleon","jointext"=>"`Q`b`iKuchiyose no Jutsu!`b`i -`x Animal Path summons a Giant Chameleon, with a Snake like tail, and `%R`Vinnegan `xeyes."), 
							"panda"=>array("name"=>"`QGiant Panda","jointext"=>"`Q`b`iKuchiyose no Jutsu!`b`i -`x Animal Path summons a Giant Panda, with rock like skin, and `%R`Vinnegan `xeyes."), 
							"centipede"=>array("name"=>"`QGiant Centipede","jointext"=>"`Q`b`iKuchiyose no Jutsu!`b`i -`x Animal Path summons a Giant Centipede, with incredible length and `%R`Vinnegan `xeyes."));
			$defender=$defenders[array_rand($defenders)];
			apply_companion('animal_defend', array(
							"name"=>$defender['name'],
							"jointext"=>$defender['jointext'],
							"hitpoints"=>round($session['user']['maxhitpoints']*0.75,0),
							"maxhitpoints"=>round($session['user']['maxhitpoints']*0.75,0),
							"attack"=>round($session['user']['attack']/3,0),
							"defense"=>round($session['user']['defense']*0.75,0),
							"abilities"=>array(
									"fight"=>0,
									"heal"=>0,
									"magic"=>0,
									"defend"=>1,
							),
							"ignorelimit"=>true, 
						), true);
			break;
		case "zofuku":
			apply_companion('animal_zofuku', array(
							"name"=>"`QGiant Multi-Headed Dog",
						//	"jointext"=>"`Q`b`iZōfuku Kuchiyose no Jutsu!`b`i -`xAnimal Path summons a Giant Dog, with many head, each containing `%R`Vinnegan `xeyes.",
							"jointext"=>"... (howls)...",
							"hitpoints"=>round($session['user']['maxhitpoints']*65,0),
							"maxhitpoints"=>round($session['user']['maxhitpoints']*65,0),
							"attack"=>round($session['user']['attack']*70+10,0),
							"defense"=>round($session['user']['defense']*60+10,0),
							"abilities"=>array(
								"fight"=>1,
								"heal"=>0,
								"magic"=>0,
								"defend"=>1,
							),
							"ignorelimit"=>true, 
						), true);
			break;
		case "summon_animal_path":
			apply_companion('animal_path', array(
							"name"=>"`QAnimal Path",
							"jointext"=>"`xYou summon the `QAnimal Path`x!",
							"hitpoints"=>max(round($session['user']['maxhitpoints']/2,0)-10,10),
							"maxhitpoints"=>max(round($session['user']['maxhitpoints']/2,0)-10,10),
							"attack"=>round($session['user']['attack']/3,0),
							"defense"=>round($session['user']['defense']/3,0),
							"abilities"=>array(
								"fight"=>0,
								"heal"=>0,
								"magic"=>0,
								"defend"=>1,
							),
							"ignorelimit"=>true, 
						), true);
			$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));
			$summons['animal_path']=true;
			set_module_pref("pathsused",serialize($summons),"circulum_rinnegan");
			break;
	}
	return true;
}

?>
