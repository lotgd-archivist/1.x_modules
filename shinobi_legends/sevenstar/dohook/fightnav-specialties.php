<?php
		$check=true;
		if (is_module_active('curse_seal')) $check=(get_module_pref('hasseal','curse_seal')<=0?true:false);
		if ($check) {
		$seals=get_module_pref("hastat");
			if ($seals==true) {
				$skill="sevenstar";
				$sealname=get_module_setting('name');
				addnav(array("%s",sanitize($sealname)));
				if (!has_buff('star1')) addnav(array("%s`)",$sealname),$args['script']."op=fight&skill=$skill&l=1");
				if (get_module_pref("tattoo-stage")>=7 && has_buff('star1') && !has_buff('star2')) addnav(array("%s`) `bEnhanced`b",$sealname),$args['script']."op=fight&skill=$skill&l=2");
			}
		}
?>