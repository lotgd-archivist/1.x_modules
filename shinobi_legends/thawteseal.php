<?php

function thawteseal_getmoduleinfo(){
$info = array(
	"name"=>"Thawte Seal on homepage",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel",
	"override_forced_nav"=>true,
	"category"=>"Administrative",

	);
	return $info;
}

function thawteseal_install(){
	module_addhook_priority("index",100);
	return true;
}

function thawteseal_uninstall(){
	output_notl("`n`c`b`QNotifier Module - Uninstalled`0`b`c");
	return true;
}

function thawteseal_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "index":
			if ($_SERVER['SERVER_PORT']==443) {
				rawoutput("<center><script src=\"https://siteseal.thawte.com/cgi/server/thawte_seal_generator.exe\"></script></center><br>");
			}
			break;
	}
	return $args;
}

function thawteseal_run(){
}

?>