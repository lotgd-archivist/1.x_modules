<?php
    switch(e_rand(1,10)){
        case 1:
        case 2:
        output("`@Zu Deiner Freude bemerkst Du, dass die T�r unverschlossen ist! Vorsichtig versuchst Du sie "
			  ."aufzuschieben ... als sie pl�tzlich ... aus ... den ... Angeln ...`n`n `#'Neeeeeeeiiiiiiin ...!'");
        output("`$`n`nDu bist tot!");
        output("`n`@Du verlierst `$%s`@ Erfahrungspunkte und all Dein Gold!`n", round($session['user']['experience']*0.03));
        output("`@Du kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.97);
        addnav("T�gliche News","news.php");
        addnews("`\$%s `4wurde im Wald von einer schweren Eichent�r erschlagen.",$session['user']['name']);
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
        output("`@Zu Deiner Freude bemerkst Du, dass die T�r unverschlossen ist! Vorsichtig schiebst Du sie auf ... "
			  ."und wirfst einen ersten Blick hinein. Du siehst einen gem�tlichen Vorraum, von dem aus eine "
			  ."Wendeltreppe nach oben f�hrt. Es gibt einen Holztisch, der sich unter der Last des schwerverletzten "
			  ."K�rper eines seltsamen Wesens biegt. Es ist halb L�we, halb Skorpion ... eine Chim�re! `n`nDas ist "
			  ."aber interessant ... Du gehst hinein, um Dir das Mischwesen genauer anzusehen.");
        addnav("Weiter.", $from . "op=drinnen");
        break;
	}
?>
