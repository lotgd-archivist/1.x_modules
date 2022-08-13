<?php



function forehead_getmoduleinfo() {
	$info = array(
	    "name"=>"Forehead Protectors",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Bio",
		"download"=>"",
	);
    return $info;
}

function forehead_install() {
	module_addhook_priority("biotop",75);
	return true;
}

function forehead_uninstall() {
	return true;
}


function forehead_dohook($hookname, $args) {
	global $session;
	switch ($hookname) {
		case "biotop":
			if (1) {
				$file="modules/forehead/".$args['race'].".png";
				if (file_exists($file)) rawoutput("<img src='$file' alt='Forehead Protector'>");
				
			}
			break;
		default:
		break;
	}
	return $args;
}

function forehead_run(){
}

?>
