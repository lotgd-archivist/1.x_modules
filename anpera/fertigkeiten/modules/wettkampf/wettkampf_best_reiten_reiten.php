<?php
		if ($op2=="reiten"){
			$sql="SELECT ".db_prefix("accounts").".login AS login, ".db_prefix("accounts").".name AS name, (" . db_prefix("module_userprefs") . ".value+0) AS data1 FROM " . db_prefix("module_userprefs") . " LEFT JOIN accounts ON " . db_prefix("module_userprefs") . ".userid=accounts.acctid WHERE `modulename` = 'fertigkeiten' AND `setting` = 'reiten' ORDER BY data1 $order, dragonkills $ow, name $order LIMIT $limit";
			$adverb = translate_inline("besten");
			if ($subop == "least") $adverb = translate_inline("schlechtesten");
			$title = output("`c`^`bDie %s Fertigkeitswerte:`b`c", $adverb);
			$tags = array("Punkte");
			$table = array($title, $sql, false, $headers, $tags);
		}												
?>