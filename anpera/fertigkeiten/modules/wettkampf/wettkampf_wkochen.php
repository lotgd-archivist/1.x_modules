<?php

function wettkampf_wkochen_run_private($op, $subop=false){
	$args=$op;
	require_once("modules/wettkampf/wettkampf_wkochen_".$op.".php");
	return call_user_func_array("wettkampf_wkochen_".$op."_run_private",$args);
}
?>