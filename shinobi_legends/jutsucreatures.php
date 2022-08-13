<?php

function jutsucreatures_getmoduleinfo(){
	$info = array(
		"name"=>"Jutsu-using forest creatures",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Forest",
		"download"=>"",

	);
	return $info;
}

function jutsucreatures_install(){
	module_addhook("buffbadguy");
	module_addhook("battle-victory");
	return true;
}

function jutsucreatures_uninstall(){
	return true;
}

function jutsucreatures_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "battle-victory":
			strip_buff("jutsucreatures");
			break;
		case "buffbadguy":
			if (e_rand(0,1)) continue;
			if (file_exists('modules/jutsucreatures/'.$args['creaturename'].'.php')) {
				$dks=8; //minimum dks to face a jutsu, might be overwritten by the creature!
				require('modules/jutsucreatures/'.$args['creaturename'].'.php');
				//require('modules/jutsucreatures/Sasuke.php');
				//translation done in the file
				if ($session['user']['dragonkills']<$dks) break;
				output_notl($creature['firstroundmessage']);
				apply_buff('jutsucreatures',$creaturebuff);
			}
			
			break;
	}
	return $args;
}

function jutsucreatures_run(){
	return true;
}

?>
