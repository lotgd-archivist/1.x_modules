<?php
/**************
Name: Collectibles Shop
Author: Dorian & eph, very loosely based on the pet shop by Eth 
Version: 1.1
Release Date: 08-29-2005
About: Shops to buy collectibles.
*****************/

function collectshop_getmoduleinfo(){
	$info = array(
		"name"=>"Collectibles Shop",
		"version"=>"1.1",
		"author"=>"Dorian and eph",
		"category"=>"Collectibles",
		"download"=>"http://www.ephralon.de/z_logd/ephstuff/collectibles.zip",
		"requires"=>array(
			"collecteditor"=>"Collectibles Editor |By Dorian and eph",
		),
		"settings"=>array(
			"Collectibles Shop Settings - Main,title",
			"collectshopname0"=>"Name of Collectibles Shop|Souvenirs deLuxe",			
			"collectshoploc0"=>"Where does the shop appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"collectshopdesc0"=>"Individual shop flavour text|Everything you could ever want.",			
			"Short flavour text to introduce category sold in shop. Gets followed by global introduction.,note",
			"collectshopname1"=>"Name of Collectibles Shop|Dwarven Souvenirs",			
			"collectshoploc1"=>"Where does the shop appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"collectshopdesc1"=>"Individual shop flavour text|You step into the shop and see Dwarven axes replicas and beer mugs.",			
			"collectshopname2"=>"Name of Collectibles Shop|Elvish Souvenirs",			
			"collectshoploc2"=>"Where does the shop appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"collectshopdesc2"=>"Individual shop flavour text|You step into the shop and see elvish boots and daggers.",			
			"collectshopname3"=>"Name of Collectibles Shop|Mystery Shoppe",			
			"collectshoploc3"=>"Where does the shop appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"collectshopdesc3"=>"Individual shop flavour text|You step into the shop and see pumpkin candles.",			
			"collectshopname4"=>"Name of Collectibles Shop|Faerie Trinkets",			
			"collectshoploc4"=>"Where does the shop appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"collectshopdesc4"=>"Individual shop flavour text|You step into the shop and see wing polish and dragon eggs.",			
			"collectshopname5"=>"Name of Collectibles Shop|Souvenirs from Iceland",			
			"collectshoploc5"=>"Where does the shop appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"collectshopdesc5"=>"Individual shop flavour text|You step into the shop and shudder at the temperature as you view the shelf with conserved snowballs.",			
			"collectshopname6"=>"Name of Collectibles Shop|Rural Keepsakes",			
			"collectshoploc6"=>"Where does the shop appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"collectshopdesc6"=>"Individual shop flavour text|You step into the shop and see dreamcatchers.",			
			"showcredit"=>"Show graphic credits at shop bottom?,bool|0",
	    	"graphcredit"=>"Artist names who did the graphics,255 chars",
		),		
	);
	return $info;
}
function collectshop_install(){	
	module_addhook("newday-runonce");
	module_addhook("meadow-1");
	module_addhook("meadow-2");
	module_addhook("meadow-3");
	module_addhook_priority("hof-add",84);
	module_addhook("changesetting");	
	//module_addhook("footer-prefs");
	//module_addhook("biotop");
	return true;
}
function collectshop_uninstall(){
	return true;
}
function collectshop_reset($id){
/*	$sql = "DROP TABLE IF EXISTS " . db_prefix("collectibles_inventory");
	db_query($sql);
	$sql = "CREATE TABLE ".db_prefix("collectibles_inventory")." (cownerid int(11) NOT NULL auto_increment, collectid int(11) NOT NULL, userid int(11) NOT NULL, PRIMARY KEY  (cownerid));";
	db_query($sql);*/
	}

function collectshop_dohook($hookname,$args)
	{
	global $session;
	$uid = $session['user']['acctid']; 	
	$from = "runmodule.php?module=collectshop&";
	//
	switch ($hookname)
 		{			
		case "newday-runonce":
		// Here the rarity value for new days are set.
		// First kill yesterday's entries
		$sql="DELETE FROM ".db_prefix("collectibles_rarity");
		$result=db_query($sql);
		
		// Now determine today's availability based on rarity for each individual item
		$sql="SELECT collectid,collectrarity FROM ".db_prefix("collectibles_items");
		$result=db_query($sql);
		while($row=db_fetch_assoc($result)) {
			$id=$row['collectid'];
			$rarity=$row['collectrarity'];
			if (e_rand(1,100)>=$rarity) {
				$sql2="INSERT INTO ".db_prefix("collectibles_rarity")." (collectid) VALUES (" .$id. ")";
				$result2=db_query($sql2);
			}
		}
		// Addnewsitem
		//addnews("The souvenir shops got restocked!");
		//invalidate caches
		massinvalidate("collectibles");
		break;
		case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("collectshoploc0")) {
				set_module_setting("collectshoploc0", $args['new']);
			}
			if ($args['old'] == get_module_setting("collectshoploc1")) {
				set_module_setting("collectshoploc1", $args['new']);
			}
			if ($args['old'] == get_module_setting("collectshoploc2")) {
				set_module_setting("collectshoploc2", $args['new']);
			}
			if ($args['old'] == get_module_setting("collectshoploc3")) {
				set_module_setting("collectshoploc3", $args['new']);
			}
			if ($args['old'] == get_module_setting("collectshoploc4")) {
				set_module_setting("collectshoploc4", $args['new']);
			}
			if ($args['old'] == get_module_setting("collectshoploc5")) {
				set_module_setting("collectshoploc5", $args['new']);
			}
			if ($args['old'] == get_module_setting("collectshoploc6")) {
				set_module_setting("collectshoploc6", $args['new']);
			}
		}
		break;
		case "meadow-1":case "meadow-2":case "meadow-3":
			$max=7;
			$shops=datacache('collectshops',600);
			if ($shops===false) {
				$shops=array();
				for ($i=0;$i<$max;$i++) {
					$shops[]=array("id"=>$i,"loc"=>get_module_setting("collectshoploc$i"),"name"=>get_module_setting("collectshopname$i"),"desc"=>get_module_setting("collectshopdesc$i"));
				}
				updatedatacache('collectshops',$shops);
			} else {
				if (!is_array($shops)) $shops=array();
			}
			$uloc=$session['user']['location'];
			addnav("Collectibles");
			foreach ($shops as $shop) {
				if ($shop['loc']!=$uloc) continue;
				addnav("Shops");			
				addnav(array("%s",$shop['name']),$from."op=enter&shop=".$shop['id']);	
			}	
			//addnav("Collections");
			//addnav("Collectibles","runmodule.php?module=collectshop&op=viewbio&page=1&cat=0");	
		break;
		/*case "footer-prefs":
			addnav("View collectibles","runmodule.php?module=collectshop&op=viewbio&page=1&cat=0");
			break;*/
		case "hof-add":
			if (!$session['user']['alive']) break;
			addnavheader("Collectibles");
			addnav("View collectibles","runmodule.php?module=collectshop&op=hofstart");
			break;
	}
	return $args;
}

