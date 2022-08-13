<?php

	global $session;
			$char = httpget('char');
			$sql = "SELECT acctid FROM ".db_prefix("accounts")." WHERE login='$char'";
			$results = db_query($sql);
			$row = db_fetch_assoc($results);
			$speise = get_module_pref('bestespeise','wettkampf',$row['acctid'], "wettkampf");
			
			if ($speise == "") output("`^Bestes Kochergebnis: `6Noch kein gelungener Versuch`n");
			else output("`^Bestes Kochergebnis: `2%s`n", $speise);
	return $args;
?>
