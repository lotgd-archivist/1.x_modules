<?php
		if (get_module_setting("fest","wettkampf")==1) {
			$chance = get_module_setting("chance","pdvapfelschuss");
			if (e_rand(1,100)<=$chance) set_module_setting("appear",1,"pdvapfelschuss");
			else set_module_setting("appear",0,"pdvapfelschuss");
		}
		$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=0 WHERE modulename='pdvapfelschuss' AND setting='teilnahme'";
		db_query($sql);
	return $args;
?>
