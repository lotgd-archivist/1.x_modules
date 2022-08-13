<?php
	require_once("modules/inventory/lib/itemhandler.php");
	$skill = httpget('skill');
	if ($skill=="ITEM"){
		$id = (int)httpget('l');
		$item = get_inventory_item((int)$id);
	 	if ($item['charges'] > 1)
	 		uncharge_item((int)$id);
	 	else
	 		remove_item((int)$id);
		require_once("lib/buffs.php");
		if ($item['buffid'] > 0)
			apply_buff($item['name'], get_buff($item['buffid']));
		if ($item['execvalue'] > "") {
			require_once("modules/inventory/lib/itemeffects.php");
			if ($item['exectext'] > "") {
				output($item['exectext'], $item['name']);
			} else {
				output("You activate %s!", $item['name']);
			}
			output_notl("%s`n", get_effect($item, $item['noeffecttext']));
		}
	}
?>
