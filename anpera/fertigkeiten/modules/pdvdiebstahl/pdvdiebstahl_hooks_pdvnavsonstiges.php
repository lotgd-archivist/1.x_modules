<?php

function pdvdiebstahl_hooks_dohook_pdvnavsonstiges_private($args=false){
	global $session;
		addnav("Taschendiebstahl","runmodule.php?module=pdvdiebstahl&op1=");
	return $args;
}
?>
