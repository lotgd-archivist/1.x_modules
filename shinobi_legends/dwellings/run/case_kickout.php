<?php
	addnav("Navigation");
	addnav("Back to Dwelling", "runmodule.php?module=dwellings&op=enter&dwid=$dwid");
	addnav("Back to Management", "runmodule.php?module=dwellings&op=manage&dwid=$dwid");
	addnav("Log Off", "runmodule.php?module=dwellings&op=logout&dwid=$dwid");
	$who = httpget('who');
	if($who>0){
		set_module_pref("dwelling_saver",0,"dwellings",$who);
		$sql="UPDATE ".db_prefix("accounts")." SET restorepage='village.php',location='".get_module_pref("location_saver","dwellings",$who)."' WHERE acctid=$who";
		db_query($sql);
		$msg = "`2%s`2 woke you up while you were sleeping in their %s`2 and sent you to the fields to sleep.";
	invalidatedatacache("dwellings-sleepers-$dwid");	$mailmessage = array($msg, $session['user']['name'], translate_inline(get_module_setting("dwname",$type)));
		require_once("lib/systemmail.php");
		systemmail($who, array("`2You were kicked out by %s`2",$session['user']['name']), $mailmessage);				
		output("You woke this person up and sent them to the fields to finish out their rest.");
	}else{
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
// This consumes a lot of resources and is not really essential. 
//					ORDER BY $mu.userid DESC";
		$result = db_query($sql);
		$name = translate_inline("Name");
		$level = translate_inline("Level");
		$dks = translate_inline("Dragon kills");		
		$laston = translate_inline("Last on");
		$writemail = translate_inline("Write mail");
		rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
		rawoutput("<tr class='trhead'><td>$name</td><td>$level</td><td>$dks</td><td>$laston</td></tr>");
		$i = 0;
		while($row = db_fetch_assoc($result)){
			$i++;
			rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
			rawoutput("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\">");
			rawoutput("<img src='images/newscroll.GIF' width='16' height='16' alt='$writemail' border='0'></a>");
			rawoutput("<a href='bio.php?char=".rawurlencode($row['login'])."'>");
			addnav("","bio.php?char=".rawurlencode($row['login'])."");
			output_notl("%s",$row['name']);
			$kickout = translate_inline("(Kick out)");
			rawoutput("</a><a href='runmodule.php?module=dwellings&op=kickout&who={$row['acctid']}&dwid=$dwid'>");
			output("`$%s`0",$kickout);
			addnav("","runmodule.php?module=dwellings&op=kickout&who={$row['acctid']}&dwid=$dwid");
			rawoutput("</a></td><td>");
			output_notl("%s",$row['level']);
			rawoutput("</td><td>");
			output_notl("%s",$row['dragonkills']);
			rawoutput("</td><td>");
			$laston = relativedate($row['laston']);
			output_notl("%s", $laston);
			rawoutput("</td></tr>");
		}
		rawoutput("</table>");
	}
?>

