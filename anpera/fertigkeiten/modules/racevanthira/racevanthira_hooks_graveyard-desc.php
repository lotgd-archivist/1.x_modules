<?php
output("`3`n`nIn der N�he des Mausoleums befinden sich zwei gro�e, dicht beieinanderstehende S�ulen: das Portal der Vanthira. Mit seiner Hilfe kann das Volk der Vanthira leichter zu den Lebenden zur�ckkehren.");
			if ($session['user']['race']==$race){
				$kosten=get_module_setting ("wiedergeburt");
				if ($session['user']['deathpower']>=$kosten) {
					output("`#`n`n`bGlei�endes Licht erstrahlt von beiden Seiten - es ist ge�ffnet!`b");
					output("`@`n`n<a href='newday.php?resurrection=true'>Ich schreite hindurch! (70 Gefallen)</a>", true);
					addnav("","newday.php?resurrection=true");
				}else{
					output("`3 Aber noch hat es sich nicht ge�ffnet ...`n");		
				}
			}
?>
