<?php
function gemverkauf_dohook_private($hookname,$args){
	require_once("modules/gemverkauf/gemverkauf_hook_".$hookname.".php");
	$args = func_get_args();
	call_user_func_array("gemverkauf_dohook_".$hookname."_private",$args);
	
	return $args;
}
?>