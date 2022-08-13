<?php
	require_once("lib/fert.php");
	
	global $session;	
	//FW
		$klettern=get_fertigkeit(klettern);
	
	//Mit welchem Wetter haben wir es gerade zu tun?
		$wetter=get_module_setting("weather","weather");
	
	//Mod je nach Wetter
	switch($wetter){
		case "overcast and cool, with sunny periods": case "warm and sunny": case "hot and sunny": $mod=0; break;
		case "rainy": $mod=-10; break;
		case "foggy": $mod=-5; break;
		case "cool with blue skies": $mod=-5; break;
		case "high winds with scattered showers": $mod=-15; break;
		case "thundershowers": $mod=-25; break;
	}
				
	//Die Probe
		$probe=probe($klettern, $mod);
		$wert=$probe[wert];
	
	if ($wert < 50) $ergebnis=1;
	else if ($wert > 50 && $wert < 0) $ergebnis=e_rand(2,5);
	else if ($wert > 0) $ergebnis=e_rand(4,10); 
	
    switch($ergebnis){
        case 1:
        case 2:
        case 3:
        output("`@Du greifst nach dem Efeu und ziehst einige Male daran. Alles in Ordnung, es scheint zu halten. "
			  ."Vorsichtig beginnst Du hinaufzuklettern ...");
        output("`@Du hast gerade die H�lfte des Weges bis zum Balkon erklommen, als Du pl�tzlich mit einem Fu� "
			  ."h�ngen bleibst. Du sch�ttelst ihn, um ihn freizubekommen, doch vergebens - die Pflanze scheint Dich "
			  ."bei sich behalten zu wollen! In Panik verfallen, wirst Du immer hektischer, aber alle M�he wird "
			  ."bestraft: schon bald kannst Du Dich �berhaupt nicht mehr bewegen. Die Pflanze h�lt Dich f�r die "
			  ."Ewigkeit gefangen.");
        output("`$`n`nDu bist tot!");
        output("`@`n`nDu verlierst `$%s`@ Erfahrungspunkte und all Dein Gold!", round($session['user']['experience']*0.03));
        output("`@`n`nDu kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.97);
        addnav("T�gliche News","news.php");
        addnews("`\$%s `4verhedderte sich im Efeu von `#Bellerophontes'`4 Turm und ist dort verhungert.",$session['user']['name']);
        $session['user']['specialinc']="";
        break;
        case 4:
        case 5:
        case 6:
        case 7:
        output("`@Du greifst nach dem Efeu und ziehst einige Male daran. Alles in Ordnung, es scheint zu halten. "
			  ."Vorsichtig beginnst Du hinaufzuklettern ...");
        output("`@Das ist aber einfach! Ohne Probleme erklimmst Du das Efeu bis zum Balkon. Mit einem letzten, "
			  ."kraftvollen Zug hievst Du Deinen edlen K�rper �ber die Br�stung und erblickst: ");
		output("Bellerophontes, den griechischen Heros! `@Er tritt Dir mit gemessenen Schritten entgegen, w�hrend "
			  ."Du nichts empfindest als Bewunderung f�r seine gro�artige Erscheinung: langes, dunkles Haar, das "
			  ."von einem Reif gehalten wird; eine strahlend wei�e Robe, die das Zeichen des Poseidon ziert; der "
			  ."ehrfurchtgebietende Blick eines Mannes, der den G�ttern entstammt ...");
		output("`@Dein Bewusstsein schwindet und Du hast einen Traum, wie keinen je zuvor. Ein gro�es Mischwesen "
			  ."aus L�we und Skorpion kommt darin vor ... `n`nAls Du wieder erwachst, liegst Du irgendwo im Wald "
			  ."und schwelgst noch immer - mit genauer Erinnerung an Bellerophontes' �sthetische Kampftaktik!");
        output("`n`n`@Da Du von nun an mutiger k�mpfen wirst, erh�ltst Du `^2`@ Charmepunkte!");
        $session['user']['charm']+=2;
        output("`n`n`@Du erh�ltst `^1`@ Punkt Angriff!");
        $session['user']['attack']++;
        $session['user']['specialinc']="";
        break;
        case 8:
        case 9:
        case 10:
        require_once("lib/commentary.php");

        output("`@Du greifst nach dem Efeu und ziehst einige Male daran. Alles in Ordnung, es scheint zu halten. "
			  ."Vorsichtig beginnst Du hinaufzuklettern ...");
        output("`@Das ist aber einfach! Ohne Probleme erklimmst Du das Efeu bis zum Balkon. Mit einem letzten, "
			  ."kraftvollen Zug hievst Du Deinen edlen K�rper �ber die Br�stung und erblickst: ");
		output("Bellerophontes, den griechischen Heros! `@Er tritt Dir mit gemessenen Schritten entgegen, w�hrend "
			  ."Du nichts empfindest als Bewunderung f�r seine gro�artige Erscheinung: langes, dunkles Haar, das "
			  ."von einem Reif gehalten wird; eine strahlend wei�e Robe, die das Zeichen des Poseidon ziert; der "
			  ."ehrfurchtgebietende Blick eines Mannes, der den G�ttern entstammt ...");
        output("`@Kam erst der Schlag und dann der Flug? Oder war es umgekehrt?");
        output("`$`n`nDu bist tot!");
        output("`n`n`@Du verlierst `$%s`@ Erfahrungspunkte und w�hrend des Fluges all Dein Gold!`n", round($session['user']['experience']*0.07));
        output("`n`@Du kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.93);
        addnav("T�gliche News","news.php");
        addnews("`\$Es wurde beobachtet, wie `\$%s`4 aus heiterem Himmel herab auf den Dorfplatz fiel und beim Aufprall zerplatzte.", $session['user']['name']);
		injectcommentary("village", "","/me `4f�llt aus heiterem Himmel herab auf den Platz und zerplatzt beim Aufprall!", $schema=false);
        $session['user']['specialinc']="";
        break;
	}
?>
