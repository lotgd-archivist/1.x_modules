<?php
	if (is_module_active('alignment')) align("-5");
	switch(e_rand(1,12)){ 
        case 1:
        case 2:
        output("`@Ein wahrhaft edles Tier ... wei� wie Milch in der Sonne ... umgeben von einem blendenden "
			  ."Schimmer ... `@Aber jetzt bleibt keine Zeit f�r Sentimentalit�ten! Du greifst nach dem Beutel "
			  ."und ... `n`n ... wirst von den Hufen des kr�ftigen Tiers gegen die Mauerreste geschleudert. "
			  ."Erschrocken, aber froh um Dein Leben rappelst Du Dich auf und rennst davon.");
        output("`n`n`@Du bekommst `^%s`@ Erfahrungspunkte hinzu, verlierst aber fast alle Deine Lebenspunkte!`n", round($session['user']['experience']*0.04));
        $session['user']['hitpoints']=1;
        $session['user']['experience']=round($session['user']['experience']*1.04);
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
        case 6:
		case 7:
		case 8:
		output("`@Ein wahrhaft edles Tier ... wei� wie Milch in der Sonne ... umgeben von einem blendenden Schimmer "
			  ."... `@Aber jetzt bleibt keine Zeit f�r Sentimentalit�ten! Du greifst nach dem Beutel und ... `n`n"
			  ."... wirst von seinem Gewicht zu Boden gerissen. Er ist voller Gold, wer h�tte das gedacht? Und je "
			  ."mehr du herausnimmst, desto schwerer scheint er zu werden! Gierig holst Du immer mehr heraus, und "
			  ."mehr, und mehr ... das Gold sprudelt nur so hervor - und hat Dich bald begraben.");
        output("`$`n`nDu bist tot!");
        output("`n`n`@Du verlierst `$%s`@ Erfahrungspunkte und all Dein Gold!`n", round($session['user']['experience']*0.05));
        output("`nDu kannst morgen weiterspielen.");
        if (is_module_active('pdvdiebstahl')){
				$erwischt=get_module_pref("erwischt","pdvdiebstahl");
				$erwischtneu=$erwischt+1;
				set_module_pref("erwischt", $erwischtneu, "pdvdiebstahl");
			}
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.95);
		addnav("T�gliche News","news.php");
        addnews("`\$%s `4wurde in %s Gier unter einem riesigen Haufen griechischer Goldm�nzen begraben.",$session['user']['name'], translate($session[user][sex]?"ihrer":"seiner"));
        $session['user']['specialinc']="";
        break;
        case 9:
        case 10:
        output("`@Ein wahrhaft edles Tier ... wei� wie Milch in der Sonne ... umgeben von einem blendenden Schimmer "
			  ."... `@Aber jetzt bleibt keine Zeit f�r Sentimentalit�ten! Du greifst nach dem Beutel und ... `n`n "
			  ."... wirst von seinem Gewicht zu Boden gerissen. Er ist voller Gold, wer h�tte das gedacht? Und je "
			  ."mehr du herausnimmst, desto schwerer scheint er zu werden! Du nimmst soviel Gold mit, wie Du tragen "
			  ."kannst und verschwindest von diesem seltsamen Ort. Schade, dass man den Beutel nicht mitnehmen kann ...");
        $foundgold = e_rand(800,1900) * $session['user']['level'];
        output("`n`n`@Du erh�ltst `^%s`@ Erfahrungspunkte und erbeutest `^%s `@Goldst�cke!`n", round($session['user']['experience']*0.03), $foundgold);
        $session['user']['gold'] += $foundgold;
        $session['user']['experience']=round($session['user']['experience']*1.03);
        addnav("Zur�ck zum Wald.","forest.php");
        addnav("T�gliche News","news.php");
        addnews("`@%s `2gelang es, dem griechischen Heros `#Bellerophontes`^ %s`2 Goldm�nzen zu stehlen!",$session['user']['name'], $foundgold);
        if (is_module_active('pdvdiebstahl')){
				$erwischt=get_module_pref("erwischt","pdvdiebstahl");
				$erwischtneu=$erwischt+1;
				set_module_pref("erwischt", $erwischtneu, "pdvdiebstahl");
			}
        $session['user']['specialinc']="";
        break;
        case 11:
        case 12:
		require_once("lib/commentary.php");

        output("`@Ein wahrhaft edles Tier ... wei� wie Milch in der Sonne ... umgeben von einem blendenden Schimmer "
			  ."... `@Aber jetzt bleibt keine Zeit f�r Sentimentalit�ten! Du greifst nach dem Beutel und ... `n`n "
			  ."... h�ltst kurz bevor Du ihn ber�hren kannst inne. Der Turm, der Pegasus, der Beutel ... das alles "
			  ."kommt Dir doch sehr, sehr merkw�rdig vor. Du nimmst dieses Ereignis als wertvolle Erfahrung, von der "
			  ."Du noch Deinen Enkeln wirst erz�hlen k�nnen, und gehst Deines Weges.");
        output("`n`n`@Du erh�ltst `^%s`@ Erfahrungspunkte!`n", round($session['user']['experience']*0.15));
        $session['user']['experience']=round($session['user']['experience']*1.15);
        addnav("Zur�ck zum Wald.","forest.php");
        addnav("T�gliche News","news.php");
        addnews("`@%s `2hat ein wundervolles M�rchen �ber einen seltsamen Turm im Wald geschrieben - und `ialle`i Dorfbewohner schw�rmen davon!",$session['user']['name']);
		$body = sprintf_translate("/me `2freut sich, als %s einige Dorfbewohner �ber das M�rchen sprechen h�rt, das %s geschrieben hat!", translate($session[user][sex]?"sie":"er"), translate($session[user][sex]?"sie":"er"));
		injectcommentary("village", "","$body", $schema=false);
        $session['user']['specialinc']="";
        break;
}
?>
