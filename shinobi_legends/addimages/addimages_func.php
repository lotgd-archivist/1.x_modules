<?php

function addimage($args) {
	if (is_module_active('addimages')) {
		if (get_module_pref('user_addimages','addimages')) {
			output_notl("`n`c<img src=\"modules/".$args."\" alt=\"$args\">`c`n`n",true);
		}
	}
}

?>