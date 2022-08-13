<?php

function tattoo_b_gone_getmoduleinfo(){
	$info = array(
		"name"=>"Tattoo Removal",
		"author"=>"Chris Vorndran",
		"version"=>"1.0",
		"category"=>"Forest",
		"settings"=>array(
			"Tattoo Removal - Settings,title",
			"gem-cost"=>"How many gems does it cost to remove a tattoo?,int|5",
			"charm-cost"=>"How many charm points are lost when removing a tattoo?,int|3",
			"time"=>"How many days does the scar take to heal?,int|10",
			"Setting to 0 disables this function. Player has to wait X days until they can remove another tattoo.,note",
		),
		"prefs"=>array(
			"Tattoo Removal - Preferences,title",
			"days"=>"How many days left until the scar heals?,int|0",
		),
		"requires"=>array(
			"petra"=>"1.31|Shannon Brown,part of the core package.",
		),
	);
	return $info;
}
function tattoo_b_gone_install(){
	module_addhook("potion");
	module_addhook("newday");
	return true;
}
function tattoo_b_gone_uninstall(){
	return true;
}
function tattoo_b_gone_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "potion":
			addnav("Special Services");
			$return=httpget('return');
			if(get_module_pref("tatnumber","petra") > 0 && !get_module_pref("days")){
				addnav("Tattoo Removal","runmodule.php?module=tattoo_b_gone&op=select&return=$return");
				output("`n`n`3If you wish, I can also remove tattoos for you... at a price, of course.");
			}
			break;
		case "newday":
			if (get_module_pref("days") > 0) increment_module_pref("days",-1);
			break;
	}
	return $args;
}
function tattoo_b_gone_run(){
	global $session;
	$op = httpget('op');
	$return= httpget('return');
	$gems = get_module_setting("gem-cost");
	$charm = get_module_setting("charm-cost");
	$time = get_module_setting("time");
	$tattoos = unserialize(get_module_pref("tatname","petra"));
	page_header("Sakura's Tattoo Removal");
	addnav("Navigation");
	addnav("Back to Sakura","healer.php?return=$return");
	addnav("Actions");
	switch ($op){
		case "select":
			require_once("modules/petra.php");
			foreach ($tattoos AS $name => $not_used){
				addnav(array("Remove %s Tattoo",petra_colortat($name)),
					"runmodule.php?module=tattoo_b_gone&op=remove&tat=$name&return=$return");
			}
			output("`#Sakura `3strides towards you, angered by something that isn't at the moment present.`n`n");
			output("\"`&So, you want to mend a mistake you made one night in a drunken stupor?`3\" she smirks.`n`n");
			output("\"`&That may not be the reason why you got it, but you want it removed now and I can help.");
			output("For the small fee of `%%s `&gems, I can remove that nasty tattoo.",$gems);
			output("Decide which one you want to remove and come join me in the back room.`3\"");
			break;
		case "remove":
			if ($session['user']['gems'] < $gems){
				output("`#Sakura `3becomes immediately angry.");
				output("\"`&You got all of your affairs in order, yet you didn't bring the right amount of gems?`3\"");
				output("Just then, she pulls out a large bat, winds up, and knocks you in the stomach, causing you to fly into the front door.");
				output("\"`&Come back when you have `%%s `&gems, ya ingrate.`3\"",$gems);
			}else{
				$tat = httpget('tat');
				require_once("modules/petra.php");
				$coloured = petra_colortat($tat);
				output("`#Sakura `3looks at you, \"`&Let's get this over with.");
				output("You've already wasted a lot of my time.`3\"");
				output("She pulls out a device that looks like a cheese grater and presses it to your skin.");
				output("In a fury of movement, you pass out from the pain.");
				output("You wake up later and find that your %s `3tattoo is missing, yet in its place is a large scar.",$tattoos[$tat]);
				debuglog("Paid $gems to have ".$tattoos[$tat]." removed");
				unset($tattoos[$tat]);
				set_module_pref("tatname",serialize($tattoos),"petra");
				increment_module_pref("tatnumber",-1,"petra");
				output("`n`n`^You lose `5%s `^charm.`n`n",$charm);
				$session['user']['charm']-=$charm;
				output("`#Sakura `3stuffs the device away.");
				if ($time > 0){
					output("\"`&That should take about `\$%s `&days to heal.`3\"",$time);
					set_module_pref("days",$time);
				}
				output("`3She strides across the room and opens the door, ushering you out of the hospital.");
				output("On the way out, you hand her `%%s `3gems and thank her.",$gems);
				$session['user']['gems']-=$gems;
			}
			break;
		}
	page_footer();
}
?>
