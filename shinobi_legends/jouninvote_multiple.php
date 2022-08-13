<?php

function jouninvote_multiple_getmoduleinfo(){
	$info = array(
		"name"=>"Vote",
		"author"=>"`2Oliver Brendel",
		"version"=>"1.0",
		"category"=>"Diplomacy",
		"download"=>"",
		"settings"=>array(
			"name"=>"Name of the female owner, text|`yO`grihime",
			"votes"=>"Voted ,viewonly",
			
			),
		"prefs"=>array(
			"hadjouninvote_multiple"=>"had jouninvote_multiple,viewonly",
			),
	);
	return $info;
}

function jouninvote_multiple_install(){
	module_addhook_priority("village-Water Country",50);
	return true;
}
function jouninvote_multiple_uninstall(){
	return true;
}

function jouninvote_multiple_dohook($hookname,$args){
	global $session;
	switch($hookname){
		default:
		$name=get_module_setting('name');
		if (($session['user']['race']=="Mist") && $session['user']['dragonkills']>7) {
			addnav("Voting");
			addnav(array(" ?`\$%s`\$'s Meeting",$name),"runmodule.php?module=jouninvote_multiple");
		}
		break;
	}
	return $args;
}

function jouninvote_multiple_run(){
	global $session;
	$op = httpget("op");
	
	$question="Which shop do you want to steal from Wind Country?";
	
	$options=array(
		"Nothing, I don't want any",
		"Petra's Tattoo Parlor",
		"Bertold's Beasts",
		"Take nothing but shut down the clan halls for a month",
		"Take nothing but shut down the mission hq for a month.",
		"Take nothing but shut down the gardens for a month."
		);
	
	$hadjouninvote_multiple=get_module_pref('hadjouninvote_multiple');

	$name=get_module_setting('name');


	if ($hadjouninvote_multiple!=1) $canbuy=1;
		else $canbuy=0;
	page_header(array("%s`g's Voting",$name));
	output("`b`i`c`l%s`g's Voting`c`i`b`n",$name);
	addnav("Navigation");
	villagenav();
	switch($op) {
		case "vote":
			$raw=(int)httpget('r');
			$vote=get_module_setting('votes');
			if ($vote=='') $votes=array();
				else $votes=unserialize($vote);
			if (isset($votes["vote".$raw])) $votes["vote".$raw]++;
				else $votes["vote".$raw]=1;
			output("`xYou place your vote, '`\$%s`x' and silently leave the hall as the others do.`n`n`4You see a sign: 'Remember: Your vote is secret! Official results will be announced later on in the MoTD!'",$options[$raw]);
			set_module_setting('votes',serialize($votes));
			set_module_pref('hadjouninvote_multiple',1);
			break;
		default:
			output("`xYou enter a large building that normally serves as a town hall where the meeting takes places. Today is it a place where you can vote. `n`nYou are approached by an administrative employee, \"`RHi, my name is %s`R, I am in charge of the current vote. `n`n`x\"`RIf you wonder what this is about, the current vote is about: '`\$%s`x'\"...`n`nThe replacement shop, if you choose to take one, will be a basic loot shop with kunai and so on.`n`n",$name,$question);
			output("`iNote: The offer with most hits will be chosen!`i`");
			if (!$canbuy) {
				output("\"`RSadly, you already placed your vote *wink*`x\"");
			} else {
				addnav("Vote");
				foreach ($options as $key=>$option) {
				addnav(array("`2Vote \"`\$%s`2\"",$option),"runmodule.php?module=jouninvote_multiple&op=vote&r=".$key);
				}
			}
			break;
			
	}
	page_footer();
}
?>
