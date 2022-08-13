<?php
if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("%sSchon von Kindheit an wurdest du im Gebrauch aller Waffen ausgebildet. ", $ccode);
			output("Du hast dich als natürliches Talent im Umgang mit Waffen aller Art erwiesen und beherrschst sie nun wie ".($session['user']['sex']?"keine andere":"kein anderer").". ");
			output("Voller Ungeduld und Neugier stürzt du dich nun ins Leben, deine Ausbildung wird dir dabei bestimmt gute Dienste erweisen.");
			//output("`nMan unterscheidet zwei Richtungen: Die der Elfen und die der Menschen. Während die Menschen auf eiserne Disziplin und harten Drill pochen, vervollkommnen die Elfen ihre Kampfkunst durch meditative Übungen, die dazu dienen sollen, das waffen als Teil des Körpers zu begreifen. Längst bedienen sich aber auch die anderen Völker dieser Richtungen, wobei die Echsen eher zu dem Weg der Elfen neigen und die Zwerge und Trolle zu dem der Menschen.`nWaffenmeister können jeder Gesinnung sein, man muss nur einen entsprechenden Lehrmeister finden. Allgemein verbindet man mit dieser hohen Kunst jedoch das Bild der großen, ehrwürdigen Paladine, der Ritter reinsten Geistes.`n");
		}
?>
