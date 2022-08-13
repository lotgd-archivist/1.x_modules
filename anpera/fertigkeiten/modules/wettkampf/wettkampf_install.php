<?php

function wettkampf_install_private(){
	global $session;
    module_addhook("gardens");
	module_addhook("biostat");
    module_addhook("moderate");
    module_addhook("village");
	module_addhook("newday");
	module_addhook("newday-runonce");
	module_addhook("footer-hof");
    return true;
}
?>
