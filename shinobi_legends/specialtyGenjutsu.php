<?php
//addnews ready
// mail ready
// translator ready

function specialtyGenjutsu_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Genjutsu",
		"author" => "Ann, based on Mystical Powers by Eric Stevens",
		"version" => "1.0",
		"download" => "",
		"category" => "Specialties",
		"prefs" => array(
			"Specialty - Genjutsu User Prefs,title",
			"skill"=>"Skill points in Genjutsu,int|0",
			"uses"=>"Uses of Genjutsu allowed,int|0",
		),
	);
	return $info;
}

function specialtyGenjutsu_install(){
	$sql = "DESCRIBE " . db_prefix("accounts");
	$result = db_query($sql);
	$specialty="MP";
	while($row = db_fetch_assoc($result)) {
		// Convert the user over
		if ($row['Field'] == "Genjutsu") {
			debug("Migrating Genjutsu field");
			$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtyGenjutsu', 'skill', acctid, Genjutsu FROM " . db_prefix("accounts");
			db_query($sql);
			debug("Dropping Genjutsu field from accounts table");
			$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP magic";
			db_query($sql);
		} elseif ($row['Field']=="Genjutsu") {
			debug("Migrating Genjutsu uses field");
			$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtyGenjutsu', 'uses', acctid, Genjutsuuses FROM " . db_prefix("accounts");
			db_query($sql);
			debug("Dropping Genjutsuuses field from accounts table");
			$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP magicuses";
			db_query($sql);
		}
	}
	debug("Migrating Genjutsu Specialty");
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='$specialty' WHERE specialty='2'";
	db_query($sql);

	module_addhook("choose-specialty");
	module_addhook("set-specialty");
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

function specialtyGenjutsu_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='MP'";
	db_query($sql);
	return true;
}

function specialtyGenjutsu_dohook($hookname,$args){
	global $session,$resline;

	$spec = "MP";
	$name = "Genjutsu";
	$ccode = "`%";
	$ccode2 = "`%%"; // We need this to handle the damned sprintf escaping.

	switch ($hookname) {
	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;
	case "choose-specialty":
		break;
		if ($session['user']['specialty'] == "" ||
				$session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Dabbling in Genjutsu");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`3Growing up, you remember knowing there was more to the world than the physical, and what you could place your hands on.");
			output("You realized that your mind itself, with training, could be turned into a weapon.");
			output("Over time, you began to control illusions to your will.");
			output("To your delight, it could also be used as a weapon against your foes.");
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;
	case "specialtymodules":
		$args[$spec] = "specialtyGenjutsu";
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
			addnav(array("$ccode2$name (%s points)`0", $uses), "");
			addnav(array("e?$ccode2 &#149; Regeneration`7 (%s)`0", 1),
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode2 &#149; Earth Fist`7 (%s)`0", 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode2 &#149; Siphon Life`7 (%s)`0", 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("g?$ccode2 &#149; Lightning Aura`7 (%s)`0", 5),
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
					apply_buff('mp1', array(
						"startmsg"=>"`^You begin to regenerate!",
						"name"=>"`%Regeneration",
						"rounds"=>5,
						"wearoff"=>"You have stopped regenerating",
						"regen"=>$session['user']['level'],
						"effectmsg"=>"You regenerate for {damage} health.",
						"effectnodmgmsg"=>"You have no wounds to regenerate.",
						"schema"=>"module-specialtyGenjutsu"
					));
					break;
				case 2:
					apply_buff('mp2', array(
						"startmsg"=>"`^{badguy}`% is clutched by a fist of earth and slammed to the ground!",
						"name"=>"`%Earth Fist",
						"rounds"=>5,
						"wearoff"=>"The earthen fist crumbles to dust.",
						"minioncount"=>1,
						"effectmsg"=>"A huge fist of earth pummels {badguy} for `^{damage}`) points.",
						"minbadguydamage"=>1,
						"maxbadguydamage"=>$session['user']['level']*3,
						"schema"=>"module-specialtyGenjutsu"
					));
					break;
				case 3:
					apply_buff('mp3', array(
						"startmsg"=>"`^Your weapon glows with an unearthly presence.",
						"name"=>"`%Siphon Life",
						"rounds"=>5,
						"wearoff"=>"Your weapon's aura fades.",
						"lifetap"=>1, //ratio of damage healed to damage dealt
						"effectmsg"=>"You are healed for {damage} health.",
						"effectnodmgmsg"=>"You feel a tingle as your weapon tries to heal your already healthy body.",
						"effectfailmsg"=>"Your weapon wails as you deal no damage to your opponent.",
						"schema"=>"module-specialtyGenjutsu"
					));
					break;
				case 5:
					apply_buff('mp5', array(
						"startmsg"=>"`^Your skin sparkles as you assume an aura of lightning",
						"name"=>"`%Lightning Aura",
						"rounds"=>5,
						"wearoff"=>"With a fizzle, your skin returns to normal.",
						"damageshield"=>2,
						"effectmsg"=>"{badguy} recoils as lightning arcs out from your skin, hitting for `^{damage}`) damage.",
						"effectnodmg"=>"{badguy} is slightly singed by your lightning, but otherwise unharmed.",
						"effectfailmsg"=>"{badguy} is slightly singed by your lightning, but otherwise unharmed.",
						"schema"=>"module-specialtyGenjutsu"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('mp0', array(
					"startmsg"=>"You furrow your brow and call on the powers of the elements.  A tiny flame appears.  {badguy} lights a cigarette from it, giving you a word of thanks before swinging at you again.",
					"rounds"=>1,
					"schema"=>"module-specialtyGenjutsu"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtyGenjutsu_run(){
}
?>
