<?php

function pdverzieherin_main_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Die Erzieherin");
	checkday();
		$preis=get_module_setting("preis", "pdverzieherin");
				
		if ($session['user']['charm'] < 150) $gems=$preis;
		else $gems=$preis * 2;
		
		output("`@`cDas Zelt der Erzieherin`c`n`nDu n�herst Dich einem l�nglichen Zelt, "
			."das auff�llig gestaltet ist: Am Eingang stehen zwei K�bel mit sch�nen Blumen "
			."und das Vordach ist mit Rosenzweigen verziert.`n`nAls Du eintrittst, wirst Du "
			."von einer alten, vornehmen Menschenfrau empfangen: `#'Ah, ein Kunde ... nur herein, nur "
			."herein ...' `@Sie f�hrt Dich an einen Tisch. ");
		
		if ($session['user']['charm'] < 250){
			output("`#'Wollen wir gleich mit dem "
				."Unterricht in H�flichkeit, Kleidung und K�rperpflege beginnen oder wollen "
				."wir zuvor noch einen Tee trinken?' `@Einem kleinen Pappaufsteller auf dem Tisch "
				."entnimmst Du, dass der Unterricht f�r Leute wie Dich %s kostet. Du bist Dir �brigens "
				."ziemlich sicher, dass sie Dir den Tee nur aus H�flichkeit angeboten hat ...", ($gems==1?"`^einen`@ Edelstein":"`^$gems`@ Edelsteine"));
			addnav("Sehr gerne!", "runmodule.php?module=pdverzieherin&op1=unterricht&subop=".$gems."");
			addnav("Nein danke ...");
		}else output("Doch bereits nach einer kurzen Unterhaltung kommt sie zu dem Schluss, dass Du "
			."bereits ein solches Ma� an Anstand und W�rde erreicht hast, dass sie Dir nichts "
			."mehr beibringen kann. Du bedankst Dich h�flich f�r die nette Unterhaltung und "
			."empiehlst Dich dann.");	
				
		addnav("Zur�ck", "runmodule.php?module=wettkampf");
	page_footer();
}
?>