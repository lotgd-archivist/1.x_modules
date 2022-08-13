<?php
function racevanthira_uninstall_private(){
global $session;
	// Force anyone who was a Vanthira to rechoose race
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Vanthira'";
	db_query($sql);
	if ($session['user']['race'] == 'Vanthira')
	$session['user']['race'] = RACE_UNKNOWN;
	return true;
}	
?>
