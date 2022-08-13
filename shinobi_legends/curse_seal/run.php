<?php
	$op = httpget('op');
	$vampirelord=get_module_setting("vampirelord","vampirelord");
	$seal=get_module_setting("name");
	$survival=get_module_setting("survival");
	page_header("Something Special");
	switch ($op) {
		case "offer":
			output("`7You stand right before the %s`7 and declare you search for power... the ultimative power that should run through you veins as you are the greatest of all and want to dominate the entire country.",$vampirelord);
			output("`n\"`\$So you search for power... I will give it to you, permanently...  what I can give you is called `n`n`c`i`\$%s`c`i`\$`n`n and you will find it useful while being evil. If you become good, nobody will know what happens.",$seal);
			output("`nAlso, there are two levels... yet you will now only be able to do it at level 1. Be aware... level 1 does not harm you that much... but level 2, once obtained, is dangerous. It will `)`berode`b`\$ your body.`7\"");
			output("`n`nThen, with a sudden movement the %s`7 is over you...",$vampirelord);
			output(" You shudder in horror as he forces his teeth into you neck. You feel your life essence mixing with something cruel...horrible....");
			output("`n`n\"`\$Oh, by the way, only one out of %s people survive this procedure....`7\" says the %s`7 and then he lays back to watch you crumble and scream as you feel hot poison running through your body...`n",e_rand($survival/2+2,$survival+2),$vampirelord);
			addnav("Will you survive?");
			addnav("Do I?","runmodule.php?module=curse_seal&op=lifeordie");
			if (is_module_active("alignment")) increment_module_pref("alignment",-10,"alignment");
			break;
		case "lifeordie":
			if (($survival==e_rand(1,$survival))) {
				//yay, lucky
				output("`7\"`\$Unbelievable! You have endured the procedure and live...`7\" are the last words you hear before your mind fades away into the darkness... and something is scratching inside your soul to come out....");
				output("`n`nYou awake in complete darkness with a pounding neck and something like a tattoo at a part of your body... only you know where.");
				$session['user']['maxhitpoints']-=get_module_setting("lifecost","vampirelord");
				$session['user']['hitpoints']=1;
				set_module_pref("hasseal",1);
			} else {
				//not lucky
				output("`7You feel your life force is snuffed away like a charm because you are too weak to endure it and die now painfully.`n");
				output("\"`\$Ha ha ha, I knew that you can't take it, what a pity... but thank you for the moneeeeey`7\" are the last words you hear before your heart stops pounding...");
				output("`n`n`1You are dead and have lost all your gold at hand.");
				$session['user']['alive']=0;
				$session['user']['hitpoints']=0;
				$session['user']['gold']=0;
				addnav("Return");
				addnav("Return to the Shades","shades.php");
				increment_module_pref("hasseal",-1);
				break;
			}
			addnav("Return");
			addnav("To the Forest","forest.php");

			break;
	}
	$session['user']['specialinc'] = "";
?>
