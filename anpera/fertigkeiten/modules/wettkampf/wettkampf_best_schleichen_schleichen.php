<?php
if ($op2=="schleichen"){
		    $sql="SELECT ".db_prefix("accounts").".login AS login, ".db_prefix("accounts").".name AS name, (" . db_prefix("module_userprefs") . ".value+0) AS data1 FROM " . db_prefix("module_userprefs") . " LEFT JOIN accounts ON " . db_prefix("module_userprefs") . ".userid=accounts.acctid WHERE `modulename` = 'fertigkeiten' AND `setting` = 'schleichen' ORDER BY data1 $order, dragonkills $ow, name $order LIMIT $limit";
		    $me = "SELECT count(*) AS count FROM " . db_prefix("module_userprefs") . " LEFT JOIN accounts ON " . db_prefix("module_userprefs") . ".userid=accounts.acctid WHERE $standardwhere";
		    $adverb = translate_inline("besten");
		    if ($subop == "least") $adverb = translate_inline("schlechtesten");
		    $title = output("`^`b`cDie %s Fertigkeitswerte:`c`b", $adverb);
		    $tags = array("Punkte");
		    $table = array($title, $sql, false, $headers, $tags);
		}				
?>