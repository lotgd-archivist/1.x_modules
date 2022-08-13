<?php

function mod_rp_uninstall_private(){
	output("`4Un-Installing Moderate Roleplay Module.`n");
	$sql = "DELETE FROM ".db_prefix("accounts")." where acctid='".get_module_setting('id')."'";
	mysql_query($sql);
	output("Blank Character deleted.`n");
	return true;
}	
?>
