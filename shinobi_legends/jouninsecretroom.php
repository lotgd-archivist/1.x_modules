<?php

function jouninsecretroom_getmoduleinfo(){
	$info = array(
		"name"=>"Jounin Secret Room (Chatroom)",
		"author"=>"`2Oliver Brendel",
		"version"=>"1.0",
		"category"=>"Gypsy",
		"download"=>"",
	);
	return $info;
}

function jouninsecretroom_install(){
	module_addhook_priority("gypsy",50);
	return true;
}
function jouninsecretroom_uninstall(){
	return true;
}

function jouninsecretroom_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "gypsy":
		if ($session['user']['dragonkills']<8) break;
		addnav("Jounin Rooms");
		for ($i=1;$i<3;$i++) {
			addnav(array("Secret Conversation Room #%s",$i),"runmodule.php?module=jouninsecretroom&op=secretroom-$i");
		}
		output("`n`nBehind a secret curtain, you see the `vJounin`5 Rooms you are allowed to enter.");
		break;
	}
	return $args;
}

function jouninsecretroom_run(){
	global $session;
	$op = httpget("op");
	require_once("lib/commentary.php");
	addcommentary();	
	page_header("Jounin Rooms");
	output("`b`i`c`vJounin `gRooms`c`i`b`n");
	$secretroom=substr($op,strpos($op,"-")+1);
	output("`b`i`c`vSecret Conversation Room #%s`c`i`b`n`n",$secretroom);
	/* enter */
	output("`4A lone barmaid strolls around, wiping the floor... this place looks really lonely compared to what it was!`n");
	output("As your eyes meet, she begins to speak: \"`2Sorry, but these rooms are closed due to the town's kage's demand. Obviously many liked to think of these rooms as a way to get knowledge not meant for them...`4\"");
	addnav("Back to the gypsy","gypsy.php");
	page_footer();
	/*end */
	output("`4This is one of the secret conversation rooms Jounins created in order to talk secretly among themselves.");
	output("`nMost things told here are not for public ears, and some seen here are not for public eyes either.");
	output("`nYou gather along fellow `vJounins`4 and even higher shinobis...`n`n");
	villagenav();
	addnav("Return to the gypsy","gypsy.php");
	addnav("Jounin Rooms");
		for ($i=1;$i<3;$i++) {
			addnav(array("Secret Conversation Room #%s",$i),"runmodule.php?module=jouninsecretroom&op=secretroom-$i");
		}	
	if(strstr($op,"secretroom")) {
		commentdisplay("`n`n`@Converse with your comrades.`n","jouninsecretroom-$secretroom","",30,"converses");
	}
	page_footer();
}
?>
