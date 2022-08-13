<?php

function wettkampf_wreiten_run_private($op, $subop=false){
	$args=$op;
	require_once("modules/wettkampf/wettkampf_wreiten_".$op.".php");
	return call_user_func_array("wettkampf_wreiten_".$op."_run_private",$args);
}
?>