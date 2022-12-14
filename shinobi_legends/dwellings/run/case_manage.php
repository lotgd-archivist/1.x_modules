<?php
//Added Guard Displays and Link to dwellings_pvp.
//Added my own "quick guard" code for players with the right amount of cash with them
//Added "renew contract" code for the guards, something I've missed the whole time
//To Do: Delete incremental guard purchase, as it's unrealistic?
//Added Upkeep + "quick upkeep" code + message when there's nothing to do
//To Do: Delete incremental dwelling upkeep? Or do some buildings actually have really high costs?
//Added simple display of the coffers
//Sell code added

//replaced op=management with op=manage, deleting and replacing the old 14 KB file. 
//added the new sell restriction
//corrected a bug in the quick-upkeep
//translation readied the whole page, deleted redundant vars
//added missing modulehook


	$dwid = httpget("dwid");
	$subop = httpget('subop');

	page_header("Dwelling Management - Main");

	$sql = "SELECT ownerid, name,location,goldvalue,gemvalue,gold,gems,type FROM " . db_prefix("dwellings") . " WHERE dwid='$dwid'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$type = $row['type'];
	$typeid = get_module_setting("typeid",$type);
	
	addnav("Management");
	addnav("Main","runmodule.php?module=dwellings&op=manage&dwid=$dwid");
	addnav("Key Management","runmodule.php?module=dwellings&op=keys&dwid=$dwid");
	addnav("Kick Out Sleepers","runmodule.php?module=dwellings&op=kickout&dwid=$dwid");
	addnav("Names and Descriptions","runmodule.php?module=dwellings&op=manage-names&dwid=$dwid");
	modulehook("dwellings-manage",array("type"=>$type,"dwid"=>$dwid));
	
	switch ($subop){
		case "sell":
			$sellgold = httpget("sellgold");
			$sellgems = httpget('sellgems');
			$confirm = httpget('confirm');
			if ($session['user']['acctid']!=$row['ownerid']) {
				output("Woops, you actually did sell that dwelling already!");
				break;
			}
			if ($confirm){
				output("You sell your dwelling for %s gold and %s gems.",$sellgold,$sellgems);
				blocknav("runmodule.php?module=dwellings&op=manage",true);
				blocknav("runmodule.php?module=dwellings&op=enter&dwid=$dwid");
				$session['user']['gold']+=$sellgold;
				$session['user']['gems']+=$sellgems;
				debuglog("got $sellgold gold and $sellgems gems for selling their dwelling no. $dwid");
				$coff = "";
				if (get_module_setting("dumpcof")) $coff=",gold=0,gems=0";				
				$sql = "UPDATE ".db_prefix("dwellings")." SET status=5,ownerid=0$coff WHERE dwid=$dwid";
				db_query($sql);
				$sql = "UPDATE ".db_prefix("dwellingkeys")." SET keyowner=0,dwidowner=0 WHERE dwid=$dwid";
				db_query($sql);
				modulehook("dwellings-sold",array("type"=>$type,"dwid"=>$dwid));
				if (get_module_setting("commwhat") == 0){
					$sql = "SELECT " .
						db_prefix("commentary").".*,".db_prefix("accounts").".name,".
						db_prefix("accounts").".login, ".db_prefix("accounts").".clanrank,".
						db_prefix("clans").".clanshort FROM ".db_prefix("commentary").
						" INNER JOIN ".db_prefix("accounts")." ON ".
						db_prefix("accounts").".acctid = " . db_prefix("commentary").
						".author LEFT JOIN ".db_prefix("clans")." ON ".
						db_prefix("clans").".clanid=".db_prefix("accounts").
						".clanid WHERE (section = 'dwellings-$dwid' OR section='coffers-$dwid')";
					$res = db_query($sql);
					$invalsections = array();
					while ($row = db_fetch_assoc($res)){
						$sql = "INSERT LOW_PRIORITY INTO ".db_prefix("moderatedcomments").
								" (moderator,moddate,comment) 
								VALUES ('{$session['user']['acctid']}',
										'".date("Y-m-d H:i:s")."',
										'".addslashes(serialize($row))."')";
						db_query($sql);
						$invalsections[$row['section']] = 1;
					}
					foreach($invalsections as $key=>$dummy) {
						invalidatedatacache("comments-$key");
					}
				}
				$sql = "DELETE FROM ".db_prefix("commentary")." 
					WHERE section = 'dwellings-$dwid' 
					OR section='coffers-$dwid'";
					db_query($sql);
			}else{
					output("`$ Are you really sure about selling your dwelling? ");
					$clickhere = translate_inline("Click here to confirm!");
					rawoutput("<a href='runmodule.php?module=dwellings&op=manage&subop=sell&sellgold=$sellgold&sellgems=$sellgems&dwid=$dwid&confirm=1'>$clickhere</a>");
					addnav("","runmodule.php?module=dwellings&op=manage&subop=sell&sellgold=$sellgold&sellgems=$sellgems&dwid=$dwid&confirm=1");
			}					
			break;
		}

		if($subop!="") 
			output("`n`n");
		output("`2Here you can find an overview of what's happening in your dwelling.`n`n");
	
		$name = appoencode($row['name']);
		if($name=="") $name = translate_inline("- None -"); 
		$location=$row['location'];
		$goldvalue = $row['goldvalue'];
		$gemvalue = $row['gemvalue'];
		$coffersgold = $row['gold'];
		$coffersgems = $row['gems'];
		$dwgold = translate_inline("gold");
		$dwgems = translate_inline("gems");
		$givegold = $goldvalue;
		$givegems = $gemvalue;
		if(get_module_setting("addcof")){
			$givegold += $coffersgold;
			$givegems += $coffersgems;
		}
		$givegold = round($givegold*(get_module_setting("valueper")*0.01));
		$givegems = round($givegems*(get_module_setting("valueper")*0.01));
		
		rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999' width=95% align=center>");
		$doot_general = translate_inline("Dwellings General Information");
		rawoutput("<tr><td colspan=2 class='trhead' style='text-align:center;'>$doot_general</td></tr>");
		$dwname = translate_inline("Dwelling name");
		rawoutput("<tr height=30px class='trlight'><td>$dwname</td><td>$name</td></tr>");
		$dwloc = translate_inline("Dwelling location");
		rawoutput("<tr height=30px class='trdark'><td>$dwloc</td><td>$location</td></tr>");
		modulehook("dwellings-management",array("type"=>$type,"dwid"=>$dwid));
		$doot_value = translate_inline("Dwelling's Values");
		rawoutput("<tr><td colspan=2 class='trhead' style='text-align:center;'>$doot_value</td></tr>");
		$goldv = translate_inline("Gold value of the dwelling");
		$gemv = translate_inline("Gem value of the dwelling");
		rawoutput("<tr height=30px class='trlight'><td>");
		output_notl($goldv);
		rawoutput("</td><td>");
		output_notl("`^%s `0%s",$goldvalue,$dwgold);
		rawoutput("</td></tr>");
		rawoutput("<tr height=30px class='trlight'><td>");
		output_notl($gemv);
		rawoutput("</td><td>");
		output_notl("`%%s `0%s",$gemvalue,$dwgems);
		rawoutput("</td></tr>");
