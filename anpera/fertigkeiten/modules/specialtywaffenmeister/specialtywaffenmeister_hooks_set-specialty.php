<?php
if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("%sSchon von Kindheit an wurdest du im Gebrauch aller Waffen ausgebildet. ", $ccode);
			output("Du hast dich als nat�rliches Talent im Umgang mit Waffen aller Art erwiesen und beherrschst sie nun wie ".($session['user']['sex']?"keine andere":"kein anderer").". ");
			output("Voller Ungeduld und Neugier st�rzt du dich nun ins Leben, deine Ausbildung wird dir dabei bestimmt gute Dienste erweisen.");
			//output("`nMan unterscheidet zwei Richtungen: Die der Elfen und die der Menschen. W�hrend die Menschen auf eiserne Disziplin und harten Drill pochen, vervollkommnen die Elfen ihre Kampfkunst durch meditative �bungen, die dazu dienen sollen, das waffen als Teil des K�rpers zu begreifen. L�ngst bedienen sich aber auch die anderen V�lker dieser Richtungen, wobei die Echsen eher zu dem Weg der Elfen neigen und die Zwerge und Trolle zu dem der Menschen.`nWaffenmeister k�nnen jeder Gesinnung sein, man muss nur einen entsprechenden Lehrmeister finden. Allgemein verbindet man mit dieser hohen Kunst jedoch das Bild der gro�en, ehrw�rdigen Paladine, der Ritter reinsten Geistes.`n");
		}
?>
