<?php
addnav("Kapitel");
		addnav("Waffenmeister", "runmodule.php?module=biblio&op1=spec&buch=waffenmeister");
		if (httpget('buch')=="waffenmeister") {
			output("`c`b%sDie Waffenmeister`b`c`n", $ccode);
			output("`nMan unterscheidet zwei Richtungen: Die der Elfen und die der Menschen. W�hrend die Menschen auf eiserne Disziplin und harten Drill pochen, vervollkommnen die Elfen ihre Kampfkunst durch meditative �bungen, die dazu dienen sollen, das waffen als Teil des K�rpers zu begreifen. L�ngst bedienen sich aber auch die anderen V�lker dieser Richtungen, wobei die Echsen eher zu dem Weg der Elfen neigen und die Zwerge und Trolle zu dem der Menschen.`nWaffenmeister k�nnen jeder Gesinnung sein, man muss nur einen entsprechenden Lehrmeister finden.`n");
		}
?>
