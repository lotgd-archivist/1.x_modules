<?php
		if (!get_module_pref("bought")) {
			addnav(array("Bio Avatar Picture (%s %s)", $cost,
					translate_inline($cost == 1 ? "point" : "points")),
				"runmodule.php?module=avatar&op=purchase&cost=$cost");
		} else {
			addnav(array("Change Avatar Picture (%s %s)", $changecost,
					translate_inline($changecost == 1 ? "point" : "points")),
				"runmodule.php?module=avatar&op=purchase&cost=$changecost");
		}
		if (get_module_setting("allowsets")) {
			addnav("Gallery");
			addnav("View Bio Avatar Gallery", "runmodule.php?module=avatar&op=view");
		}

?>