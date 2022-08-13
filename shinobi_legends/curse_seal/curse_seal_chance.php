<?php

function curse_seal_chance($type){
	global $session;
	if ($session['user']['location']!=get_module_setting("locationfive","curse_seal")) return 0;
	if (get_module_pref("hasseal","curse_seal")==1) {
		$days=get_module_pref("days","curse_seal");
		if ($days>1) return (log($days)*10);
	}
	return 0;
}
?>