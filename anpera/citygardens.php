<?php

function citygardens_getmoduleinfo(){
        $info = array(
                "name"=>"Städtegärten",
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
                addnav("die Sumpfgärten","runmodule.php?module=citygardens&op=tro");
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
            output("Blumen finden sich überall, genau wie die Bäume und kleine andere Gewächse.");
            output("Es ist ein Wundersschöner aber etwas kleiner Garten. Auf einer kleinen Lichtung, auf der kaum Bäume sind dafür aber überall Blumen und kleine Heilkräuter.");
            addcommentary();
            viewcommentary("dwa","Hier flüstern",20,"flüstert");
                addnav("Wandern");
                addnav("Zurück zum Garten","gardens.php");
            villagenav();
                }
        if($op=="elf"){
            page_header("Der romantische See");
            output("`c`b`#Der romantische See Glorfindals`b`c`n`n");
            output("Als du auf einem kleinen Weg im Garten läufst entdeckst du plötzlich ein dir unbekanntes Gebiet des Gartens.");
            output("Du siehst den See vor dir, der durch das Blätterdach der Bäume geschützt scheint.");
            output("Das Licht das durch die gewaltigen Baumkronen durchblinzelt reflektiert auf den Wellen des Sees und kleine Lichtschwaden werden sichtbar.");
            output("`3Eine kleine Fee flüstert dir zu das du dich hier `^ leise `3 mit deinen Freunden unterhalten kannst.`n`n");                addcommentary();
            viewcommentary("elf","Hier flüstern",20,"flüstert");
            addnav("Wandern");
            addnav("Zurück zum Garten","gardens.php");
            villagenav();
                }
        if($op=="hum"){
            page_header("romantischer Garten");
            output("`c`b`^romantischer Garten Romars`b`c`n`n");
            output("Du gehst durch geschmiedetes Eisentor und betritts den Garten, indem großen Eichen und ein romatischer See liegt. Auf dem See schwimmen ein paar Schwäne.");
            output("Im Geäst der Eichen singen die Vögel ihre wunderbaren Lieder. Als du weiter in den Garten wanderst, entdeckst du kleine Bänkchen, auf denen sich so manches Liebespaar niederlässt.");
            output("Es scheint, als würde die Zeit hier stillstehen.");
            addcommentary();
            viewcommentary("hum","Hier flüstern",20,"flüstert");
            addnav("Wandern");
            addnav("Zurück zum Garten","gardens.php");
            villagenav();
                }
       if($op=="tro"){
                page_header("die Sumpfgärten");
                output("`c`b`%Trollgarten`b`c`n`n");
                output("Du gehst durch das Eichentor, das von großen Farnen umgeben ist. Der schöne Garten riecht herrlich nach exotischen Sumpfblumen.");
                output(" Von den süßen Düften beflügelt wagst du dich weiter vor. Du gehst an warmen Heilquellen vorbei und lässt dich auf einer Bank nieder und hörst den singenden Vögeln zu, die um den See in der mitte des Gartens fliegen.");
                output("Unter den hohen Baumfarnen gibt es viele Plätze zum entspannen.`n`n");
                addcommentary();
                viewcommentary("tro","Hier flüstern",20,"flüstert");
                addnav("Wandern");
                addnav("Zurück zum Garten","gardens.php");
                villagenav();
                }
        if($op=="cap"){
                page_header("Der japanische Garten ");
                output("`c`b`#Der japanische Garten %s`b`c`n`n",$city);
                output("Als du auf einem kleinen Weg im Garten läufst entdeckst du plötzlich ein dir unbekanntes Gebiet des Gartens.");
                output("Du betrittst den japanischen Garten, der mit asiatischer Dekoration überzogen ist.");
                output("Durch das Blätterdach der asiatischen Gewächse sieht es aus als wüden überall Lichtschwaden sein.");
                output("`Eine japanische Dame sagt dir das du dich hier `^ ungestört mit deinen Freunden unterhalten kannst`n`n");
                addcommentary();
                viewcommentary("cap","Hier flüstern",20,"flüstert");
                addnav("Wandern");
                addnav("Zurück zum Garten","gardens.php");
                villagenav();
                }
page_footer();
}
?>