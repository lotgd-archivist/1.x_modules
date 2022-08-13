<?php
	global $session;
	page_header("Collectibles Editor");
	//
	$ce_cat0=get_module_setting("collectshopname0" , "collectshop");
	$ce_cat1=get_module_setting("collectshopname1" , "collectshop");
	$ce_cat2=get_module_setting("collectshopname2" , "collectshop");
	$ce_cat3=get_module_setting("collectshopname3" , "collectshop");
	$ce_cat4=get_module_setting("collectshopname4" , "collectshop");
	$ce_cat5=get_module_setting("collectshopname5" , "collectshop");
	$ce_cat6=get_module_setting("collectshopname6" , "collectshop");
	$collectarray=array(
	"Collectibles Costs and Stuff,title",
	"collectid"=>"Collectible ID,hidden",
	"collectname"=>"Collectible Name,Name|Souvenir",		
	"collectcat"=>"Collectible Category,enum,0,$ce_cat0,1,$ce_cat1,2,$ce_cat2,3,$ce_cat3,4,$ce_cat4,5,$ce_cat5,6,$ce_cat6,7,Special",
	"Special category does not show up in shops. Can be used to create stuff to give out by events or quests...,note",
	"collectcostgold"=>"Collectible Cost in Gold,int|0",
	"collectcostgems"=>"Collectible Cost in Gems,int|0",
	"collectdk"=>"Dragon Kills Needed to Own,int|0",
	"collectrarity"=>"How rare is the item? 1=common 100=rarest,int|0",
	"collectimage"=>"Path to image (images/collect/yourpic.png),Enter Message Here|",
	"collectdesc"=>"Flavour text,Enter Message Here|",
	);	
	$id = httpget('id');
	//
	$op = httpget('op');
	$from = "runmodule.php?module=collecteditor&";	
	if ($op=="view"){
	$edit = translate_inline("Edit");
	$del = translate_inline("Delete");
	$delconfirm = translate_inline("Are you sure you wish to delete this collectible?");
	$cat = httpget('category');	
	$sql = "SELECT * FROM " . db_prefix("collectibles_items") . " WHERE collectcat=" .$cat . " ORDER BY collectcat, collectdk";
	$result = db_query($sql);	
	if (db_num_rows($result)<1){
	}else{
	$row = db_fetch_assoc($result);
	}
	$category = translate_inline(collecteditor_getCatName($cat));
    rawoutput("<table cellspacing=0 cellpadding=2 width='100%' align='center'><tr><td colspan='8'>");
	output("`Q%s`&",$category); 
	rawoutput("</td></tr><tr><td>");
	output("`bOps`b");
	rawoutput("</td><td>");
	output("`bID`b");
	rawoutput("</td><td>");
	output("`bPic`b");
	rawoutput("</td><td>");
	output("`bCollectible Name`b");
	rawoutput("</td><td>");
	output("`bCost Gold`b");
	rawoutput("</td><td>");
	output("`bCost Gems`b");
	rawoutput("</td><td>");
	output("`bRarity`b");
	rawoutput("</td><td>");
	output("`bDKs`b");
	rawoutput("</td></tr>");    
    $result = db_query($sql);
	$i=false;
    while ($row = db_fetch_assoc($result)) {
		$i=!$i;
	    rawoutput("<tr class='".($i?"trlight":"trdark")."'>"); 
	    rawoutput("<td>[<a href='runmodule.php?module=collecteditor&op=edit&id={$row['collectid']}'>$edit</a>|<a href='runmodule.php?module=collecteditor&op=delete&id={$row['collectid']}' onClick='return confirm(\"$delconfirm\");'>$del</a>]</td><td>");   	   
	    addnav("","runmodule.php?module=collecteditor&op=edit&id={$row['collectid']}");
		addnav("","runmodule.php?module=collecteditor&op=delete&id={$row['collectid']}");    
	    output("`6%s",$row['collectid']);
		rawoutput("</td><td><img src='". $row['collectimage'] ."' width=20 height=20></td><td>");
		output($row['collectname']);
		rawoutput("</td><td>");
	    output("`^%s ",$row['collectcostgold']);
		rawoutput("</td><td>");
	    output("`@%s ",$row['collectcostgems']);    	   
		rawoutput("</td><td>");
	    output("`@%s ",$row['collectrarity']);    	   
		rawoutput("</td><td>");
	    output("`@%s ",$row['collectdk']);    	   
	    rawoutput("</td></tr>");
    }    	
    rawoutput("</table>");
    modulehook("collecteditor", array());      
    addnav("Functions");
    addnav("Add a Collectible", $from."op=add"); 
	// eph: this snipped was copied and modified from creatures.php to sort the collectibles by category
	addnav("Categories");
	$sql1 = "SELECT collectcat,count(collectid) AS n FROM " . db_prefix("collectibles_items") . " group by collectcat ";
	$result1 = db_query($sql1);
	while ($row=db_fetch_assoc($result1)) {
		$var="ce_cat".$row['collectcat'];
		if ($$var=='') continue;
		$category = collecteditor_getCatName($row['collectcat']);
		addnav(array("$category: (%s Items)", $row['n']),
				"runmodule.php?module=collecteditor&op=view&category=".$row['collectcat']);
		}
	//
    addnav("Other");	
	addnav("Return to the Grotto", "superuser.php");
	
	}else if($op=="edit" || $op=="add"){
		if ($op=="edit"){
			$sql = "SELECT * FROM " . db_prefix("collectibles_items") . " WHERE collectid='$id'";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
		}else{
			$row=array(
			"collectid"=>"",
			"collectname"=>"",		
			"collectcat"=>"",
			"collectcostgold"=>"",
			"collectcostgems"=>"",
			"collectdk"=>"",
			"collectrarity"=>"",
			"collectimage"=>"",
			"collectdesc"=>"",
			);
		}
	rawoutput("<form action='runmodule.php?module=collecteditor&op=save' method='POST'>");
	addnav("","runmodule.php?module=collecteditor&op=save");
	showform($collectarray,$row);
	rawoutput("</form>");
	addnav("Go Back","runmodule.php?module=collecteditor&op=view&category=0");

}else if($op=="save"){
	$collectid = (int)httppost('collectid');
	$collectname = httppost('collectname');
	$collectcat = (int)httppost('collectcat');
	$collectcostgold = (int)httppost('collectcostgold');
	$collectcostgems = (int)httppost('collectcostgems');
	$collectdk = (int)httppost('collectdk');
	$collectrarity = (int)httppost('collectrarity');
	$collectimage = httppost('collectimage');
	$collectdesc = httppost('collectdesc');
	if ($collectid>0){
		$sql = "UPDATE " . db_prefix("collectibles_items") . " SET collectname=\"$collectname\",collectcat=$collectcat, collectcostgold=$collectcostgold, collectcostgems=$collectcostgems,collectdk=$collectdk,collectrarity=$collectrarity,collectimage=\"$collectimage\",collectdesc=\"$collectdesc\"  WHERE collectid='$collectid'";
		output("`6%s `6has been successfully edited.`n`n", $collectname);		
	}else{
		$sql = "INSERT INTO " . db_prefix("collectibles_items") . " (collectname,collectcat,collectcostgold,collectcostgems,collectdk,collectrarity,collectimage,collectdesc) VALUES (\"$collectname\",$collectcat, $collectcostgold,$collectcostgems,$collectdk,$collectrarity,\"$collectimage\",\"$collectdesc\")";
		output("`6The item \"%s\" `6has been saved to the database.`n`n",$collectname);
	}
	db_query($sql);
	$op = "";
	httpset("op", $op);	
	addnav("Go Back","runmodule.php?module=collecteditor&op=view&category=0");
}else if($op=="delete"){
	$sql = "DELETE FROM " . db_prefix("collectibles_items") . " WHERE collectid='$id'";
	db_query($sql);
	output("Collectible deleted!`n`n");
	redirect($from."op=view");
	addnav("Go Back","runmodule.php?module=collecteditor&op=view&category=0");
	$op = "";
	httpset("op", $op);
}
	page_footer();
?>