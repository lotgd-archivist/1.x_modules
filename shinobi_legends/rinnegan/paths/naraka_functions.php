<?php

function get_naraka_fightnavs($points,$path){
	
	global $companions;
	$name = translate_inline("`\$地獄道, Jigokudō (Naraka Path)");
	tlschema('module-specialtysystem_kekkei_genkai_rinnegan_naraka');
	
	$summons=unserialize(get_module_pref("pathsused","circulum_rinnegan"));
	debug($summons);

	require_once("lib/buffs.php");
	
	if($path==false && $summons['naraka_path']==false){
		if ($points > 0) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if ($points > 0) specialtysystem_addfightnav("`\$Summon Naraka Path","summon_naraka_path&cost=1&path=naraka",1);
		
	} elseif($path) {
		if ($points > 1) specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_rinnegan"));
		if ($points > 1&&!has_buff("outer_path")) specialtysystem_addfightnav("`\$Summon Outer Path","summon_outer&cost=2&path=naraka",2);
		
		if ($points>2&&has_buff("outer_path")){
			
			$paths = array("deva_path","animal_path","asura_path","preta_path","human_path");
			foreach ($companions as $name=>$companion) {
				
				if (in_array($name,$paths)) {
					if ($companion['hitpoints']<$companion['maxhitpoints']) specialtysystem_addfightnav(array("`\$Heal %s",$companion['name']),"heal&cost=3&path=naraka&healed=$name&action=heal",3);
				}		
			}			
		}
		
		if ($points>4&&has_buff("outer_path")){
			
			unset($summons['naraka_path']);
			$coloured = array("deva_path"=>"`!Deva Path",
							"animal_path"=>"`QAnimal Path",
							"asura_path"=>"`7Asura Path",
							"preta_path"=>"`@Preta Path",
							"human_path"=>"`THuman Path");
				
			foreach ($summons as $name=>$status) {
				debug($name." ".$status);
				if ($status==true&&!in_array($name,array_keys($companions))) specialtysystem_addfightnav(array("`\$Revive %s",$coloured[$name]),"heal&cost=5&path=naraka&healed=$name&action=revive",5);		
			}			
		}
		
	}
	
	return;
}

function naraka_path_apply($skill) {

	global $session;
	$pers=get_module_pref("stack","circulum_rinnegan");
	$healed=httpget('healed');
	$action=httpget('action');
	
	require_once("modules/rinnegan/functions.php");
	$companions=check_paths();
	if ($companions['naraka_path']==false&&$skill!='summon_naraka_path') {
		output("`\$Can't do that, you don't have body anymore!");
		return false;
	}

	switch ($skill) {
	
		case "summon_outer":
			apply_buff('outer_path',array(
				"startmsg"=>"`\$`b`iOuter Path!`b`i - `xNaraka Path summons the Seventh Path, the Outer Path, which appears as a large demonic head with `%R`Vinnegan `xeyes.",
				"name"=>"`\$Outer Path",
				"rounds"=>10,
				"roundmsg"=>"The Outer Path is present, purple flames flickering around it.",
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan"
			));
			break;
		case "heal":
			require_once("lib/buffs.php");
			if (!has_buff('outer_path')) {
				output("`\$You need the Outer Path to do that!");
				return false;
			}			
			if ($action=='heal'&&!$companions[$healed]) {
				output("`\$You can't heal a body that's already dead! Better Revive it!");
				return false;
			}
			//This way actually works better than trying to get in there and healing, plus it works for both healing and reviving.
			$fname=$healed."_apply";
			$required=substr_replace($healed, '_functions.php', -5);
			$heal="summon_".$healed;
			require_once("modules/rinnegan/paths/".$required);
			$fname($heal);
			break;
		case "summon_naraka_path":
			apply_companion('naraka_path', array(
							"name"=>"`\$Naraka Path",
							"jointext"=>"`xYou summon the `\$Naraka Path`x!",
							"hitpoints"=>round($session['user']['maxhitpoints']*0.4,0),
							"maxhitpoints"=>round($session['user']['maxhitpoints']*0.4,0),
							"attack"=>round($session['user']['attack']*0.3,0),
							"defense"=>round($session['user']['defense']*0.4,0),
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
			$summons['naraka_path']=true;
			set_module_pref("pathsused",serialize($summons),"circulum_rinnegan");
			break;
	}
	return true;
}

?>