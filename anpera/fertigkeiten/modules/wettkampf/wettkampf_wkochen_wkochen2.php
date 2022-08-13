<?php

function wettkampf_wkochen_wkochen2_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
		output("`@`bDer Koch- und Backwettbewerb`b`n");
		output("`@Gute Hausmannskost also, mit einem leichten Anflug von Raffinesse. Dann wollen wir mal anfangen ...");
		set_module_setting("schwierigkeit", 0, "wettkampf");
		output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochenergebnis'>`n`nWeiter.</a>", true);
		addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochenergebnis");
		addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochenergebnis");
	page_footer();
}
?>