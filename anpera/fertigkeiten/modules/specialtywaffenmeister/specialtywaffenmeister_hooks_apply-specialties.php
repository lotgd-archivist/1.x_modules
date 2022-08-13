<?php
$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('wm1',array(		// Halbes Voodoo
						"startmsg"=>"`3Blitzschnell ziehst Du einen Deiner Wurfdolche und wirfst ihn auf `^{badguy}`3.",
						"minioncount"=>1,
						"minbadguydamage"=>round($session['user']['attack']*0.75),
						"maxbadguydamage"=>round($session['user']['attack']*1.5),
						"effectmsg"=>"`3Du triffst für `^{damage}`3 Schaden.",
						"schema"=>"specialtywaffenmeister"
					));
					break;
				case 2:
					apply_buff('wm2',array(
						"name"=>"`3Heftiger Angriff",
						"startmsg"=>"`3Du stürzt Dich mit voller Wucht auf `^{badguy}`3!",
						"rounds"=>5,
						"atkmod"=>2,
						"roundmsg"=>"`^{badguy}`3 erzittert unter der Wucht Deiner Schläge.",
						"wearoff"=>"`3Deine Kraft läßt nach.",
						"schema"=>"specialtywaffenmeister"
					));
					break;
				case 3:
					apply_buff('wm3', array(
						"name"=>"`3Kriegsschrei",
						"startmsg"=>"`3Dein markerschütternder Kriegsschrei schüchtert `^{badguy}`3 ein!",
						"rounds"=>5,
						"badguyatkmod"=>0.4,	// 0.3
						"badguydefmod"=>0.4,	// 0.3
						"roundmsg"=>"`^{badguy}`3 zittert angsterfüllt und kämpft schlechter!",
						"wearoff"=>"`^{badguy}`3 greift Dich wieder mutiger an!",
						"schema"=>"specialtywaffenmeister"
					));
					break;
				case 5:
					apply_buff('wm5',array(
						"name"=>"`3Gezielter Angriff",
						"startmsg"=>"`3Du suchst Lücken in der Verteidigung des Gegners und versuchst empfindliche Körperteile zu treffen.",
						"rounds"=>5,
						"atkmod"=>2.5,
						"damageshield"=>0.8,
						"effectmsg"=>"`3Du triffst `^{badguy}`3an einer empfindlichen Stelle für `^{damage}`3 Schaden.",// empfindliche Stelle
						"effectnodmg"=>"`3Du triffst `^{badguy}`3, aber dein Schlag prallt an der Rüstung ab.",
						//"effectfailmsg"=>"`^{badguy}`3 kann Deinem Paradeschlag ausweichen.",
						"wearoff"=>"`^{badguy} `3ist jetzt vorsichtiger und ermöglicht Dir nicht mehr so viele Treffer.",
						"schema"=>"specialtywaffenmeister"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('wm0', array(
					"startmsg"=>"`3Du fuchtelst mit deiner Waffe vor `^{badguy}`3 herum, aber das macht nicht den erhofften Eindruck.",
					"rounds"=>1,
					"schema"=>"specialtywaffenmeister"
				));
			}
		}
?>
