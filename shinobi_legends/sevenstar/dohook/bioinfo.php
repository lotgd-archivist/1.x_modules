<?php
//bioinfo
	$seal=get_module_pref("hastat","sevenstar",$args['acctid']);
	if ($seal>0) {
		$name=get_module_setting('name');
			// check for upgrades
				$tats = unserialize(get_module_pref("tatname","petra",$args['acctid']));
				$stage= get_module_pref("tattoo-stage","sevenstar",$args['acctid']);
				$prev_stage = $stage-1;
				$star = $prev_stage."star";
				if (isset($tats[$star])) unset($tats[$star]);
				$star_next = $stage."star";
				$tats[$star_next] = 1;
				set_module_pref("tatname",serialize($tats),"petra",$args['acctid']);
		if (get_module_pref("tattoo-stage","sevenstar",$args['acctid'])>=7) $name.=translate_inline('`) `$enhanced');
			output("`^Tattoo: %s`^`n",$name);
		}
?>
