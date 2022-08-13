<?php
/*
 * Copyright (C) 2006 the_Cr0w (aka Vancha March)
 * Email: c.herold@inode.at
 * Homepage: http://www.logd-diablo.at
 *
 * Plattform: LOTGD - 1.1.0 DragonPrime Edition
 * music.php
 */
// translator ready
// addnews ready
// mail ready

function sutitles_getmoduleinfo(){
	$info = array(
		"name"=>"Musik",
		"version"=>"1.0",
		"author"=>"`@Vancha March",
		"category"=>"General",
		"download"=>"http://www.logd-diablo.at",
		"settings"=>array(
			"Musik,title",
			"url"=>"URL zum Musicfile|http://",
			"loop"=>"Soll die Musik wiederholt werden?,bool|1",
		),
	);
	return $info;
}

function sutitles_install(){
	module_addhook("index");
	return true;
}

function sutitles_uninstall(){
	return true;
}

function sutitles_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "index":
		$url = get_module_setting("url");
		$loop = get_module_setting("loop");
	    $op = httpget("op");
		switch($op){
		case "":
		rawoutput("<embed src=$url loop=");
		if($loop == 1)
		{
		 rawoutput("true");
		}else{
		 rawoutput("false");
		}
		rawoutput(" hidden=true></embed>");
		break;
	}
	return $args;
}

function sutitles_run(){
}	
?>