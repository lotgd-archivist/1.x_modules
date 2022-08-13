<?php
	$op = httpget('op');
	$gyu=get_module_setting("gyururu");
	$seal=get_module_setting("name");
	$clanname=get_module_setting("clanname");
	$survival=get_module_setting("survival");
	page_header("Something Special");
	switch ($op) {
		case "offer":
			output("`7In front of you is the legendary %s`7. You declare your search for power... as an %s`7 you may think of retaliating for all your slaughtered clan members... but... as a clan principle... ultimate power is the greatest achievement, not meant to be wasted for revenge.",$gyu,$clanname);
			output("`n\"`\$A young %s`\$ I will give it to you, permanently...  what I can give you is called `n`n`c`i`\$%s`c`i`\$`n`n and you may grow attached to it.",$clanname,$seal);
			output("`nThere are two stages... I can try to implant these eyes into you. This is the first.`n`nBe aware... they might not be compatible with you... but if they are, it will be much more dangerous. These eyes will `q`beat`b`\$ your soul.`7\"");
			output("`n`nWithin the blink of an eye, %s`7 is a hairlength away from you...",$gyu);
			output("You feel a stinging pain as your vision goes black, but even before you can react, it is over...");
			output("`n`n\"`\$There is only one out of %s people who survive this ...`7\" says %s`7 and then lays back as you try to regain your eyesight...`n",e_rand($survival/2+2,$survival+2),$gyu);
			addnav("Will you survive?");
			addnav("Do I?","runmodule.php?module=susanoo&op=lifeordie");
//			if (is_module_active("alignment")) increment_module_pref("alignment",-10,"alignment");
			break;
		case "lifeordie":
			if (($survival==e_rand(1,$survival))) {
				//yay, lucky
				output("`7\"`\$The implant is a success! The eyes are compatible with you...`7\" are the last words you hear before your mind fades away into the darkness... and something is scratching inside your soul to come out....");
				output("`n`nYou start to regain sight and slowly feel an itch on the newly transplanted eye you cannot scratch... this will take you a while to get used to.");
				$session['user']['maxhitpoints']-=get_module_setting("lifecost","vampirelord");
				$session['user']['hitpoints']=1;
				set_module_pref("hasseal",1);
			} else {
				//not lucky
				output("`7It seems you didn't make it...`n");
				output("\"`\$Oh my, seems they were not... compatible with you after all. I knew I should have thrown that one away. The wart was a dead giveaway... what a pity... but thank you for your contribution to my experiment.`7\" are all you hear as you could no longer withstand the excruciating pain in your eyes and fade into darkness...");
				output("`n`n`1You are dead and have lost all your gold at hand.");
				$session['user']['alive']=0;
				$session['user']['hitpoints']=0;
				$session['user']['gold']=0;
				addnav("Return");
				addnav("Return to the Shades","shades.php");
//				increment_module_pref("hasseal",-1);
				break;
			}
			addnav("Return");
			addnav("To the Forest","forest.php");

			break;
	}
	$session['user']['specialinc'] = "";
?>
