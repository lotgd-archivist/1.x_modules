<?php

function wettkampf_statue_run_private($op){
	global $session;
	page_header("Der Platz der V�lker");
		$fest=get_module_setting("fest");
		$blumen=get_module_setting("statueblumen");
		$blumentext="";
			if ($blumen!=0 && $blumen <=5) $blumentext=translate_inline("Einige wenige Blumenstr�u�e sind der Vermittlerin zu Ehren darauf niedergelegt worden.");
			if ($blumen>5 && $blumen <=15) $blumentext=translate_inline("Ein paar Blumenstr�u�e sind der Vermittlerin zu Ehren darauf niedergelegt worden.");
			if ($blumen>15 && $blumen <=50) $blumentext=translate_inline("Eine gro�e Zahl Blumenstr�u�e ist der Vermittlerin zu Ehren darauf niedergelegt worden.");
			if ($blumen>50 && $blumen <=100) $blumentext=translate_inline("Viel ist von dem Rasen aber nicht zu erkennen, angesichts der vielen Blumenstr�u�e, die der Vermittlerin zu Ehren darauf niedergelegt worden sind.");
			if ($blumen>100 && $blumen <=200) $blumentext=translate_inline("Der Rasen l�sst sich aber nur erahnen, angesichts der unheimlich vielen Blumenstr�u�e, die der Vermittlerin zu Ehren darauf niedergelegt worden sind.");
			if ($blumen>200) $blumentext=translate_inline("Der Rasen l�sst sich aber nur vage erahnen - ein pr�chtiges Meer von Blumenstr�u�en, die der Vermittlerin zu Ehren darauf niedergelegt worden sind, bedeckt die gesamte Fl�che!");
		
			$sch�nheit=translate_inline("deren Anblick Dir den Atem nimmt; so unvergleichlich sch�n ist diese junge Frau noch in Stein gemei�elt, dass Du es bedauerst, selbst so vergleichsweise wenig f�r Dein Aussehen zu tun");
			if ($session[user][charm]>240) $sch�nheit=translate_inline("deren Anblick Dir ein L�cheln ins Gesicht zaubert. Sie ist Deiner eigenen, �berm��igen Sch�nheit mehr als ebenb�rtig");
			
		output ("`@`bDie Statue der Vermittlerin`@`b`n`n");
		output ("`@Du bist direkt vom Haupttor zur Mitte des Platzes gegangen, wo sich die gro�e Marmorstatue der Frau befindet, auf die das Fest der V�lker zur�ckgeht. Um den Sockel, der Dir bis zum Hals reicht, herum ist eine gro�e, kreisrunde Rasenfl�che angelegt, die man aber nicht betreten muss, um bis an ihn heranzugehen. %s`n`n Aus jeder der vier Himmelsrichtungen f�hrt ein gerader, gepflasterter Pfad bis zur Statue, %s. Die gro�e Vermittlerin ist in einer Pose dargestellt, wie viele sie kennen, die ihr schon einmal begegnet sind: Sie tr�gt ein langes, �rmelloses Sommerkleid und sitzt gedankenverloren l�chelnd am Rande eines Brunnens. Ihre linke Hand l�sst sie langsam durchs Wasser gleiten, w�hrend ihre rechte auf dem Scho� ruht. Die Statue wirkt so echt, als w�re die Vermittlerin tats�chlich hier bei Dir ...", $blumentext, $sch�nheit);
	
		addnav("Die Statue");
		addnav("Gedenktafel lesen","runmodule.php?module=wettkampf&op1=statuetafel");
		addnav("Blumen niederlegen`n (`^50`0 Goldst�cke)","runmodule.php?module=wettkampf&op1=statueblumen");
		addnav("Zur�ck","runmodule.php?module=wettkampf&op1=");
	page_footer();
}
?>