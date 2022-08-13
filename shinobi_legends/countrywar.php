<?php
function countrywar_getmoduleinfo(){
	$info = array(
		"name"=>"countrywar",
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

function countrywar_install(){
	module_addeventhook("village","return 5;");
	module_addhook("village-Fire Country");
	module_addhook("village-Rice Field Country");
	return true;
}

function countrywar_uninstall(){
	return true;
}

function countrywar_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "village-Wind Country":
	case "village-Rice Field Country":
		$seen=get_module_setting('seen');
		if ($seen>5000) {
			output("`n`l Some fully armed ANBU are patrolling around the city... kind of tense mood... something is not right here... you see the local weaponry and armoury are kind of heavily frequented as people arm themselves...");
			output_notl("`n");
		} 
		break;
	}
	return $args;
}

function countrywar_runevent($type) {
	global $session;
	$session['user']['specialinc'] = "module:countrywar";
	$seen=get_module_setting('seen');
	$op = httpget('op');
	$here=(($session['user']['location']=="Rice Field Country" || $session['user']['location']=="Fire Country")?1:0);
	switch($op) {
	case "pursue":
		output("`7Quickly you get a hold of one of the commoners and ask what that conversation is about...`n`n");
		
		if ($here) 
			$who="in this Country!";
			else
			$who="between Fire Country and Rice Field Country!";
		output("\"`&You don't know? There are rumours that there will soon be war %s! Already some weapons and armours got sold out as they have to produce for their kages...`7\"`n`n",$who);
		output("`7Without another word he takes his leave.`n");
		output_notl("`n`n");
		$session['user']['specialinc'] = "";
		break;
	case "away":
		output("`7You don't have time for that fellas and take your leave. May others interrogate them.`n");
		output_notl("`n`n");
		$session['user']['specialinc'] = "";
		break;
	default:
		output("`7While you idle around, you hear some local folks conversing and overhear:`n`n");
		if ($here) {
			$one="we are";
			$two=($session['user']['location']=="Fire Country"?"Rice Field Country":"Fire Country");
		} else {
			$one="Fire Country is";
			$two="Rice Field Country";
		}
		output("\"`&We will all be destroyed! There is the rumour that %s being attacked by %s!!!!!`7\"",$one,$two);
		if ($seen>1300 && $here) output("You shiver as they say... \"`&Oh yes... they are almost here... in the `\$hot`& country...not too long and there will be a raging war...`7\" ");
		output("`n`nHowever as you close in, the commoners quickly walk away.");
		output_notl("`n`n");
		increment_module_setting("seen");
		addnav("Pursue one","village.php?op=pursue");
		addnav("Take your leave","village.php?op=away");
		break;
	}
}

?>
