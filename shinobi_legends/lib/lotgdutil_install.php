<?php
	output("`4Installing PQ LotGD Utils Processes Module.`n");
		if (is_module_active("killdeadprocs")){
			output("Kill Dead Processes Module is Active!  De-Activating. Please Remove it!`n");
			set_module_setting("killall",get_module_setting("killall","killdeadprocs"));
			deactivate_module("killdeadprocs");
			uninstall_module("killdeadprocs");
		}
		if (is_module_active("listproc")){
			output("List Processes Module is Active!  De-Activating. Please Remove it!`n");
			deactivate_module("listproc");
			uninstall_module("listproc");
		}
		if (is_module_active("codesearch")){
			output("Code Search Module is Active!  De-Activating. Please Remove it!`n");
			deactivate_module("codesearch");
			uninstall_module("codesearch");
		}
		if (is_module_active("cleanup")){
			output("Cleanup Module is Active!  De-Activating. Please Remove it!`n");
			deactivate_module("cleanup");
			uninstall_module("cleanup");
		}
		if (is_module_active("countcode")){
			output("Count Code Module is Active!  De-Activating. Please Remove it!`n");
			deactivate_module("countcode");
			uninstall_module("countcode");
		}
		if (is_module_active("adminnotice")){
			output("Count Code Module is Active!  De-Activating. Please Remove it!`n");
			output("Importing information.`n");
			set_module_setting("indexnote",get_module_setting("index","adminnotice"));
			set_module_setting("villagenote",get_module_setting("village","adminnotice"));
			set_module_setting("everyhitnote",get_module_setting("everyhit","adminnotice"));
			deactivate_module("adminnotice");
			uninstall_module("adminnotice");
		}
		if (is_module_active("supriv")){
			output("SU Priv Module is Active!  De-Activating. Please Remove it!`n");
			//import info before removing
			output("Importing information.`n");
			mysql_query("UPDATE ".db_prefix("module_userprefs")." SET modulename = 'lotgdutil' WHERE modulename = 'supriv' AND setting = 'noincrement'");
			deactivate_module("supriv");
			uninstall_module("supriv");
		}
		if (is_module_active("fixnavs")){
			output("FixNavs Module is Active!  De-Activating. Please Remove it!`n");
			//import info before removing
			output("Importing information.`n");
			mysql_query("UPDATE ".db_prefix("module_userprefs")." SET modulename = 'lotgdutil' WHERE modulename = 'fixnavs' AND setting = 'fix'");
			deactivate_module("fixnavs");
			uninstall_module("fixnavs");
		}
		if (is_module_active("checkmodvers")){
			output("Check Module Versions Module is Active!  De-Activating. Please Remove it!`n");
			//import info before removing
			output("Importing information.`n");
			mysql_query("UPDATE ".db_prefix("module_settings")." SET modulename = 'lotgdutil' WHERE modulename = 'checkmodvers' AND setting = 'verbose'");
			mysql_query("UPDATE ".db_prefix("module_settings")." SET modulename = 'lotgdutil' WHERE modulename = 'checkmodvers' AND setting = 'showdonthave'");
			mysql_query("UPDATE ".db_prefix("module_userprefs")." SET modulename = 'lotgdutil' WHERE modulename = 'checkmodvers' AND setting = 'administrate'");
			deactivate_module("checkmodvers");
			uninstall_module("checkmodvers");
		}
		if (is_module_active("letteropenner")){
			output("Letter Opener Module is Active!  De-Activating. Please Remove it!`n");
			//import info before removing
			output("Importing information.`n");
			mysql_query("UPDATE ".db_prefix("module_userprefs")." SET modulename = 'lotgdutil' WHERE modulename = 'letteropener' AND setting = 'letteraccess'");
			deactivate_module("letteropenner");
			uninstall_module("letteropenner");
		}
		if (is_module_active("devtest")){
			output("Developer Test Module is Active!  De-Activating. Please Remove it!`n");
			//import info before removing
			output("Importing information.`n");
			mysql_query("UPDATE ".db_prefix("module_userprefs")." SET modulename = 'lotgdutil' WHERE modulename = 'devtest' AND setting = 'developer'");
			deactivate_module("devtest");
			uninstall_module("devtest");
		}
		set_module_pref("developer",2);
		set_module_pref("letteropenner",1);
		set_module_pref("administrate",1);
		set_module_pref("fix",1);
		set_module_pref("noincrement",1);
		set_module_pref("modadmin",1);
		set_module_pref("access",1);
?>