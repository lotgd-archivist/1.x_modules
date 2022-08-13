<?php
function dpafterwk_getmoduleinfo(){
	$info = array(
		"name"=>"WK DonationPoints",
		"author"=>"Daisuke",
		"version"=>"1.0",
		"category"=>"General",
		"settings"=>array(
		"WK DonationPoints - Settings,title",
			"dp"=>"DP für einen Perfekten WK,range,1,15,1|1",
		),
	);
	return $info;
}

function dpafterwk_install(){
	module_addhook("battle-victory");
	return true;
}

function dpafterwk_uninstall(){
	return true;
}

function dpafterwk_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "battle-victory":
        $dp = get_module_setting("dp");
        $type = httpget('type');
        if ($args['diddamage'] != 1 && $type == "thrill") {
            output("`n`&Du erhälst %s DP's`n",$dp);
            $session['user']['donation']+=$dp;
        }
		}
	return $args;
}

function dpafterwk_run(){
}
?>