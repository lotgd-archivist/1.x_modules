<?php

function pdvapfelschuss_opfer_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Der schmierige Schie�stand");
	$schuetze=get_module_setting("schuetze");
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid='$schuetze'";
	$results = db_query($sql);
	$row = db_fetch_assoc($results);
	$name=$row['name'];
	
	$gold=get_module_setting("preis", "pdvapfelschuss");
	$session['user']['gold']+=$gold;
	
	output("`@So viel Geld l�sst Du Dir nicht zweimal anbieten! Du greifst beherzt nach dem Beutel und gehst auf %s`@ "
		."zu, um zu versichern, dass Du Dir bei dem Schuss alle M�he geben wirst. %s`@ jedoch ergreift "
		."noch vor Dir das Wort: `#'Ich werde mir alle M�he geben, den Apfel auf Eurem Kopf zu treffen!'`@`n "
		."Die umstehenden Leute starren Dich an und so langsam aber sicher hast Du das Gef�hl, hier k�nnte es ein Missverst�ndnis "
		."geben ... ein gef�hrliches Missverst�ndnis! Doch als der schmierige Troll wieder beginnt, die "
		."Menge anzuheizen - eine Wand von mindestens f�nfzig Leuten -, gibt es kein Zur�ck mehr ...", $name, $name);
	set_module_pref("teilnahme", 1);
			
	addnav("Der Schuss!", "runmodule.php?module=pdvapfelschuss&op1=schuss&subop=".$schuetze."");
	page_footer();
}
?>