<?php

	global $session;
			$schluessel=get_module_setting("bgegenstand5", "wettkampf");
			
			//Der seltsame Schl�ssel
			if ($session[user][acctid] == $schluessel){
				$zufall=e_rand(1,7);
				if  ($zufall == 1){
					addnav("Eine seltsame T�r ...", "runmodule.php?module=wettkampf&op1=tuer");
				}
			}
	return $args;
?>
