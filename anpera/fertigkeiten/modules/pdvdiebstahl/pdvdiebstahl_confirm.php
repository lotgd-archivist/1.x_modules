<?php

function pdvdiebstahl_confirm_run_private($args=false){
	global $session;
	page_header("Immunit�t gegen Taschendiebstahl");

	output("`7Herzlichen Gl�ckwunsch, von nun an wird Dich kein Taschendieb mehr behelligen!");
		set_module_pref("diebstahlsimmun", 1, "pdvdiebstahl");
		$cost = get_module_setting("immun_kosten");
		$session['user']['donationspent'] += $cost;
		addnav("L?Zur�ck zur J�gerh�tte","lodge.php");
	page_footer();
}
?>