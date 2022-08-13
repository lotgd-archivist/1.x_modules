<?php
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:sevenstar";
	$op = httpget('op');
	
	switch ($op){
		case "search": case "":
			output("`2As you were searching the forest for a worthy opponent to battle, a `%`imysterious masked shinobi`i `2suddenly appears before you with weapon drawn.`n`n");
			output("`\$The masked shinobi releases some kind of `t`b`ipowerful energy`i`b `\$which seals your chakra.");
			output("`2You quickly reach for your `^%s `2and prepare yourself for a troublesome battle.",$session['user']['weapon']);
			addnav("Battle!",$from."op=battle");
			addnav("Flee!",$from."op=flee");
			break;
		case "flee":
			$session['user']['specialinc'] = "";
			output("`2You turn around and begin sprinting through the forest, trying to find something familiar.");
			break;
		case "touch":
			$session['user']['specialinc'] = "";
			if (is_module_active("alignment")) {
				require_once("modules/alignment/func.php");
				if (!is_good()) {
					output("`2You reach out and touch the Shinobi's back, feeling a `t`b`ilight energy`i`b `2 coming from it.");
					if (is_evil()) {
						output("`n`n`v`bSuddenly, your skin reacts to that `t`ienergy`i`v... and a `~black  `vgust of wind swirls around your hand, wrapping yourself into `)darkness`v, as your `\$evil`v soul's energy gets multiplied...`b`n`n");
						output("`^Unfortunately, your human body is not able to compensate, and you perish instantly... your friend %s`) is eager to see... again.",getsetting('deathoverlord','`$Ramius'));
						$session['user']['gold'] = 0;
						$session['user']['experience'] *= .90;
						output("`\$You are DEAD!`n`nYou lost 10% of experience!");
						$session['user']['alive'] = FALSE;
						$session['user']['hitpoints'] = 0;
						addnav("Return to Shades","shades.php");
						debuglog("Killed by the seven star tattoo");
					} else {
						output("`n`n`v`bSuddenly, your skin reacts to that `t`ienergy`i`v... and a gust of wind swirls around your hand, wrapping yourself into `)darkness`v, as your `^neutral`v soul's energy rejects the tattoo...`b`n`n");
						output("`n`nYou awake a few hours later and feel dizzy... but fortunately you have still power to continue on your journey.");
					}
				break;
				}
			}		
			$check=true;
			if (is_module_active('curse_seal')) $check=(get_module_pref('hasseal','curse_seal')<=0?true:false);
			if (e_rand(1,100) <= get_module_setting("chance") && $check){
				set_module_pref("hastat",1);
				debuglog("obtained the seven star tattoo");
				output("`2You reach out and touch the Shinobi's back, feeling a `t`b`ilight energy`i`b `2coming from it.`n`n");
				output("The tattoo on the Shinobi's back disappears and several odd markings appear on the back of your hand.");
				output("\"`6Who knows about tattoos...?`2\" you think to yourself.");
			}else{
				$session['user']['gold'] = 0;
				$session['user']['experience'] *= .90;
				output("`2You reach out and touch the Shinobi's back and sense a `7`idark energy`i`2 coming out.");
				output("The energy begins to wrap around your body, binding your arms to your side.`n`n");
				output("After struggling for a few minutes, you pass out.");
				output("When you awaken, you find that your gold purse has been nicked and you have some memory loss.");
			}
			break;
	}	
	if ($op == "battle"){
		$seven_star_shinobi = array(
			"creaturename"=>translate_inline("Seven Star Warrior"),
			"creatureweapon"=>translate_inline("Seven Star Katana"),
			"creaturelevel"=>($session['user']['level']+1),
			"creatureattack"=>($session['user']['attack']+$session['user']['level']),
			"creaturedefense"=>($session['user']['defense']+$session['user']['level']),
			"creaturehealth"=>round($session['user']['hitpoints']*1.5),
			"schema"=>"module-sevenstar",
		);
		$session['user']['badguy'] = createstring($seven_star_shinobi);
		$op = "fight";
	}	
	if ($op == "fight"){
		$battle = true;
	}
	if ($battle){
		include("battle.php");
		if ($victory){
			output("`n`\$The masked shinobi drops dead before you.");
			output("`2As you search the body for any possible loot, you notice a strange tattoo on his back.");
			if (!get_module_pref("promise") && !get_module_pref("hastat")){
				addnav("Touch the Tattoo",$from."op=touch");
				addnav("Walk away",$from."op=flee");
			}else{
				output("Having already earned the rights to this tattoo, you walk out of the clearing.");
				$session['user']['specialinc'] = "";
			}
		}elseif($defeat){
			output("`n`n`\$The masked shinobi robs you of your gold and disappears.");
			$session['user']['gold'] = 0;
			$session['user']['experience'] *= .80;
			debuglog("lost all gold and 20% experience due to Seven Star Warrior.");
			$session['user']['specialinc'] = "";
			$session['user']['alive'] = FALSE;
			$session['user']['hitpoints'] = 0;
			addnav("Return to Shades","shades.php");
		}else{
			require_once("lib/fightnav.php");
			fightnav(true,false);
		}
	}
?>
