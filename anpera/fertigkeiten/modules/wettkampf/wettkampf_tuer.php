<?php

function wettkampf_tuer_run_private($op){
	global $session;
	page_header("Eine Tür ...");
		require_once("lib/commentary.php");
		output("`2Du näherst Dich der seltsamen Tür, die Du hier noch nie zuvor gesehen hast ... und spürst, dass Dein seltsamer Schlüssel heißer wird. An der kleinen, grauen Tür angekommen, die sich kaum von der Mauer unterscheidet, die den Garten umgibt, holst Du ihn hervor. Natürlich, er passt ...`n`n");	
		output("`2Vorsichtig schiebst Du sie auf und betrittst einen dunklen Raum, der nur durch einen schwachen, aber dennoch blendenden Schein erleuchtet wird. Du näherst Dich dem Licht und erkennst eine Person ... `n`n");
		output("`2Sie sitzt auf einen Stuhl gekauert und tippt mit den Fingern auf einem schmalen, grauen Kasten herum ... Du gehst noch näher heran und siehst, dass die Lampe rechteckig ist ... Aber es ist keine Lampe, denn wie durch ein Wunder ist eine Art Schrift darauf zu erkennen ...`n`n");	
		output("`2Plötzlich dreht sich die fremde Gestalt herum - offenbar ist ihr Stuhl drehbar: `#'Wer zum --'`2`n`n");	
		output("`2Sie starrt Dich an - und Du starrst ... `bDich`b selbst an! Wie ist das ... möglich ... Erschrocken gehst Du einen Schritt zurück ... ein Doppelgänger?`n`n");	
		output("`2Dir schwinden die Sinne. Als Du wieder aufwachst, stehen einige Bürger um Dich herum. Du befindest Dich auf dem Gemeindeplatz und der Schrecken sitzt noch immer tief. Fast möchtest Du weinen ...`n`n");
		$exp_bonus=round($session[user][experience]*0.2);
		$session[user][experience]+=$exp_bonus;
		output("`2Wenngleich die Erinnerung nur vage ist, hast Du durch diese Begegnung `^%s`2 Erfahrungspunkte hinzugewonnen!", $exp_bonus);	
		set_module_setting("bgegenstand5", "");
		$comment=translate_inline("/me `\$sitzt vor einer kahlen Wand und wacht gerade aus einer Ohnmacht auf. Seltsam, alle Anwesenden könnten schwören, dass dort schon seit geraumer Zeit niemand gesessen hatte ...");
		injectcommentary(village, "", $comment, $schema=false);
		addnav("Zurück","village.php");	
	page_footer();
}
?>