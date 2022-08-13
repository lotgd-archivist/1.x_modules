<?php

function wettkampf_wklettern_run_private($op, $subop=false){
	$args=$op;
	require_once("modules/wettkampf/wettkampf_wklettern_".$op.".php");
	return call_user_func_array("wettkampf_wklettern_".$op."_run_private",$args);
}
?>