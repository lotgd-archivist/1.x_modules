<?php

function pdvdiebstahl_buy_run_private($args=false){
	global $session;
	page_header("Immunit�t gegen Taschendiebstahl");

	$cost = get_module_setting("immun_kosten");
	$points = $session['user']['donation']-$session['user']['donationspent'];
	page_header("J�gerh�tte");
	
	if($points<$cost){
		output("`n`7J.C. Petersen sch�ttelt den Kopf und sagt: `3\"Du ben�tigst `^%s`3 Punkte, um das zu kaufen!\"`n`n", $cost);
		addnav("L?Zur�ck zur J�gerh�tte","lodge.php");
	}else{
		output("`7J.C. Petersen nickt und sagt: `3\"F�r `^%s`3 Punkte wirkst Du in Zukunft so absto�end auf Taschendiebe, dass Dich keiner mehr behelligen wird. Du darfst dann nat�rlich auch selbst nicht mehr auf Tour gehen. Ist das Dein Wunsch?\"`n", $cost);
		addnav("Best�tige Kauf");
		addnav("JA", "runmodule.php?module=pdvdiebstahl&op1=confirm");
		addnav("NEIN","lodge.php");
	}
	page_footer();
}
?>