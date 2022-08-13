<?php


function thanksgiving_getmoduleinfo(){
    $info = array(
        "name"=>"Thanksgiving Special Treat in the village",
        "version"=>"1.0",
        "author"=>"`2Oliver Brendel",
        "category"=>"Holidays|Thanksgiving",
        "download"=>"",

		"requires"=>array(
			"slayerguild"=>"1.0|Slayerguild by Sichae",
			),
        
    );
    return $info;
}

function thanksgiving_chance() {
	global $session;
	$chance = max(5,50-$session['user']['dragonkills'])+50;
	return $chance;
}

function thanksgiving_install(){
	module_addeventhook("village", "require_once(\"modules/thanksgiving.php\"); return thanksgiving_chance();");
    return true;
}

function thanksgiving_uninstall(){
    return true;
}

function thanksgiving_dohook($hookname,$args){
    global $session;
    switch($hookname){
		default:
			break;
		}
    return $args;
}

function thanksgiving_runevent($type) {
    global $session;
	$from = "village.php?";
    $op = httpget('op');
	$points=$session['user']['maxhitpoints']-$session['user']['hitpoints'];
	if ($points>0) {
		thanksgiving_addimage("feast.jpg");	
		output("`vAs you're walking around, you have the tingling sensation something is happening.`n`n`qAnd in fact, you realize you now sit at a well-prepared Thanksgiving table... ready to eat.");
		output("You eat and eat... and are grateful for the food. You recover `\$%s %s`q!`\$`n`n",$points,translate_inline(($points>1?"hitpoints":"hitpoint")));
		$text=translate_inline("Happy Thanksgiving!");
		rawoutput("<center><h2>$text</h2></center><br>");
		$session['user']['hitpoints']=$session['user']['maxhitpoints'];
	} else {
		output("`vYou run into an old wife... older than you ever have seen and more ugly than you could imagine an old woman could be.`n");
		output("It takes all your willpower not to run away from this ghastly sight. Slowly, you continue your steps...`n`n");
	}
}

function thanksgiving_addimage($args) {
	output_notl("`c<img src=\"modules/thanksgiving/".$args."\">`c<br>\n",true);
}

?>
