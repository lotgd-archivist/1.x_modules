<?php

require_once("lib/villagenav.php");
require_once("lib/http.php");

function akasoul_getmoduleinfo(){
    $info = array(
        "name"=>"Akatsuki Souls",
        "version"=>"1.0",
        "author"=>"`2Oliver Brendel",
        "category"=>"Village Specials",
        "download"=>"",
        "settings"=>array(
            "Souls - Settings,title",
			//"location"=>"Where does this event appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"maxsouls"=>"How many souls are maximum (randomized), range,1,10,1|5",
			),
		"requires"=>array(
			"slayerguild"=>"1.0|Slayerguild by Sichae",
			),
        
    );
    return $info;
}

function akasoul_chance() {
	global $session;
	return (max(1, ($session['user']['dragonkills'])*5));
}

function akasoul_install(){
	module_addeventhook("village", "require_once(\"modules/akasoul.php\"); return akasoul_chance();");
    return true;
}

function akasoul_uninstall(){
    return true;
}

function akasoul_dohook($hookname,$args){
    global $session;
    switch($hookname){
		default:
			break;
		}
    return $args;
}

function akasoul_runevent($type) {
    global $session;
	$from = "village.php?";
    $op = httpget('op');
	$souls=e_rand(1,get_module_setting("maxsouls"));
	$holding = get_module_pref("holding", "slayerguild");
	$maxhold = get_module_setting("maxhold","slayerguild");
	if (get_module_pref('apply','slayerguild') && $holding+$souls<=$maxhold) {
		output("`vAs you're walking around, you have the tingling sensation you know from killing many good guys for your organisation.`n`n");
		output("You suck them in as they are floating around you... you get %s %s!`n`n",$souls,translate_inline(($souls>1?"souls":"soul")));
		increment_module_pref("holding",$souls,"slayerguild");
	} else {
		output("`vYou run into an old woman... older than you ever have seen and more ugly than you could imagine an old woman could be.`n");
		output("It takes all your willpower not to run away from this ghastly sight. Slowly, you continue your steps...`n`n");
	}
}
?>
