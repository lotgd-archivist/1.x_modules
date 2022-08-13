<?php
    switch(e_rand(1,10)){
        case 1:
        case 2:
        output("`@Zu Deiner Freude bemerkst Du, dass die Tür unverschlossen ist! Vorsichtig versuchst Du sie "
			  ."aufzuschieben ... als sie plötzlich ... aus ... den ... Angeln ...`n`n `#'Neeeeeeeiiiiiiin ...!'");
        output("`$`n`nDu bist tot!");
        output("`n`@Du verlierst `$%s`@ Erfahrungspunkte und all Dein Gold!`n", round($session['user']['experience']*0.03));
        output("`@Du kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.97);
        addnav("Tägliche News","news.php");
        addnews("`\$%s `4wurde im Wald von einer schweren Eichentür erschlagen.",$session['user']['name']);
        $session['user']['specialinc']="";
        break;
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        output("`@Zu Deiner Freude bemerkst Du, dass die Tür unverschlossen ist! Vorsichtig schiebst Du sie auf ... "
			  ."und wirfst einen ersten Blick hinein. Du siehst einen gemütlichen Vorraum, von dem aus eine "
			  ."Wendeltreppe nach oben führt. Es gibt einen Holztisch, der sich unter der Last des schwerverletzten "
			  ."Körper eines seltsamen Wesens biegt. Es ist halb Löwe, halb Skorpion ... eine Chimäre! `n`nDas ist "
			  ."aber interessant ... Du gehst hinein, um Dir das Mischwesen genauer anzusehen.");
        addnav("Weiter.", $from . "op=drinnen");
        break;
	}
?>
