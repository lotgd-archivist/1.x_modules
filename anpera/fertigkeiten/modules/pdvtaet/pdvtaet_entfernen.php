<?php

function pdvtaet_entfernen_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Der Tätowierer");
	
		if ($session['user']['gems'] < 7) output("`@Dafür hast Du nicht genügend Edelsteine dabei!");
		else if ($session['user']['maxhitpoints'] < 15) output("`@Dafür bist Du zu schwach - Du würdest es nicht überleben!");
		else{
			$ort=$_GET[subop];
			output("`@Phral versetzt Dich mit einem Zaubertrank in eine Art Halbschlaf ... Als Du wieder "
				."erwachst, bist Du völlig erschöpft - aber die Tätowierung ist rückstandsfrei verschwunden!");
			$session['user']['maxhitpoints']-=5;
			$session['user']['hitpoints']-=5;
			$koerper=createarray(get_module_pref("koerper"));
			$koerper[$ort]['motiv']="";
			set_module_pref("koerper", createstring($koerper));
			$session['user']['gems']-=7;
		}
		addnav("Zurück", "runmodule.php?module=pdvtaet");
	page_footer();
}
?>