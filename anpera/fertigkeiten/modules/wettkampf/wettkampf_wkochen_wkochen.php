<?php

function wettkampf_wkochen_wkochen_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
		$teilnahme=get_module_setting("teilnahme", "wettkampf");
		if ($session[user][gold]<$session[user][level]*$teilnahme){
		output("`@Ag'nsra beginnt zu lachen. `#'Ihr habt Euer Geld wohl zu Hause vergessen!'`@");
		addnav("Zurück","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=kochen");
		}else {
			$session[user][gold]-=$session[user][level]*$teilnahme;
			output("`@`bDer Koch- und Backwettbewerb`b`n");
			output("`@Was möchtest Du kochen? Je höher der Schwierigkeitsgrad, desto höher die mögliche Höchstpunktzahl. Aber übernimm Dich nicht, Spezialitäten stellen selbst für die Besten der Besten noch eine große Herausforderung dar!`n");
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen1'>`n`nIch möchte mich an etwas ganz Einfachem versuchen ...</a>", true);
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen2'>`n`nIch werde etwas Bodenständiges kochen.</a>", true);
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen3'>`n`nIch werde mit etwas Raffiniertem gewinnen.</a>", true);
			output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen4'>`n`nAuf die Knie, hier kommt %s! Um meine Spezialitäten beneiden mich selbst die Götter!</a>", ($session[user][sex]?"die Meisterköchin":"der Meisterkoch") ,true);
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen1");
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen2");
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen3"); 
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen4");
			addnav("Etwas Einfaches","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen1");
			addnav("Etwas Bodenständiges","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen2");
			addnav("Etwas Raffiniertes","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen3");
			addnav("Eine Spezialität!","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=wkochen4");
		}
	page_footer();
}
?>