<?php

function pdvtaet_entfernen_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Der T�towierer");
	
		if ($session['user']['gems'] < 7) output("`@Daf�r hast Du nicht gen�gend Edelsteine dabei!");
		else if ($session['user']['maxhitpoints'] < 15) output("`@Daf�r bist Du zu schwach - Du w�rdest es nicht �berleben!");
		else{
			$ort=$_GET[subop];
			output("`@Phral versetzt Dich mit einem Zaubertrank in eine Art Halbschlaf ... Als Du wieder "
				."erwachst, bist Du v�llig ersch�pft - aber die T�towierung ist r�ckstandsfrei verschwunden!");
			$session['user']['maxhitpoints']-=5;
			$session['user']['hitpoints']-=5;
			$koerper=createarray(get_module_pref("koerper"));
			$koerper[$ort]['motiv']="";
			set_module_pref("koerper", createstring($koerper));
			$session['user']['gems']-=7;
		}
		addnav("Zur�ck", "runmodule.php?module=pdvtaet");
	page_footer();
}
?>