<?php

function susanoo_chance($type){
	global $session;
	if ($session['user']['location']!=get_module_setting("locationfive","susanoo")) return 0;
	$suslevel = get_module_pref("hasseal","susanoo");
	if ($suslevel>0 && $suslevel<4) {
		$days=get_module_pref("days","susanoo");
		if ($days>1) return (log($days)*10);
	}
	return 0;
}
?>
