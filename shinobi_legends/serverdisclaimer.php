<?php


function serverdisclaimer_getmoduleinfo(){
$info = array(
	"name"=>"Serverdisclaimer",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel",
//	"override_forced_nav"=>true,
	"allowanonymous"=>true,
	"category"=>"Administrative",
	"download"=>"",
	);
	return $info;
}

function serverdisclaimer_install(){
	module_addhook("index");
	module_addhook("village");
	return true;
}

function serverdisclaimer_uninstall(){
	return true;
}

function serverdisclaimer_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "index":
			addnav("Info");
			addnav("`vDisclaimer`! of`4 this`q Game","runmodule.php?module=serverdisclaimer");
			break;
		case "village":
			addnav("Info");
			addnav("`vDisclaimer`! of`4 this`q Game","runmodule.php?module=serverdisclaimer");
	}
	return $args;
}

function serverdisclaimer_run() {
	global $session;
	$type=httpget('type');
	if ($type!=1) {
		$func="page_header";
		$funcend="page_footer";
	} else {
		$func="popup_header";
		$funcend="popup_footer";
	}
	$func("Disclaimer");
	serverdisclaimer_dis();
	if ($session['user']['loggedin'])
		villagenav();
		else
		addnav("Back to the index page","index.php");
	$funcend();
}

function serverdisclaimer_dis() {
	output("`b`i`c`\$<h2>Shinobi Legends Disclaimer</h2>`c`i`b`n`n`n",true);
	output("`qThis server is basically a fan server where people from all over the world join forces to play together and enjoy the Naruto world. The game engine is the 'Legend of the Green Dragon' core, which has been extended with modules to fit to the setting, also names have been replaced. The source code of this core engine is freely available and forbids commercial use explicitly.`n`n");
	output("All trademarks, rights, etc are reserved by the respective parties, i.e. all Naruto-based names, items, creatures etc hold their original copyright.`n`n");
	output("`xAll Naruto related materials are copyrighted and belong to Masashi Kishimoto, VIZ, Shounen Jump and TV Tokyo.`q`n`n");
	output("Being a non-commercial site that keeps itself up with donations the server offers a unique roleplaying experience for fans of the series as well as for other players as well.`n`n");
	output("Therefore, the donations will be used to maintain the server by paying the costs (domain name, monthly fees, setup fees, etc). The following owner of the server does only manage the funds and is not receiving any payment for his work.");
	output("`n`nThank your for acknowledging this.");
	output("`n`n`vNeji");
	output_notl("`n`n");
	output("`c`4Technical Admin & Server Holder Data:`n`n");
	output("`7Oliver Brendel`n");
	output("Paul-Strian-Str. 8`n");
	output("D-91301 Forchheim`n");
	output("`nGermany");
	output("`n`n(contact via petition system 'Petition for Help', address `vNeji`7)`c");
	return;
}

?>
