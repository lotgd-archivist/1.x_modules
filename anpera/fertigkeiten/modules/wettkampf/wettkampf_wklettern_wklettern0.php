<?php

function wettkampf_wklettern_wklettern0_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
	
		if ($session[user][gold]<$session[user][level]*$teilnahme){
			output("`@Regon rümpft die Nase. `#'Aber nicht umsonst! Rabatt gibt es auch nicht ...'`@");
			addnav("Zurück","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=klettern");
		}else {
			output("`@`bTiefenklettern`b`nRegon bindet Dir mit viel Geschick eine Sicherheitsleine um: `n`#'Denkt daran: Wenn Ihr meint, nicht mehr tieferkommen zu können, gebt mir ein Signal. Wenn Ihr abrutscht, werdet ihr disqualifiziert!'");
			$session[user][gold]-=$session[user][level]*$teilnahme;
			addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern0a");
		}
	page_footer();
}
?>