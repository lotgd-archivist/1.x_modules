<?php
				if (get_module_setting("fest","wettkampf")==1) {
					$chance = get_module_setting("chance","pdvmissionar");
					if (e_rand(1,100)<=$chance) set_module_setting("appear",1,"pdvmissionar");
					else set_module_setting("appear",0,"pdvmissionar");
				}
	return $args;
?>
