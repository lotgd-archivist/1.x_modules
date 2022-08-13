<?php
function bingobook_remove() {
	global $session;
	$ac = httpget('ac');
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
	$result = db_query($sql);
	$row=db_fetch_assoc($result);
	if (bingobook_delete($ac)) {
		$info = translate_inline("That user has been removed.");
	} else {
		$info = translate_inline("No user found! Report this error.");
	}
	if (db_num_rows($result)>0) {
		$info = sprintf_translate("%s has been removed.`n`n",$row['name']);
	}
	output_notl($info);
}
?>