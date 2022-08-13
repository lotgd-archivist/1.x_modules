<?php

function addmasterpics_getmoduleinfo(){
	$info = array(
		"name"=>"Add masterpics",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Pictures",
		"download"=>"",
		/*
		"prefs"=>array(
			"Add Master Images User Preferences,title",
			"user_addmasterpics"=>"Display Mount Images?,bool|1",
		),*/
	);
	return $info;
}

function addmasterpics_install(){
	module_addhook("header-train");
	return true;
}

function addmasterpics_uninstall(){
	return true;
}

function addmasterpics_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "header-train":
			$op=httpget('op');
			if ($op=='challenge' || $op=='question'|| $op=='fight' || $session['user']['level']==15) {
				if (is_module_active('addimages')) {
					if (get_module_pref("user_addimages","addimages") != 1) break;
					switch ($session['user']['level']) {
						case 1:
							$master = 'Umino Iruka.png';
							break;
						case 2:
							$master = 'Ebisu.png';
							break;
						case 3:
							$master = 'Yuuhi Kurenai.png';
							break;
						case 4:
							$master = 'Shizune.png';
							break;
						case 5:
							$master = 'Maito Gai.png';
							break;
						case 6:
							$master = 'Gekkou Hayate.png';
							break;
						case 7:
							$master = 'Nara Shikamaru.png';
							break;
						case 8:
							$master = 'Inuzuka Hana.png';
							break;
						case 9:
							$master = 'Aburame Shibi.png';
							break;
						case 10:
							$master = 'Akimichi Choza.png';
							break;
						case 11:
							$master = 'Yamanaka Inoichi.png';
							break;
						case 12:
							$master = 'Hyuuga Hiashi.png';
							break;
						case 13:
							$master = 'Hatake Kakashi.png';
							break;
						case 14:
							$master = 'Jiraiya.png';
							break;
							
						default:
							$master = 'Hyuuga Hiashi.png';
							break;
														
					}
					output_notl("`c<img title='Fight!' alt='Master' src=\"modules/addmasterpics/$master\">`c<BR>\n",true);
				}
			}
			break;
	}
	return $args;
}

function addmasterpics_run(){
	return true;
}

?>
