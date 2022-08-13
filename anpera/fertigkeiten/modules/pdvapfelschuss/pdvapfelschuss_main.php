<?php

function pdvapfelschuss_main_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Der schmierige Schie�stand");
	
	checkday();
	$schuetze=get_module_setting("schuetze");
	$gold=get_module_setting("preis", "pdvapfelschuss");
	$teilnahme=get_module_pref("teilnahme");
	
	output("`@`cDer schmierige Schie�stand`c`n");
	
	if ($session['user']['acctid'] == $schuetze) output("`@Du stehst noch immer hier und wartest ...");
	else if ($schuetze == -1) output("`@Du versuchst, einen Blick zu erhaschen, doch es ist kein Durchkommen "
			."bei diesem dichten Gedr�nge. Warte einen Moment ...");
	else if ($teilnahme == 1){
		output("`@Du versuchst, einen Blick zu erhaschen, doch es ist kein Durchkommen "
			."bei diesem dichten Gedr�nge.");
	}else if ($schuetze == 0 && $session['user']['gold'] < $gold){
		output("`@Du versuchst, einen Blick zu erhaschen, doch es ist kein Durchkommen "
			."bei diesem dichten Gedr�nge. Dann versuchst Du es noch einmal und wirst von "
			."einem Bauern angesprochen: `#'Habt Ihr viel Gold dabei?' `@Verdutzt sch�ttelst "
			."Du den Kopf. `#'Dann kommt Ihr hier auch nicht durch!' `@Er versucht nun selbst "
			."wieder einen guten Platz zu ergattern ...");
	}else{
		output("`@Im hinteren Bereich der St�nde hat sich eine dichte Menge schaulustiger B�rger neben "
			."einem kleinen Zelt versammelt. Als Du n�hertrittst, macht man Dir Platz und Du hast das "
			."Gef�hl, dies liegt an dem prallgef�llten Goldbeutel, den Du bei Dir tr�gst ... warum "
			."auch immer. Als Du die Menge durchbrochen hast, f�llt Dir eine etwa mannshohe Holzwand auf, "
			."die teils mit Blut verschmiert ist. ");
		
		if ($schuetze != 0){
			$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid='$schuetze'";
			$results = db_query($sql);
			$row = db_fetch_assoc($results);
			$name=$row['name'];
			
			output("Davor steht - etwas gelangweilt und mit einem Apfel in der Hand - %s`@ und scheint auf "
				."irgendetwas zu warten ... ", $name);
		}else output("Davor steht - etwas gelangweilt und mit einem Apfel in der Hand - ein kleiner Junge`@ und scheint auf "
				."irgendetwas zu warten ... ");
		
		output("`n`nBevor Du Dir das alles n�her anschauen kannst, wirst Du auch schon von einem etwas schmierigen "
			."Troll angesprochen, so dass alle es deutlich h�ren k�nnen: `#'Haben wir hier einen Freiwilligen? Ich glaube, wir haben "
			."hier einen Freiwilligen! Wie hei�t Ihr?' `@Du nennst Deinen Namen. Dabei f�llt Dir auf, dass der Troll "
			."einen alten Langbogen in der Hand h�lt. `#'H�rt her, Leute! %s`# m�chte "
			."sich an unserem Spiel beteiligen! - Oder?' `@Du schaust ihn unschl�ssig an. ", $session['user']['name']);
		
		if ($schuetze != 0){
			output("Daraufhin f�ngt der Troll an zu lachen: `#'Ihr seid Euch noch unsicher? Dann lasst mich erkl�ren, "
				."worum es geht.' `@Er holt einen kleinen Beutel hervor und dr�ckt ihn Dir in die Hand. `#'Es ist ganz "
				."einfach! Behaltet das, es geh�rt auf jeden Fall Euch, wenn Ihr mitmacht! Satte `^%s`# Goldst�cke! "
				."Na, ist das nichts?!' `@Alle Anwesenden schauen Dich erwartungsvoll an ...", $gold);
			addnav("Teilnehmen!", "runmodule.php?module=pdvapfelschuss&op1=opfer");
			addnav("Nein danke ...");
		}else{
			output("Daraufhin f�ngt der Troll an zu lachen: `#'Ihr seid Euch noch unsicher? Dann lasst mich erkl�ren, was "
			."Ihr tun sollt.' `@Er lacht noch lauter. `#'Es ist "
			."ganz einfach! Die Teilnahme am Schuss auf den Apfel kostet nur `^%s`# Goldst�cke! Gewinnen k�nnt "
			."Ihr aber das *Doppelte*, wenn der Apfel hinterher an der Wand h�ngt! "
			."Na, ist das nichts?' `@Alle Anwesenden schauen Dich erwartungsvoll an ...", $gold);
			addnav("Teilnehmen!", "runmodule.php?module=pdvapfelschuss&op1=auswahl1");
			addnav("Nein danke ...");
		}
	}
	addnav("Zur�ck", "runmodule.php?module=wettkampf");
	page_footer();
}
?>