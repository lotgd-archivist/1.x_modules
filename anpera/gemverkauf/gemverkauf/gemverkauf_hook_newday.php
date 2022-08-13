<?php
function gemverkauf_dohook_newday_private($hookname, $args){
	set_module_pref("menge", 0, "gemverkauf");
	return $args;
}
?>