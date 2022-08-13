<?php
  if (is_module_active("racehuman")) {
        $city = get_module_setting("villagename", "racehuman");
    } else {
        $city = getsetting("villagename", LOCATION_FIELDS);
    }

if ($session['user']['race']==$race){ // it helps if you capitalize correctly
				output("`3Als Wanderer zwischen den Welten fällt es Dir leichter als jedem anderen Wesen, `\$Ramius`3 gnädig zu stimmen. Deshalb kannst Du schon mit nur 70 Gefallen wiederauferstehen.`n`nDu bist ein Wesen, das sich nicht für eine der beiden Welten entscheiden kann - mal sehnt es sich nach der einen, mal nach der anderen. ");
				if (is_module_active('alignment')) output("Deshalb solltest Du stets darum bemüht sein, neutral gesinnt zu bleiben. ");
				output("`n`nDein Äußeres gleicht dem eines Menschen, doch Du hast silbern schimmerndes, weißes Haar.`n`n");
				if (is_module_active('biblio')) output("`bLies bitte unbedingt den Eintrag in der Bibliothek, bevor Du anfängst, diese Rasse im Rollenspiel zu spielen!`b");
				//Vanthira können sich nicht für eine Seite entscheiden - weder nur für das Licht noch nur für die Dunkelheit.
				if ($session['user']['ctitle']=="`\$Ramius´ ".($session[user][sex]?"Sklavin":"Sklave").""){
					output("`n`n`bWichtig:`b`n`3Da Du als Vanthira wiedergeboren wurdest, hat Dein bisheriger Meister `\$Ramius`3 keinen Einfluss mehr auf Dich, denn Vanthira können sich nicht für nur eine der beiden Seiten - Licht und Dunkelheit - entscheiden.");
					$session[user][ctitle] ="";
					$session[user][name] ="".$session[user][title]." ".$session[user][login]."";
					addnews("`#%s `3wurde als Vanthira wiedergeboren und entkam auf diese Weise ".($session[user][sex]?"ihrem":"seinem")." Schicksal als ".($session[user][sex]?"Sklavin":"Sklave")." des `\$Ramius`3!",$session['user']['login']);		
				}
				if (is_module_active("cities")) {
					if ($session['user']['dragonkills']==0 && $session['user']['age']==0){
						set_module_setting("newest-$city",
						$session['user']['acctid'],"cities");
				}
				set_module_pref("homecity",$city,"cities");
				$session['user']['location']=$city;
				}
			}
?>
