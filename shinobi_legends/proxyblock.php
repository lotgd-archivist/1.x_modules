<?php
function proxyblock_getmoduleinfo(){
	$info = array(
		"name"=>"Proxy Blocker",
		"author"=>"`%Simon Welsh",
		"version"=>"1.0.0",
		"category"=>"Administrative",
		"description"=>"Attempts to stop people logging in from behind a proxy"
	);
	return $info;
}
function proxyblock_install(){
	debug("Installing module proxyblock.");
	module_addhook("check-login");
	return true;
}
function proxyblock_uninstall(){
	debug("Uninstalling module.");
	return true;
}
function proxyblock_dohook($hookname, $args){
	switch($hookname){
		case "check-login":
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['REMOTE_ADDR'] != $_SERVER['HTTP_X_FORWARDED_FOR']) {
				proxyblock_doblock();
			}
			if(version_compare('4.3.11', PHP_VERSION) == 1 || !function_exists('mhash')) {
				break;
			}
			@include_once 'Net/DNSBL.php';
			if(!class_exists('Net_DBLS')) {
				set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . 'proxyblock/classes');
				include_once 'Net/DNSBL.php';
			}
			$checker = new Net_DNSBL();
			if($checker->isListed($_SERVER['REMOTE_ADDR'])) {
				proxyblock_doblock();
			}
			break;
	}
	return $args;
}

function proxyblock_doblock() {
	global $session;
	$session['message'] .= "You appear to be coming from behind a proxy. This has been disallowed by the site administrator.\nIf you are not, or if you want to tell use more, please visit the forums at http://forum.shinobilegends.com and/or email admin@shinobilegends.com providing ALL information about WHICH provider you use, WHERE you are from, AND if there is any 'safety network' in between.";
	tlschema();
	header("Location: index.php");
	exit();
}
