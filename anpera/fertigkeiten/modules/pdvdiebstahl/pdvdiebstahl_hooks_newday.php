<?php

function pdvdiebstahl_hooks_dohook_newday_private($args=false){
	global $session;
		set_module_pref("bestohlen",0);
		set_module_pref("geklaut",0);
		
		//Rehabilitation
		$erwischt=get_module_pref("erwischt");
		if ($erwischt>0){
			$vergessen=e_rand(1,4);
			
			if ($vergessen==1 && $erwischt>1){
				output("`n`4Du hast das Gefühl, dass sich die Stadtwache und die Dorfbewohner heute ein kleines bisschen weniger an Deine Verbrechen erinnern als gestern.`n");
				$random=e_rand(1,3);
				$erwischtneu=$erwischt-$random;
				if ($erwischtneu < 1) $erwischtneu=1;
				set_module_pref("erwischt", $erwischtneu);
			}
			
			if ($vergessen==1 && $erwischt==1){
				output("`n`4Du hast das Gefühl, dass inzwischen Gras über all Deine Diebstähle gewachsen ist.`n");
				set_module_pref("erwischt", 0);
			}
		}
	return $args;
}
?>
