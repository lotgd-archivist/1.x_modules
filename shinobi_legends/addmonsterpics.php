<?php

function addmonsterpics_getmoduleinfo(){
	$info = array(
		"name"=>"Add Monster Pics",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Pictures",
		"download"=>"",
		"prefs"=>array(
			"Enemy Images In The Forest Preferences,title",
			"user_addmonsterpics"=>"Display Forest Enemy Images?,bool|1",
		),
	);
	return $info;
}

function addmonsterpics_install(){
	module_addhook("battle");
	return true;
}

function addmonsterpics_uninstall(){
	return true;
}

function addmonsterpics_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		default:
				if (is_module_active('addimages')) {
					$pics='';
					foreach ($args as $creature) {
						if (get_module_pref("user_addmonsterpics","addmonsterpics") != 1) break;
						if (isset($creature['image']) && $creature['image']!='') {
							$name=$creature['image'];
							$pic=true;
						} else {
							$name="modules/addmonsterpics/".$creature['creaturename']." Lv".$creature['creaturelevel'].".gif";
							$pic=file_exists($name);
							
						}
						if ($pic==true)	$pics.="<IMG SRC=\"$name\" ALT='$name'>";
					}
					output_notl("`c".$pics."`c`n`n",true);
				}
			break;
	}
	return $args;
}

function addmonsterpics_run(){
	return true;
}

?>
