<?php

require_once("lib/villagenav.php");
require_once("lib/http.php");
require_once("lib/systemmail.php");

function snowball_getmoduleinfo(){
    $info = array(
        "name"=>"Snowball",
        "version"=>"1.0",
        "author"=>"`LShinobiIceSlayer",
        "category"=>"Gardens",
        "download"=>"",
		"settings"=>array(
            "Snowball - Settings,title",
			"snowballlimit"=>"How many Snowballs a user can throw a day,int|3",
		),
		"prefs"=>array(
            "Snowball - User Preferences,title",
			"throwstoday"=>"Has the player visited today?,int|0",
        )
    );
    return $info;
}

function snowball_install(){
	module_addhook("footer-gardens");
	module_addhook("village");
	module_addhook("newday");
    return true;
}

function snowball_uninstall(){
    return true;
}

function snowball_dohook($hookname,$args){
    global $session;
	
	switch($hookname){
	case "footer-gardens":
		addnav("Snowy Banks","runmodule.php?module=snowball");
		break;
	case "newday":
		set_module_pref("throwstoday",0);
		break;
	}
    return $args;
}

function snowball_run() {
    global $session;
	$op=httpget('op');
	$throwstoday=get_module_pref("throwstoday");
	$limit=get_module_setting("snowballlimit");
	$name = stripslashes(rawurldecode(httppost('target')));

	page_header("Snowy Banks");
	output("`&`c`bThe Snowy Banks!`b`c");
	
	if($throwstoday>=$limit){
		output("`7`nYou walk towards the snowy banks, but you are much to cold so you cannot even bear to place your hands in the frozen snow any longer.");
		output("`nYou turn and return to the Gardens.`n`n");
		addnav("G?Return to the Gardens","gardens.php");
	}elseif ($op==""){
		output("`7`nYou walk over to the side of the gardens, where the snow has been pushed to the side forming large banks. ");
		output("As you walk behind them you see a group of shinobi cupping snow in their hands to make small balls of ice. ");
		output("One of them nods towards the gathered snow, then he stands as flings his snowball at great speed towards a nearby friend, before rapidly ducking behind the snow back again. ");
		output("`n`nYou look to the snow in front of you, then back up towards the nearby group of shinobi in the gardens, deciding if you want to start a war or not.");
		addnav("Throw a snowball","runmodule.php?module=snowball&op=throw");
		addnav("G?Return to the Gardens","gardens.php");
	}elseif ($op=="throw"){
		addnav("G?Return to the Gardens","gardens.php");		
		if (httpget('found')==1){
			$sql = "SELECT acctid,name FROM " . db_prefix("accounts"). " WHERE name ='".addslashes($name)."'";
		} else{
			output("`n`7You bend down behind the banks, and gather up some snow in your hands. ");
			output("After you quickly crush it into a ball, you think about who you would like to toss it at. ");
			$sql = "SELECT acctid,name FROM " . db_prefix("accounts"). " WHERE loggedin = 1 AND acctid <> ".$session['user']['acctid'];
		}
		$result = db_query($sql);
		$count = db_num_rows($result);
		$row = db_fetch_assoc(db_query($sql));
		if ($count == 0) {
			output("`n`7Looking up you see no one else around, sadly you throw the snow back at the ground and walk off");
		} elseif ($count > 1){
			rawoutput("<form action='runmodule.php?module=snowball&op=throw&found=1' method='POST'>");
			addnav("", "runmodule.php?module=snowball&op=throw&found=1");
			output("`^Available: ");
			rawoutput("<select name='target'>");
			for ($i = 0; $i < $count; $i++) {
				$row = db_fetch_assoc($result);
				rawoutput("<option value='".rawurlencode(addslashes($row['name']))."'>".full_sanitize($row['name'])."</option>");
			}
			rawoutput("</select>");
			$sname = translate_inline("Throw at");
			rawoutput("<input type='submit' class='button' value='$sname'>");
			rawoutput("</form>");
		} else{
			output("`n`7You quickly leap to your feet to find where %s is hiding, and you even lob your snowball right them.",$row['name']);
			output("You quickly duck behind the snowbank again before sneaking off");
			$acctid=$row['acctid'];
			$from=$session['user']['name'];
			$subj="Snowball attack!";
			$msg="$from has hit you with a snowball, don't ya think it is time to hit back?";
			systemmail($acctid,$subj,$msg);
		}
	}
	page_footer();
}
	
?>
