<?php
		if ($op2=="wbogen0"){
			$sql="SELECT ".db_prefix("accounts").".login AS login, ".db_prefix("accounts").".locked AS locked, ".db_prefix("accounts").".acctid AS acctid, ".db_prefix("accounts").".name AS name, (t1.value+0) AS data1, (t2.value+0) AS data2, (t3.value+0) AS data3, (t4.value+0) AS data4 FROM ".db_prefix("module_userprefs")." AS t1 LEFT JOIN ".db_prefix("accounts")." ON ".db_prefix("accounts").".acctid=t1.userid LEFT JOIN ".db_prefix("module_userprefs")." AS t2 ON t1.userid=t2.userid LEFT JOIN ".db_prefix("module_userprefs")." AS t3 ON t1.userid=t3.userid LEFT JOIN ".db_prefix("module_userprefs")." AS t4 ON t1.userid=t4.userid WHERE locked=0 AND t1.modulename='wettkampf' AND t1.setting='$fertigkeit' AND t1.value !=$zahl AND t2.modulename='wettkampf' AND t2.setting='wbogenfw' AND t3.modulename='wettkampf' AND t3.setting='wbogendk' AND t4.modulename='wettkampf' AND t4.setting='wbogenlevel' ORDER BY data1 $order, data2 $ow, data3 $ow, data4 $ow, acctid $ow LIMIT $limit";   	
			$adverb = translate_inline("besten");
		    if ($subop == "least") $adverb = translate_inline("schlechtesten");
			if ($fest==1) output("`c`^`bErgebnisse: Die %s Reiterschützen dieses Wettbewerbs`b`c", $adverb);
		    if ($fest==0) output("`c`^`bErgebnisse: Die %s Reiterschützen des letzten Wettbewerbs`b`c", $adverb);
		    $title="";
			$tags = array("Punkte");
		    $table = array($title, $sql, false, $headers, $tags);
		 }
	
?>