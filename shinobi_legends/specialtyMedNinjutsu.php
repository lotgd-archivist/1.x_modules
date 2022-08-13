<?php
//addnews ready
// mail ready
// translator ready

function specialtyMedNinjutsu_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Medical Ninjutsu",
		"author" => "`2Oliver`0, based on Dark Arts by Eric Stevens",
		"version" => "1.0",
		"download" => "",
		"category" => "Specialties",
		"prefs" => array(
			"Specialty - Ninjutsu User Prefs,title",
			"skill"=>"Skill points in Ninjutsu,int|0",
			"uses"=>"Uses of Ninjutsu allowed,int|0",
		),
	);
	return $info;
}

function specialtyMedNinjutsu_install(){
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

function specialtyMedNinjutsu_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='DA'";
	db_query($sql);
	return true;
}

function specialtyMedNinjutsu_dohook($hookname,$args){
	global $session,$resline;

	$spec = "MN";
	$name = "Medical Ninjutsu";
	$ccode = "`!";

	switch ($hookname) {
	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;
	case "choose-specialty":break;
		if ($session['user']['specialty'] == "" ||
				$session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=$spec$resline");
			$t1 = translate_inline("Healing a lot of woodland creatures");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`5Growing up, you recall being bad when it comes to attack an opponent using offensive Ninjutsu. You somehow had more compassion helping injured shinobi and learned to form your chakra into tools necessary for wound treatment.");
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;
	case "specialtymodules":
		$args[$spec] = "specialtyMedNinjutsu";
		break;
	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$c = $args['color'];
			$name = translate_inline($name);
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
				output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode, $name, $ccode, $name);
			} else {
				output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode, $name,$bonus, $ccode,$name);
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
			addnav(array("$ccode$name (%s points)`0", $uses),"");
			addnav(array("$ccode &#149; Minor Selfhealing`7 (%s)`0", 1),
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Poisonous Needles`7 (%s)`0", 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Major Selfhealing`7 (%s)`0", 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			if (is_module_active('alignment')) {
				$evilalign = get_module_setting('evilalign','alignment');
				$goodalign = get_module_setting('goodalign','alignment');
				$useralign = get_module_pref('alignment','alignment');
				if ($useralign <= $evilalign) {
					addnav(array("$ccode &#149; `\$Attack Organs`7 (%s)`0", 5),
					$script."op=fight&skill=$spec&l=5&e=1",true);
				} elseif ($useralign >= $goodalign) {
					addnav(array("$ccode &#149; Ninpou Sozo Saize`7 (%s)`0", 5),
					$script."op=fight&skill=$spec&l=5",true);
				} else {//neutral
					if (e_rand(0,1))
					addnav(array("$ccode &#149; `\$Attack Organs`7 (%s)`0", 5),
					$script."op=fight&skill=$spec&l=5&e=1",true);
					else
					addnav(array("$ccode &#149; Ninpou Sozo Saize`7 (%s)`0", 5),
					$script."op=fight&skill=$spec&l=5",true);
				}
			} else {
				addnav(array("$ccode &#149; Ninpou Sozo Saize`7 (%s)`0", 5),
				$script."op=fight&skill=$spec&l=5",true);
			}
		}
		break;
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('mdn1',array(
						"startmsg"=>"`!You concentrate and form your chakra. Your body starts to regenerate",
						"name"=>"`!Minor Selfhealing",
						"rounds"=>5,
						"wearoff"=>"You have stopped regenerating.",
						"regen"=>$session['user']['level']+1,
						"effectmsg"=>"You regenerate for {damage} health.",
						"effectnodmgmsg"=>"You have no wounds to regenerate.",
						"schema"=>"module-specialtyMedNinjutsu"
					));
					break;
				case 2:
					apply_buff('mdn2',array(
						"startmsg"=>"`\$You stare at {badguy}`\$ and spit out some poisonous needles.",
						"name"=>"`2Poisonous Needles",
						"effectmsg"=>"{badguy} is unable to fight you at full power!",
						"rounds"=>5,
						"minioncount"=>1,
						"badguyatkmod"=>0.3,
						"schema"=>"module-specialtyMedNinjutsu"
					));
					break;
				case 3:
					if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) {
						$session['user']['hitpoints']=$session['user']['maxhitpoints'];
						}
					apply_buff('mdn3',array(
						"startmsg"=>"`!You concentrate and form an enormous mass of your chakra. Your body starts to regenerate and your current wounds have healed completely",
						"name"=>"`4Major Selfhealing",
						"rounds"=>10,
						"wearoff"=>"You have stopped regenerating.",
						"atkmod"=>0.9,
						"regen"=>$session['user']['level'],
						"effectmsg"=>"You regenerate for {damage} health.",
						"effectnodmgmsg"=>"You have no wounds to regenerate.",
						"schema"=>"module-specialtyMedNinjutsu"
					));
					break;
				case 5:
					if (!httpget('e')) {
						if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) {
						$session['user']['hitpoints']=$session['user']['maxhitpoints'];
						}
						apply_buff('mdn4',array(
						"startmsg"=>"`7You form secret seals. {badguy}`7 is not sure what you plan... you open the seals and your wounds seem to close rapidly.",
						"name"=>"`!Ninpou Sozo Saize",
						"rounds"=>5,
						"wearoff"=>"The effect of your renewal dissipates...",
						"regen"=>5*$session['user']['level'],
						"atkmod"=>0.9,
						"defmod"=>3,
						"roundmsg"=>"{badguy} is having difficulty to approach you.",
						"schema"=>"module-specialtyMedNinjutsu"
					));
					} else
					apply_buff('mdn4',array(
						"startmsg"=>"`7You form secret seals. {badguy}`7 is not sure what you plan... thin chakra layers emerge from your palms and you form them to scalpels... and rush in on your enemy.",
						"name"=>"`\$Attack Inner Organs",
						"rounds"=>5,
						"wearoff"=>"The chakra around your palms disappears...",
						"atkmod"=>3.5,
						"defmod"=>0.9,
						"roundmsg"=>"{badguy} is under pressure.",
						"schema"=>"module-specialtyMedNinjutsu"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('mdn0', array(
					"startmsg"=>"Exhausted, you try your medical ninjutsu, a bad joke.  {badguy} looks at you for a minute, thinking, and finally gets the joke.  Laughing, it swings at you again.",
					"rounds"=>1,
					"schema"=>"module-specialtyMedNinjutsu"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtyMedNinjutsu_run(){
}
?>
