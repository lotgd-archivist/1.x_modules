<?php
function racevanthira_install_private(){
// Vanthira leben unter allen Völkern. Der Einfachheit halber beginnen sie beim größten: den Menschen.
	if (!is_module_installed("racehuman")) {
		output("Vanthira starten bei den Menschen. Du musst das entsprechende Modul installieren.");
		return false;
		}
		module_addhook("graveyard-desc");
		module_addhook("chooserace");
		module_addhook("setrace");
		module_addhook("newday");
		module_addhook("charstats");
		module_addhook("raceminedeath");
		module_addhook("battle-victory");
	return true;
}	
?>
