<?php
	switch(e_rand(1,10)){
        case 1:
        case 2:
        case 3:
        output("`@Nach den heutigen Strapazen kommt Dir ein solcher Ort gerade recht. Du legst Dich ins Gras und "
			  ."schlie�t die Augen ...`n`n`@Als Du wieder erwachst, liegst Du noch immer im Gras - aber der Turm "
			  ."ist verschwunden. Verwundert stehst Du auf und verl�sst die friedliche Lichtung. Du musst wohl "
			  ."getr�umt haben.`n`n");
        $turns3 = e_rand(4,9);
        output("`^�ber die Ma�en ausgeruht erh�ltst Du %s Waldk�mpfe hinzu!", $turns3);
        $session['user']['turns']+=$turns3;
		$session['user']['specialinc']="";
        break;
        case 4:
		case 5:
		output("`@Nach den heutigen Strapazen kommt Dir ein solcher Ort gerade recht. Du legst Dich ins Gras und "
			  ."schlie�t die Augen ...`n`n");
        output("`@Dein Schlaf ist unruhig ... und Deine Tr�ume sind es auch ... Als Du wieder erwachst, liegst Du "
			  ."noch immer im Gras - aber der Turm ist verschwunden. Nichts bleibt, nur ein Satz, von dem Du "
			  ."getr�umt haben musst: `#'Hier ist es zu gef�hrlich ...'`n`n");
        output("`^Irgendjemand scheint Dir 30 Goldst�cke zugesteckt zu haben ...");
		$session['user']['gold'] += 30;
        $session['user']['specialinc']="";
        break;
        case 6:
		case 7:
		case 8:
		output("`@Nach den heutigen Strapazen kommt Dir ein solcher Ort gerade recht. Du legst Dich ins Gras und "
			  ."schlie�t die Augen ...`n`n");
        output("`@Dein Schlaf ist unruhig ... und Deine Tr�ume sind es auch ... Als Du wieder erwachst, liegst Du "
			  ."noch immer im Gras - aber der Turm ist verschwunden. Nichts bleibt, nur ein unwohles Gef�hl in der "
			  ."Magengegend.`n`n");
        if ($session['user']['gold']>0) output("`^Man hat Dich im Schlaf um all Dein Gold erleichtert!");
		$session['user']['gold'] = 0;
        $session['user']['specialinc']="";
        break;
		case 9:
        case 10:
		output("`@Nach den heutigen Strapazen kommt Dir ein solcher Ort gerade recht. Du legst Dich ins Gras und "
			  ."schlie�t die Augen ...`n`n");
        output("`@Dein Schlaf ist ruhig ... und Deine Tr�ume sind es auch ... Als Du wieder erwachst, liegst Du "
			  ."noch immer im Gras - und jemand steht vor Dir. Er hat langes, dunkles Haar, das von einem Reif "
			  ."gehalten wird, und tr�gt eine strahlendwei�e Robe, die das Zeichen des Poseidon ziert. Mit dem "
			  ."ehrfurchtgebietenden Blick eines Mannes, der den G�ttern entstammt sagt er:`n`#'Ich wei�, wer Du "
			  ."bist, %s.`# Im Schlaf hast Du mir alles �ber Dich erz�hlt, was ich wissen wollte. Deine gr��te "
			  ."Angst gilt dem Drachen ... nun, wenn es weiter nichts ist: Trink von diesem Ambrosia ...'`n`n"
			  ."`@Bereits nach einem winzigen Schluck schl�fst Du wieder ein ... Und als Du aufwachst, befindest "
			  ."Du Dich allein auf einer leeren Lichtung.`n`n", $session['user']['name']);
        output("`n`n`@Du erh�ltst `^drei`@ permanente Lebenspunkte!");
        $session['user']['maxhitpoints']+=3;
        $session['user']['hitpoints']+=3;
        addnav("T�gliche News","news.php");
        addnav("Zur�ck zum Wald.","forest.php");
        addnews("`#Bellerophontes`2 lie� `@%s`2 am Trank der G�tter nippen!", $session['user']['name']);
        $session['user']['specialinc']="";
        break;
	}
?>
