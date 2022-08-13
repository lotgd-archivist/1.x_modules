<?php

function jouninvote2_getmoduleinfo(){
	$info = array(
		"name"=>"Vote",
		"author"=>"`2Oliver Brendel",
		"version"=>"1.0",
		"category"=>"Diplomacy",
		"download"=>"",
		"settings"=>array(
			"name"=>"Name of the female owner, text|`yP`Rarissa",
			"Yes"=>"Voted Yes,viewonly",
			"No"=>"Voted No,viewonly",			
			),
		"prefs"=>array(
			"hadjouninvote2"=>"had jouninvote2,viewonly",
			),
	);
	return $info;
}

function jouninvote2_install(){
	module_addhook_priority("village-Rice Field Country",50);
	return true;
}
function jouninvote2_uninstall(){
	return true;
}

function jouninvote2_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "village-Rice Field Country":
	$name=get_module_setting('name');
		if ($session['user']['race']=="Sound" && $session['user']['dragonkills']>7) {
			addnav("Voting");
			addnav(array(" ?`\$%s`\$'s Sound Meeting",$name),"runmodule.php?module=jouninvote2");
		}
		break;
	}
	return $args;
}

function jouninvote2_run(){
	global $session;
	$op = httpget("op");
	
	$question="Do you want to submit a mercenary offer (asking for what they would pay) for support to Water Country?";
	
	
	$hadjouninvote2=get_module_pref('hadjouninvote2');

	$name=get_module_setting('name');


	if ($hadjouninvote2!=1) $canbuy=1;
		else $canbuy=0;
	page_header(array("%s`g's Voting",$name));
	output("`b`i`c`l%s`g's Voting`c`i`b`n",$name);
	addnav("Navigation");
	villagenav();
	switch($op) {
		case "vote":
			$raw=(int)httpget('r');
			$vote=($raw?"Yes":"No");
			increment_module_setting($vote,1);
			output("`xYou place your vote, '`\$%s`x' and silently leave the hall as the others do.`n`n`4You see a sign: 'Remember: Your vote is secret! Official results will be announced later on in the MoTD!'",$vote);
			$session['user']['gold']-=$cost;
			set_module_pref('hadjouninvote2',1);
			break;
		default:
			output("`xYou enter a large building that normally serves as a town hall where the meeting takes places. Today is it a place where you can vote. `n`nYou are approached by an administrative employee, \"`RHi, my name is %s`R, I am in charge of the current vote. `n`n`x\"`RIf you wonder what this is about, the current vote question is: '`\$%s`x'\"...`n`n",$name,$question);
			if (!$canbuy) {
				output("\"`RSadly, you already placed your vote *wink*`x\"");
			} else {
				addnav("Vote");
				addnav("Vote `\$Yes","runmodule.php?module=jouninvote2&op=vote&r=1");
				addnav("Vote `2No","runmodule.php?module=jouninvote2&op=vote&r=0");
			}
			break;
			
	}
	page_footer();
}
?>
