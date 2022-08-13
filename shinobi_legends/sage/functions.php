<?php

function check_toads() {
	global $companions;
	$toads = array("toad_gamatatsu", "toad_gamakichi", "toad_gamahiro", "toad_gamaken", "toad_gamabunta", "toad_shima", "toad_fukasaku", "toad_gamakiri");
	$return = array("toad_gamatatsu"=>false,
				"toad_gamakichi"=>false,
				"toad_gamahiro"=>false,
				"toad_gamaken"=>false,
				"toad_gamabunta"=>false,
				"toad_shima"=>false,
				"toad_fukasaku"=>false,
				"toad_gamakiri"=>false
				);
	foreach($companions as $name=>$companion) {
		
		if(in_array($name,$toads)) {
			$return[$name]=true;
		}
		
	}
	return $return;
}

function summon_toad($toad) {
	global $session;
	$pers=4;//get_module_pref("stack","circulum_sage");
		
	$status = true;
		
	$toads = array(
		'gamatatsu'=>array(
				"name"=>"`tG`qa`tma`qt`tat`qs`tu",
				"attack"=>$session['user']['level']+min($pers,2)*2,
				"defense"=>$session['user']['level']+min($pers,2)*2,
				"hitpoints"=>$session['user']['level']*10+(5*min($pers,2)),
				"maxhitpoints"=>$session['user']['level']*10+(5*min($pers,2)),
				"companionactive"=>1,
				"jointext"=>'`b`i`jKuchiyose no Jutsu`i`b `2- `@The small Toad, `tG`qa`tma`qt`tat`qs`tu, `@appears in a puff of smoke.',
				"cannotdie"=>0,
				"cannotbehealed"=>1,
				"dyingtext"=>"`tG`qa`tma`qt`tat`qs`tu`@, disappears in a puff of smoke. `j*Poof*",
				"expireafterfight"=>0,
				"ignorelimit"=>true,
				"abilities"=>array(
					"fight"=>1,
					"heal"=>0,
					"magic"=>0,
					"defend"=>0,
					),
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			),
		'gamakichi'=>array(
				"name"=>"`qG`5a`qm`5ak`qic`5h`qi",
				"attack"=>$session['user']['level']+min($pers,3)*3,
				"defense"=>$session['user']['level']+min($pers,3)*3,
				"hitpoints"=>$session['user']['level']*10+(10*min($pers,3)),
				"maxhitpoints"=>$session['user']['level']*10+(10*min($pers,3)),
				"companionactive"=>1,
				"jointext"=>'`b`i`jKuchiyose no Jutsu`i`b `2- `@You summon the young Toad, `qG`5a`qm`5ak`qic`5h`qi`@.',
				"cannotdie"=>0,
				"cannotbehealed"=>1,
				"dyingtext"=>"`qG`5a`qm`5ak`qic`5h`qi`@, disappears in a puff of smoke. `j*Poof*",
				"expireafterfight"=>0,
				"ignorelimit"=>true,
				"abilities"=>array(
					"fight"=>1,
					"heal"=>0,
					"magic"=>0,
					"defend"=>0,
					),
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			),
		'gamahiro'=>array(
				"name"=>"`kG`3a`km`3a`khi`3r`ko",
				"attack"=>$session['user']['level']+8+min($pers,3),
				"defense"=>$session['user']['level']+5+min($pers,3),
				"hitpoints"=>$session['user']['level']*10+$session['user']['dragonkills']*min($pers,3),
				"maxhitpoints"=>$session['user']['level']*10+$session['user']['dragonkills']*min($pers,3),
				"companionactive"=>1,
				"jointext"=>'`b`i`jKuchiyose no Jutsu`i`b `2- `@You summon the large, dual sword-wielding Toad warrior, `kG`3a`km`3a`khi`3r`ko`@.',
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
			),
		'gamaken'=>array(
				"name"=>"`4G`~a`4mak`~e`4n",
				"attack"=>$session['user']['level']+5+min($pers,3),
				"defense"=>$session['user']['level']+8+min($pers,3),
				"hitpoints"=>$session['user']['level']*10+$session['user']['dragonkills']*min($pers,3),
				"maxhitpoints"=>$session['user']['level']*10+$session['user']['dragonkills']*min($pers,3),
				"companionactive"=>1,
				"jointext"=>'`b`i`jKuchiyose no Jutsu`i`b `2- `@You summon the large, shield holding Toad, `4G`~a`4mak`~e`4n`@.',
				"cannotdie"=>0,
				"cannotbehealed"=>1,
				"dyingtext"=>"`4G`~a`4mak`~e`4n`@, disappears in a puff of smoke. `j*Poof*",
				"expireafterfight"=>0,
				"ignorelimit"=>true,
				"abilities"=>array(
					"fight"=>1,
					"heal"=>0,
					"magic"=>0,
					"defend"=>1,
					),
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			),
		'gamabunta'=>array(
				"name"=>"`qG`\$a`qm`\$ab`qun`\$t`qa",
				"attack"=>$session['user']['level']+7+min($pers,3),
				"defense"=>$session['user']['level']+7+min($pers,3),
				"hitpoints"=>$session['user']['level']*12+$session['user']['dragonkills']*min($pers,3),
				"maxhitpoints"=>$session['user']['level']*12+$session['user']['dragonkills']*min($pers,3),
				"companionactive"=>1,
				"jointext"=>'`b`i`jKuchiyose no Jutsu`i`b `2- `@You summon the Mighty Toad boss, `qG`$a`qm`$ab`qun`$t`qa`@.',
				"cannotdie"=>0,
				"cannotbehealed"=>1,
				"dyingtext"=>"`qG`\$a`qm`\$ab`qun`\$t`qa`@, disappears in a puff of smoke. `j*Poof*",
				"expireafterfight"=>0,
				"ignorelimit"=>true,
				"abilities"=>array(
					"fight"=>1,
					"heal"=>0,
					"magic"=>0,
					"defend"=>1,
					),
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			),
		'shima'=>array(
				"name"=>"`KS`%him`Ka",
				"attack"=>$session['user']['level']+$pers,
				"defense"=>$session['user']['level']+2+$pers,
				"hitpoints"=>$session['user']['level']*10+$session['user']['dragonkills'],
				"maxhitpoints"=>$session['user']['level']*10+$session['user']['dragonkills'],
				"companionactive"=>1,
				"jointext"=>'`b`i`jKuchiyose no Jutsu`i`b `2- `@You summon the female Elder Toad `KS`%him`Ka`@.',
				"cannotdie"=>0,
				"cannotbehealed"=>1,
				"dyingtext"=>"`KS`%him`Ka`@, disappears in a puff of smoke. `j*Poof*",
				"expireafterfight"=>0,
				"ignorelimit"=>true,
				"abilities"=>array(
					"fight"=>1,
					"heal"=>0,
					"magic"=>0,
					"defend"=>1,
					),
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			),
		'fukasaku'=>array(
				"name"=>"`YF`ju`@k`jas`@a`jk`Yu",
				"attack"=>$session['user']['level']+2+$pers,
				"defense"=>$session['user']['level']+$pers,
				"hitpoints"=>$session['user']['level']*10+$session['user']['dragonkills'],
				"maxhitpoints"=>$session['user']['level']*10+$session['user']['dragonkills'],
				"companionactive"=>1,
				"jointext"=>'`b`i`jKuchiyose no Jutsu`i`b `2- `@You summon the male Elder Toad `YF`ju`@k`jas`@a`jk`Yu`@.',
				"cannotdie"=>0,
				"cannotbehealed"=>1,
				"dyingtext"=>"`YF`ju`@k`jas`@a`jk`Yu`@, disappears in a puff of smoke. `j*Poof*",
				"expireafterfight"=>0,
				"ignorelimit"=>true,
				"abilities"=>array(
					"fight"=>1,
					"heal"=>0,
					"magic"=>0,
					"defend"=>1,
					),
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			),
		'gamakiri'=>array(
			"name"=>"`@G`Ra`@m`Qak`@i`Rr`@i",
			"attack"=>$session['user']['level']*5,
			"defense"=>$session['user']['level']*5,
			"hitpoints"=>$basehp,
			"maxhitpoints"=>$basehp,
			"companionactive"=>1,
			"jointext"=>'`b`i`jKuchiyose no Jutsu`i`b `2- `@Oh no... something went wrong. You summoned the cross-dressing Toad, `@G`Ra`@m`Qak`@i`Rr`@i`@.',
			"cannotdie"=>0,
			"cannotbehealed"=>1,
			"dyingtext"=>"`@G`Ra`@m`Qak`@i`Rr`@i`@, disappears in a puff of smoke. `j*Poof*",
			"expireafterfight"=>0,
			"ignorelimit"=>true,
			"abilities"=>array(
				"fight"=>1,
				"heal"=>0,
				"magic"=>0,
				"defend"=>0,
				),
			"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			));
		
	if($pers<3 && $session['user']['dragonkills']<11){
		if(e_rand(1,20)==1){
			$toad = 'gamariki';
			$status = false;
		}
	}	
		
	if ($toad != 'elders'){
		$startmsg = $toads[$toad]['jointext'];
		apply_companion("toad_$toad",$toads[$toad],true);
	} elseif ($toad == 'elders') {
		$startmsg = "`b`i`jKuchiyose no Jutsu`i`b `2- `@You summon the Elder Toads `KS`%him`Ka `@and `YF`ju`@k`jas`@a`jk`Yu`@.";
		apply_companion("toad_shima",$toads['shima'],true);
		apply_companion("toad_fukasaku",$toads['fukasaku'],true);
	}	
		
	apply_buff('summon',array(
				"startmsg"=>$startmsg,
				"name"=>"`jKuchiyose no Jutsu",
				"rounds"=>1,
				"defmod"=>0.9,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			));
			
	return $status;
}

function sage_mode($method) {	
	global $session;
	$pers=get_module_pref("stack","circulum_sage");
	$buffs=$session['bufflist'];
	$rounds=0;
	$ryosei=false;
	$used=get_module_pref("bonus","circulum_sage");
	$bonusmsg='.';	
		
	switch ($method){
		case 'gather':
			if (isset($buffs['kekkei_genkai_sage_gather'])) {
				$charged=30-$buffs['kekkei_genkai_sage_gather']['rounds'];
				$rounds=min(20,$charged)*$pers;
				strip_buff('kekkei_genkai_sage_gather');
				$startmsg='`@You take the natural chakra you gather to activate Sage Mode, ';
			} else {
				return "`\$You have no natural chakra gathered.";
			}
			break;
		case 'ryosei':
			$toadi=check_toads();
			if ($toadi['toad_shima'] &&  $toadi['toad_fukasaku']){
				$rounds=15*$pers+5;
				$ryosei=true;
				$companions=unserialize($session['user']['companions']);
				if (isset($companions['toad_shima'])) {
					apply_companion("toad_shima",array( //Only way I could get this to work.
						"expireafterfight"=>1,
						"ignorelimit"=>true,
						"abilities"=>array(
							"fight"=>1,
							"heal"=>0,
							"magic"=>0,
							"defend"=>1,
							),
						"schema"=>"module-specialtysystem_kekkei_genkai_sage"
					),true);;
				}
				if (isset($companions['toad_fukasaku'])) {
					apply_companion("toad_fukasaku",array(
						"expireafterfight"=>1,
						"ignorelimit"=>true,
						"abilities"=>array(
							"fight"=>1,
							"heal"=>0,
							"magic"=>0,
							"defend"=>1,
							),
						"schema"=>"module-specialtysystem_kekkei_genkai_sage"
					),true);;
				}
				$startmsg='`KS`%him`Ka `@and `YF`ju`@k`jas`@a`jk`Yu`@ fuse to your shoulders, activating Sage Mode, ';
			} else {
				return "`\$You don't have Both Shima and Fukasaku to help you!";
			}
			break;
		case 'clone':
			if (get_module_pref("clone","circulum_sage")){
				if (isset($buffs['kekkei_genkai_sage_mode'])) $bonus=$buffs['kekkei_genkai_sage_mode']['rounds'];
				$rounds=15*$pers+$bonus;
				set_module_pref("clone",0,"circulum_sage");
				if (isset($bonus)) $startmsg="`@You disperse the clone, giving you an extra ".$rounds." rounds in Sage Mode, ";
				else $startmsg='`@You disperse the clone, which fills you with Natural Chakra, activating Sage Mode, ';
			} else {
				return "`\$You have no clone to poof!";
			}
			break;
	} 
		
	if ($used==0){
		$increase=($pers*5-5);
		increment_module_pref("uses",-$increase,"specialtysystem");
		set_module_pref("bonus",1,"circulum_sage");
		$bonusmsg="`@, giving you `\$".$increase." `@extra chakra points for today!";
	}
		
	if ($pers<5) {
		$msg='though you look a little toad like still';
		$title='Imperfect';
	} else {
		$msg='showing only your Toad eyes as a sign of your Mastery of the skill';
		$title='Perfect';
	}
		
	apply_buff('kekkei_genkai_sage_mode',array(
				"startmsg"=>$startmsg." ".$msg.$bonusmsg,
				"name"=>array("`@%s Sennin Modo", $title),
				"rounds"=>$rounds,
				"ryosei"=>$ryosei,
				"tempstat-strength"=>$pers-2,
				"tempstat-dexterity"=>$pers*0.5-1,
				"tempstat-constitution"=>$pers*0.5-1,
				"tempstat-speed"=>$pers-2,
				"tempstat-intelligence"=>max(0,$pers-4),
				"tempstat-wisdom"=>max(0,$pers*0.5-4),
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			));
	if ($pers>4){
		apply_buff('kekkei_genkai_sage_katas',array(
				"startmsg"=>"`@Your body is surrounded in Natural Chakra which increases your attack and defence!",
				"name"=>"`2K`@awazu `2K`@umite!",
				"rounds"=>$rounds,
				"atkmod"=>2.5,
				"defmod"=>1.5,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			));
	}	
	return '';
}

?>
