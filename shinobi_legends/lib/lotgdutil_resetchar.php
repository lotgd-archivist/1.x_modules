<?php
	//get some player info first
	require_once("lib/names.php");
	require_once("lib/titles.php");
	$sql = "SELECT sex,name,title,ctitle,superuser FROM ".db_prefix("accounts")." WHERE acctid = '".$op."'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	page_header("Resetting character %s",$row['name']);
	$newtitle = get_dk_title(0, $row['sex']);
	$newname = change_player_title($newtitle,$row);
	//now set start values
	$sql = "UPDATE ".db_prefix("accounts")." SET ";
	$sql .= "experience = 0, ";
	$sql .= "gold = ".getsetting("newplayerstartgold",50).", ";
	$sql .= "weapon = 'Fists', ";
	$sql .= "armor = 'T-Shirt', ";
	$sql .= "level = 1, ";
	$sql .= "defense = 1, ";
	$sql .= "attack = 1, ";
	$sql .= "goldinbank = 0, ";
	$sql .= "gems = 0, ";
	$sql .= "hitpoints = 10, ";
	$sql .= "maxhitpoints = 10, ";
	$sql .= "weaponvalue = 0, ";
	$sql .= "armorvalue = 0, ";
	$sql .= "location = 'Degolburg', ";
	$sql .= "title = '".$newtitle."', ";
	$sql .= "name = '".$newname."', ";
	$sql .= "weapondmg = 0, ";
	$sql .= "armordef = 0, ";
	$sql .= "charm = 0, ";
	$sql .= "hashorse = 0, ";
	$sql .= "dragonpoints = '', ";
	$sql .= "dragonkills = 0, ";
	$sql .= "race = 0, ";
	$sql .= "pk = 0, ";
	$sql .= "deathpower = 0, ";
	$sql .= "soulpoints = 0, ";
	$sql .= "specialty = '', ";
	$sql .= "dragonage = 0, ";
	$sql .= "lasthit='0000-00-00 00:00:00' ";
	$sql .= " WHERE acctid = '".$op."'";
	db_query($sql);
	//cannot remove all module prefs for moderators and admins - so only doing those with no superuser vaules
	//if ($row['superuser']  ==  1){
		//module_delete_userprefs($op);
	//}
	addnav("Continue","user.php?op=edit&userid=".$op);
?>