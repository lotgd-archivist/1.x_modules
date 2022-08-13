<?php

function wettkampf_wbogen_run_private($op, $subop=false){
	$args=$op;
	require_once("modules/wettkampf/wettkampf_wbogen_".$op.".php");
	return call_user_func_array("wettkampf_wbogen_".$op."_run_private",$args);
}
?>