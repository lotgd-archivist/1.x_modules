<?php

function wettkampf_dohook_private($hookname,$args){
	global $session;
		include("modules/wettkampf/wettkampf_hooks_".$hookname.".php");
			
	return $args;
}

?>