function collectshop_runevent($type){
}

function collectshop_run(){
	require("modules/collectibles/collectshop.php");

}

function collectshop_getCatName($cat){
	switch ($cat) {
		case 0: $category = get_module_setting("collectshopname0" , "collectshop");
				break;
		case 1: $category = get_module_setting("collectshopname1" , "collectshop");
				break;
		case 2: $category = get_module_setting("collectshopname2" , "collectshop");
				break;
		case 3: $category = get_module_setting("collectshopname3" , "collectshop");
				break;
		case 4: $category = get_module_setting("collectshopname4" , "collectshop");
				break;
		case 5: $category = get_module_setting("collectshopname5" , "collectshop");
				break;
		case 6: $category = get_module_setting("collectshopname6" , "collectshop");
				break;
		case 7: $category = "Special";
	}
	return $category;
}

function collectshop_viewcollection($page, $cat, $acctid, $operation){
			// this part allows you to view a users collection
			$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid='$acctid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			page_header("%s's Collection",sanitize($row['name']));
			$pp = 5;
			$pageoffset = (int)$page;
			if ($pageoffset > 0) $pageoffset--;
			$pageoffset *= $pp;
			$from = $pageoffset;
			$images = array();
			// SQL statement for cowner
			$sql= "SELECT count(i.collectid) as counter FROM " . 
				db_prefix("collectibles_inventory") . " AS i INNER JOIN " . db_prefix("collectibles_items") . " AS r ON r.collectid=i.collectid " . 
				" WHERE i.userid=$acctid AND r.collectcat=$cat group by i.userid";
			$result=db_query($sql);
			$row=db_fetch_assoc($result);
			$count=(int)$row['counter'];
			$sql= "SELECT r.collectimage,r.collectdesc,r.collectid, r.collectcat, r.collectname FROM " . 
				db_prefix("collectibles_inventory") . " AS i INNER JOIN " . db_prefix("collectibles_items") . " AS r ON r.collectid=i.collectid " . 
				" WHERE i.userid=$acctid AND r.collectcat=$cat ORDER BY r.collectname LIMIT $pageoffset,$pp";
			$result = db_query($sql);

			// Now we have the ids for the collectibles
			$total = $count;
			if ($from + $pp < $total){
				$cond = $pageoffset + $pp;
			}else{
				$cond = $total;
			}
			$num = translate_inline("Number");
			$image = translate_inline("Image");
			$op = translate_inline("Item Name");
			$describe = translate_inline("Description");
			if ($count > 0){
				rawoutput("<big>");
				output("`c`b`^Currently on Page `\$%s`b`c`0",$page);
				rawoutput("</big>");
				output_notl("`n`n");
				rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
				rawoutput("<tr class='trhead'><td>$num</td><td>$image</td><td>$op</td><td>$describe</td></tr>");
				$i=$pageoffset;
				while ($row = db_fetch_assoc($result)) {
					// Select collectible data from collect
					rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
					$j=$i+1;
					output_notl("$j.");
					rawoutput("</td><td><center><img src='" . $row['collectimage'] . "'></center></td><td><center>");
					output($row['collectname']);
					rawoutput("</center></td><td><center>");
					output ($row['collectdesc']);
					rawoutput("</center></td></tr>");
				    $i++;					
				}
				rawoutput("</table>");
			}		
			// über alle Cats
			for ($c=0;$c<8;$c++){
				$category=collectshop_getCatName($c);
				$sql= "SELECT count(i.collectid) as counter FROM " . 
				db_prefix("collectibles_inventory") . " AS i INNER JOIN " . db_prefix("collectibles_items") . " AS r ON r.collectid=i.collectid " . 
				" WHERE i.userid=$acctid AND r.collectcat=$c group by i.userid";
				$result = db_query($sql);
				$row=db_fetch_assoc($result);
    			$total=$row['counter'];
				if ($total==0) continue;
				if ($category!="") addnav($category);debug($total);
				for ($p=0;$p<$total;$p+=$pp){
					addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=collectshop&op=".  $operation ."&typ=$type&page=". ($p/$pp+1) . "&cat=" .$c."&ouser=" .$acctid);
				}
			}
}
?>
