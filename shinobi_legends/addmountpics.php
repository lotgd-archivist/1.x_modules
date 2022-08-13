<?php

function addmountpics_getmoduleinfo(){
	$info = array(
		"name"=>"Add Mountpics",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Pictures",
		"download"=>"",
		/*
		"prefs"=>array(
			"Add Mount Images User Preferences,title",
			"user_addmountpics"=>"Display Mount Images?,bool|1",
		),*/
	);
	return $info;
}

function addmountpics_install(){
	module_addhook("stables-nav");
	return true;
}

function addmountpics_uninstall(){
	return true;
}

function addmountpics_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "stables-nav":
			if (httpget('op')!='') {
				if (is_module_active('addimages')) {
					if (get_module_pref("user_addimages","addimages") != 1) break;
					$id=httpget('id');
					if ($id=='' && $session['user']['hashorse']!='') $id=$session['user']['hashorse'];
						elseif ($id=='') break;
					$sql = "SELECT * FROM " . db_prefix("mounts") . " WHERE mountid='$id'";
					$result = db_query_cached($sql, "mountdata-$id", 3600);
					$row=db_fetch_assoc($result);
					$mount=$row['mountname'];
					$file = "modules/addmountpics/".$mount;
					if (file_exists($file.".png")) {
						$file = $file . ".png";
					}elseif (file_exists($file.".png")) {
						$file = $file . ".jpg";
					}elseif (file_exists($file.".gif")) {
						$file = $file . ".gif";
					} else {
						$file = '';
					}
					if ($file!='')
						output_notl("`c<IMG SRC=\"$file\" height='150' length='150'>`c<BR>\n",true);
				}
			}
			break;
	}
	return $args;
}

function addmountpics_run(){
	return true;
}

?>
