<?php
		$werte = array(	"name"=>"Schießstand",		// Text der im Link erscheinen soll
		"appear"=>get_module_setting("appear","pdvapfelschuss"));	// Abfrage ob anwesend oder nicht
		$args['pdvapfelschuss'] = $werte;
	return $args;
?>
