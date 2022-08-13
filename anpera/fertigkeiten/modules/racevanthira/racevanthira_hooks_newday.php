<?php
if ($session['user']['race']==$race){
				racevanthira_checkcity();
				//Da Vanthira weniger Gefallen für die Wiedererweckung zahlen, bekommen sie hier die Differenz zurück.
				if ($session['user']['spirits']==-6){
				$kosten=get_module_setting ("wiedergeburt");
				$session['user']['deathpower']+=100-$kosten;
				output("`3`nDurch das Portal der Vanthira kehrst Du neugierig und voller Tatendrang, aber etwas erschöpft, zurück in die Welt der Lebenden.`n");  
			}else output("`3`nDu fragst Dich, was der heutige Tag wohl bringen mag ... und vor allem: Wo er am schönsten sein wird, hier oder im Schattenreich. Etwas unentschlossen, aber neugierig trittst Du nach draußen.`n");
			
			//Vanthira wollen neutral bleiben
				if  (is_module_active('alignment')){
					$alignment = get_align();
					$evil=get_module_setting('evilalign','alignment');
					$good=get_module_setting('goodalign','alignment');
					if ($alignment < 43 || $alignment > 57){
						if ($alignment < $evil){
							output("`3`bWeil Du merkst, dass Dein Karma sehr im Ungleichgewicht ist, machst Du aber erstmal ein paar intensive Konzentrationsübungen, was Dich einen Waldkampf kostet.`b`n");
							$session[user][turns]--;
							align("+4");
						}else if ($alignment > $good){
							output("`3`bWeil Du merkst, dass Dein Karma sehr im Ungleichgewicht ist, machst Du aber erstmal ein paar intensive Konzentrationsübungen, was Dich einen Waldkampf kostet.`b`n");
							$session[user][turns]--;
							align("-4");
						}else
							output("`3`bWeil Du merkst, dass Dein Karma etwas im Ungleichgewicht ist, machst Du auf dem Weg zum Dorfplatz ein paar Konzentrationsübungen.`b`n");
							if ($alignment < 43) align("2");
							else if ($alignment > 57) align("-2");
					}
				}
			}
?>
