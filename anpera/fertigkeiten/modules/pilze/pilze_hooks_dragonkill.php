<?php

function pilze_hooks_dohook_dragonkill_private($args=false){
		$a = array();
		set_module_pref("alleitems",createstring($a));
	return $args;
}
