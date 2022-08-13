<?php
require_once("lib/buffs.php");
require_once("lib/commentary.php");

function weddingparty_getmoduleinfo(){
	$info = array(
		"name"=>"Wedding Party, based on gardenparty by Eric Stevens",
		"author"=>"Eric Stevens, modified by Oliver Brendel",
		"category"=>"Gardens",
		"version"=>"1.0",
		"download"=>"core_module",
		"settings"=>array(
			"Garden Party Settings,title",
			"partytype"=>"Type of party?|wedding party for Niamh & Neji",
			"partycouple"=>"Name of the pair?|Niamh & Neji",
			"partystart"=>"When does the part start,dayrange,+360 days,+1 day|2016-06-03 00:00:00",
			"partyduration"=>"How long does the party last,datelength|72 hours",
			"cedrikclothes"=>"What is Cedrik wearing?|a smoking",
			"buff"=>"Text to output as the player fights in the forest?|In the distance you hear the sounds of \"Congratulations to you two!\" being sung.",
			"cakename"=>"Name of the cake?|Wedding Cake",
			"cakecost"=>"Cost per level for cake,int|15",
			"cakeemote"=>"What will display in the conversation when you order cake?|pigs out and takes a huge bite of the Wedding Cake.",
			"maxcake"=>"How many slices of cake can a player buy in one day?,int|4",
			"drinkname"=>"Name of the drink?|Prune Juice",
			"drinkcost"=>"Cost per level for drink,int|40",
			"drinkemote"=>"What will display in the conversation when you order drink?|takes a big swig of Prune Juice.",
			"maxdrink"=>"How many party drinks can a player buy in one day?,int|3",
		),
		"prefs"=>array(
			"Garden Party User Preferences,title",
			"caketoday"=>"How many pieces of cake have they eaten today?,int|0",
			"drinkstoday"=>"How many drinks have they had today in the party?,int|0"
		)
	);
	return $info;
}

function weddingparty_install(){
	module_addhook("village");
	module_addhook("gardens");
	module_addhook("newday");
	return true;
}

function weddingparty_uninstall(){
	debug("Uninstalling module.");
	return true;
}

function weddingparty_dohook($hookname, $args) {
	global $session;

	switch($hookname){
	case "village":
		$start = strtotime(get_module_setting("partystart"));
		$end = strtotime(get_module_setting("partyduration"), $start);
		$now = time();
		if ($start <= $now && $end >= $now) {
			output_notl("<div style='font-size: 1.8em'>",true);
			output("`\$There's a party going on in the gardens!`\$ It's a %s!`0`n",
					get_module_setting("partytype"));
			output_notl("</div>",true);
		}
		break;
	case "newday":
		set_module_pref("caketoday",0);
		set_module_pref("drinkstoday",0);
		break;
	case "gardens":
		// See if the party is currently running.
		$start = strtotime(get_module_setting("partystart"));
		$end = strtotime(get_module_setting("partyduration"), $start);
		$now = time();
		/*debug($start);
		debug($now);
		debug($end);*/
		if ($start <= $now && $end >= $now) {
			output("`xThere's a party going on!  It's a %s`x!",
					get_module_setting("partytype"));
			output("`x%s`x is here, wearing (of all things) %s`x, serving food and drinks.`n`n",getsetting('barkeep','`tCedrik'), get_module_setting("cedrikclothes"));
			output("`xYou also see the happy couple `\$%s`x amongst a lot of their friends, receiving a lot of positive feeling for making that final step.`n`n",$partycouple);
			addnav("Wedding Treats!");
			$caketoday = get_module_pref("caketoday");
			$drinkstoday = get_module_pref("drinkstoday");
			$cakecost = get_module_setting("cakecost")*$session['user']['level'];
			$drinkcost = get_module_setting("drinkcost")*$session['user']['level'];
			if ($caketoday < get_module_setting("maxcake") &&
					$session['user']['gold'] >= $cakecost) {
				$cake = get_module_setting("cakename");
				addnav(array("%s (`^%s gold`0)", $cake, $cakecost),
						"runmodule.php?module=weddingparty&buy=cake");
			}
			if ($drinkstoday < get_module_setting("maxdrink") &&
					$session['user']['gold']>=$drinkcost) {
				$drink = get_module_setting("drinkname");
				addnav(array("%s (`^%s gold`0)", $drink, $drinkcost),
						"runmodule.php?module=weddingparty&buy=drink");
			}
		}
		break;
	}
	return $args;
}

function weddingparty_run(){
	global $session;

	// See if the party is currently running.
	$start = strtotime(get_module_setting("partystart"));
	$end = strtotime(get_module_setting("partyduration"), $start);
	$now = time();
	if ($now < $start || $now > $end) {
		redirect("gardens.php");
	}

	$missed = "a bogus item";
	$comment = "mutters something that you cannot make out.";

	$partycouple = get_module_setting('partycouple');
	switch(httpget("buy")){
	case "cake":
		$caketoday = get_module_pref("caketoday");
		$cost = get_module_setting("cakecost")*$session['user']['level'];
		if ($session['user']['gold'] >= $cost){
			$session['user']['gold'] -= $cost;
			$comment = get_module_setting("cakeemote");
			set_module_pref("caketoday",$caketoday+1);
		}else{
			//they probably timed out, and got PK'd.
			//Let's handle it gracefully.
			$cantafford = true;
			$missed = get_module_setting("cakename");
		}
		break;
	case "drink":
		$cost = get_module_setting("drinkcost")*$session['user']['level'];
		$drinkstoday = get_module_pref("drinkstoday");
		if ($session['user']['gold'] >= $cost) {
			$session['user']['gold'] -= $cost;
			$comment = get_module_setting("drinkemote");
			set_module_pref("drinkstoday",$drinkstoday+1);
		}else{
			//they probably timed out, and got PK'd.
			//Let's handle it gracefully.
			$cantafford = true;
			$missed = get_module_setting("drinkname");
		}
		break;
	}

	if ($cantafford){
		page_header("%s in %s", sanitize(getsetting('barkeep','`tCedrik')), get_module_setting("cedrikclothes"));
		output("You wander over to where %s`0 is standing in the gardens, and ask to buy %s, but he tells you that you don't have enough gold to buy it.",getsetting('barkeep','`tCedrik'), $missed);
		output("You think it's a little odd that you're being charged for food and drinks at %s, but ignore this, eager to get back to the revelry.", get_module_setting("partytype"));
		addnav("Back to the party","gardens.php");
		page_footer();
	}else{
		injectcommentary("gardens", "whispers", ":".addslashes($comment));
		$buff = array(
			"name"=>"`xW`yedding `xF`yever",
			"minioncount"=>1,
			"defmod"=>1.1,
			"maxbadguydamage"=>0,
			"minbadguydamage"=>0,
			"effectnodmgmsg"=>get_module_setting("buff"),
			"rounds"=>-1,
			"schema"=>"module-weddingparty",
		);
		apply_buff('weddingparty', $buff);
		redirect("gardens.php");
	}
}

?>
