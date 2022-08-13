<?php
		$hascurse=(get_module_pref('hasseal','curse_seal')<=0?0:1);
		$hastat= get_module_pref('hastat','sevenstar');
		if (!$hascurse && $hastat) {
			if (get_module_pref("hastat")>0) set_module_pref("todaylevel2",0);
			if (get_module_pref("days") > 0) increment_module_pref("days",-1);
			if (get_module_pref("tattoo-stage") < 6 && get_module_pref("tattoo-stage") != 0 && get_module_pref("days")>0){
				$session['user']['hitpoints']*=.1;
				output("`n`n`^As your tattoo `@heals, `^you suffer a great deal of `\$pain`^.`n");
			}elseif (get_module_pref("tattoo-stage") == 7 && !get_module_pref("days")){
				switch($session['user']['spirits']){
					case 1: case 2:
						$session['user']['charm']++;
						output("`n`n`^You look at the tattoo on your back in the mirror and the stars give out a `%`imysterious`i `t`btwinkle`b`^.");
						output("`^You feel `@charming.`n");
						break;
					case 0;
						switch(e_rand(1,2)){
							case 1:
								$session['user']['charm']++;
								output("`n`n`^You look at the tattoo on your back in the mirror and the stars give out a `%`imysterious`i `t`btwinkle`b`^.");
								output("`^You feel `@charming`^.`n");
								break;
							case 2:
								$session['user']['charm']--;
								output("`n`n`^You look at the tattoo on your back in the mirror and the stars turn into `)`iugly`i `~`bdark`b `^spots.");
								output("`^You feel `)ugly`^.`n");
								break;
						}
						break;
					case -1: case -2:
						$session['user']['charm']--;
						output("`n`n`^You look at the tattoo on your back in the mirror and the stars turn into `)`iugly`i `~`bdark`b `^spots.");
						output("`^You feel `)ugly`^.`n");
						break;
				}
				$seven_star_buff = array(
					"name"=>array("%s`g Energy",get_module_setting('name')),
					"rounds"=>-1,
					"allowinpvp"=>1,
					"allowintrain"=>1,
					"atkmod"=>"(<spirits>>0?1.25:1.0)",
					"defmod"=>"(<spirits>>0?1.25:1.0)",
					"effectmsg"=>"`$`iThe power of the `t`bstars`b flow through your veins!`i",
					"wearoff"=>"`)`iThe `7stars `)on your back stopped glowing`i.",
				);
				apply_buff("sevenstar",$seven_star_buff);
			}
		} elseif ($hastat && $hascurse) {
			$seal=get_module_setting("name","curse_seal");
			$tattoo=get_module_setting("name","sevenstar");
			output("`\$You bear the %s`\$, which makes your %s`\$ absolutely unstable and not usable.`n`n",$seal,$tattoo);
		}

?>
