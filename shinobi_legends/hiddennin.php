<?php
function hiddennin_getmoduleinfo(){
	$info = array(
		"name"=>"Hidden Nin",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Village Specials",
		"download"=>"",
		"requires"=>array(
			"hokagevillage"=>"1.0|Hokagevillage"
			),
		"settings"=>array(
			"seen"=>"overall time seen this?,int|0",
			),
	);
	return $info;
}

function hiddennin_install(){
	module_addeventhook("forest","return (get_module_pref('hasmap','hokagevillage')||\$session['user']['dragonkills']<50?0:20);");
	return true;
}

function hiddennin_uninstall(){
	return true;
}

function hiddennin_dohook($hookname,$args){
	global $session;
	return $args;
}

function hiddennin_runevent($type) {
	global $session;
	$session['user']['specialinc'] = "module:hiddennin";
	$seen=get_module_setting('seen');
	$op = httpget('op');
	$gemprice=100;
	switch($op) {
	case "buy":
		output("`7The `~dark `)clothed figure `7 nods.`n`n");
		output("\"`&Here you go... take care....`7\"`n`n");
		output("`^You have received a card to %s`^!",get_module_setting("villagename","hokagevillage"));
		$session['user']['gems']-=$gemprice;
		set_module_pref("hasmap",1,"hokagevillage");
		$session['user']['specialinc'] = "";
		output_notl("`n`n");
		break;
	case "myself":
		output("`7The `~dark `)clothed figure `7 nods.`n`n");
		output("\"`&So be it, with your self-esteem I can offer you a map to the %s`& where people of your kind meet. It costs `%%s gems`&, plenty, but maybe worth the price.`7\"`n`n",get_module_setting("villagename","hokagevillage"),$gemprice);
		if ($session['user']['gems']>=$gemprice) {
			addnav("Buy the map","village.php?op=buy");
		} else {
			output("`gSadly, checking your purse, you have not enough `%gems`7!");
		}
		addnav("Leave","forest.php?op=away");
		break;
	case "away":
		output("`7You don't have time for that fella and take your leave. May others kick him around.`n");
		if (e_rand(0,1)) {
			$who=($session['user']['sex']==SEX_MALE?"chicks":"lads");
			output("You feel others might agree with you... even the hot `\$%s`7 around here. Your `%charm`7 increases!",translate_inline($who));
			$session['user']['charm']++;
		}
		output_notl("`n`n");
		$session['user']['specialinc'] = "";
		break;
	case "hokage": case "kazekage": case "mizukage": case "orochimaru":
		output("\"`&I see.`7\"");
		output("`n`n`7He then takes his leave as sudden and mysterious as he approached.");
		$session['user']['specialinc'] = "";
		break;
	default:
		output("`7While you idle around, a `~darkly `)clothed figure `7 approaches you.`n`n");
		output("\"`&Whom do you serve?`7\"");
		output_notl("`n`n");
		addnav("`vHokage","forest.php?op=hokage");
		addnav("`2Orochimaru","forest.php?op=orochimaru");
		addnav("`gKazekage","forest.php?op=kazekage");
		addnav("`!Mizukage","forest.php?op=mizukage");
		addnav(array("`l%s",$session['user']['name']),"forest.php?op=myself");
		addnav("Take your leave","forest.php?op=away");
		break;
	}
}

?>
