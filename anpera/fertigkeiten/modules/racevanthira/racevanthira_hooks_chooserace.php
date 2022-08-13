<?php
	$mindk = get_module_setting("mindk");
			if ($session['user']['dragonkills'] < $mindk){
				output("`3Die Vanthira, die Wanderer zwischen den Welten, sind ein uraltes Volk ohne eigene Kultur. Sie lieben das Leben gleichermaßen wie den Tod und sind dementsprechend offen für alle Eindrücke, seien sie leben-, seien sie todbringend. Sie sehen sich selbst als ewige Reisende und Lernende an. Ihr Äußeres gleicht dem eines Menschen, doch sie haben silbern schimmerndes, weißes Haar.`n`b`4[Diese Rasse steht nur Spielern zur Verfügung, die bereits %s Titelsteigerungen hinter sich haben.]`b`n`n", $mindk, true);
			}else{
				output("<a href='newday.php?setrace=Vanthira$resline'>Die Vanthira, `3die Wanderer zwischen den Welten, sind ein uraltes Volk ohne eigene Kultur. Sie lieben das Leben gleichermaßen wie den Tod und sind dementsprechend offen für alle Eindrücke, seien sie leben-, seien sie todbringend. Sie sehen sich selbst als ewige Reisende und Lernende an. Ihr Äußeres gleicht dem eines Menschen, doch sie haben silbern schimmerndes, weißes Haar.`n`n", true);
				addnav("`3Vanthira`0","newday.php?setrace=$race$resline");
				addnav("","newday.php?setrace=$race$resline");
			}
?>
