<?php
	output("`@Nun stehst Du vor ihm, einem verwitterten, mit Efeu bewachsenen Wehrturm, der von den Überresten "
	  	  ."einer einstigen Mauer umgeben ist. Den Eingang bildet eine schwere Eichentür, die kein Zeichen der "
		  ."Abnutzung aufweist. An einem Pfosten ist ein weißes Pferd mit Flügeln angebunden; ein Pegasus, der "
		  ."friedlich grast, und an dessem Sattel ein praller Lederbeutel hängt. Schaust Du nach oben, erblickst "
		  ."Du einen Balkon.");
    output("`n`nWas wirst Du tun?");
    output("`n`n<a href='forest.php?op=klopfen'>An die schwere Eichentür klopfen.</a>",true);
    output("`n`n<a href='forest.php?op=rufen'>Zum Balkon hinaufrufen.</a>",true);
    output("`n`n<a href='forest.php?op=stehlen'>Zu dem Pegasus gehen und den Beutel stehlen.</a>",true);
    output("`n`n<a href='forest.php?op=oeffnen'>Versuchen, die Eichentür zu öffnen, um unbemerkt hineinzugelangen.</a>",true);
    output("`n`n<a href='forest.php?op=klettern'>Über das Efeu zum Balkon hinaufklettern.</a>",true);
    output("`n`n<a href='forest.php?op=ausruhen'>Diesen auf eine besondere Art friedlichen Ort zum Ausruhen nutzen.</a>",true);
    output("`n`n<a href='forest.php?op=gehen'>Dem Ganzen den Rücken kehren - das sieht doch sehr verdächtig aus ...</a>",true);
    addnav("", $from . "op=klopfen");
    addnav("", $from . "op=rufen");
    addnav("", $from . "op=stehlen");
    addnav("", $from . "op=oeffnen");
    addnav("", $from . "op=klettern");
    addnav("", $from . "op=ausruhen");
    addnav("", $from . "op=gehen");
    addnav("Klopfen.", $from . "op=klopfen");
    addnav("Rufen.", $from . "op=rufen");
    addnav("Stehlen.", $from . "op=stehlen");
    addnav("Öffnen.", $from . "op=oeffnen");
    addnav("Klettern.", $from . "op=klettern");
    addnav("Ausruhen.", $from . "op=ausruhen");
    addnav("Gehen.", $from . "op=gehen");
?>
