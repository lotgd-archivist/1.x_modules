<?php
/*
 * Copyright (C) 2006 the_Cr0w (aka Vancha March)
 * Email: c.herold@inode.at
 * Homepage: http://www.the-crows-hp.at.tf/
 *
 * Plattform: LOTGD - 1.1.0 DragonPrime Edition
 * sutitles.php
 */
// translator ready
// addnews ready
// mail ready

function sutitles_getmoduleinfo(){
	$info = array(
		"name"=>"Superusertitel",
		"version"=>"0.2",
		"author"=>"`@Vancha March",
		"category"=>"Administrative",
		"download"=>"",
		"prefs"=>array(
			"Admintitel Preferences,title",
			"active"=>"Soll dieser User einen speziellen Superusertitel haben?,enum,yes,Ja,no,Nein",
			"title"=>"Superusertitel,enum,Webmaster,Webmaster,Admin,Admin,Moderator,Moderator,Helfer,Helfer,Übersetzer,Übersetzer,Entwickler,Entwickler,User,User",
		),
	);
	return $info;
}

function sutitles_install(){
	module_addhook("charstats");
	module_addhook("biostat");
	module_addhook("newday");
	return true;
}

function sutitles_uninstall(){
	return true;
}

function sutitles_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "charstats":
		if(get_module_pref("active") == "no")
		break;
		addcharstat("Personal Info");
		addcharstat("Superuserrang", get_module_pref("title"));
		break;
		
	case "biostat":
		if(get_module_pref("active") == "no")
		break;
		$sutitle = get_module_pref("title");
		output("`^Superuserrang: `@%s`n", $sutitle);
		break;
		
	case "newday":
		if(get_module_pref("active") == "no")
		break;
		$sutitle = get_module_pref("title");
		output("`@`n`nSuperusertitel wurde auf `^%s `@gesetzt.", $sutitle);
		$session['user']['title'] = $sutitle;
		$session['user']['ctitle'] = $sutitle;
		break;
	}
	return $args;
}

function sutitles_run(){
}	
?>