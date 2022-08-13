<?php
	global $session;
	$shop=httpget('shop');
	$returnpath=rawurldecode(httpget('retpath'));
	$shopname = get_module_setting("collectshopname$shop");

	$op = httpget('op');	
	$from = "runmodule.php?module=collectshop&shop=$shop&";
	$id = httpget('id');
	$uid = $session['user']['acctid']; 	
	$buy = translate_inline("Purchase");
	//Collectibles shop main
	switch ($op) {
		case "enter":
			page_header("%s",$shopname);
			output("`b`c%s`c`b`n`n", $shopname);
			output_notl($shopdesc . "`n");
			output("A small bell over the door announces your arrival. Loads of trinkets and treasures sit on the shelves which line the walls.");
			output(" A young woman comes in from a backdoor and gives you a friendly smile.`n`n");
			output("\"Welcome to the souvenir shop, traveler,\" she greets you. \"We sell all kinds of lovely keepsakes for you to collect and cherish. May I help you with anything?\"`n`n");
			
			// Get player's location to define the category sold in this shop
			$cat=(int)$shop;
			$userdk = $session['user']['dragonkills'];
			$userid = $session['user']['acctid'];

			// Select shop items available
			$sql = "SELECT i.*,min(inv.userid) as user,r.available as stock FROM " . db_prefix("collectibles_items") .
				   " AS i INNER JOIN " . db_prefix("collectibles_rarity").
				   " AS r ON i.collectid=r.collectid LEFT JOIN ".db_prefix("collectibles_inventory")." AS inv ON i.collectid=inv.collectid WHERE ((inv.userid is null) OR inv.userid!=$userid) AND i.collectcat=$cat AND r.available>0".
				   " GROUP BY i.collectid ORDER BY i.collectdk DESC, i.collectname";	

			$tabletext = translate_inline("Below is a Listing of Souvenirs");
			$choice = translate_inline("Choice");
			$souvenir = translate_inline("Souvenir");
			$costgold = translate_inline("Gold Cost");
			$costgem = translate_inline("Gem Cost");
			rawoutput("<table cellspacing=0 cellpadding=2 width='500' align='center'><tr><td align='center'><b>$tabletext</b></td></tr></table>");
	    	$result = db_query_cached($sql,"collectibles-$cat");
	    	rawoutput("<table cellspacing=0 cellpadding=2 width='500' align='center'><tr><td><b>$choice</b></td><td>&nbsp;</td><td><b>$souvenir</b></td><td><b>$costgold</b></td><td><b>$costgem</b></td></tr>");    
			$i=true;
			debug($sql);
			$id=$has=$collection=array();
	    	/*while ($row = db_fetch_assoc($result)) {
				$collection[]=$row;
				$id[]=$row['collectid'];
			}
			/*$sql="SELECT * FROM ".db_prefix('collectibles_inventory')." WHERE collectid IN (".implode(",",$id).") AND userid=".$session['user']['acctid'].";";
			$result=db_query($sql);
	    	while ($row = db_fetch_assoc($result)) {
				$has[$row['collectid']]=1;
			}*/			
			$dk=$session['user']['dragonkills'];
			//foreach ($collection as $row) {
			$stock=translate_inline("`\$Only %s`\$ left!");
			while ($row = db_fetch_assoc($result)) {			
				$i=!$i;
	   			rawoutput("<tr class='".($i?"trlight":"trdark")."'>"); 
				if (isset($has['collectid']) && $has['collectid']==1) {
					output("<td>Already bought!</td>",true);
				} elseif ($dk<$row['collectdk']) {
					output("<td>Grow more!</td>",true);
				} else {
		   			rawoutput("<td>[<a href='runmodule.php?module=collectshop&op=buycollect&id={$row['collectid']}'>$buy</a>]</td>");
				}      	 	   
	   			addnav("","runmodule.php?module=collectshop&op=buycollect&id={$row['collectid']}");	
	 
				rawoutput("<td><img src='". $row['collectimage'] ."'></td><td>");
				output_notl($row['collectname']);
				output_notl(sprintf($stock,$row['stock']));
				rawoutput("</td><td>");
				output("`^%s Gold",$row['collectcostgold']);
				rawoutput("</td><td>");
	    		output("`@%s Gems",$row['collectcostgems']); 
				rawoutput("</td></tr>");
			}
	    	rawoutput("</table>");

			if (db_num_rows($result)==0) {
				output("`c`xSorry, we're sold out for today! Please stop by tomorrow.`c");
			} 
			
			if (get_module_setting("showcredit"))
			{
				$graphcredit = get_module_setting("graphcredit");
				output("`n`n`cGraphics credits go to: %s. Licensed for use in LotGD.`c",$graphcredit);
			}

			addnav("Navigation");
			addnav("Back to the meadows","runmodule.php?module=marriage&op=meadows");
			break;
		case "purchase":
			page_header("%s",$shopname);
			output("`b`c`2%s`c`b`n`n", $shopname);
			$sql = "SELECT collectid,collectname FROM " . db_prefix("collectibles_items") . " WHERE collectid=$collectid";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);						
			break;
			//BUY SOUVENIRS
		case "buycollect":
			page_header("%s",$shopname);
			output("`b`c`2%s`c`b`n`n", $shopname);
			$sql = "SELECT * FROM " . db_prefix("collectibles_items") . " WHERE collectid='$id'";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);		
			$collect = translate_inline($row['collectname']);
			$collectid = $row['collectid'];
			$costgold = $row['collectcostgold'];
			$costgems = $row['collectcostgems'];
			$cat = $row['collectcat'];
			$ugold = $session['user']['gold'];
			$ugems = $session['user']['gems'];		
			//sorry, not enough money...	
			if ($ugold<$costgold){
				output("`^The shopkeeper informs you with a sad look that you haven't enough gold to purchase this `^%s.`n`n", $collect);			
				addnav("Go Back", $from."op=enter");
			}else if ($ugems<$costgems){
				output("`^You count your gems and realize you don't have enough to purchase this `^%s.`n`n", $collect);
				addnav("Go Back", $from."op=enter");
			//My mistake, carry on...
			} else {
				output("`^The shopkeeper smiles warmly and congratulates on your purchase of `3%s.", $collect);
				//set prefs and what not
				// Patrick: add row to cowner table.
				$userid=$session['user']['acctid'];

				$sql = "INSERT into " . db_prefix("collectibles_inventory") . " (collectid, userid) VALUES (".$collectid.",".$userid.")";
				$result = db_query($sql);
				$sql = "UPDATE " . db_prefix('collectibles_rarity') . " SET available=available-1 WHERE collectid=$collectid;";
				db_query($sql);
				
				invalidatedatacache("collectibles-$cat");
				
				//deduct cost of collectible
				$session['user']['gold']-=$costgold;
				$session['user']['gems']-=$costgems;			
				addnav("Go Back", $from."op=enter");						
				
			} 
			break;
		case "viewbio":
			$page = httpget('page');
			$cat = httpget('cat');
			collectshop_viewcollection($page, $cat, $session['user']['acctid'], $op);
			addnav("Navigation");
			addnav("Back to the meadows","runmodule.php?module=marriage&op=meadows");
			break;
		case "hofstart":
		// this part allows you to view other player's collection in the hof
		//bio is unsafe (cheating to other places)
			$user=$session['user']['acctid'];
			$limit=50;
			page_header("HOF Collectibles");
			addnav("Return to HOF","hof.php");
			addnav("View Collectibles");
			addnav("Search For Somebody","runmodule.php?module=collectshop&op=hofstart");
			addnav("View Random Collectibles","runmodule.php?module=collectshop&op=view&random=1");
			$target=httppost('target');
			$ta=addslashes($target);
			output("`c`b`tFind a collection`0`b`c`n`n");
			if ($target!='') {
				$sql="SELECT a.name as name,a.acctid FROM ".db_prefix('accounts')." AS a WHERE (name LIKE '%$ta%' OR login LIKE '%$ta') AND a.acctid!=$user limit $limit;";
				$result=db_query($sql);
				if (db_num_rows($result)<1) {
					$end=strlen($target);
					$search='%';
					for ($x=0;$x<$end;$x++){
						$search .= substr($target,$x,1)."%";
					}
					$sql="SELECT a.name as name,a.acctid FROM ".db_prefix('accounts')." AS a WHERE name LIKE '$search' OR LOGIN LIKE '$search' LIMIT $limit;";
					$result=db_query($sql);
				}
				if (db_num_rows($result)>0) {
					$name=translate_inline("Name");
					$message=translate_inline("Message");
					rawoutput("<center><table cellpadding='3' cellspacing='0' border='0' ><tr class='trhead'><td>$name</td></tr>");//<td>$message</td></tr>");
					while ($row=db_fetch_assoc($result)) {
						$class=($class=='trlight'?'trdark':'trlight');
						rawoutput("<tr class='$class'><td>");
						$link="<a href='runmodule.php?module=collectshop&op=view&cat=0&page=1&ouser=".$row['acctid']."'>".$row['name']."</a>";
						addnav("","runmodule.php?module=collectshop&op=view&cat=0&page=1&ouser=".$row['acctid']);
						output_notl($link,true);
						// rawoutput("</td><td>");
						// output_notl($row['message']);
						rawoutput("</td></tr>");
					}
					rawoutput("</table></center>");
				} else {
					output("`\$Sorry, I was unable to find anybody with that supplied name!`n`n");
				}
			}
			output("`xWhom are you looking for?`n`4Try to enter the name without the title, or completely to narrow down the search (results limited to %s hits).`2`n`n",$limit);
			rawoutput("<form action='runmodule.php?module=collectshop&op=hofstart' method='POST'>");
			addnav("","runmodule.php?module=collectshop&op=hofstart");
			rawoutput("<input type='input' length='50' name='target' value='".addslashes($target)."'><br>");
			$submit=translate_inline("Search!");
			rawoutput("<input type='submit' class='button' value='$submit'>");
			rawoutput("</form>");
			break;	
		case "view":
			page_header("HOF Collectibles");
			addnav("Return to HOF","hof.php");
			addnav("View Collectibles");
			addnav("Search For Somebody","runmodule.php?module=collectshop&op=hofstart");
			addnav("View Random Collectibles","runmodule.php?module=collectshop&op=view&random=1");		
			if (httpget('random')==1) {
				$sql="SELECT acctid,count(c.collectid) as collects FROM ".db_prefix('accounts')." INNER JOIN ".db_prefix('collectibles_inventory')." as c ON acctid=c.userid GROUP BY c.userid ORDER BY RAND() DESC;";
				$result=db_query($sql);
				$row=db_fetch_assoc($result);debug($sql);
				$ouser=(int)$row['acctid'];
				debug($row);
				if ($ouser==0) {
					output("No one found, try again");
					break;
				}
				$page=0;
				$cat=0;
			} else {
				$ouser = (int)httpget('ouser');
				$page = (int)httpget('page');
				$cat = (int)httpget('cat');
			}

			collectshop_viewcollection($page, $cat, $ouser, $op);
			
	}

page_footer();	
?>
