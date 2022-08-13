<?php

function clan_avatars_getmoduleinfo(){
	$info = array(
		"name"=>"Clan Avatars",
		"author"=>"Chris Vorndran modified by `2Oliver Brendel",
		"category"=>"Clan",
		"version"=>"1.0",
		"settings"=>array(
			"Upload Settings,title",
				"uploaddir"=>"What folder (needs 777 permissions!) should be used locally (do not add a / at the end and use relative from the document root downwards!)?,text|clan_avatars",
				"the default is avatars - which is in your lotgd root the folder avatars. You need to create that folder and give it the proper permissions!,note",
				"it is also commonsense to put a .htaccess file into it to deny all accesses from outside the host,note",
				"uploadsize"=>"How many bytes can future uploaded avatars have?,int|50000",
			"Size restrictions,title",
				"restrictsize"=>"Is the size restricted?,bool|1",
				"maxwidth"=>"Max. width of clan avatars (Pixel),range,20,400,20|200",
				"maxheight"=>"Max. height of clan avatars (Pixel),range,20,400,20|200",
			"Other restrictions,title",
				"gemcost"=>"How many gems does this action cost?,int|50",
				"days"=>"How many days before a clan can submit an avatar?,int|0",
		),
		"prefs-clans"=>array(
			"validate"=>"Has this clan's avatar been validated?,bool|0",
			"filename"=>"What is this clan's avatar's filename?,text|",
			"days"=>"How many days have passed for this clan's avatar?,int|0",
		),
	);
	return $info;
}
function clan_avatars_install(){
	module_addhook("newday-runonce");
	module_addhook("superuser");
	module_addhook("superuser-headlines");
	module_addhook("biotop");
	module_addhook("clanhall");
	module_addhook("header-clan");
	module_addhook("clan-enter");
	return true;
}
function clan_avatars_uninstall(){
	return true;
}
function clan_avatars_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "superuser-headlines":
			require("modules/clan_avatars/do_hook/$hookname.php");
			break;
		case "newday-runonce":
			/*
				Just keep decreasing the days.
				Module checks in clan hall for a value less than 0.
				When clan leader sets a new avatar, the value gets reset to the setting amount.
			*/
			$sql = "UPDATE ".db_prefix("module_objprefs")." 
					SET value=value-1 
					WHERE modulename = 'clan_avatars' 
					AND objtype = 'clans' 
					AND setting = 'days'";
			db_query($sql);
			break;
		case "superuser":
			if ($session['user']['superuser'] & SU_EDIT_COMMENTS) {
				addnav("Validations");
				addnav("Validate Clan Avatars","runmodule.php?module=clan_avatars&op=validate");
			}
			break;
		case "biotop":
			$id = $args['clanid'];
			if ($id != 0 && $args['clanrank'] > CLAN_APPLICANT){
				if (get_module_objpref("clans",$id,"validate","clan_avatars")){
					require_once("modules/clan_avatars/func.php");
					$image = clan_avatar_getimage($id);
					addnav("Clan Avatar");
					addnav("$image","!!!addraw!!!",true);
				}
			}
			break;
		case "clanhall":
			if (httpget('op') == "" && $session['user']['clanrank'] >= CLAN_LEADER){
				addnav("Editors");
				addnav("Set Clan Avatar","runmodule.php?module=clan_avatars&op=upload");
			}
			addnav("Clan Options");
			addnav("View Clanavatars","runmodule.php?module=clan_avatars&op=viewothers");
			break;
		case "clan-enter":
			addnav("Clan Options");
			addnav("View Clanavatars","runmodule.php?module=clan_avatars&op=viewothers");
			break;
		case "header-clan":
			if (httpget('op')=='') {
				$det=(int)httpget('detail');
				$id = $session['user']['clanid'];
				if ($det>0) $id=$det;
				if ($id != 0 && $session['user']['clanrank'] > CLAN_APPLICANT){
					if (get_module_objpref("clans",$id,"validate","clan_avatars")){
						require_once("modules/clan_avatars/func.php");
						$image = clan_avatar_getimage($id);
						$sql="SELECT clanname,clanid FROM ".db_prefix('clans')." WHERE clanid=$id;";
						$row=db_fetch_assoc(db_query_cached($sql,"clanavatarnames_".$id));
						$clanname=$row['clanname'];
						rawoutput("<table width='100%'><tr><td valign='top' align='center' width='100%'>");
						output("`^%s`0`n",$clanname);
						rawoutput("</td></tr><tr><td valign='top' align='center' width='100%'>$image</td></tr></table>");
						
					}
				}
			}
			break;
	}
	return $args;
}
function clan_avatars_run(){
	global $session;
	$op = httpget("op");
	require_once("modules/clan_avatars/func.php");
	if ($op == 'validate') {
		page_header("Avatar Validation");
	} else {
		page_header("Clan Hall");
	}
	require("modules/clan_avatars/run/$op.php");
	if ($op != 'validate') {
		addnav("Return");
		addnav("L?Return to Clan Hall", "clan.php");
	}
	page_footer();
}
?>
