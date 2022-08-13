<?php
function mod_rp_dohook_private($hookname,$args){
	include("modules/mod_rp/mod_rp_hooks_".$hookname.".php");
	return $args;
}
?>