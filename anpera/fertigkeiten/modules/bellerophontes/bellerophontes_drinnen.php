<?php
	require_once("lib/fert.php");
	
	global $session;		
	//FW
		$schleichen=get_fertigkeit(schleichen);
	
	//Mod je nachdem, wie besch�ftigt Bellerophontes gerade ist
		$mod=e_rand(-10,10);
		
	//Die Probe
		$probe=probe($schleichen, $mod);
		$wert=$probe[wert];
	
	if ($wert < 50) $ergebnis=1;
	else if ($wert > 50 && $wert < 0) $ergebnis=e_rand(2,7);
	else if ($wert > 0) $ergebnis=e_rand(4,10); 
	
    switch($ergebnis){
        case 1:
        case 2:
        output("`@Das Wesen ist tot. Der Wunde nach muss es mit einem einzigen Schwertstreich erlegt worden sein. "
			  ."Wenn da nur nicht die Verbrennungen w�ren ... `@Als Du pl�tzlich die schnellen Schritte schwerer "
			  ."Eisenstulpen auf der Treppe vernimmst, greifst Du panisch nach dem ersten Gegenstand, den Du zu "
			  ."fassen bekommst - ganz ohne Beute willst Du diese Gefahr nicht auf Dich genommen haben. Es ist ein "
			  ."bronzenes Amulett - das Du w�nschtest, nun lieber nicht in der Hand zu halten. Vor Dir steht der "
			  ."griechische Heros Bellerophontes, Reiter des Pegasus und Bezwinger der Chim�ren!");
        output("`#'Wer bist Du, Wurm, dass Du es wagst, mich zu bestehlen?!' `@`n`n Er erweist sich als wahrer "
			  ."Meister der Rhetorik und streckt Dich kurzerhand mit seinem Flammenschwert nieder.");
        output("`$`n`nDu bist tot!");
        output("`n`@Du verlierst `$%s`@ Erfahrungspunkte und all Dein Gold!", round($session['user']['experience']*0.07));
        output("`n`@Du kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.93);
        addnav("T�gliche News","news.php");
        addnews("`\$%s ebenso gemeine wie unf�hige `\$%s `\$%s `4wurde von `#Bellerophontes`4 mit einem Flammenschwert in der Mitte zerteilt.", translate($session['user']['sex']?"Die":"Der"), translate($session['user']['sex']?"Diebin":"Dieb"), $session['user']['name']);
        if (is_module_active('pdvdiebstahl')){
				$erwischt=get_module_pref("erwischt","pdvdiebstahl");
				$erwischtneu=$erwischt+1;
				set_module_pref("erwischt", $erwischtneu, "pdvdiebstahl");
			}
        $session['user']['specialinc']="";
        break;
        case 3:
        case 4:
        case 5:
        output("`@Der Wunde nach muss das Wesen mit einem einzigen Schwertstreich erlegt worden sein. Wenn da nur "
			  ."nicht die Verbrennungen w�ren ... Na, Hauptsache es ist tot. Als Du pl�tzlich die schnellen "
			  ."Schritte schwerer Eisenstulpen auf der Treppe vernimmst, greifst Du panisch nach dem ersten "
			  ."Gegenstand, den Du zu fassen bekommst - ganz ohne Beute willst Du diese Gefahr nicht auf Dich "
			  ."genommen haben. Es ist ein bronzenes Amulett - das Dir aus der Hand rutscht, als Du Dich umdrehst. "
			  ."Vor Dir steht der griechische Heros Bellerophontes, Reiter des Pegasus und Bezwinger der Chim�ren! "
			  ."Er rei�t sein flammendes Schwert nach oben, um zum Schlag auszuholen. Jetzt ist es aus!");
        output("`#'Runter mit Dir, Du Wurm!'`@ Reflexartig tust Du, wie Dir gehei�en und sp�rst die Hitze des "
			  ."Schwertes an Deiner Wange entlangsausen. Wi-der-lich-es, gr�nes Chim�renblut bespritzt Dich �ber "
			  ."und �ber. Dankbar schaust Du auf, Deinem Retter ins Gesicht.`n`n `#'Das w�re beinahe Dein Tod "
			  ."gewesen, Du sch�biger Dieb. Aber diesmal sei Dir der Schrecken Lehre genug!' `@Bellerophontes ist "
			  ."gn�dig und jagt Dich mit Fu�tritten nach drau�en.");
        output("`n`n`@Du erh�ltst `^%s`@ Erfahrungspunkte!", round($session['user']['experience']*0.08));
        output("`@`n`nDu verlierst `$2`@ Charmepunkte!");
        $session['user']['charm']-=2;
        output("`n`n`@Auf der Flucht hast Du die H�lfte Deines Goldes verloren!`n");
        $session['user']['experience']=round($session['user']['experience']*1.08);
        $session['user']['gold']*0.50;
        $session['user']['specialinc']="";
        break;
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
		output("`@Das Wesen ist tot. Der Wunde nach muss es mit einem einzigen Schwertstreich erlegt worden sein. "
			."Wenn da nur nicht die Verbrennungen w�ren ... Als Du pl�tzlich die schnellen Schritte schwerer "
			."Eisenstulpen auf der Treppe vernimmst, greifst Du panisch nach dem ersten Gegenstand, den Du zu "
			."fassen bekommst - ganz ohne Beute willst Du diese Gefahr nicht auf Dich genommen haben. Es ist ein "
			."bronzenes Amulett ...`n`n`@Du hast dem griechischen Heros Bellerophontes das Amulett des Lebens gestohlen!");
			if (is_module_active('alignment')) align("-5");
			if (is_module_active('pdvdiebstahl')){
				$erwischt=get_module_pref("erwischt","pdvdiebstahl");
				$erwischtneu=$erwischt+1;
				set_module_pref("erwischt", $erwischtneu, "pdvdiebstahl");
			}
        $session['user']['maxhitpoints']++;
        $session['user']['hitpoints']++;
        output("`n`n`@Du erh�ltst `^%s`@ Erfahrungspunkte!", round($session['user']['experience']*0.05));
        output("`n`n`@Du erh�ltst `^einen`@ permanenten Lebenspunkt!");
        $session['user']['experience']=round($session['user']['experience']*1.05);
        $session['user']['specialinc']="";
        break;
}
?>
