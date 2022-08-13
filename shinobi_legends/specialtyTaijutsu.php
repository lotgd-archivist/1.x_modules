<?php
//addnews ready
// mail ready
// translator ready

function specialtyTaijutsu_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Taijutsu",
		"author" => "Ann, based on Thieving Skills by Eric Stevens",
		"version" => "1.0",
		"download" => "e",
		"category" => "Specialties",
		"prefs" => array(
			"Specialty - Taijutsu User Prefs,title",
			"skill"=>"Skill points in Taijutsu,int|0",
			"uses"=>"Uses of Taijutsu allowed,int|0",
		),
	);
	return $info;
}

function specialtyTaijutsu_install(){
	$sql = "DESCRIBE " . db_prefix("accounts");
	$result = db_query($sql);
	$specialty="TS";
	while($row = db_fetch_assoc($result)) {
		// Convert the user over
		if ($row['Field'] == "Taijutsu") {
			debug("Migrating Taijutsu field");
			$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtythiefskills', 'skill', acctid, thievery FROM " . db_prefix("accounts");
			db_query($sql);
			debug("Dropping Taijutsu field from accounts table");
			$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP thievery";
			db_query($sql);
		} elseif ($row['Field']=="Taijutsuuses") {
			debug("Migrating Taijutsu uses field");
			$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtythiefskills', 'uses', acctid, thieveryuses FROM " . db_prefix("accounts");
			db_query($sql);
			debug("Dropping Taijutsu field from accounts table");
			$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP thieveryuses";
			db_query($sql);
		}
	}
	debug("Migrating Taijutsu Specialty");
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='$specialty' WHERE specialty='3'";
	db_query($sql);

	//module_addhook("choose-specialty");
	//module_addhook("set-specialty");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addhook("incrementspecialty");
	module_addhook("specialtynames");
	module_addhook("specialtymodules");
	module_addhook("specialtycolor");
	module_addhook("dragonkill");
	return true;
}

function specialtyTaijutsu_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='TS'";
	db_query($sql);
	return true;
}

function specialtyTaijutsu_dohook($hookname,$args){
	global $session,$resline;

	$spec = "TS";
	$name = "Taijutsu";
	$ccode = "`^";

	switch ($hookname) {
	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;
	case "choose-specialty":break;
		if ($session['user']['specialty'] == "" ||
				$session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Hand to hand combat skills used to damage an opponent externally or internally.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`6Growing up, you generally just did so much better in the academy in Taijutsu.");
			output("You also discovered that you just like to punch people in the gut.");
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;
	case "specialtymodules":
		$args[$spec] = "specialtyTaijutsu";
		break;
	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$name = translate_inline($name);
			$c = $args['color'];
			output("`n%sYou gain a level in `&%s%s to `#%s%s!",
					$c, $name, $c, $new, $c);
			$x = $new % 3;
			if ($x == 0){
				output("`n`^You gain an extra use point!`n");
				set_module_pref("uses", get_module_pref("uses") + 1);
			}else{
				if (3-$x == 1) {
					output("`n`^Only 1 more skill level until you gain an extra use point!`n");
				} else {
					output("`n`^Only %s more skill levels until you gain an extra use point!`n", (3-$x));
				}
			}
			output_notl("`0");
		}
		break;
	case "newday":
		$bonus = getsetting("specialtybonus", 1);
		if($session['user']['specialty'] == $spec) {
			$name = translate_inline($name);
			if ($bonus == 1) {
				output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode,$name,$ccode,$name);
			} else {
				output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode,$name,$bonus,$ccode,$name);
			}
		}
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt = $amt + $bonus;
		set_module_pref("uses", $amt);
		break;
	case "fightnav-specialties":
		$uses = get_module_pref("uses");
		$script = $args['script'];
		if ($uses > 0) {
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode &#149; Lion Combo`7 (%s)`0", 1),
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Leaf Grand Spinning Wind`7 (%s)`0", 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Leaf Violent Wind`7 (%s)`0", 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Lotus`7 (%s)`0", 5),
					$script."op=fight&skill=$spec&l=5",true);
		}
		break;
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('ts1',array(
						"startmsg"=>"`^You launch {badguy} into the air, punching {badguy} until the reaches the ground again.",
						"name"=>"`^Insult",
						"rounds"=>5,
						"wearoff"=>"Your victim can't move very well from the injuries caused by that harsh landing.",
						"roundmsg"=>"{badguy} feels gimp and cannot attack as well.",
						"badguyatkmod"=>0.5,
						"schema"=>"module-specialtyTaijutsu"
					));
					break;
				case 2:
					apply_buff('ts2',array(
						"startmsg"=>"`^You begin to speed up and do plenty of hardcore roundhouse kicks.",
						"name"=>"`^Poison Attack",
						"rounds"=>5,
						"wearoff"=>"Your victim got kicked in the most undesireable places.",
						"atkmod"=>2,
						"roundmsg"=>"Your attack is multiplied!",
						"schema"=>"module-specialtyTaijutsu"
					));
					break;
				case 3:
					apply_buff('ts3', array(
						"startmsg"=>"`^You begin a few select moves from the Lotus move.",
						"name"=>"`^Hidden Attack",
						"rounds"=>5,
						"wearoff"=>"Your victim backs away afraid to attack you.",
						"roundmsg"=>"{badguy} is scared, and swings wildly!",
						"badguyatkmod"=>0,
						"schema"=>"module-specialtyTaijutsu"
					));
					break;
				case 5:
					apply_buff('ts5',array(
						"startmsg"=>"`^Using your skill, you perform the Lotus, even though you know its forbidden.  Man, {badguy} is in for a world of pain!",
						"name"=>"`^Lotus",
						"rounds"=>1,
						"wearoff"=>"Your victim won't be so likely to ever attack you again!",
						"atkmod"=>5,
						"defmod"=>5,
						"roundmsg"=>"Your attack is multiplied, as is your defense!",
						"schema"=>"module-specialtyTaijutsuu"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('ts0', array(
					"startmsg"=>"You try to attack {badguy} by putting your best Taijutsu into practice, but instead, you trip over your feet.",
					"rounds"=>1,
					"schema"=>"module-specialtyTaijutsu"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtyTaijutsu_run(){
}
?>
