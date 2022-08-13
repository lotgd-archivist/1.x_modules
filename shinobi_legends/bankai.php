<?php

function bankai_getmoduleinfo(){
	$info = array(
		"name"=>"Bankai",
		"author"=>"`2Oliver Brendel",
		"version"=>"1.0",
		"category"=>"Forest Specials",
		"settings"=>array(
			"Bankai - Settings,title",
		),
		"prefs"=>array(
			"Bankai - Prefs,title",
				"hadevent"=>"Has the user had this event,bool|0",
		),
	);
	return $info;
}
function bankai_install(){
	module_addeventhook("forest","return 
(get_module_pref('hadevent','bankai')==1?0:10);");
	return true;
}
function bankai_uninstall(){
	return true;
}
function bankai_runevent($type){
	global $session;
	$op = httpget('op');
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:bankai";
	output_notl("`n");
	switch ($op){
		case "":
			output("`)Suddenly... as you wander through the forest on the lookout for enemies to defeat... you a tingling sensation runs down your spine ... You stop to move and feel a powerful presence... more powerful than you ever have dreamt of.`n`n");
			output("What do you do...");
			addnav("Turn around...",$from."op=turn");
			addnav("Run away at maximum speed",$from."op=run");
			addnav("Stand still",$from."op=stand");
			break;
		case "stand": 
			output("`)You stand ... for quite some time... nothing happens... and then the world goes black...`n`n");
			if ($session['user']['turns']>0) {
				output("You `\$lose`) a forest fight ... you wake up and wonder what happened...`n`n");
				$session['user']['turns']--;
			}
			output("You continue your way...`n`n");			
			$session['user']['specialinc']='';
			break;
		case "run":
			output("`)You charge away... and nobody seems to follow you.`n`n");
			$session['user']['specialinc']='';
			break;
		case "turn":
			output("`qYou turn around ... and somehow unheard a human figure has made its approach and stands now a few steps away from you... you do not recognize the unusual outfit. Black uniform with white undergarments... old Japanese style.`n`n Yet you do realize he is carrying `\$a huge katana`q that is pointed directly towards you....`n`n");
			output_notl("`l");
			rawoutput("<span style='font-family:Arial,sans-serif; font-size:42px;'>");
			output_notl("\"");
			rawoutput("</span>");
			output_notl("`g");
			rawoutput("<span style='font-family:Arial,sans-serif; font-size:42px;'>");
			output_notl("%s!",$session['user']['login']);
			rawoutput("</span>");
			output_notl("`l");
			rawoutput("<span style='font-family:Arial,sans-serif; font-size:42px;'>");
			output_notl("\"");
			rawoutput("</span>");
			output_notl("`n");
			output_notl("`l");
			rawoutput("<span style='font-family:Arial,sans-serif; font-size:42px;'>");
			output_notl("\"");
			rawoutput("</span>");
			output_notl("`\$");
			rawoutput("<span style='font-family:Arial,sans-serif; font-size:52px;font-weight:bold;'>Ban</span>");
			output_notl("`l");
			rawoutput("<span style='font-family:Arial,sans-serif; font-size:52px;font-weight:bold;'>Kai!</span>");
			output_notl("`l");
			rawoutput("<span style='font-family:Arial,sans-serif; font-size:42px;'>");
			output_notl("\"");
			rawoutput("</span>");
			output_notl("`q");
			output("`n`n`tBlazing`q energy surrounds you as his ... chakra?... explodes and engulfs you...");
			output("`n`n`gSomehow, your soul drifts away... or doesn't it? Your head hurts... and you realize you are lying on an empty clearing... with a large bump on your head. Could this be a dream? It must be... you seem to have stumbled and this was the result... weird dream...`n`n");
			set_module_pref("hadevent",1);
			addnav("Leave",$from."op=leave");
			break;
		case "leave":
			output("`@You continue on your journey...`n`n");
			$session['user']['specialinc']='';
			break;

	}

}
?>
