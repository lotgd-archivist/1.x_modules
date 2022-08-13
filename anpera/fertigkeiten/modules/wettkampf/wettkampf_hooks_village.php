<?php
	if ($session['user']['location'] == get_module_setting("wettkampfloc", "wettkampf")){
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav("V?Platz der Völker", "runmodule.php?module=wettkampf");
			}
	return $args;
?>
