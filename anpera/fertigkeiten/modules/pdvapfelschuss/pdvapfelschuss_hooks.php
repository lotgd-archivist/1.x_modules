<?php

function pdvapfelschuss_hooks_dohook_private($hookname,$args=false){
	include("modules/pdvapfelschuss/pdvapfelschuss_hooks_".$hookname.".php");
	return $args;
}
?>