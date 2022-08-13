<?php

function wettkampf_wkochen_wkochen1_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
		output("`@`bDer Koch- und Backwettbewerb`b`n");
		output("`@Das sollte ja wohl kein Problem sein ...");
		set_module_setting("schwierigkeit", 25, "wettkampf");
		output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochenergebnis'>`n`nWeiter.</a>", true);
		addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochenergebnis");
		addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochenergebnis");
	page_footer();
}
?>