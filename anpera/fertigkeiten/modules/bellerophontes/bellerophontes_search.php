<?php
    output("`@Vor Dir liegt ein langer, gerader Waldweg, über dem die Bäume zu dicht wachsen, als dass man reiten "
		  ."könnte. Es ist schon seit langem nichts Aufregendes mehr passiert - da erblickst Du, als Du eine "
		  ."Kreuzung erreichst, plötzlich etwas am Ende des ausgetrampelten Pfades: einen Turm im dunstigen "
		  ."Zwielicht des Waldes.`n`n");
    output("Was wirst Du tun?`n`n <a href='forest.php?op=weiter'>Weitergehen und versuchen, den Turm zu finden,</a>`n`n"
		  ."oder <a href='forest.php?op=abbiegen1'>hier abbiegen und den Weg verlassen.</a>`n", true);
    addnav("","forest.php?op=weiter");
    addnav("","forest.php?op=abbiegen1");
    addnav("Weitergehen.", $from . "op=weiter");
    addnav("Abbiegen.", $from . "op=abbiegen1");
?>
