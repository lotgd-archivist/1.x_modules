<?php

function pdvapfelschuss_auswahl1_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Der schmierige Schießstand");
	output("`@Du zahlst den Preis und gehst auf den "
		."kleinen Jungen zu, um ihm zu versichern, dass Du Dir alle Mühe geben wirst. Dieser jedoch ergreift "
		."als erster das Wort: `#'Viel Glück!'`@ sagt er und drückt Dir den Apfel in die Hand. Die umstehenden "
		."Leute starren Dich an und so langsam aber sicher hast Du das Gefühl, hier könnte es ein Missverständnis "
		."geben. Da kommt der schmierige Troll auch schon wieder auf Dich zu: `#'So, nun wollen wir mal sehen, "
		."was in Euch steckt! Sobald ich einen Freiwilligen gefunden habe, könnt Ihr Euer Glück versuchen! "
		."Wartet einfach hier ...'`@ Du willst noch etwas fragen, aber er hat schon wieder begonnen, die Menge "
		."anzuheizen. Jetzt heißt es also warten ... Währenddessen solltest Du Dir schon mal überlegen, "
		."wie Du zielen willst.");
			
		output("`n`n<a href='runmodule.php?module=pdvapfelschuss&op1=auswahl2&subop=1'>Ich werde versuchen, den Apfel zu treffen.</a>",true);
		output("`n`n<a href='runmodule.php?module=pdvapfelschuss&op1=auswahl2&subop=2'>Haha, natürlich auf den Kopf!</a>",true);
		addnav("", "runmodule.php?module=pdvapfelschuss&op1=auswahl2&subop=1");
		addnav("", "runmodule.php?module=pdvapfelschuss&op1=auswahl2&subop=2");
		addnav("Schuss auf");
		addnav("... den Apfel", $from . "runmodule.php?module=pdvapfelschuss&op1=auswahl2&subop=1");
		addnav("... den Kopf", $from . "runmodule.php?module=pdvapfelschuss&op1=auswahl2&subop=2");
			
		set_module_setting("schuetze", -1);
		$gold=get_module_setting("preis", "pdvapfelschuss");
		$session['user']['gold']-=$gold;
		set_module_pref("teilnahme", 1);
	page_footer();
}
?>