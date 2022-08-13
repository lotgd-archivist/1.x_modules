<?php

function pdvdiebstahl_confirm_run_private($args=false){
	global $session;
	page_header("Immunität gegen Taschendiebstahl");

	output("`7Herzlichen Glückwunsch, von nun an wird Dich kein Taschendieb mehr behelligen!");
		set_module_pref("diebstahlsimmun", 1, "pdvdiebstahl");
		$cost = get_module_setting("immun_kosten");
		$session['user']['donationspent'] += $cost;
		addnav("L?Zurück zur Jägerhütte","lodge.php");
	page_footer();
}
?>