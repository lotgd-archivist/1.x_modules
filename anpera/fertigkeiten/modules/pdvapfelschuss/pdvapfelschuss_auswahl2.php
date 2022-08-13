<?php

require_once("lib/fert.php");

function pdvapfelschuss_auswahl2_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Der schmierige Schießstand");
	$subop=$_GET[subop];
	output("`@Gut, so soll es sein. Achte auf Dein Postfach! Wenn etwas passiert, bekommst "
		."Du eine Mail.");	
	
	$bogen=get_fertigkeit(bogen);
	
	set_module_setting("schuetze", $session['user']['acctid']);
	set_module_setting("ziel", $subop);
	set_module_setting("fw", $bogen);
	
	addnav("Zurück", "runmodule.php?module=wettkampf");
	page_footer();
}
?>