<?php

function pdvapfelschuss_auswahl1_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Der schmierige Schie�stand");
	output("`@Du zahlst den Preis und gehst auf den "
		."kleinen Jungen zu, um ihm zu versichern, dass Du Dir alle M�he geben wirst. Dieser jedoch ergreift "
		."als erster das Wort: `#'Viel Gl�ck!'`@ sagt er und dr�ckt Dir den Apfel in die Hand. Die umstehenden "
		."Leute starren Dich an und so langsam aber sicher hast Du das Gef�hl, hier k�nnte es ein Missverst�ndnis "
		."geben. Da kommt der schmierige Troll auch schon wieder auf Dich zu: `#'So, nun wollen wir mal sehen, "
		."was in Euch steckt! Sobald ich einen Freiwilligen gefunden habe, k�nnt Ihr Euer Gl�ck versuchen! "
		."Wartet einfach hier ...'`@ Du willst noch etwas fragen, aber er hat schon wieder begonnen, die Menge "
		."anzuheizen. Jetzt hei�t es also warten ... W�hrenddessen solltest Du Dir schon mal �berlegen, "
		."wie Du zielen willst.");
			
		output("`n`n<a href='runmodule.php?module=pdvapfelschuss&op1=auswahl2&subop=1'>Ich werde versuchen, den Apfel zu treffen.</a>",true);
		output("`n`n<a href='runmodule.php?module=pdvapfelschuss&op1=auswahl2&subop=2'>Haha, nat�rlich auf den Kopf!</a>",true);
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