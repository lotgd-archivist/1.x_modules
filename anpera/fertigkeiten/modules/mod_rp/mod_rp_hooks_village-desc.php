<?php
	global $session;
		$loc = $session['user']['location'];
		if (get_module_pref("mod")==1) {
			modulehook("collapse{");
			$edit = translate_inline("Edit Describtion");
			output_notl("`n`7[<a href='runmodule.php?module=mod_rp&op=change'>`2$edit</a>`7]`n`0",true);
			addnav("","runmodule.php?module=mod_rp&op=change");
			modulehook("}collapse");
		}
		$rpdesc = get_module_setting($loc);
		if ($rpdesc!="") {
			output_notl("`&`n$rpdesc`n`0");
		}		
	return $args;
?>