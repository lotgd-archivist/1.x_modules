<?php

function wettkampf_wschleichen_run_private($op, $subop=false){
	$args=$op;
	require_once("modules/wettkampf/wettkampf_wschleichen_".$op.".php");
	return call_user_func_array("wettkampf_wschleichen_".$op."_run_private",$args);
}
?>