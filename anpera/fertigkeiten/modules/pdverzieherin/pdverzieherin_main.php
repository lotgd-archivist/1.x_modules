<?php

function pdverzieherin_main_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Die Erzieherin");
	checkday();
		$preis=get_module_setting("preis", "pdverzieherin");
				
		if ($session['user']['charm'] < 150) $gems=$preis;
		else $gems=$preis * 2;
		
		output("`@`cDas Zelt der Erzieherin`c`n`nDu näherst Dich einem länglichen Zelt, "
			."das auffällig gestaltet ist: Am Eingang stehen zwei Kübel mit schönen Blumen "
			."und das Vordach ist mit Rosenzweigen verziert.`n`nAls Du eintrittst, wirst Du "
			."von einer alten, vornehmen Menschenfrau empfangen: `#'Ah, ein Kunde ... nur herein, nur "
			."herein ...' `@Sie führt Dich an einen Tisch. ");
		
		if ($session['user']['charm'] < 250){
			output("`#'Wollen wir gleich mit dem "
				."Unterricht in Höflichkeit, Kleidung und Körperpflege beginnen oder wollen "
				."wir zuvor noch einen Tee trinken?' `@Einem kleinen Pappaufsteller auf dem Tisch "
				."entnimmst Du, dass der Unterricht für Leute wie Dich %s kostet. Du bist Dir übrigens "
				."ziemlich sicher, dass sie Dir den Tee nur aus Höflichkeit angeboten hat ...", ($gems==1?"`^einen`@ Edelstein":"`^$gems`@ Edelsteine"));
			addnav("Sehr gerne!", "runmodule.php?module=pdverzieherin&op1=unterricht&subop=".$gems."");
			addnav("Nein danke ...");
		}else output("Doch bereits nach einer kurzen Unterhaltung kommt sie zu dem Schluss, dass Du "
			."bereits ein solches Maß an Anstand und Würde erreicht hast, dass sie Dir nichts "
			."mehr beibringen kann. Du bedankst Dich höflich für die nette Unterhaltung und "
			."empiehlst Dich dann.");	
				
		addnav("Zurück", "runmodule.php?module=wettkampf");
	page_footer();
}
?>