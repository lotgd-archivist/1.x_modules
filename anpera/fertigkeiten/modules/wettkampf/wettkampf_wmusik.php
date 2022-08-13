<?php

function wettkampf_wmusik_run_private($op, $subop=false){
	$args=$op;
	require_once("modules/wettkampf/wettkampf_wmusik_".$op.".php");
	return call_user_func_array("wettkampf_wmusik_".$op."_run_private",$args);
}
?>