<?php

function wettkampf_statue_run_private($op){
	global $session;
	page_header("Der Platz der Völker");
		$fest=get_module_setting("fest");
		$blumen=get_module_setting("statueblumen");
		$blumentext="";
			if ($blumen!=0 && $blumen <=5) $blumentext=translate_inline("Einige wenige Blumensträuße sind der Vermittlerin zu Ehren darauf niedergelegt worden.");
			if ($blumen>5 && $blumen <=15) $blumentext=translate_inline("Ein paar Blumensträuße sind der Vermittlerin zu Ehren darauf niedergelegt worden.");
			if ($blumen>15 && $blumen <=50) $blumentext=translate_inline("Eine große Zahl Blumensträuße ist der Vermittlerin zu Ehren darauf niedergelegt worden.");
			if ($blumen>50 && $blumen <=100) $blumentext=translate_inline("Viel ist von dem Rasen aber nicht zu erkennen, angesichts der vielen Blumensträuße, die der Vermittlerin zu Ehren darauf niedergelegt worden sind.");
			if ($blumen>100 && $blumen <=200) $blumentext=translate_inline("Der Rasen lässt sich aber nur erahnen, angesichts der unheimlich vielen Blumensträuße, die der Vermittlerin zu Ehren darauf niedergelegt worden sind.");
			if ($blumen>200) $blumentext=translate_inline("Der Rasen lässt sich aber nur vage erahnen - ein prächtiges Meer von Blumensträußen, die der Vermittlerin zu Ehren darauf niedergelegt worden sind, bedeckt die gesamte Fläche!");
		
			$schönheit=translate_inline("deren Anblick Dir den Atem nimmt; so unvergleichlich schön ist diese junge Frau noch in Stein gemeißelt, dass Du es bedauerst, selbst so vergleichsweise wenig für Dein Aussehen zu tun");
			if ($session[user][charm]>240) $schönheit=translate_inline("deren Anblick Dir ein Lächeln ins Gesicht zaubert. Sie ist Deiner eigenen, übermäßigen Schönheit mehr als ebenbürtig");
			
		output ("`@`bDie Statue der Vermittlerin`@`b`n`n");
		output ("`@Du bist direkt vom Haupttor zur Mitte des Platzes gegangen, wo sich die große Marmorstatue der Frau befindet, auf die das Fest der Völker zurückgeht. Um den Sockel, der Dir bis zum Hals reicht, herum ist eine große, kreisrunde Rasenfläche angelegt, die man aber nicht betreten muss, um bis an ihn heranzugehen. %s`n`n Aus jeder der vier Himmelsrichtungen führt ein gerader, gepflasterter Pfad bis zur Statue, %s. Die große Vermittlerin ist in einer Pose dargestellt, wie viele sie kennen, die ihr schon einmal begegnet sind: Sie trägt ein langes, ärmelloses Sommerkleid und sitzt gedankenverloren lächelnd am Rande eines Brunnens. Ihre linke Hand lässt sie langsam durchs Wasser gleiten, während ihre rechte auf dem Schoß ruht. Die Statue wirkt so echt, als wäre die Vermittlerin tatsächlich hier bei Dir ...", $blumentext, $schönheit);
	
		addnav("Die Statue");
		addnav("Gedenktafel lesen","runmodule.php?module=wettkampf&op1=statuetafel");
		addnav("Blumen niederlegen`n (`^50`0 Goldstücke)","runmodule.php?module=wettkampf&op1=statueblumen");
		addnav("Zurück","runmodule.php?module=wettkampf&op1=");
	page_footer();
}
?>