//Coffers Display	
		$doot_coffer = translate_inline("Dwelling's Coffers");
		$goldcof = translate_inline("Gold in Coffers");
		$gemscof = translate_inline("Gems in Coffers");
		$maxallow = translate_inline(", the maximum amount allowed is");
		rawoutput("<tr><td colspan=2 class='trhead' style='text-align:center;'>$doot_coffer</td></tr>");
		rawoutput("<tr height=30px class='trlight'><td>");
		output_notl($goldcof);
		rawoutput("</td><td>");
		output_notl("`^%s `0%s%s `^%s `0%s",$coffersgold,$dwgold,$maxallow,get_module_setting("maxgold",$type),$dwgold);
		rawoutput("</td></tr>");
		rawoutput("<tr height=30px class='trlight'><td>");
		output_notl($gemscof);
		rawoutput("</td><td>");
		output_notl("`%%s `0%s%s `%%s `0%s",$coffersgems,$dwgems,$maxallow,get_module_setting("maxgems",$type),$dwgems);
		rawoutput("</td></tr>");
//Selling Option
		$levelsell = get_module_setting("levelsell");
		if ($session['user']['level'] >= $levelsell) {
			$doot_sell = translate_inline("Dwelling's Real Estate");
			rawoutput("<tr><td colspan=2 class='trhead' style='text-align:center;'>$doot_sell</td></tr>");
			rawoutput("<tr height=30px class='trlight'><td colspan=2>");
			output("You have received an offer for your dwelling of `^%s `0gold and `%%s `0gems. Do you wish to",$givegold,$givegems);
			$sellit = translate_inline("sell it");
			rawoutput("[<a href='runmodule.php?module=dwellings&op=manage&subop=sell&sellgold=$givegold&sellgems=$givegems&dwid=$dwid'>$sellit</a>]?</td></tr>");
		  addnav("","runmodule.php?module=dwellings&op=manage&subop=sell&sellgold=$givegold&sellgems=$givegems&dwid=$dwid");
		}else{
			$tooyoung = translate_inline("You're still too young to start thinking about selling your dwelling. You'll never manage to sell this rat infested hole to people more experienced than you. Try again when you're level");
			rawoutput("<tr height=30px class='trlight'><td colspan=2>$tooyoung $levelsell.</td></tr>");
		}
		rawoutput("</table>"); 
		addnav("Leave");
		addnav("Back to Dwelling","runmodule.php?module=dwellings&op=enter&dwid=$dwid");
?>