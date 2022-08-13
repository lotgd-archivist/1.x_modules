<?php
function sevenstar_chance() {
	global $session;
	require_once("modules/alignment/func.php");
	$val=min(45,max(3,get_align()-60));
	$dk=(get_module_setting('min-dk','sevenstar')<=$session['user']['dragonkills']?1:0);
	$hastat=get_module_pref('hastat','sevenstar');
	return ($dk&!$hastat?$val:0);
}
?>