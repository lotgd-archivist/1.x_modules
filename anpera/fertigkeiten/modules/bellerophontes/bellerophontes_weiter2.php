<?php
    output("`@Du gibst nicht auf und folgst dem Pfad noch tiefer in den Wald hinein. Er scheint noch immer nicht "
		  ."enden zu wollen, und es wird immer dunkler. Noch etwa eine Stunde und auch das letzte Licht, das sich "
		  ."seinen Weg durch die B�ume k�mpft, wird erloschen sein - und immer siehst Du den Turm vor Dir, am Ende "
		  ."des Weges.`n`n");
	switch(e_rand(1,15)){
		case 1:
        case 2:
        case 3:
        case 4:
        case 5:
		case 6:
        output("`@Schlie�lich kannst Du Deine Hand kaum noch vor Augen sehen - doch der Turm bleibt am Horizont, "
			  ."als w�rde es dort niemals dunkel werden. Es hilft nichts; schwer entt�uscht nimmst Du die n�chste "
			  ."Abzweigung und gelangst sp�t in der Nacht und v�llig �berm�det zur�ck ins Dorf. Da Du im Dunkeln "
			  ."nichts sehen konntest, hast Du Dir einige derbe Schrammen eingehandelt. Immerhin eine Erfahrung, "
			  ."die man nicht jeden Tag macht.`n`n");
		if ($session['user']['turns']>=20){
           	output("`n`nDu bekommst `^%s`@ Erfahrungspunkte hinzu, verlierst aber alle verbliebenen Waldk�mpfe!", round($session['user']['experience']*0.08));
           	$session['user']['experience']=round($session['user']['experience']*1.08);
		}elseif($session['user']['turns']>=13){
           	output("`n`nDu bekommst `^%s`@ Erfahrungspunkte hinzu, verlierst aber alle verbliebenen Waldk�mpfe!", round($session['user']['experience']*0.07));
           	$session['user']['experience']=round($session['user']['experience']*1.07);
		}elseif($session['user']['turns']>=6){
           	output("`n`nDu bekommst `^%s`@ Erfahrungspunkte hinzu, verlierst aber alle verbliebenen Waldk�mpfe!", round($session['user']['experience']*0.05));
           	$session['user']['experience']=round($session[user][experience]*1.05);
		}else{
			output("Du bekommst `^%s`@ Erfahrungspunkte hinzu, verlierst aber `$%s`@ Lebenspunkte und alle verbliebenen Waldk�mpfe!`n", round($session['user']['experience']*0.04), round($session['user']['hitpoints']*0.20));
           	$session['user']['hitpoints']=round($session['user']['hitpoints']*0.80);
           	$session['user']['experience']=round($session['user']['experience']*1.04);
		}
        $session['user']['turns']=0;
        $session['user']['specialinc']="";
        break;
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        case 11:
        case 12:
        case 13:
        case 14:
        case 15:
        output("`@Schlie�lich kannst Du Deine Hand kaum noch vor Augen erkennen - doch der Turm bleibt am "
			  ."Horizont, als w�rde es dort niemals dunkel werden. Du willst schon an der n�chsten Abbiegung "
			  ."aufgeben - als der Turm beginnt, sich mit jedem weiteren Schritt um einige Hundert Meter zu "
			  ."n�hern! Er liegt trotz der sp�ten Stunde noch immer im Hellen ...`n`n");
        output("`^Die Suche hat Dich alle verbliebenen Waldk�mpfe gekostet!");
        $session['user']['turns']=0;
        output("`n`n`@<a href='forest.php?op=turm'>Weiter.</a>", true);
        addnav("", $from . "op=turm");
        addnav("Weiter.", $from . "op=turm");
    	break;
	}
?>
