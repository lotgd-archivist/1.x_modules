<?php
  if (is_module_active("racehuman")) {
        $city = get_module_setting("villagename", "racehuman");
    } else {
        $city = getsetting("villagename", LOCATION_FIELDS);
    }
    $race = "Vampir";  

if ($session['user']['race']==$race){ // it helps if you capitalize correctly
            output("`4Als Kind der Nacht regenerierst Du am Tage in Deiner Gruft oder in einem �hnlichen, sicheren Versteck und erwachst au�erordentlich erholt. Der t�gliche Bonus auf Deine Lebenspunkte erh�ht sich, je �lter Du bist (Erlangung weiterer Titel).`n`n");
            output("Jedesmal, wenn Du aufwachst, d�rstet es Dich nach frischem Blut, weshalb Du unter Adrenalin stehst und Deine t�gliche Jagd mit erh�hter Kraft beginnst. Wielange sie anh�lt variiert je nachdem, wie durstig Du Dich jeweils f�hlst.`n`nWenn Du Deinen Blutdurst befriedigt hast, l�sst der Adrenalinschub nach - Dir ist nicht mehr nach weiterer Jagd.`n`n");
            if (is_module_active('alignment')) output("Zudem wirst Du es k�rperlich zu sp�ren bekommen, sollte sich Dein Karma in den Bereich des Guten bewegen.");
            if (is_module_active('biblio')) output("`n`n`bLies bitte unbedingt den Eintrag in der Bibliothek, bevor Du anf�ngst, diese Rasse im Rollenspiel zu spielen!`b");
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
