<?php

	global $session;
		$heilung=get_module_pref("heilung", "pdvtaet");
		if ($heilung != 0){
			if ($heilung == 1){
				output("`@`nDeine Tätowierung ist endlich verheilt!`n");
				set_module_pref("heilung", 0, "pdvtaet");
			}else{
				$session['user']['hitpoints']=round($session['user']['hitpoints']*0.85);
				output("`\$`nDie Entzündung an Deiner Tätowierung ist noch nicht ganz abgeklungen und schmerzt hin und wieder ...`n");
				set_module_pref("heilung", $heilung-1, "pdvtaet");
			}
		}
	return $args;

?>
