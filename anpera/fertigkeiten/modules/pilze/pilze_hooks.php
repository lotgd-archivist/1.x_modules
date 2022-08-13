<?php

function pilze_dohook_private($hookname,$args=false){
	if ($hookname == "fightnav-specialties"){
		$script = $args['script'];
		$alleitems = createarray(get_module_pref("alleitems"));
		foreach ($alleitems as $key => $item) {
			if ($item['identifiziert']==1) {
				addnav("Items");
				switch ($item['idkat']) {	// Items farblich markieren, je nach Wirkung (Kategorie)
				case 1: $cc = "`@"; break;	// gut
				case 2: $cc = "`7"; break;	// neutral
				case 3: $cc = "`4"; break;	// schlecht
				}
				addnav(array("%s&#149; %s", $cc, $item['idname']), $script."op=fight&key=".$key."",true);
			}
		}
	}else if ($hookname == "apply-specialties"){
		$key = httpget('key');
		if ($key!="") {
			require_once("modules/pilze/pilze_lib.php");
			$alleitems = createarray(get_module_pref("alleitems"));
			pilze_buff($alleitems[$key]['buff']);
			array_splice($alleitems, $key , 1);
			set_module_pref("alleitems",createstring($alleitems));
		}
	}else{
		require_once("modules/pilze/pilze_hooks_".$hookname.".php");
		$args = func_get_args();
		call_user_func_array("pilze_hooks_dohook_".$hookname."_private",$args);
	}
	return $args;
}

?>