<?php
		$number=(httpget('who'));
		set_module_pref('who',$number);
		set_module_pref('entryhealth',$session['user']['hitpoints']);
		//set up the roots
		$modify=2*sqrt($number);
		$badguy = array("creaturename"=>$gladiators[$number]['name']."`0"
						,"creaturelevel"=>$gladiators[$number]['level']
      					,"creatureweapon"=>$gladiators[$number]['weapon']
      					,"creatureattack"=>1+$number+($modify*3)//70+($number*5)
      					,"creaturedefense"=>1+$number+($modify*3)//70+($number*5)
      					,"creaturehealth"=>120+($number*11)
      					,"creaturegold"=>0
      					,"diddamage"=>0);
		//and now buff him up a bit
		if ($number>6) {

			$badguy['creaturehealth']+=e_rand(1,180)+$session['user']['hitpoints'];
			$badguy['creatureattack']+=e_rand(2,12);
			$badguy['creaturedefense']+=e_rand(2,12);
			if ($number>7) {
				$badguy['creaturelevel']+=e_rand(1,2);
				if ($badguy['creaturelevel'] == $gladiators[$number]['level']+2) output("`\$`b%s`\$ levels up!`b`n",$gladiators[$number]['name']);
				$badguy['creaturehealth']+=100*($modify-7);
				if ($badguy['creatureattack'] < $session['user']['attack']) $badguy['creatureattack'] = ($session['user']['attack'] + e_rand(5,10));
				if ($badguy['creaturehealth'] < $session['user']['hitpoints']) $badguy['creaturehealth'] = ($session['user']['hitpoints'] + e_rand(5,75));
			}
		} else {
			$badguy['creaturehealth']+=e_rand(1,50+($number*5));
			$badguy['creaturelevel']+=1;
			$badguy['creatureattack']+=5;
		}
	$badguy['creaturehealth']=round($badguy['creaturehealth'],0);
	if (e_rand(0,1)) $badguy=array_merge($badguy,array("hidehitpoints"=>1));
    	$session['user']['badguy']=createstring($badguy);
		//Opponent is now set up
		$skill = httpget('skill');
		if ($skill!="" && (bool)get_module_setting("allowspecial") == false){
		output("Your honor prevents you from using any special ability");
		$skill="";
		httpset('skill', $skill);
		}
		set_module_pref('crhealth',$badguy['creaturehealth']);
		output("`#You are led down to the battle arena, and literally thrown in.`n");
		output("`#The crowd roars with delight as you are thrown into the arena.`n");
		output("%s `#comes at you in a fury, and the battle begins.`n",$badguy['creaturename']);
		set_module_pref('healthtemp', $badguy['creaturehealth']);
		if ((bool)get_module_setting("allowspecial") == false){
			require_once("lib/battle-skills.php");
			require_once("lib/extended-battle.php");			
			suspend_buffs("allowinpvp");
			suspend_companions("allowinpvp");
		}
?>
