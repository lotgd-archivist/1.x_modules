<?php

function wettkampf_tuer_run_private($op){
	global $session;
	page_header("Eine T�r ...");
		require_once("lib/commentary.php");
		output("`2Du n�herst Dich der seltsamen T�r, die Du hier noch nie zuvor gesehen hast ... und sp�rst, dass Dein seltsamer Schl�ssel hei�er wird. An der kleinen, grauen T�r angekommen, die sich kaum von der Mauer unterscheidet, die den Garten umgibt, holst Du ihn hervor. Nat�rlich, er passt ...`n`n");	
		output("`2Vorsichtig schiebst Du sie auf und betrittst einen dunklen Raum, der nur durch einen schwachen, aber dennoch blendenden Schein erleuchtet wird. Du n�herst Dich dem Licht und erkennst eine Person ... `n`n");
		output("`2Sie sitzt auf einen Stuhl gekauert und tippt mit den Fingern auf einem schmalen, grauen Kasten herum ... Du gehst noch n�her heran und siehst, dass die Lampe rechteckig ist ... Aber es ist keine Lampe, denn wie durch ein Wunder ist eine Art Schrift darauf zu erkennen ...`n`n");	
		output("`2Pl�tzlich dreht sich die fremde Gestalt herum - offenbar ist ihr Stuhl drehbar: `#'Wer zum --'`2`n`n");	
		output("`2Sie starrt Dich an - und Du starrst ... `bDich`b selbst an! Wie ist das ... m�glich ... Erschrocken gehst Du einen Schritt zur�ck ... ein Doppelg�nger?`n`n");	
		output("`2Dir schwinden die Sinne. Als Du wieder aufwachst, stehen einige B�rger um Dich herum. Du befindest Dich auf dem Gemeindeplatz und der Schrecken sitzt noch immer tief. Fast m�chtest Du weinen ...`n`n");
		$exp_bonus=round($session[user][experience]*0.2);
		$session[user][experience]+=$exp_bonus;
		output("`2Wenngleich die Erinnerung nur vage ist, hast Du durch diese Begegnung `^%s`2 Erfahrungspunkte hinzugewonnen!", $exp_bonus);	
		set_module_setting("bgegenstand5", "");
		$comment=translate_inline("/me `\$sitzt vor einer kahlen Wand und wacht gerade aus einer Ohnmacht auf. Seltsam, alle Anwesenden k�nnten schw�ren, dass dort schon seit geraumer Zeit niemand gesessen hatte ...");
		injectcommentary(village, "", $comment, $schema=false);
		addnav("Zur�ck","village.php");	
	page_footer();
}
?>