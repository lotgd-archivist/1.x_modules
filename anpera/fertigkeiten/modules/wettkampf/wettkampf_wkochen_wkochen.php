<?php

function wettkampf_wkochen_wkochen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		if ($session[user][gold]<$session[user][level]*$teilnahme){
		output("`@Ag'nsra beginnt zu lachen. `#'Ihr habt Euer Geld wohl zu Hause vergessen!'`@");
		addnav("Zur�ck","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=kochen");
		}else {
			$session[user][gold]-=$session[user][level]*$teilnahme;
			output("`@`bDer Koch- und Backwettbewerb`b`n");
			output("`@Was m�chtest Du kochen? Je h�her der Schwierigkeitsgrad, desto h�her die m�gliche H�chstpunktzahl. Aber �bernimm Dich nicht, Spezialit�ten stellen selbst f�r die Besten der Besten noch eine gro�e Herausforderung dar!`n");
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen1'>`n`nIch m�chte mich an etwas ganz Einfachem versuchen ...</a>", true);
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen2'>`n`nIch werde etwas Bodenst�ndiges kochen.</a>", true);
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen3'>`n`nIch werde mit etwas Raffiniertem gewinnen.</a>", true);
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen4'>`n`nAuf die Knie, hier kommt %s! Um meine Spezialit�ten beneiden mich selbst die G�tter!</a>", ($session[user][sex]?"die Meisterk�chin":"der Meisterkoch") ,true);
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen1");
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen2");
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen3"); 
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen4");
			addnav("Etwas Einfaches","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen1");
			addnav("Etwas Bodenst�ndiges","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen2");
			addnav("Etwas Raffiniertes","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen3");
			addnav("Eine Spezialit�t!","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen4");
		}
	page_footer();
}
?>