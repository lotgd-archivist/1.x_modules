<?php $owner = httpget('owner');
	$ac = db_prefix("accounts"); 
	$mu = db_prefix("module_userprefs");
	$sql = "SELECT $ac.name AS name,
			$ac.acctid AS acctid,
			$ac.level AS level,
			$ac.login AS login,
			$ac.laston AS laston,
			$ac.dragonkills AS dragonkills,
			$mu.userid FROM $mu 
			INNER JOIN $ac ON $ac.acctid = $mu.userid 
			WHERE $mu.setting = 'dwelling_saver'
			and $mu.value = $dwid 
			and $ac.loggedin = 0";
	$result = db_query($sql);
	$count = db_num_rows($result);
	$allowed = get_module_setting("maxsleep",$type);
	$hook = modulehook("dwellings-addsleepers",array("type"=>$row['type'],"dwid"=>$dwid,"allowed"=>$allowed));
	$allowed = $hook['allowed'];
	if($count >= $allowed){
	   output("There is no place for you to sleep here!  The following people are already taking up the available sleeping areas:`n`n");
		$name = translate_inline("Name");
		$level = translate_inline("Level");
		$dks = translate_inline("Dragon kills");		
		$laston = translate_inline("Last on");
		rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
		rawoutput("<tr class='trhead'><td>$name</td><td>$level</td><td>$dks</td><td>$laston</td></tr>");
		$i = 0;
		while($row = db_fetch_assoc($result)){
			rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
			output_notl("%s",$row['name']);
			if($owner==1){
				$kickout = translate_inline("`$(Kick out)");
				rawoutput("<a href='runmodule.php?module=dwellings&op=kickout&who={$row['acctid']}&dwid=$dwid>$kickout</a>");
				addnav("","runmodule.php?module=dwellings&op=kickout&who={$row['acctid']}&dwid=$dwid");
			}
			rawoutput("</td><td>");
			output_notl("%s",$row['level']);
			rawoutput("</td><td>");
			output_notl("%s",$row['dragonkills']);
			rawoutput("</td><td>");
			$laston = relativedate($row['laston']);
			output_notl("%s", $laston);
			rawoutput("</td></tr>");
			$i++;
		}
		rawoutput("</table>");
	}else{
		$loc = get_module_setting("logoutlocation");
		if ($session['user']['loggedin']){
			$session['user']['restorepage'] = "runmodule.php?module=dwellings&op=enter&dwid=$dwid";
			$sql = "UPDATE " . db_prefix("accounts") . " SET loggedin=0, location='".get_module_setting("logoutlocation")."', restorepage='{$session['user']['restorepage']}' WHERE acctid = ".$session['user']['acctid'];
			db_query($sql);
			invalidatedatacache("charlisthomepage");
			invalidatedatacache("list.php-warsonline");
			invalidatedatacache("dwellings-sleepers-$dwid");
			modulehook("player-logout");
		}
		$session = array();
		redirect("index.php");
	}
?>
