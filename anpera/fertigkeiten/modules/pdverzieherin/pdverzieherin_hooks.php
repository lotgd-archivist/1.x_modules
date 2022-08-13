<?php

function pdverzieherin_hooks_dohook_private($hookname,$args=false){
	if ($hookname == "pdvstände"){	
		$werte = array(	"name"=>"Erzieherin",		// Text der im Link erscheinen soll
						"appear"=>get_module_setting("appear","pdverzieherin"));	// Abfrage ob anwesend oder nicht
		$args['pdverzieherin'] = $werte;
	}else if ($hookname == "newday") set_module_pref("teilnahme", 0, "pdverzieherin");	
	return $args;
}
?>