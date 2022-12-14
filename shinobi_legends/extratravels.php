<?php

function extratravels_getmoduleinfo(){
	$info = array(
		"name"=>"Extra Travels",
		"author"=>"`2Oliver Brendel, based on Extra Forest Fights by Eric Stevens",
		"version"=>"1.0",
		"category"=>"Lodge",
		"download"=>"",
		"settings"=>array(
			"Extra Forest Fights Module Settings,title",
			"points"=>"How many points per purchase?,int|100",
			"length"=>"How many game days do purchase last for?,int|30",
			"maxactive"=>"How many times may this be purchased consequetively?,int|5",
			"extend"=>"Allow players to extend existing purchases?,bool,0",
		),
		"requires"=>array(
			"cities"=>"1.0|Multiple Cities",
			),
		"prefs"=>array(
			"Extra Travels User Preferences,title",
			"currentbuys"=>"Current purchases for user,viewonly",
		),
	);
	return $info;
}

function extratravels_install(){
	module_addhook("lodge");
	module_addhook("pointsdesc");
	module_addhook("validateprefs");
	module_addhook("newday");
	return true;
}
function extratravels_uninstall(){
	return true;
}

function extratravels_convert()
{
	global $session;
	$config = unserialize($session['user']['donationconfig']);
	if (!is_array($config)) $config = array();
	if (array_key_exists('extratravels', $config) && $config['extratravels']) {
		set_module_pref("currentbuys", serialize($config['extratravels']));
		unset($config['extratravels']);
		$session['user']['donationconfig'] = serialize($config);
	}
}

function extratravels_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "newday":
		extratravels_convert();
		$current = get_module_pref("currentbuys");
		$current = @unserialize($current);
		if (!is_array($current)) $current = array();

		reset($current);
		$donateff=0;
		while(list($key, $val) = each($current)) {
			$current[$key]['left']--;
			increment_module_pref("traveltoday",-1,"cities");
			$donateff++;
			output("`n`vYou gain an extra travel from points spent on `^%s`v.", $val['bought']);
			if ($val['left'] > 1) {
				$remain = $val['left']-1;
				output(" You have `^%s`v day%s left on this purchase.`n", $remain, ($remain==1?"":"s"));
			} else {
				unset($current[$key]);
				output(" This purchase has expired.`n");
			}
		}
		

		$current = serialize($current);
		set_module_pref("currentbuys", $current);
		break;
	case "validateprefs":
		if ($args['currentbuys']) {
			$args['currentbuys'] = serialize($args['currentbuys']);
		}
		break;
	case "pointsdesc":
		$args['count']++;
		$format = $args['format'];
		$str = translate("One extra travel per day for %s days costs %s points.  You may have up to %s of these active at a time. The extra travels start the game day after you purchase them.");
		$str = sprintf($str, get_module_setting("length"),
				get_module_setting("points"),
				get_module_setting("maxactive"));
		output($format, $str, true);
		break;
	case "lodge":
		extratravels_convert();
		$cost = get_module_setting("points");
		addnav(array("Travels (%s points)", $cost), "runmodule.php?module=extratravels&op=buy");
		break;
	}
	return $args;
}

function extratravels_run(){
	global $session;

	extratravels_convert();

	$cost = get_module_setting("points");
	$max = get_module_setting("maxactive");
	$length = get_module_setting("length");
	$op = httpget("op");

	page_header("Hunter's Lodge");
	addnav("Lodge");
	addnav("L?Return to the Lodge","lodge.php");
	if ($op=="buy"){
		$current = get_module_pref("currentbuys");
		$current = unserialize($current);
		if (!is_array($current)) $current = array();
		output("`7J. C. Petersen looks at you carefully, \"`&One extra travel each game day will cost you %s points and will provide you with one extra travel each game day that you log in for the next %s game days.  You may only purchase up to %s additional travels at a time.`7\"`n`n", $cost, $length, $max);
		if (count($current) > 0) {
			output("`7He flips through a small book for a moment. \"`&Ah yes, here we go.  You have the following purchases already made.`7\"`n`n");
			reset($current);
			while(list($key, $val)=each($current)) {
				output("`7%s - You have `^%s %s `7of extra travels left due to points spent on `^%s`7`n", $key+1, $val['left'], translate_inline($val['left']==1?"day":"days"), $val['bought']);
				if (get_module_setting("extend")) {
					$pointsavailable=$session['user']['donation'] -
						$session['user']['donationspent'];
					if ($pointsavailable > $cost) {
						addnav("Extend Time");
						addnav(array("Extend Purchase #%s", $key+1),
								"runmodule.php?module=extratravels&op=extend&num=$key");
					}
				}
			}
			output("`n");
		}
		$pointsavailable=$session['user']['donation'] -
			$session['user']['donationspent'];
		if ($pointsavailable < $cost) {
			output("`7He then smiles regretfully, \"`&I'm sorry, but purchasing travels costs %s points, which you do not seem to have.`7\"`n", $cost);
		} elseif(count($current) >= $max) {
			output("`7He then smiles regretfully, \"`&I'm sorry, but you may only have %s additional travels active at a time.`7\"`n", $max);
			if(get_module_setting("extend")) {
				output("`7He pauses a second, \"`&However, you can buy more time on your existing travels if you wish.`7\"");
			}
		} else {
			output("`7\"`&Are you sure you wish to spend %s points on additional travels?`7\" he asks.`n", $cost);
			if (get_module_setting("extend")) {
				output("`7He pauses a second, \"`&Additionally, you can buy more time on your existing travels if you wish.`7\"`n");
			}
			addnav("Buy Extra Travels");
			addnav("Yes", "runmodule.php?module=extratravels&op=confirm");
			addnav("No", "lodge.php");
		}
	} elseif ($op=="confirm") {
		$current = get_module_pref("currentbuys");
		$current = unserialize($current);
		if (!is_array($current)) $current = array();
		$session['user']['donationspent']  += $cost;
		$arr = array("bought"=>date("F dS"), "left"=>$length);
		array_push($current, $arr);
		$current = serialize($current);
		set_module_pref("currentbuys", $current);
		output("`7J. C. Petersen nods and hopes you enjoy your extra travels.");
	} elseif ($op=="extend" && get_module_setting("extend")) {
		$index = (int)httpget("num");
		$current = get_module_pref("currentbuys");
		$current = unserialize($current);
		if (!is_array($current)) $current = array();
		if (isset($current[$index])) {
			$cur = $current[$index]['left'];
			$session['user']['donationspent']  += $cost;
			output("`7J. C. Petersen nods, \"`&I have extended your time from %s %s to %s days for the purchase made on `^%s`7.  I hope you enjoy your extra travels.`7\"", $cur, translate_inline($cur==1?"day":"days"), $cur+$length, $current[$index]['bought']);
			$current[$index]['left'] += $length;
			$current = serialize($current);
			set_module_pref("currentbuys", $current);
		} else {
			output("`7J. C. Petersen looks puzzled. \"`&I cannot seem to figure out which set of travels you wish to extend.`7\"");
		}
	}
	page_footer();
}
?>
