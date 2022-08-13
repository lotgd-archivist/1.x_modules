<?php
	switch(e_rand(1,10)){
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
        output("`@Du folgst dem Pfad immer tiefer in den Wald hinein, stundenlang, doch der Turm bleibt fest "
			  ."am Horizont. Es ist, als könnte man nicht zu ihm gelangen .... Du willst schon aufgeben - als "
			  ."er plötzlich mit jedem weiteren Schritt einige Hundert Meter näher kommt!`n`n");
        $turns2 = e_rand(1,5);
		if ($session['user']['turns']<=$turns2){
			output("`^Bis hierher zu gelangen hat Dich %s gekostet!", translate($turns2==1?"Deinen letzten Waldkampf":"Deine restlichen Waldkämpfe"));
           	$session['user']['turns']=0;
			output("`n`n`@<a href='forest.php?op=turm'>Weiter.</a>", true);
           	addnav("", $from . "op=turm");
			addnav("Weiter.", $from . "op=turm");
			break;
		}else{
           	output("`^Bis hierher zu gelangen hat Dich bereits %s %s gekostet!", $turns2, translate($turns2==1?"Waldkampf":"Waldkämpfe"));
           	$session['user']['turns']-=$turns2;
           	output("`n`n`@<a href='forest.php?op=turm'>Weiter.</a>", true);
           	addnav("", $from . "op=turm");
           	addnav("Weiter.", $from . "op=turm");
           	break;}
        case 7:
		case 8:
		case 9:
		case 10:
        output("`@Du folgst dem Pfad immer tiefer in den Wald, stundenlang. Er scheint nicht enden zu wollen - "
			  ."und immer siehst Du den Turm an seinem Ende. An der nächsten Weggabelung bleibst Du stehen. "
			  ."Weiter nach dem Turm zu suchen wird Dich möglicherweise alle Deine Waldkämpfe kosten, aber Du "
			  ."spürst, dass Du `bganz dicht dran`b bist ...");
        output("`n`n`@<a href='forest.php?op=weiter2'>Weiter.</a>", true);
        output("`n`n`@<a href='forest.php?op=abbiegen2'>Abbiegen.</a>", true);
        addnav("", $from . "op=weiter2");
        addnav("", $from . "op=abbiegen2");
        addnav("Weitergehen.", $from . "op=weiter2");
        addnav("Abbiegen.", $from . "op=abbiegen2");
        break;
	}
?>
