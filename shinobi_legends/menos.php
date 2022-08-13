<?php
function menos_getmoduleinfo(){
	$info = array(
		"name"=>"Menos",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Village Specials",
		"download"=>"",
		"settings"=>array(
			"seen"=>"overall time seen this?,int|0",
			),
	);
	return $info;
}

function menos_install(){
	module_addhook("newday");
	module_addeventhook("village","return 25;");
	module_addhook("village-Wind Country");
	return true;
}

function menos_uninstall(){
	return true;
}

function menos_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "village-Wind Country":
		$seen=get_module_setting('seen');
		if ($seen>2000) {
			output("`n The `~black `2Fog is gone... inquiring what happened, you are told that some... people with dark clothes showed up and the `5'thing'`2 retreated where it came from... you wonder... if you can meet those guys...");
		} else if ($seen>300) {
			output("`n`2A `~black `2Fog lies around the city... and drains your life force... A large hand is seen above in the sky...");
			$session['user']['hitpoints']--;
			if ($seen>700) {
				output(".. the second hand,  vast and huge, is also visible now.");
				if ($seen>1200) {
					output (".. the gigantic head also is visible... it looks around... you have an uneasy feeling.");
					$session['user']['hitpoints']--;
				}
			}
			output_notl("`n");
		} 
			
		break;
	}
	return $args;
}

function menos_runevent($type) {
	global $session;
	$session['user']['specialinc'] = "module:menos";
	$seen=get_module_setting('seen');
	$op = httpget('op');
	switch($op) {
	case "praise":
		output("`7The `~dark `)clothed figure `7 nods.`n`n");
		output("\"`&So be it, you will then witness the destruction of the world!!!`7\"`n`n");
		output("`7Without another word he takes his leave.`n");
		if (e_rand(0,1)) {
			output("You feel that something watches `)you`7 from above. Your `%charm`7 decreases as you frown!");
			$session['user']['charm']--;
		}
		output_notl("`n`n");
		$session['user']['specialinc'] = "";
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
	default:
		output("`7While you idle around, a `~dark `)clothed figure `7 approaches you.`n`n");
		output("\"`&We will all be destroyed! Praise `1`iMenos `4Grande`& `~Renga`&`i!!!!`7\"");
		output_notl("`n`n");
		if ($seen>300) output("You shiver as he says... \"`&Oh yes... he is almost here... in the `\$hot`& country...not too long and he is here completely...`7\" ");
		increment_module_setting("seen");
		addnav(array("Praise `1`iMenos `4Grande`& `~Renga`0"),"village.php?op=praise");
		addnav("Take your leave","village.php?op=away");
		break;
	}
}

?>
