<?php
output("`3`n`nIn der Nähe des Mausoleums befinden sich zwei große, dicht beieinanderstehende Säulen: das Portal der Vanthira. Mit seiner Hilfe kann das Volk der Vanthira leichter zu den Lebenden zurückkehren.");
			if ($session['user']['race']==$race){
				$kosten=get_module_setting ("wiedergeburt");
				if ($session['user']['deathpower']>=$kosten) {
					output("`#`n`n`bGleißendes Licht erstrahlt von beiden Seiten - es ist geöffnet!`b");
					output("`@`n`n<a href='newday.php?resurrection=true'>Ich schreite hindurch! (70 Gefallen)</a>", true);
					addnav("","newday.php?resurrection=true");
				}else{
					output("`3 Aber noch hat es sich nicht geöffnet ...`n");		
				}
			}
?>
