<?php
    output("`@Du biegst an der Kreuzung ab und verlässt den Weg.`n`n");
    output("`^Bis hierher zu gelangen hat Dich jedoch bereits einen Waldkampf gekostet!");
    $session['user']['turns']-=1;
    $session['user']['specialinc']="";
?>
