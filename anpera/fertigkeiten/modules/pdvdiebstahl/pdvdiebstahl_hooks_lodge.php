<?php

function pdvdiebstahl_hooks_dohook_lodge_private($args=false){
	global $session;
			$cost = get_module_setting("immun_kosten", "pdvdiebstahl");
		if (get_module_pref("diebstahlsimmun")!=1 && $cost>0) 
			addnav(array("Immunit�t gegen Taschendiebe (%s Punkte)", $cost), "runmodule.php?module=pdvdiebstahl&op1=buy");
	return $args;
}
?>
