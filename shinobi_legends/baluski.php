<?php

function baluski_getmoduleinfo(){
$info = array(
	"name"=>"Baluski",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel",
	"category"=>"Forest",
	"download"=>"",
	"prefs"=>array(
		"Baluski Preferences,title",
		"used"=>"How often used?,int|0",
		),
	);
	return $info;
}

function baluski_install(){
	module_addhook("forest");
	return true;
}

function baluski_uninstall(){
	return true;
}

function baluski_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "forest":
			if ($session['user']['login']!="Baluski" && $session['user']['acctid']!=7) break;
			$used=(int)get_module_pref("used");
			if ($used>9) break;
			addnav("Baluski's Thingies");
			addnav(array("Neji, give me the merchant (%s left)!",10-$used),"runmodule.php?module=baluski&op=merchant");
			break;
		break;
	}
	return $args;
}

function baluski_run(){
	global $session;
	$op=httpget('op');
	switch ($op) {
		case "merchant":
			increment_module_pref("used",1);
			$session['user']['specialinc'] = "module:ninjamerchant";
			redirect("forest.php");
		break;
	}
	
}

?>