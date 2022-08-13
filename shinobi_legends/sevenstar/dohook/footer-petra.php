<?php
			if (httpget('op') == ""){
				addnav("Extra");
				if (get_module_pref("hastat") && !get_module_pref("promise")){
					addnav("Show Seven Star Tattoo","runmodule.php?module=sevenstar&op=enter");
				}
				if (get_module_pref("tattoo-stage") < 7 && get_module_pref("promise")){
					if (!get_module_pref("days")){
						addnav("Work on `&Seven `t`bS`vta`tr `vT`\$a`ttt`\$o`vo`b","runmodule.php?module=sevenstar&op=work");
					}else{
						output("`n`n`^The `\$pain `^from your `&Seven `t`bS`vta`tr `vT`\$a`ttt`\$o`vo`b `^forces you to turn away from working on it more.`0");
					}
				}elseif (get_module_pref("tattoo-stage") == 7){
					output("`^`n`n%s `^looks upon you, \"`2You have attained the perfect tattoo.",get_module_setting("npc-name"));
					output("I can no longer ink on your body.");
					output("`^If you wish to get any more tattoos, you will have to remove the `&Seven `t`bS`vta`tr `vT`\$a`ttt`\$o`vo`b`^.`^\"");
				}
				if (get_module_pref("promise")) blocknav("runmodule.php?module=petra&op=yes",true);
			}
?>