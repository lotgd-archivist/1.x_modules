<?php
/**************
Name: Collect Editor
Author: Dorian and eph 
Version: 1.1
Release Date: 08-29-2005
About: The editor needed to add/edit/delete collectible souvenirs for the shops.
*****************/
require_once("common.php");
require_once("lib/showform.php");
require_once("lib/http.php");
require_once("lib/villagenav.php");
function collecteditor_getmoduleinfo(){
	$info = array(
		"name"=>"Collectibles Editor",
		"version"=>"1.1",
		"author"=>"Dorian and eph",
		"category"=>"Collectibles",
		"download"=>"http://www.ephralon.de/z_logd/ephstuff/collectibles.zip",
	);
	return $info;
}
function collecteditor_install(){
	if (db_table_exists(db_prefix("collectibles_items"))) {
	}else{
	output("`6Installing collect table, cowner table and crare table.`n");
	output("`6Tables created.`n`n");
	$sql = array(	
	"CREATE TABLE ".db_prefix("collectibles_items")." (collectid int(11) NOT NULL auto_increment, collectname varchar(50) NOT NULL default 'Unnamed', collectcat int(11) NOT NULL default '0', collectcostgold int(11) NOT NULL default '0', collectcostgems int(11) NOT NULL default '0', collectdk int(11) NOT NULL default '0', collectrarity int(11) NOT NULL default '0', collectimage varchar(50) DEFAULT 'none', collectdesc varchar(255) NOT NULL default 'none', PRIMARY KEY  (collectid));",	
	"INSERT INTO ".db_prefix("collectibles_items")." VALUES (1, 'Teddybear', 0, 1000, 1, 0, 30, 'images/collect/teddybear01.png', 'Aaaw, this teddybear is so cute!');",
	"INSERT INTO ".db_prefix("collectibles_items")." VALUES (2, 'Red Apple', 0, 200, 0, 0, 10, 'images/collect/redapple01.png', 'This apple looks very delicious! Almost too good to be eaten.');",
	"CREATE TABLE ".db_prefix("collectibles_inventory")." (cownerid int(11) NOT NULL auto_increment, collectid int(11) NOT NULL, userid int(11) NOT NULL, PRIMARY KEY  (cownerid));",
	"CREATE TABLE ".db_prefix("collectibles_rarity")." (collectid int(11) NOT NULL, PRIMARY KEY  (collectid)) TYPE=MyISAM;",
	"INSERT INTO ".db_prefix("collectibles_rarity")." VALUES (1, 1);",
	);	
		foreach ($sql as $statement) {
		db_query($statement);	
		}
	}	
			
	module_addhook("superuser");	
	return true;
}

function collecteditor_uninstall(){	
	//delete the table at deinstall...		
	output("`6Deinstalling the collect table.`n`n");	
	$sql = "DROP TABLE IF EXISTS " . db_prefix("collectibles_items");
	db_query($sql);
	output("`6Deinstalling the cowner table.`n`n");	
	$sql = "DROP TABLE IF EXISTS " . db_prefix("collectibles_inventory");
	db_query($sql);
	output("`6Deinstalling the crare table.`n`n");	
	$sql = "DROP TABLE IF EXISTS " . db_prefix("collectibles_rarity");
	db_query($sql);
	return true;
}

function collecteditor_dohook($hookname,$args){
	global $session;
	$from = "runmodule.php?module=collecteditor&";
	switch ($hookname) {	
	case "superuser":
	if ($session['user']['superuser'] & SU_EDIT_MOUNTS)addnav("Editors");	
	if ($session['user']['superuser'] & SU_EDIT_MOUNTS)addnav("Collectibles Editor", $from."op=view&category=0");
	break;
	}
	return $args;
}
function collecteditor_run(){
	require("modules/collectibles/collecteditor.php");
}

function collecteditor_getCatName($cat){
	switch ($cat) {
		case 0:	case 1: 	case 2: 	case 3: 	case 4: 	case 5: 
		case 6: $category = get_module_setting("collectshopname$cat" , "collectshop");
				break;
		case 7: $category = "Special";
	}
	return $category;
}

?>