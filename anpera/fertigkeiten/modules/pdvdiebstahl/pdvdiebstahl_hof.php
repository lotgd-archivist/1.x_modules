<?php

function pdvdiebstahl_hof_run_private($args=false){
	global $session;
	page_header("Verbrecherliste");
	
	$playersperpage=50;
	$sql = "SELECT count(t2.acctid) AS c FROM ".db_prefix("module_userprefs")." AS t1 LEFT JOIN ".db_prefix("accounts")." AS t2 ON t2.acctid=t1.userid WHERE t1.modulename='pdvdiebstahl' AND t1.setting='erwischt' AND t1.value >0 AND t2.login !='blank_char'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$totalplayers = $row['c'];
	$pageoffset = (int)httpget('page');
	if ($pageoffset>0) $pageoffset--;
	$pageoffset*=$playersperpage;
	$from = $pageoffset+1;
	$to = min($pageoffset+$playersperpage,$totalplayers);
	$limit=" ORDER BY value DESC, login ASC, name ASC LIMIT $pageoffset,$playersperpage";
	addnav("Seiten");
	for ($i=0;$i<$totalplayers;$i+=$playersperpage) addnav("Seite ".($i/$playersperpage+1)." (".($i+1)."-".min($i+$playersperpage,$totalplayers).")","runmodule.php?module=pdvdiebstahl&op1=hof&page=".($i/$playersperpage+1));
	
	$sqls="SELECT ".db_prefix("accounts").".login AS login, ".db_prefix("accounts").".acctid AS acctid, ".db_prefix("accounts").".name AS name, ".db_prefix("accounts").".sex AS sex, ".db_prefix("accounts").".name AS name, (t1.value+0) AS value FROM ".db_prefix("module_userprefs")." AS t1 LEFT JOIN ".db_prefix("accounts")." ON ".db_prefix("accounts").".acctid=t1.userid WHERE t1.modulename='pdvdiebstahl' AND t1.setting='erwischt' AND t1.value !=0 AND name!='blank_char' $limit";
	$results = db_query($sqls) or die(sql_error($sqls));
	$max = db_num_rows($results);
				
	output("`c`^`bDie Liste der übelsten Verbrecher `b");
	output("(`^Seite ".($pageoffset/$playersperpage+1).": `^$from-$to`^ von `^$totalplayers`^)`0`n`n");
	if (is_module_active('botschaft')){
		output("`%Die drassorianische Verwaltung warnt ausdrücklich vor den hier Verzeichneten!`nDiese Personen genießen den zweifelhaften Ruhm, für eine Vielzahl von Verbrechen verantwortlich zu sein!`nWer sie kennt, nimm sich in acht, beim allmächtigen Zrarek!`c`n`n");
	}else output("`2Hier findest Du Helden einer ganz besonderen Art:`nJene, die den zweifelhaften Ruhm genießen, für eine Vielzahl von Verbrechen verantwortlich zu sein.`nWenn Du jemanden kennst, der hier verzeichnet ist, nimm Dich in acht!`c`n`n");
			
	output("<center><table border=0 cellpadding=2 cellspacing=0 bgcolor='#999999'>",true);
	output("<tr class='trhead'><td><b>Rang</b></td><td><b>Name</b></td><td><b>Straftaten</b></td><td><b>Einstufung</b></td></tr>",true);
	
	for ($i=1; $i<=$max; $i++){
		$rows=db_fetch_assoc($results);
		
		if ($rows['name']==$session['user']['name']) output("<tr bgcolor='#506050'><td>",true);
		else rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>", true);

		//output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
					
		output("`c`^".($i+$from-1)."`2`c");
		output("</td><td>",true);
		
		output_notl("<a href='bio.php?char=".rawurlencode($rows['login'])."&ret=hhof.php'>`&".$rows['name']."</a>", true);
		output_notl("`0</td>", true);
		addnav("","bio.php?char=".rawurlencode($rows['login'])."&ret=hhof.php");
		
		//output($rows['name'],true);
		output("</td><td>",true);
		output("`\$`c");
		output($rows['value'],true);
		output("`c");
		output("</td><td>",true);
			
		if ($rows['value']<=5) output("%s", ($rows[sex]?"Gelegenheitsverbrecherin":"Gelegenheitsverbrecher"));
		else if ($rows['value']> 5 && $rows['value'] <= 10) output("%s", ($rows[sex]?"Kleinkriminelle":"Kleinkrimineller"));
		else if ($rows['value']> 10 && $rows['value'] <= 15) output("%s", ($rows[sex]?"Routinierte Kriminelle":"Routinierter Krimineller"));
		else if ($rows['value']> 15 && $rows['value'] <= 30) output("%s", ($rows[sex]?"Serientäterin":"Serientäter"));
		else if ($rows['value']> 30 && $rows['value'] <= 60) output("`4Bürgerschreck");
		else if ($rows['value']> 60 && $rows['value'] <= 90) output("%s", ($rows[sex]?"`\$Schwerverbrecherin`0":"`\$Schwerverbrecher`0"));
		else if ($rows['value']> 90 && $rows['value'] <= 120) output("%s", ($rows[sex]?"`\$Gefährliche Schwerverbrecherin!`0":"`\$Gefährlicher Schwerverbrecher!`0"));
		else if ($rows['value']> 120) output("`b`\$Ernsthafte Gefahr für die Gemeinde!`0`b");
		output("</td></tr>",true);
	}
	output("</table></center>",true);
	output("`c`4`nAchtung: Straftaten verfallen nach und nach, wenn der Kriminelle eine Weile nichts angestellt hat.`c");
	
	addnav("Zurück");
	addnav("Zu den Listen","hof.php");
	page_footer();
}
?>