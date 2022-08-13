<?php

function wettkampf_wschwimmen_run_private($op, $subop=false){
	$args=$op;
	require_once("modules/wettkampf/wettkampf_wschwimmen_".$op.".php");
	return call_user_func_array("wettkampf_wschwimmen_".$op."_run_private",$args);
}	
?>