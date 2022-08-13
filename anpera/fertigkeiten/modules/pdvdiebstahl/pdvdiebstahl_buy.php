<?php

function pdvdiebstahl_buy_run_private($args=false){
	global $session;
	page_header("Immunität gegen Taschendiebstahl");

	$cost = get_module_setting("immun_kosten");
	$points = $session['user']['donation']-$session['user']['donationspent'];
	page_header("Jägerhütte");
	
	if($points<$cost){
		output("`n`7J.C. Petersen schüttelt den Kopf und sagt: `3\"Du benötigst `^%s`3 Punkte, um das zu kaufen!\"`n`n", $cost);
		addnav("L?Zurück zur Jägerhütte","lodge.php");
	}else{
		output("`7J.C. Petersen nickt und sagt: `3\"Für `^%s`3 Punkte wirkst Du in Zukunft so abstoßend auf Taschendiebe, dass Dich keiner mehr behelligen wird. Du darfst dann natürlich auch selbst nicht mehr auf Tour gehen. Ist das Dein Wunsch?\"`n", $cost);
		addnav("Bestätige Kauf");
		addnav("JA", "runmodule.php?module=pdvdiebstahl&op1=confirm");
		addnav("NEIN","lodge.php");
	}
	page_footer();
}
?>