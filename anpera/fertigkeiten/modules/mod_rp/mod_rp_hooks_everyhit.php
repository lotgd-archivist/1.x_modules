<?php
	function system_commentary($section,$comment) {
		if ($comment != ""){
			require_once("lib/commentary.php");
			$blankID = get_module_setting("id","mod_rp");
			injectrawcomment($section, $blankID,":".$comment);
		}
	}	
	return $args;
?>