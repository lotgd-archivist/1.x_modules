<?php
  if (is_module_active("racehuman")) {
        $city = get_module_setting("villagename", "racehuman");
    } else {
        $city = getsetting("villagename", LOCATION_FIELDS);
    }
    $race = "Vampir";  

if ($session['user']['race']==$race){ // it helps if you capitalize correctly
            output("`4Als Kind der Nacht regenerierst Du am Tage in Deiner Gruft oder in einem ähnlichen, sicheren Versteck und erwachst außerordentlich erholt. Der tägliche Bonus auf Deine Lebenspunkte erhöht sich, je älter Du bist (Erlangung weiterer Titel).`n`n");
            output("Jedesmal, wenn Du aufwachst, dürstet es Dich nach frischem Blut, weshalb Du unter Adrenalin stehst und Deine tägliche Jagd mit erhöhter Kraft beginnst. Wielange sie anhält variiert je nachdem, wie durstig Du Dich jeweils fühlst.`n`nWenn Du Deinen Blutdurst befriedigt hast, lässt der Adrenalinschub nach - Dir ist nicht mehr nach weiterer Jagd.`n`n");
            if (is_module_active('alignment')) output("Zudem wirst Du es körperlich zu spüren bekommen, sollte sich Dein Karma in den Bereich des Guten bewegen.");
            if (is_module_active('biblio')) output("`n`n`bLies bitte unbedingt den Eintrag in der Bibliothek, bevor Du anfängst, diese Rasse im Rollenspiel zu spielen!`b");
            if (is_module_active("cities")) {
                if ($session['user']['dragonkills']==0 &&
                        $session['user']['age']==0){
                    //new farmthing, set them to wandering around this city.
                    set_module_setting("newest-$city",
                            $session['user']['acctid'],"cities");
                }
                set_module_pref("homecity",$city,"cities");
                $session['user']['location']=$city;
            }
        }
?>
