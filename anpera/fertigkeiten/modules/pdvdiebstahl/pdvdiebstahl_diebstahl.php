<?php

function pdvdiebstahl_diebstahl_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Taschendiebstahl");

	if ($session[user][turns]>1){
		//Fertigkeitswert
		$schleichen=get_fertigkeit("schleichen");
		
		output("`@Du begibst Dich auf Diebestour. Unauffällig mischt Du Dich unter die Leute und beobachtest sie beim Bezahlen. Warte jetzt einfach ab, es dauert eine Weile, bis man das richtige Opfer gefunden hat ...");
		$dieb=get_module_setting("dieb");
		
		//Mail an erfolglosen Dieb
		if ($dieb!=""){
			$diebid=get_module_setting("diebid");
			$mailmessage1 = array("`@Nach fast einer Stunde hast Du entnervt aufgegeben. Es wollte sich einfach keine günstige Gelegenheit bieten.");
			systemmail($diebid,array("`@Erfolglose Diebestour ..."),$mailmessage1);
		} 
		
		set_module_pref("diebstahlsimmun", 0, "pdvdiebstahl");
		set_module_pref("meldung", 0, "pdvdiebstahl");
		set_module_setting("dieb", $session[user][name]);
		set_module_setting("diebid", $session[user][acctid]);
		set_module_pref("geklaut",1);
		$session[user][turns]-=1;
		
		//Malus für bekannte Diebe
		$erwischt=get_module_pref("erwischt");
		$mod1=$erwischt*3;
		
		//Maximale Verbrechereinstufung
		if ($mod1>50)$mod1=50;
		
		//Bonus im Festgedränge
		$fest=get_module_setting("fest", "wettkampf");
		if ($fest==1) $mod2=15;
		
		//Malus im freien Feld
		else if ($fest==0) $mod2=-15;
		
		//Zufallswert, der angibt, wie schwer die konkrete Situation ist (unvorhersehbar)
		$mod3=e_rand(-15,0);
				
		//Probe 
		$probe=probe($schleichen, $mod2+$mod3-$mod1);
		
		$wert=$probe[wert];
		if ($probe[ergebnis] == "kritischer misserfolg") $wert=-100;
		else if ($probe[ergebnis] == "kritischer erfolg") $wert=120;
			
		set_module_setting ("probe", $wert);
		
		//Diebe zieht es zur dunklen Seite der Macht ...
		if (is_module_active('alignment')) align("-1");
				
		addnav("Zurück","runmodule.php?module=wettkampf&op1=");
	}else{
		output("`@Dazu hast Du heute keine Zeit mehr.");
		addnav("Zurück","runmodule.php?module=wettkampf&op1=");
	}
	page_footer();
}
?>