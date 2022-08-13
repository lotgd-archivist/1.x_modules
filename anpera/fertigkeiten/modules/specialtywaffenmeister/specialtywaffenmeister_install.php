<?php
function specialtywaffenmeister_install_private(){
$sql = "DESCRIBE " . db_prefix("accounts");
	$result = db_query($sql);
	$specialty="WM";
	while($row = db_fetch_assoc($result)) {
		// Convert the user over
		if ($row['Field'] == "thievery") {
			debug("Migrating thieving skills field to Waffenmeister");
			$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtywaffenmeister', 'skill', acctid, thievery FROM " . db_prefix("accounts");
			db_query($sql);
			debug("Dropping thievery field from accounts table");
			$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP thievery";
			db_query($sql);
		} elseif ($row['Field']=="thieveryuses") {
			debug("Migrating thieving skills uses field to Waffenmeister");
			$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtywaffenmeister', 'uses', acctid, thieveryuses FROM " . db_prefix("accounts");
			db_query($sql);
			debug("Dropping thieveryuses field from accounts table");
			$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP thieveryuses";
			db_query($sql);
		}
	}
	debug("Migrating Thieving Skills Specialty");
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='$specialty' WHERE specialty='3'";
	db_query($sql);
	
	module_addhook("biblio-spec");
	module_addhook("dragonkill");
	module_addhook("choose-specialty");
	module_addhook("set-specialty");
	module_addhook("specialtycolor");
	module_addhook("specialtynames");
	module_addhook("specialtymodules");
	module_addhook("incrementspecialty");
	module_addhook("newday");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	return true;
}	
?>
