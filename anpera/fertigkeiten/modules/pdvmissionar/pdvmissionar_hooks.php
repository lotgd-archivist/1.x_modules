<?php

function pdvmissionar_hooks_dohook_private($hookname,$args=false){
	include("modules/pdvmissionar/pdvmissionar_hooks_".$hookname.".php");
	return $args;
}
?>