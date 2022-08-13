<?php
		if (get_module_pref("user_addimages") != 1) {
			$enemies=@unserialize($session['user']['badguy']);
			if (is_array($enemies) && is_array($enemies['enemies'])) {
				foreach ($enemies['enemies'] as $args) {
					$args=sanitize($args['creaturename']).".gif";
					output_notl("`c<img src=\"modules/battlearena/gladiators/".$args."\" alt='Enemy'>`c",true);
				}
			}
			rawoutput("<br>");
		}
		require("battle.php");
		$session['user']['specialinc'] = "module:battlearena";
		if ($victory){
			if ((bool)get_module_setting("allowspecial") == false){
				unsuspend_buffs("allowinpvp");
				unsuspend_companions("allowinpvp");
			}
			output("`n`7You have beaten `^%s`7.`n",$badguy['creaturename']);
			output("`#The crowd chants \"%s `#%s`#.\"`n",$session['user']['name'],$session['user']['name']);
			output("`6Announcer: %s`6 deals the final blow!",$session['user']['name']);
			addnav("Continue","runmodule.php?module=battlearena&op=win&who=".get_module_pref('who'));
			output("`n`n`3Your health: `n");
			$bar="";
			for ($i=0;$i<1;$i+=.02){
				$bar.="<img src=\"modules/battlearena/images/chart3.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 2px;\">";
				}
			output_notl("%s",$bar,true);
			output_notl("`n");
			$bar="";
			for ($i=0;$i<1;$i+=.02){
				if ($session['user']['hitpoints'] > $session['user']['maxhitpoints'] * $i){
					$bar.="<img src=\"modules/battlearena/images/chart.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 12px;\">";
					}
				}
			output_notl("%s",$bar,true);
			output_notl("`n");
			$bar="";
			output("`n%s`3 health: `n",$badguy['creaturename']);
			for ($i=0;$i<1;$i+=.02){
				$bar.="<img src=\"modules/battlearena/images/chart3.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 2px;\">";
				}
			output_notl("%s",$bar,true);
			output_notl("`n`n");
			$badguy=array();
			$session['user']['badguy']="";
			$session['user']['specialinc']="";
			set_module_pref('healthtemp', 0);
		} elseif ($defeat){
			if ((bool)get_module_setting("allowspecial") == false){
				require_once("lib/battle-skills.php");
				require_once("lib/extended-battle.php");
				unsuspend_buffs("allowinpvp");
				unsuspend_companions("allowinpvp");
			}
			if (is_module_active('abc.php')) 
				blocknav("runmodule.php?module=abc&op=res");
			//just to let the Aravis Talismans not work here
			output("`n`7You have been beaten by `^%s `7.`n",$badguy['creaturename']);
			output("`#The crowd chants \"%s `#%s`#.\"`n",$badguy['creaturename'],$badguy['creaturename']);
			output("`6Announcer: %s`6 deals the final blow!",$badguy['creaturename']);
			$session['user']['hitpoints']=1;
			$who=$badguy['creaturename'];
			addnav("Continue","runmodule.php?module=battlearena&op=loose&who=".get_module_pref('who'));
			output("`n`n`3Your health: `n");
			for ($i=0;$i<1;$i+=.02){
				$bar.="<img src=\"modules/battlearena/images/chart3.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 2px;\">";
			}
			output_notl("%s",$bar,true);
			output_notl("`n`n");
			$bar="";
			output("`n%s`3 health: `n",$badguy['creaturename']);
			for ($i=0;$i<1;$i+=.02){
				$bar.="<img src=\"modules/battlearena/images/chart3.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 2px;\">";
				}
			output_notl("%s",$bar,true);
			output_notl("`n");
			$bar="";
			for ($i=0;$i<1;$i+=.02){
				if ($badguy['creaturehealth'] > get_module_pref('healthtemp') * $i){
					$bar.="<img src=\"modules/battlearena/images/chart2.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 12px;\">";
				}
			}
			output_notl("%s",$bar,true);
			$session['user']['specialinc']="";
			set_module_pref('healthtemp',0);
		} else {
			require_once("lib/fightnav.php");
			fightnav((bool)get_module_setting("allowspecial"),false,"runmodule.php?module=battlearena");
			output_notl("`n");
			switch(e_rand(1,11)){
				case 1:
				output("`b%s`4 tries to take a cheap shot.`b`n",$badguy['creaturename']);
				break;
				case 4:
				output("`b%s`4 snarles at you.`b`n",$badguy['creaturename']);
				break;
				case 5:
				output("`b%s`4 tries to bite your ear off!`b`n",$badguy['creaturename']);
				break;
				case 6:
				output("`b%s`4 calls you a wimp!`b`n",$badguy['creaturename']);
				break;
				case 7:
				break;
				case 8:
				output("`b%s`4 says your granny fights better!`b`n",$badguy['creaturename']);
				break;
				case 9:
				output("`b%s`4 says you fight like a child!`b`n",$badguy['creaturename']);
				break;
				case 10:
				output("`b%s`4 says your ugly and your mommy dresses you funny!`b`n",$badguy['creaturename']);
				break;
			}
			switch(e_rand(1,15)){
					case 1:
					output("`#The crowd roars with delight!`n");
					break;
					case 2:
					output("`#The crowd chants \"%s `#%s`#.\"`n",$session['user']['name'],$session['user']['name']);
					break;
					case 3:
					output("`#The crowd chants \"%s `#%s`#.\"`n",$badguy['creaturename'],$badguy['creaturename']);
					break;
					case 4:
					output("`#The crowd Goes Silent.`n");
					break;
					case 5:
					output("`#The crowd is getting excited!`n");
					break;
					case 6:
					output("`#The crowd does the Wave.`n");
					break;
					case 7:
					output("`#The tension builds.`n");
					break;
					case 8:
					output("`#The crowd chants \"down with %s `#\".`n",$badguy['creaturename']);
					break;
					case 9:
					output("`#The crowd chants \"down with %s `#\".`n",$session['user']['name']);
					break;
					case 10:
					output("`#The crowd gets into the action!`n A few of them fall into the arena, only to	 drug off by an arena guard.`n");
					break;
					case 11:
					output("`#The crowd screams \"finish him, finish him\".`n");
					break;
					case 12:
					output("`#The crowds sceams loudly at that last blow!`n");
					break;
					case 13:
					output("`#The crowd surges forward.`n");
					break;
					case 14:
					output("`#A big fat guy painted red hops up and does a dance.`n");
					break;
					case 15:
					output("`#A fan runs across the arena, %s`# clotheslines him and tosses him in a heap in corner.`n",$badguy['creaturename']);
					break;
					case 15:
					output("`#A fan runs across the arena, you clothesline him and toss him in a heap in a corner.`n");
					break;
			}
			if (!get_module_pref('newfight')){
				output("`6Announcer: ");
				if (get_module_pref('health') > $session['user']['hitpoints']) output("`6Ouch %s`6 hits %s`6 for %s hitpoints!`n",$badguy['creaturename'],$session['user']['name'],(get_module_pref('health') - $session['user']['hitpoints']));
				if (get_module_pref('health') == $session['user']['hitpoints']) output("%s`6 swings at %s`6 but misses!`n",$badguy['creaturename'],$session['user']['name']);
				output("`6Announcer: ");
				if (get_module_pref('crhealth') > $badguy['creaturehealth']) output("`6Ouch %s`6 hits %s`6 for %s hitpoints!`n",$session['user']['name'],$badguy['creaturename'],(get_module_pref('crhealth') - $badguy['creaturehealth']));
				if (get_module_pref('crhealth') == $badguy['creaturehealth']) output("%s`6 swings at %s`6 but misses!`n",$session['user']['name'],$badguy['creaturename']);
			}else{
				output("`6Announcer: ");
				output("`6Our two contenders %s`6 and %s`6 square off.`n",$session['user']['name'],$badguy['creaturename']);
			}
			set_module_pref('newfight',false);
			set_module_pref('health',min($session['user']['hitpoints'],$session['user']['maxhitpoints']));
			set_module_pref('crhealth',$badguy['creaturehealth']);
	    	output("`n`n`3Your health: `n");
			$bar="";
			for ($i=0;$i<1;$i+=.02){
				$bar.="<img src=\"modules/battlearena/images/chart3.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 2px;\">";
				}
			output_notl("%s",$bar,true);
			output_notl("`n");
			$bar="";
			for ($i=0;$i<1;$i+=.02){
			if ($session['user']['hitpoints'] > $session['user']['maxhitpoints'] * $i){
				$bar.="<img src=\"modules/battlearena/images/chart.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 12px;\">";
				}
			}
			output_notl("%s",$bar,true);
			output_notl("`n");
			$bar="";
			output("`n%s`3 health: `n",$badguy['creaturename']);
			for ($i=0;$i<1;$i+=.02){
			$bar.="<img src=\"modules/battlearena/images/chart3.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 2px;\">";
			}
			output_notl("%s",$bar,true);
			output_notl("`n");
			$bar="";
			$bar="";
			for ($i=0;$i<1;$i+=.02){
			if ($badguy['creaturehealth'] > get_module_pref('healthtemp') * $i){
				$bar.="<img src=\"modules/battlearena/images/chart2.gif\" title=\"\" alt=\"\" style=\"width: 4px; height: 12px;\">";
				}
			}
			output_notl("%s",$bar,true);
		}
?>
