<?php
	require_once("modules/wettkampf/wettkampf_lib.php");
			
			$fest=get_module_setting("fest", "wettkampf"); //Fest ja/nein
			$tage=get_module_setting("tage", "wettkampf"); //Wie lange dieses/bist zum nächsten Fest?
			$dauer0=get_module_setting("dauer0", "wettkampf"); //Einstellung: jeweils nächstes
			$dauer1=get_module_setting("dauer1", "wettkampf"); //Einstellung: jeweilige Dauer
			$tage-=1;
			set_module_setting("tage", $tage, "wettkampf");
					
			if ($tage == 1 && $fest == 1) addnews("`@`bHeute ist die letzte Möglichkeit, am Fest der Völker teilzunehmen!`b", true); //Fest fast zuende
			else if ($tage == 0 && $fest == 1) fest_endet(); //Fest endet 
			else if ($tage == 0 && $fest == 0) fest_beginnt(); //Fest beginnt
	return $args;
?>
