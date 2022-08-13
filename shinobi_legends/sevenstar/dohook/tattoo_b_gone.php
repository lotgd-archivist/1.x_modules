<?php
			if (strpos($args['tatname'],"star",1) === FALSE){
				// This isn't a part of the 7 Star Series
			}else{
				set_module_pref("hastat",0);
				set_module_pref("promise",0);
				set_module_pref("tattoo-stage",0);
			}
?>