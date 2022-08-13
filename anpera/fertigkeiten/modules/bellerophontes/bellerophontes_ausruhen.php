<?php
	switch(e_rand(1,10)){
        case 1:
        case 2:
        case 3:
        output("`@Nach den heutigen Strapazen kommt Dir ein solcher Ort gerade recht. Du legst Dich ins Gras und "
			  ."schließt die Augen ...`n`n`@Als Du wieder erwachst, liegst Du noch immer im Gras - aber der Turm "
			  ."ist verschwunden. Verwundert stehst Du auf und verlässt die friedliche Lichtung. Du musst wohl "
			  ."geträumt haben.`n`n");
        $turns3 = e_rand(4,9);
        output("`^Über die Maßen ausgeruht erhältst Du %s Waldkämpfe hinzu!", $turns3);
        $session['user']['turns']+=$turns3;
		$session['user']['specialinc']="";
        break;
        case 4:
		case 5:
		output("`@Nach den heutigen Strapazen kommt Dir ein solcher Ort gerade recht. Du legst Dich ins Gras und "
			  ."schließt die Augen ...`n`n");
        output("`@Dein Schlaf ist unruhig ... und Deine Träume sind es auch ... Als Du wieder erwachst, liegst Du "
			  ."noch immer im Gras - aber der Turm ist verschwunden. Nichts bleibt, nur ein Satz, von dem Du "
			  ."geträumt haben musst: `#'Hier ist es zu gefährlich ...'`n`n");
        output("`^Irgendjemand scheint Dir 30 Goldstücke zugesteckt zu haben ...");
		$session['user']['gold'] += 30;
        $session['user']['specialinc']="";
        break;
        case 6:
		case 7:
		case 8:
		output("`@Nach den heutigen Strapazen kommt Dir ein solcher Ort gerade recht. Du legst Dich ins Gras und "
			  ."schließt die Augen ...`n`n");
        output("`@Dein Schlaf ist unruhig ... und Deine Träume sind es auch ... Als Du wieder erwachst, liegst Du "
			  ."noch immer im Gras - aber der Turm ist verschwunden. Nichts bleibt, nur ein unwohles Gefühl in der "
			  ."Magengegend.`n`n");
        if ($session['user']['gold']>0) output("`^Man hat Dich im Schlaf um all Dein Gold erleichtert!");
		$session['user']['gold'] = 0;
        $session['user']['specialinc']="";
        break;
		case 9:
        case 10:
		output("`@Nach den heutigen Strapazen kommt Dir ein solcher Ort gerade recht. Du legst Dich ins Gras und "
			  ."schließt die Augen ...`n`n");
        output("`@Dein Schlaf ist ruhig ... und Deine Träume sind es auch ... Als Du wieder erwachst, liegst Du "
			  ."noch immer im Gras - und jemand steht vor Dir. Er hat langes, dunkles Haar, das von einem Reif "
			  ."gehalten wird, und trägt eine strahlendweiße Robe, die das Zeichen des Poseidon ziert. Mit dem "
			  ."ehrfurchtgebietenden Blick eines Mannes, der den Göttern entstammt sagt er:`n`#'Ich weiß, wer Du "
			  ."bist, %s.`# Im Schlaf hast Du mir alles über Dich erzählt, was ich wissen wollte. Deine größte "
			  ."Angst gilt dem Drachen ... nun, wenn es weiter nichts ist: Trink von diesem Ambrosia ...'`n`n"
			  ."`@Bereits nach einem winzigen Schluck schläfst Du wieder ein ... Und als Du aufwachst, befindest "
			  ."Du Dich allein auf einer leeren Lichtung.`n`n", $session['user']['name']);
        output("`n`n`@Du erhältst `^drei`@ permanente Lebenspunkte!");
        $session['user']['maxhitpoints']+=3;
        $session['user']['hitpoints']+=3;
        addnav("Tägliche News","news.php");
        addnav("Zurück zum Wald.","forest.php");
        addnews("`#Bellerophontes`2 ließ `@%s`2 am Trank der Götter nippen!", $session['user']['name']);
        $session['user']['specialinc']="";
        break;
	}
?>
