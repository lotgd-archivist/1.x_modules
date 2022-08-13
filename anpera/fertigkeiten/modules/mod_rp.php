<?php

/*
Based on the NPC-charakters by Lonny Luberts
and commentary.php from the core code

Provides a function system_commentary($section, $comment); on everyhit
For simple system comments do (example):

if (is_module_active('mod_rp')) system_commentary(village,"`\$Add whatever you want...");

Installing this module:
- a change in lib/commentary.php is necessary:
	- in addcommantary() find:
		injectcommentary($section, $talkline, $comment, $schema);
		(ok, it's the last line...)
		and add:
		$syscomment = trim(httppost('insertsystemcommentary'));
		if ($syscomment) system_commentary($section, $syscomment);

Last Changes: 05.04.05
*/
function mod_rp_getmoduleinfo(){
	$info = array(
		"name"=>"Moderate Roleplay",
		"version"=>"1.1",
		"author"=>"Michael Jandke",
		"category"=>"General",
		"download"=>"http://dragonprime.net/users/Nathan/mod_rp.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Nathan/",
		"settings"=>array(
			"Moderate Roleplay - Module Settings,title",
			"id"=>"NPC blank_char id,int",
		),
		"prefs"=>array(
			"Moderate Roleplay - User Preferences,title",
			"mod"=>"May this User moderate RP and add describtions to the cities?,bool|0",
		),
	);
	return $info;
}

function mod_rp_install(){
	require_once("modules/mod_rp/mod_rp_install.php");
	$args = func_get_args();
	return call_user_func_array("mod_rp_install_private",$args);
}

function mod_rp_uninstall(){
	require_once("modules/mod_rp/mod_rp_uninstall.php");
	$args = func_get_args();
	return call_user_func_array("mod_rp_uninstall_private",$args);
}

function mod_rp_dohook($hookname,$args){
	require_once("modules/mod_rp/mod_rp_hooks.php");
	$args = func_get_args();
	return call_user_func_array("mod_rp_dohook_private",$args);
}

function mod_rp_run() {
	require_once("modules/mod_rp/mod_rp_main.php");
	$args = func_get_args();
	return call_user_func_array("mod_rp_main_run_private",$args);
}

?>