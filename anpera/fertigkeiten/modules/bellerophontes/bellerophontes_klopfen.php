<?php
	output("`@Du nimmst all Deinen Mut zusammen und klopfst an die Eichent�r. Die Schritte schwerer Eisenstulpen "
		  ."ert�nen aus dem Innern des Turmes und werden immer lauter ...`n`n");
    switch(e_rand(1,13)){
		case 1:
        case 2:
        case 3:
        output("`@Jemand dr�ckt die T�r von innen auf - doch wer es war, sollst Du nie erfahren. Die Wucht muss "
			  ."jedenfalls gewaltig gewesen sein, sonst h�ttest Du es �berlebt.`n`n");
        output("`$ Du bist tot!`n");
        output("`@Du verlierst `$%s`@ Erfahrungspunkte und all Dein Gold!`n", round($session['user']['experience']*0.03));
        output("Du kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.97);
        addnav("T�gliche News","news.php");
        addnews("`\$%s `4wurde im Wald von einer schweren Eichent�r erschlagen.",$session['user']['name']);
        $session['user']['specialinc']="";
        break;
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        output("Zumindest in Deiner Einbildung. Als sich Dein Herzschlag wieder beruhigt, musst Du zu Deiner "
			  ."Entt�uschung feststellen, dass wohl niemand zu Hause ist. Du gehst zur�ck in den Wald.");
        $session['user']['specialinc']="";
        break;
        case 11:
        output("Die T�r �ffnet sich und Du stehst vor Bellerophontes, dem gro�en Heros und Chim�renbezwinger! "
			  ."Und tats�chlich, auf einem Tisch im Innern siehst Du das Mischwesen liegen; halb L�we, halb "
			  ."Skorpion. Aber Dein Blick wird sofort wieder auf den Helden gezogen, diesen �beraus stattlichen "
			  ."Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er tr�gt eine strahlend wei�e "
			  ."Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, "
			  ."der den G�ttern entstammt ... `#'Das Orakel von Delphi hatte vorhergesagt, dass jemand kommen "
			  ."w�rde, um mich nach bestandenem Kampf zu ermorden.'");
        output("`@Er mustert Dich - und beginnt dann schallend zu lachen: `#'Aber damit kann es `bDich`b ja "
			  ."wohl kaum gemeint haben, Wurm!'`n`n `@Er nimmt sich etwas Zeit und zeigt Dir, wie man sich im "
			  ."Wald verteidigt, damit Du Deinen Weg zum Dorf sicher zur�cklegen kannst!");
        output("`n`n`^Du erh�ltst 1 Punkt Verteidigung!");
        $session['user']['defense']++;
        $session['user']['specialinc']="";
        break;
        case 12:
        case 13:
        output("Die T�r �ffnet sich und Du stehst vor Bellerophontes, dem gro�en Heros und Chim�renbezwinger! "
			  ."Und tats�chlich, auf einem Tisch im Innern siehst Du das Mischwesen liegen; halb L�we, halb "
			  ."Skorpion. Aber Dein Blick wird sofort wieder auf den Helden gezogen, diesen �beraus stattlichen "
			  ."Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er tr�gt eine strahlend wei�e "
			  ."Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, "
			  ."der den G�ttern entstammt ... `#'Das Orakel von Delphi hatte vorhergesagt, dass jemand kommen "
			  ."w�rde, um mich nach bestandenem Kampf zu ermorden.'");
        output("`@Er mustert Dich - und beginnt dann schallend zu lachen: `#'Aber damit kann es `bDich`b ja wohl "
			  ."kaum gemeint haben, Wurm!'`@`n`n Er nimmt sich etwas Zeit und zeigt Dir, wie man gro� und stark "
			  ."wird!");
        output("`n`n`^Du erh�ltst 1 Punkt Angriff!");
        $session['user']['attack']++;
        $session['user']['specialinc']="";
        break;
	}
?>
