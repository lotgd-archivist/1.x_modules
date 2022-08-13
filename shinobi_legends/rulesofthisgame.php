<?php

function rulesofthisgame_getmoduleinfo(){
$info = array(
	"name"=>"Rules of this Server",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel",
//	"override_forced_nav"=>true,
	"allowanonymous"=>true,
	"category"=>"Administrative",
	"download"=>"",
	);
	return $info;
}

function rulesofthisgame_install(){
	module_addhook("index");
	module_addhook("village");
    module_addhook("create-form");
	return true;
}

function rulesofthisgame_uninstall(){
	return true;
}

function rulesofthisgame_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "create-form":
			output("`\$If you choose an insecure password like your first name or something that is in a dictionary, you `bRISK LOSING YOUR ACCOUNT`b!`n`n");
			break;
		case "index":
			addnav("Info");
			addnav("`\$Rules`! of`4 this`q Game","runmodule.php?module=rulesofthisgame");
			break;
		case "village":
			addnav("Info");
			addnav("`\$Rules`! of`4 this`q Game","runmodule.php?module=rulesofthisgame");
			break;
	}
	return $args;
}

function rulesofthisgame_run() {
	global $session;
	$type=httpget('type');
	if ($type!=1) {
		$func="page_header";
		$funcend="page_footer";
	} else {
		$func="popup_header";
		$funcend="popup_footer";
	}
	$func("Rules of this Game");
	rulesofthisgame_rules();
	if ($session['user']['loggedin'])
		villagenav();
		else
		addnav("Back to the index page","index.php");
	$funcend();
}

function rulesofthisgame_rules() {
	output("`b`i`c`\$<h2>Some rules for the roleplay on this Server</h2>`c`i`b`n`n`n",true);
	output("`21. Not be tolerated:`n`n");
	output("`c`b`4pornographic content`nsexual discrimination`nrude behaviour`nrude language`nracism`2`b`c`n`n");
	output("Neither on village squares nor in houses nor anywhere else. We use moderators to keep an eye on it, as well as we will follow anonymous hints. From deletion of posts or mails up to permanent bans... any punishment can be taken. Remember: This is a children-friendly game.`n`n");
	output("2. `\$Be nice to each other.`2 Harassment, stalking and other such things will not be tolerated. Your freedom ends when another players freedom is harmed, basically. There is no catalog on what exactly is covered, but a fine invention called 'commonsense' should give you a few hints on what is allowed and what not.`n`n");
	output("3. You may use multiaccounts as this is currently allowed.`n`n");
	output("4. Yet: You are not allowed to make a multiaccount just to get yourself more donation points, gold, buffs (marriage), etc. To put it short, you should not do so to benefit one character through the other. If you do, punishment includes cancellation of credited donation points and a ban from the server.`n`n");
	output("5. To stress it: Do not transfer donation points from one multiacc to the other.`n`n");
	output("6. `b`4No advertising for LotGD-games`2 (any themes like Bleach, Naruto or whatever) nor something commercial, the only exceptions are linked partner sites on the main page. Violation causes permanent bans. To clarify: Any submission of game links or 'how to get there' are meant. Do so outside the game if you need to tell your friends about a game, but for multiple reasons this is forbidden ingame.`b`n`n");
	output("7. `4Report obvious bugs.`2 If you exploit bugs you will be punished according to the degree of the exploit.`n`n");
	output("8. `4Try to use normal language.`2 \"Ey bud wazzup\" is not really something a Naruto character would say, would he.`n`n");
	output("9.0. The policy here is: Email Account Holder == Character Owner. `\$There are no 'changes back'.`2`n");
	output("9.1. You are not allowed to give your password out. We won't check that, but if you do, all actions done with that account are *YOUR* actions, regardless of who sits in front of the monitor.`n");
	output("9.2. If somebody has the password and changes the email, he/she is the account holder. Full stop. Request for a 'rollback' will not be accepted.`n");
	output("9.3. If you pick an unsafe password like your first name or similar things, you, and `\$only you`2 are responsible for it.`n`n");
	output("10. We cannot distinguish real persons over the net. But we can distinguish computers. So, if you play with friends on *one* PC, and *one* of them gets banned, *you* will get banned too. Make different user accounts on that machine or use different computers.`n`n");
	output("`n`nThis list is likely to be lengthened so be sure to take a look at it sometimes.`n");
	output("`vIf there are things not mentioned here, this does not mean it is allowed explicitly. If something feels wrong, most likely it is (moral compass of that person still intact). So don't feel too invulnerable if you found a loophole.`2`n`n");
	output("Thank you for your time and for following these rules as all should have fun on this server.`n`n`vNeji");
	output("`n`n");
}
?>
