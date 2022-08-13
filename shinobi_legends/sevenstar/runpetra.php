<?php
	$npc = get_module_setting("npc-name");
	page_header(array("%s's Tattoo Parlor",color_sanitize($npc)));

	switch($op){
		case "enter":
			output("`^%s `2takes a good look at you and then exclaims in a surprised tone, \"`@T-this is...the Seven-Star-Tattoo!",$npc);
			output("Where did you get this?`2\"");
			output("`2You tell the tattooist about the encounter with the `&Seven `t`bS`vta`tr `vW`\$a`trr`\$io`tr`b.");
			output("After hearing your story, %s looks you in the eye and said in a slow voice, \"`@I see...I would like you to do me a favor.",$npc);
			output("Please keep this a secret. In return, I will ink this tattoo for you, deal?`2\"");
			addnav("Extort for Gems","runmodule.php?module=sevenstar&op=extort");
			addnav("Keep the Secret","runmodule.php?module=sevenstar&op=promise");
			break;
		case "extort":
			output("`2\"`@Very well, have it your way.`2\"");
			output("`^%s `2does a few hand signs and spits out `\$a ball of flame `2from her mouth, destroying the markings on your hand.",$npc);
			output("\"`@I am sorry, but I cannot let the existance of this tattoo leak into the outside world.`2\"");
			output("`^%s `2chases you out of the shop empty handed.",$npc);
			set_module_pref("hastat",0);
			break;
		case "promise":
			output("`^%s `2reaches out and removes the markings from your skin..",$npc);
			output("\"`@Thank you, and as promised, I will do the `&Seven `t`bS`vta`tr `vT`\$a`ttt`\$o`vo`b for you.`2\"");
			set_module_pref("promise",1);
			if (get_module_pref("tatnumber","petra") > 0){
				output("`2\"`@Ah...I can see that you are one of my customers.");
				output("Unfortunately, I can only apply this tattoo on unstained skin.");
				output("You will have to remove all your tattoos before I can begin.`2\"");
			}else{
				output("`2\"`@My, what beautiful skin you have.");
				output("Let's get started shall we?`2\"");
				addnav("Work on `&Seven `t`bS`vta`tr `vT`\$a`ttt`\$o`vo`b","runmodule.php?module=sevenstar&op=work");
			}
			break;
		case "work":
			if (get_module_pref("tatnumber","petra") > 0 && !get_module_pref("promise")){
				// In case they get booted from the first part and try to come back in.
				output("`2\"`@Ah...I can see that you are one of my customers.");
				output("Unfortunately, I can only apply this tattoo on unstained skin.");
				output("You will have to remove all your tattoos before I can begin.`2\"");
			}else{
				$stage = get_module_pref("tattoo-stage")+1;
				addnav(array("Get %s Star Tattoo",$stage),"runmodule.php?module=sevenstar&op=inking&stage=$stage");
				output("`^%s `2turns to you and says, \"`@For me to ink this tattoo, it will cost 10 gems.`2\"",$npc);
				output_notl("`n`n");
				output("\"`@If at any time you remove one of these special tattoos, you will lose all memory from this entire ordeal.",$npc);
				output("Take heed of my words...`2\"");
			}
			break;
		case "inking":
			if ($session['user']['gems'] < 10){
				output("`^%s `2gives you a stern look.",$npc);
				output("\"`@You don't have 10 gems... how do you expect for me to ink this tattoo?`2\"");
				break;
			}
			if (is_module_active('inventory')) {
				require_once("modules/inventory/lib/itemhandler.php");
				$hasink=check_qty_by_name('Special Ink');
			} else $hasink=1;
			if (!$hasink) {
				output("\"`@I thought I told you to fetch me the `bSpecial Ink`b? Without it, I cannot make any progress to your tattoo.`2\"");
				break;
			}
			if (is_module_active("curse_seal")) {
				$hasseal=(int)get_module_pref("hasseal","curse_seal");
				if ($hasseal>0) {
				$name=get_module_setting("name","curse_seal");
				output("\"`@I sense the power of the %s`@ in you... though not visible at present. I won't help you with your tattoo... the aura of your seal would make it instable and not usable anyway.`2\"",$name);					
				break;
				}
			}
			$fail = 0;
			switch (httpget('stage')){
				case 1: case 2: case 3:
					if ($session['user']['maxhitpoints'] >= (($session['user']['level']*10) + 5)){
						$session['user']['maxhitpoints'] -= 5;
					}else{
						$fail = 1;
					}
					$days_take = 3;
					break;
				case 4: case 5: case 6:
					if ($session['user']['maxhitpoints'] >= (($session['user']['level']*10) + 5)){
						$session['user']['maxhitpoints'] -= 5;
					}else{
						$fail = 1;
					}
					$days_take = 6;
					break;
				case 7:
					if ($session['user']['maxhitpoints'] >= (($session['user']['level']*10) + 10)){
						$session['user']['maxhitpoints'] -= 10;
					}else{
						$fail = 1;
					}
					$days_take = 9;
					break;
			}
			if ($fail){
				output("`^%s `2looks at you, \"`2You don't possess the `@strength `2to have this tattoo.",$npc);
				output("Come back once you have more life to sacrifice.`2\"");
			}else{
				require_once("modules/inventory/lib/itemhandler.php");
				remove_item_by_name("Special Ink");
				$session['user']['gems']-=10;
				set_module_pref("days",$days_take);
				increment_module_pref("tattoo-stage",1);
				set_module_pref("tatnumber",1,"petra");
				$tats = unserialize(get_module_pref("tatname","petra"));
				$prev_stage = $stage-1;
				$star = $prev_stage."star";
				if (isset($tats[$star])) unset($tats[$star]);
				$star_next = $stage."star";
				$tats[$star_next] = 1;
				set_module_pref("tatname",serialize($tats),"petra");
				$session['user']['hitpoints'] *= 0.2;
				if ($session['user']['hitpoints'] <= 1) {
					$session['user']['hitpoints'] = 1;
				}
				if ($stage < 7){
					output("`2\"`@We are done for now.");
					output("`2The `&Seven `t`bS`vta`tr `vT`\$a`ttt`\$o`vo`b `2is made out of seven tattoos that are done on specific spots on your back.");
					output("I can only apply 1 tattoo at a time. ");
					output("`2Rushing the process will `4kill `2you.`n`n");
					output("`2Come back in %s days when you are `@healed`2.",$days_take);
					if (is_module_active('inventory')) output(" `bDon't forget to get me another can of special ink!`b`2\"");
				}else{
					output("`2\"`@Finally, all `&seven `t`bstars`b `@are in place.");
					output("Rest for now.");
					output("Once you are healed, in %s days, you will be able to feel the power of the `&Seven `t`bS`vta`tr `vT`\$a`ttt`\$o`vo`b.",$days_take);
					output("Oh, and please do not forget to keep your promise.`2\"");
				}
			}
			break;
	}
	villagenav();
?>
