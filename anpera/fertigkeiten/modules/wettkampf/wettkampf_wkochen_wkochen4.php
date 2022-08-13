<?php

function wettkampf_wkochen_wkochen4_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
			output("`@`bDer Koch- und Backwettbewerb`b`n");
		output("`@Ist das Dein Ernst? Dann bist Du entweder unglaublich überheblich oder unglaublich begabt. Nun wird sich zeigen, ob Du in der Lage bist, mit den erlesensten Zutaten dieser Welt umzugehen ...");
		set_module_setting("schwierigkeit", -50, "wettkampf");
		output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochenergebnis'>`n`nWeiter.</a>", true);
		addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochenergebnis");
		addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochenergebnis");
	page_footer();
}
?>