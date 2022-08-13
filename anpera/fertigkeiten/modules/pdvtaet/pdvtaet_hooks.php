<?php

function pdvtaet_hooks_dohook_private($hookname,$args=false){
	include("modules/pdvtaet/pdvtaet_hooks_".$hookname.".php");
	return $args;
}
?>