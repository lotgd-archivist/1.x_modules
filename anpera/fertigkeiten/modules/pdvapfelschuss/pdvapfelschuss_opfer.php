<?php

function pdvapfelschuss_opfer_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Der schmierige Schießstand");
	$schuetze=get_module_setting("schuetze");
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid='$schuetze'";
	$results = db_query($sql);
	$row = db_fetch_assoc($results);
	$name=$row['name'];
	
	$gold=get_module_setting("preis", "pdvapfelschuss");
	$session['user']['gold']+=$gold;
	
	output("`@So viel Geld lässt Du Dir nicht zweimal anbieten! Du greifst beherzt nach dem Beutel und gehst auf %s`@ "
		."zu, um zu versichern, dass Du Dir bei dem Schuss alle Mühe geben wirst. %s`@ jedoch ergreift "
		."noch vor Dir das Wort: `#'Ich werde mir alle Mühe geben, den Apfel auf Eurem Kopf zu treffen!'`@`n "
		."Die umstehenden Leute starren Dich an und so langsam aber sicher hast Du das Gefühl, hier könnte es ein Missverständnis "
		."geben ... ein gefährliches Missverständnis! Doch als der schmierige Troll wieder beginnt, die "
		."Menge anzuheizen - eine Wand von mindestens fünfzig Leuten -, gibt es kein Zurück mehr ...", $name, $name);
	set_module_pref("teilnahme", 1);
			
	addnav("Der Schuss!", "runmodule.php?module=pdvapfelschuss&op1=schuss&subop=".$schuetze."");
	page_footer();
}
?>