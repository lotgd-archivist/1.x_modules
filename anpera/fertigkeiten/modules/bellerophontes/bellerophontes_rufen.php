<?php
	switch(e_rand(1,11)){
    	case 1:
        case 2:
        case 3:
        output("`@Du r�usperst Dich und rufst so laut Du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'");
        output("`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... ");
		output("als Du einen Schlag im Genick sp�rst. Und es ist das letzte, was Du sp�rst.`n`n");
        output("`$ Du bist tot!`n");
        output("`@Du verlierst `$%s`@ Erfahrungspunkte und all Dein Gold!`n", round($session['user']['experience']*0.03));
        output("Du kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.97);
        addnav("T�gliche News","news.php");
       	addnews("`\$%s `4machte durch %s lautes Rufen einen hungrigen Ork auf sich aufmerksam ...", $session['user']['name'], translate($session['user']['sex']?"ihr":"sein"));
        $session['user']['specialinc']="";
        break;
        case 4:
		case 5:
		case 6:
        output("`@Du r�usperst Dich und rufst so laut Du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'");
        output("`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... ");
		output("als jemand zur�ckruft: `#'Nein, hier ist niemand!' `@`n`nTja, das nenne ich ein Pech! Du findest es "
			  ."zwar seltsam, dass niemand zu Hause ist, schlie�lich steht ja drau�en der Pegasus, aber Dir bleibt "
			  ."wohl nichts anderes �brig, als diesen Ort zu verlassen.");
        $session['user']['specialinc']="";
        break;
        case 7:
        case 8:
        output("`@Du r�usperst Dich und rufst so laut Du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'");
        output("`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... ");
        output("als jemand zur�ckruft: `#'Herakles, bist Du's? Nimm Dir von dem Gold in dem Beutel, es ist auch das "
			  ."Deine!'`n`@Mit etwas dumpferer Stimme rufst Du zur�ck - `#'Danke!'`@ -, greifst in den Beutel auf "
			  ."dem R�cken des Pegasus und begibst Dich so schnell Du kannst zur�ck zum Dorf.`n`n");
        $gold = e_rand(400,900);
        output("`@Du bekommst `^%s `@Erfahrungspunkte hinzu und `^".round($gold * $session['user']['level'])." `@Goldst�cke!", round($session['user']['experience']*0.03));
        $session['user']['experience']=round($session['user']['experience']*1.03);
        $session['user']['gold'] += round($gold * $session['user']['level']);
        $session['user']['specialinc']="";
        break;
        case 9:
        case 10:
        require_once("lib/commentary.php");

        output("`@Du r�usperst Dich und rufst so laut Du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'");
        output("`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... ");
        output("als jemand an den Balkon tritt: ein stattlicher Mann mit langem, dunklem Haar, das von einem Reif "
			  ."gehalten wird. Er tr�gt eine strahlend wei�e Robe, die das Zeichen des Poseidon ziert, und hat den "
			  ."ehrfurchtgebietenden Blick eines Mannes, der den G�ttern entstammt ...");
        output("`n`n`#'Sei gegr��t, Sterblicher! Du hast gro�e Entbehrungen auf Dich genommen, um meinen Turm zu "
			  ."erreichen. Daf�r hast Du Dir eine Belohnung redlich verdient! Nimm! Und berichte in aller Welt, "
			  ."dass ich, Bellerophontes, die Chim�re besiegt habe!'`&`n`n `@Er wirft Dir einen Beutel herunter!`n");
        $gems = e_rand(2,3);
        output("`nIn dem Beutel befanden sich `^%s`@ Edelsteine!", $gems);
        $session['user']['gems']+=$gems;
        addnav("T�gliche News","news.php");
        addnav("Zur�ck zum Wald.","forest.php");
        addnews("`@%s `2hielt heute auf dem Dorfplatz einen langen Vortrag �ber `#Bellerophontes'`2 gro�artige Heldentaten!",$session['user']['name']);
		injectcommentary("village", "","/me `2stellt sich auf die Mitte des Platzes, r�uspert sich und h�lt einen langen Vortrag �ber die Heldentaten eines gewissen `#Bellerophontes`2!", $schema=false);
        $session['user']['specialinc']="";
        break;
        case 11:
        output("`@Du r�usperst Dich und rufst so laut Du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'");
        output("`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...`n`n ... ");
        output("als jemand an den Balkon tritt: ein stattlicher Mann mit langem, dunklem Haar, das von einem Reif "
			  ."gehalten wird. Er tr�gt eine strahlend wei�e Robe, die das Zeichen des Poseidon ziert, und hat den "
			  ."ehrfurchtgebietenden Blick eines Mannes, der den G�ttern entstammt ...");
        output("`#Ich habe viel von Deinen Heldentaten geh�rt, %s! Hier, dies soll Dir auf Deinen Drachenjagden "
			  ."behilflich sein! Nach meinem Sieg �ber die Chim�re brauche ich es nicht mehr.'`@`n`n Er �berreicht "
			  ."Dir sein Amulett des Lebens!", $session['user']['name']);
        output("`n`n`@Du erh�ltst `^einen`@ permanenten Lebenspunkt!");
        $session['user']['maxhitpoints']++;
        $session['user']['hitpoints']++;
        $session['user']['specialinc']="";
        break;
	}
?>
