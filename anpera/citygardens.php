<?php

function citygardens_getmoduleinfo(){
        $info = array(
                "name"=>"St�dteg�rten",
                "author"=>"Daisuke; Texte von Daisuke, Lucia und Moschus",
                "version"=>"1.0",
                "category"=>"Garten",
                "requires"=>array(
                        "cities" => "1.0|Eric Stevens, core_module",
                        "racedwarf" => "1.0|Eric Stevens, core_module",
                        "raceelf" => "1.0|Eric Stevens, core_module",
                        "racehuman" => "1.0|Eric Stevens, core_module",
                        "racetroll" => "1.0|Eric Stevens, core_module",
            ),
        );
        return $info;
}

function citygardens_install(){
        module_addhook("gardens");
        return true;
}

function citygardens_uninstall(){
        return true;
}

function citygardens_dohook($hookname,$args){
        global $session;
	    $city = getsetting("villagename", LOCATION_FIELDS);
	    $capital = $session['user']['location']==$city;
        $dvname = get_module_setting("villagename", "racedwarf");
        $evname = get_module_setting("villagename", "raceelf");
        $hvname = get_module_setting("villagename", "racehuman");
        $tvname = get_module_setting("villagename", "racetroll");
        require_once("lib/http.php");
        switch($hookname){
                case "gardens":
                addnav("Herumlaufen");
               if ($session['user']['location'] == $dvname) {
            addnav("Zwerggarten","runmodule.php?module=citygardens&op=dwa");
               } elseif ($session['user']['location'] == $evname) {
                addnav("romantischer See","runmodule.php?module=citygardens&op=elf");
               } elseif ($session['user']['location'] == $hvname) {
                addnav("romantischer Garten","runmodule.php?module=citygardens&op=hum");
               } elseif ($session['user']['location'] == $tvname) {
                addnav("die Sumpfg�rten","runmodule.php?module=citygardens&op=tro");
               } elseif ($session['user']['location'] == $capital) {
                addnav("japanischer Garten","runmodule.php?module=citygardens&op=cap");
               }
                addnav("Sonstiges");
                break;
        }
        return $args;
}

function citygardens_run(){
        global $session;
        require_once("lib/commentary.php");
        $op = httpget('op');
        if($op=="dwa"){
            page_header("Garten der Zwerge");
            output("`c`b`#Garten der Zwerge`b`c`n`n");
            output("Hier ist der Garten der Zwerge wie man es sieht,da alles sehr klein ist.");
            output("Blumen finden sich �berall, genau wie die B�ume und kleine andere Gew�chse.");
            output("Es ist ein Wunderssch�ner aber etwas kleiner Garten. Auf einer kleinen Lichtung, auf der kaum B�ume sind daf�r aber �berall Blumen und kleine Heilkr�uter.");
            addcommentary();
            viewcommentary("dwa","Hier fl�stern",20,"fl�stert");
                addnav("Wandern");
                addnav("Zur�ck zum Garten","gardens.php");
            villagenav();
                }
        if($op=="elf"){
            page_header("Der romantische See");
            output("`c`b`#Der romantische See Glorfindals`b`c`n`n");
            output("Als du auf einem kleinen Weg im Garten l�ufst entdeckst du pl�tzlich ein dir unbekanntes Gebiet des Gartens.");
            output("Du siehst den See vor dir, der durch das Bl�tterdach der B�ume gesch�tzt scheint.");
            output("Das Licht das durch die gewaltigen Baumkronen durchblinzelt reflektiert auf den Wellen des Sees und kleine Lichtschwaden werden sichtbar.");
            output("`3Eine kleine Fee fl�stert dir zu das du dich hier `^ leise `3 mit deinen Freunden unterhalten kannst.`n`n");                addcommentary();
            viewcommentary("elf","Hier fl�stern",20,"fl�stert");
            addnav("Wandern");
            addnav("Zur�ck zum Garten","gardens.php");
            villagenav();
                }
        if($op=="hum"){
            page_header("romantischer Garten");
            output("`c`b`^romantischer Garten Romars`b`c`n`n");
            output("Du gehst durch geschmiedetes Eisentor und betritts den Garten, indem gro�en Eichen und ein romatischer See liegt. Auf dem See schwimmen ein paar Schw�ne.");
            output("Im Ge�st der Eichen singen die V�gel ihre wunderbaren Lieder. Als du weiter in den Garten wanderst, entdeckst du kleine B�nkchen, auf denen sich so manches Liebespaar niederl�sst.");
            output("Es scheint, als w�rde die Zeit hier stillstehen.");
            addcommentary();
            viewcommentary("hum","Hier fl�stern",20,"fl�stert");
            addnav("Wandern");
            addnav("Zur�ck zum Garten","gardens.php");
            villagenav();
                }
       if($op=="tro"){
                page_header("die Sumpfg�rten");
                output("`c`b`%Trollgarten`b`c`n`n");
                output("Du gehst durch das Eichentor, das von gro�en Farnen umgeben ist. Der sch�ne Garten riecht herrlich nach exotischen Sumpfblumen.");
                output(" Von den s��en D�ften befl�gelt wagst du dich weiter vor. Du gehst an warmen Heilquellen vorbei und l�sst dich auf einer Bank nieder und h�rst den singenden V�geln zu, die um den See in der mitte des Gartens fliegen.");
                output("Unter den hohen Baumfarnen gibt es viele Pl�tze zum entspannen.`n`n");
                addcommentary();
                viewcommentary("tro","Hier fl�stern",20,"fl�stert");
                addnav("Wandern");
                addnav("Zur�ck zum Garten","gardens.php");
                villagenav();
                }
        if($op=="cap"){
                page_header("Der japanische Garten ");
                output("`c`b`#Der japanische Garten %s`b`c`n`n",$city);
                output("Als du auf einem kleinen Weg im Garten l�ufst entdeckst du pl�tzlich ein dir unbekanntes Gebiet des Gartens.");
                output("Du betrittst den japanischen Garten, der mit asiatischer Dekoration �berzogen ist.");
                output("Durch das Bl�tterdach der asiatischen Gew�chse sieht es aus als w�den �berall Lichtschwaden sein.");
                output("`Eine japanische Dame sagt dir das du dich hier `^ ungest�rt mit deinen Freunden unterhalten kannst`n`n");
                addcommentary();
                viewcommentary("cap","Hier fl�stern",20,"fl�stert");
                addnav("Wandern");
                addnav("Zur�ck zum Garten","gardens.php");
                villagenav();
                }
page_footer();
}
?>