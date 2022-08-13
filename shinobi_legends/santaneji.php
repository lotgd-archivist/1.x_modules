<?php
function santaneji_getmoduleinfo(){
	$info = array(
		"name"=>"Santa Neji",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Holidays|Christmas",
		"download"=>"",
		"prefs"=>array(
			"Santa Neji Preferences,title",
			"Also seen in the village!,note",
			"seen"=>"Has the user had this one?,bool|0",
			"kyuubi"=>"Has Kyuubi from here?,bool|0",
			"date"=>"Acquired when,text|",
			),
	);
	return $info;
}

function santaneji_install(){
	module_addeventhook("forest",'$check=get_module_pref("seen","santaneji");return ($check==0?100:0);');
	module_addeventhook("village",'$check=get_module_pref("seen","santaneji");return ($check==0?100:0);');
	module_addhook("forest");
	
	#Cinder Kitty Table
	if (db_table_exists(db_prefix("cinder_santa"))) {
		debug("Cinder Santa table already exists");
	} else {
		$sql = array(
			"CREATE TABLE ".db_prefix("cinder_santa")." (id int(2) NOT NULL auto_increment, category varchar(50) NOT NULL, code varchar(255) NOT NULL, log varchar(255) DEFAULT 'System', PRIMARY KEY  (id)) Engine=InnoDB;",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (1, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (2, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (3, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (4, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (5, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (6, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (7, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (8, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (9, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (10, 'Code US' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (11, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (12, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (13, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (14, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (15, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (16, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (17, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (18, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (19, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (20, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (21, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (22, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (23, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (24, 'Code EU' ,'####','');",
			"INSERT INTO ".db_prefix("cinder_santa")." VALUES (25, 'Code EU' ,'####','');",
			);
			
			foreach ($sql as $statement) {
				db_query($statement);
				}
		}	
		
	return true;
}

function santaneji_run(){
	global $session;
	$session['user']['specialinc'] = "module:santaneji";
	redirect("forest.php");	
}
function santaneji_uninstall(){
	$table = db_prefix("cinder_santa");
	$sql = "DROP TABLE $table";
	debug("Dropped the $table table.");
	return true;
}

function santaneji_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "forest":
			addnav("Christmas's Thingies");
			$seen=get_module_pref('seen');
			if ($seen<1) {
				addnav(array("`2Neji, give me `\$Santa`2!"),"runmodule.php?module=santaneji");
			} else {
				addnav(array("`2Neji, give me `\$Santa`2! (You had this)"),"");
			}
			break;
		default:
	
			break;
	}
	return $args;
}

function santaneji_runevent($type) {
	global $session;
	$session['user']['specialinc'] = "module:santaneji";
	$seen=get_module_setting('seen');
	$op = httpget('op');
	$from=$type.".php?op=";
	$santa="`\$Santa `lN`Ve`lj`Vi";

//presents
	$gold_b = 50000;
	$gems_b = 35;
	$susannoo_b = 3;
	$star_b = 3;
	$curse_b = 3;
	$donation_b = 1000;
	$kyuub_b = 1500;
	$forest_b = 100;
	$pvp_b = 50;

	//output("`c`b`lSomething Special!!!`b`c`2`n`n");
	switch($op) {
	case "away": case "leave":
		output("`tYou leave the strange place very fast... leaving only some dustclouds behind you....`n`n");
		$session['user']['specialinc'] = "";
		break;
	case "gold":
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want `^gold`t... so here you go!`t\"`n`n");
		output("You pick up a laaarge bag of `^gold`t and leave the place happily!");
		output("`n`nAlso, a voice echoes: \"`vMerry `lChristmas `gfrom all the staff of `)S`~hinobi `)L`~egends`g to you and your family! Enjoy!`t\"`n`n");
		if (is_module_active("stafflist")) {
			output("`tSincerly,`n");
			$sql="SELECT a.name as name FROM ".db_prefix('accounts')." AS a INNER JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='rank' AND b.value>0 ORDER BY b.value DESC";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				output_notl("`v".$row['name']."`n");
			}
		}
		$session['user']['gold']+=$gold_b;
		$session['user']['specialinc'] = "";
		debuglog("Santa Neji gave $gold_b gold");
		set_module_pref("seen",1);
		break;
	case "gems":
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want `%gems`g... so here you go!`t\"`n`n");
		output("You pick up a large bag of `%gems`t and leave the place happily!");
		output("`n`nAlso, a voice echoes: \"`vMerry `lChristmas `gfrom all the staff of `)S`~hinobi `)L`~egends`g to you and your family! Enjoy!`t\"`n`n");
		if (is_module_active("stafflist")) {
			output("`tSincerly,`n");
			$sql="SELECT a.name as name FROM ".db_prefix('accounts')." AS a INNER JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='rank' AND b.value>0 ORDER BY b.value DESC";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				output_notl("`v".$row['name']."`n");
			}
		}
		$session['user']['gems']+=$gems_b;
		$session['user']['specialinc'] = "";
		debuglog("Santa Neji gave $gems_b gems");
		set_module_pref("seen",1);
		break;
	case "points":
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want `^donation points`g... so here you go!`t\"`n`n");
		output("You feel that you suddenly became more known in the realm and leave the place happily!");
		output("`n`nAlso, a voice echoes: \"`vMerry `lChristmas `gfrom all the staff of `)S`~hinobi `)L`~egends`g to you and your family! Enjoy!`t\"`n`n");
		if (is_module_active("stafflist")) {
			output("`tSincerly,`n");
			$sql="SELECT a.name as name FROM ".db_prefix('accounts')." AS a INNER JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='rank' AND b.value>0 ORDER BY b.value DESC";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				output_notl("`v".$row['name']."`n");
			}
		}
		$session['user']['donation']+=$donation_b;
		$session['user']['specialinc'] = "";
		set_module_pref("seen",1);
		debuglog("Santa Neji gave $donation_b donation points");
		break;
	case "kyuubi":
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want `^Kyuubi`g for a couple of days... so here you go! See him on the next newday!`t\"`n`n");
		output("You are anxious to expect him to show up and leave the place happily!");
		output("`n`nAlso, a voice echoes: \"`vMerry `lChristmas `gfrom all the staff of `)S`~hinobi `)L`~egends`g to you and your family! Enjoy!`t\"`n`n");
		// quick and dirty
		modulehook("donation",array("id"=>$session['user']['acctid'],"amt"=>$kyuub_b,"silent"=>1));	
		set_module_pref("kyuubi",1);
		set_module_pref("date",date("Y-m-d H:i:s"));
		if (is_module_active("stafflist")) {
			output("`tSincerly,`n");
			$sql="SELECT a.name as name FROM ".db_prefix('accounts')." AS a INNER JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='rank' AND b.value>0 ORDER BY b.value DESC";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				output_notl("`v".$row['name']."`n");
			}
		}
		$session['user']['specialinc'] = "";
		debuglog("Santa Neji gave Kyuubi days worth ".($kyuub_b/50)." dp");
		set_module_pref("seen",1);
		break;
	case "seal2":
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want `^that nasty %s`g a bit more enroute to level 2?`g ... so here you go!`t\"`n`n",get_module_setting('name','curse_seal'));
		output("You feel yourself more close to level 2 and leave the place happily! (Sparring Points have been added)");
		output("`n`nAlso, a voice echoes: \"`vMerry `lChristmas `gfrom all the staff of `)S`~hinobi `)L`~egends`g to you and your family! Enjoy!`t\"`n`n");
		increment_module_pref("sparring",$curse_b,"curse_seal");
		if (is_module_active("stafflist")) {
			output("`tSincerly,`n");
			$sql="SELECT a.name as name FROM ".db_prefix('accounts')." AS a INNER JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='rank' AND b.value>0 ORDER BY b.value DESC";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				output_notl("`v".$row['name']."`n");
			}
		}
		$session['user']['specialinc'] = "";
		debuglog("Santa Neji gave seal $curse_b sparring levels");
		set_module_pref("seen",1);
		break;
	case "susanoo2":
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want `^that nasty %s`g a bit more enroute to the next level?`g ... so here you go!`t\"`n`n",get_module_setting('name','susanoo'));
		output("You feel yourself more close to the next level and leave the place happily! (Sparring Points have been added)");
		output("`n`nAlso, a voice echoes: \"`vMerry `lChristmas `gfrom all the staff of `)S`~hinobi `)L`~egends`g to you and your family! Enjoy!`t\"`n`n");
		increment_module_pref("sparring",$susanoo_b,"susanoo");
		if (is_module_active("stafflist")) {
			output("`tSincerly,`n");
			$sql="SELECT a.name as name FROM ".db_prefix('accounts')." AS a INNER JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='rank' AND b.value>0 ORDER BY b.value DESC";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				output_notl("`v".$row['name']."`n");
			}
		}
		$session['user']['specialinc'] = "";
		debuglog("Santa Neji gave $susanoo_b susanoo seal sparring levels");
		set_module_pref("seen",1);
		break;		
	case "star2":
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want `^that %s`g a bit more complete?`g... so here you go!!!`t\"`n`n",get_module_setting('name','sevenstar'));
		$tattoo=get_module_pref('tattoo-stage','sevenstar');
		if ($tattoo<(7-$star_b)) {
			output("You feel that the tattoo is more complete and leave the place happily!");
			increment_module_pref("tattoo-stage",$star_b,"sevenstar");
		} else {
			output("You feel that the tattoo is now COMPLETE and leave the place happily!");
			set_module_pref("tattoo-stage",7,"sevenstar");
		}
		output("`n`nAlso, a voice echoes: \"`vMerry `lChristmas `gfrom all the staff of `)S`~hinobi `)L`~egends`g to you and your family! Enjoy!`t\"`n`n");

		if (is_module_active("stafflist")) {
			output("`tSincerly,`n");
			$sql="SELECT a.name as name FROM ".db_prefix('accounts')." AS a INNER JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='rank' AND b.value>0 ORDER BY b.value DESC";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				output_notl("`v".$row['name']."`n");
			}
		}
		$session['user']['specialinc'] = "";
		debuglog('Santa Neji gave star levels');
		set_module_pref("seen",1);
		break;
	case "forest":
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want `2forest fights`g... so here you go!`t\"`n`n");
		output("You feel that you suddenly have A LOT more energy and leave the place happily!");
		output("`n`nAlso, a voice echoes: \"`vMerry `lChristmas `gfrom all the staff of `)S`~hinobi `)L`~egends`g to you and your family! Enjoy!`t\"`n`n");
		if (is_module_active("stafflist")) {
			output("`tSincerly,`n");
			$sql="SELECT a.name as name FROM ".db_prefix('accounts')." AS a INNER JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='rank' AND b.value>0 ORDER BY b.value DESC";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				output_notl("`v".$row['name']."`n");
			}
		}
		$session['user']['turns']+=$forest_b;
		$session['user']['specialinc'] = "";
		debuglog("Santa Neji gave $star_b turns");
		set_module_pref("seen",1);
		break;
	case "pvp":
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want `1PvP Fights`g, you little slayer you... so here you go!`t\"`n`n");
		output("You feel that you suddenly have A LOT more energy and leave the place happily!");
		output("`n`nAlso, a voice echoes: \"`vMerry `lChristmas `gfrom all the staff of `)S`~hinobi `)L`~egends`g to you and your family! Enjoy!`t\"`n`n");
		if (is_module_active("stafflist")) {
			output("`tSincerly,`n");
			$sql="SELECT a.name as name FROM ".db_prefix('accounts')." AS a INNER JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='rank' AND b.value>0 ORDER BY b.value DESC";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				output_notl("`v".$row['name']."`n");
			}
		}
		$session['user']['playerfights']+=$pvp_b;
		$session['user']['specialinc'] = "";
		debuglog("Santa Neji gave $pvp_b pvp fights");
		set_module_pref("seen",1);
		break;		
	#Cinder Kitty	
	case "cinder":
		$code = httpget('code');
		if ($code=='US') { //safety for sql
			$code='US';
		}	else {
			$code='EU';
		}
		$sql = "SELECT * FROM " . db_prefix("cinder_santa") . " WHERE category='Code ".$code."' AND log='' ORDER by rand(".e_rand().") LIMIT 1";
		$result = db_query($sql);
		$data = db_fetch_assoc($result);
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you want a Cinder Kitty... so here you go!`t\"`n`n");
		output("`\$The code for your Cinder Kitty is... `n`n`c`^%s`\$`c `nA copy of the code will be sent to your inbox also.",$data['code']);
		require_once("lib/systemmail.php");
		$subject = "`^Cinder Kitty Code`0";
		$body = array("`\$The code for your Cinder Kitty is... `n`n`c`^%s`\$`c `nPlease record this for your own use.`n`nYou can redeem the code via battle.net",$data['code']);
		systemmail($session['user']['acctid'],$subject,$body);
		$name = $session['user']['acctid'];
		$id = $data['id'];
		$sql = "UPDATE " . db_prefix("cinder_santa") . " SET log=\"$name\"  WHERE id='$id'";
		db_query($sql);
		set_module_pref("seen",1);
		debug("User $name obtained Cinder code $id.");
		break;
	case "sit":
		output("`tYou decide to sit down... at least for now... and wait what %s`t has to say to you....`n`n",$santa);
		output("\"`gYou see, the work is hard, and `qLee`g and `!Ten`1ten`g and all the other elves that help me run all of this (Thanks to all of you!!!) are eager to punch some guts... so well, but there are also presents... and for this season, you can expect one too... also, you may freely choose what you personally long for... I think there is something for you here, I guess`t\"... says the man smilingly.`n`n");
		output("`vWhat do you want to answer?");
		output("`n`n`iNote: if you already have Kyuubi, you get those days additionally`i");
		addnav(array("%s Gold Pieces!",$gold_b),$from."gold");
		addnav(array("%s Gems!",$gems_b),$from."gems");
		addnav(array("%s Forest Fights!",$forest_b),$from."forest");
		addnav(array("%s PvP Fights!",$pvp_b),$from."pvp");
		addnav(array("%s Donation Points!",$donation_b),$from."points");
		addnav(array("Kyuubi for %s game days!",($kyuub_b/50)),$from."kyuubi");
		if ($session['user']['acctid']==3783) {
			addnav("Black Arabian Stallion(`isold out, sorry`i)!","");
		}
		$seal=(int)get_module_pref('hasseal','curse_seal');
		if ($seal==1) {
			addnav(array("Get one step closer to %s`0 level 2!",get_module_setting('name','curse_seal')),$from."seal2");
		}
		$sus_seal=(int)get_module_pref('hasseal','susanoo');
		if ($sus_seal>=1 && $sus_seal<=3) {
			addnav(array("Get one step closer to %s`0 level %s!",get_module_setting('name','susanoo'),$sus_seal+1),$from."susanoo2");
		}
		$tattoo=(int)get_module_pref('tattoo-stage','sevenstar');
		$hastattoo=(int)get_module_pref('hastat','sevenstar');
		if ($seal==0 && $hastattoo==0) {
		} elseif ($seal<=0 && $hastattoo>0 && $tattoo<7) {
			addnav(array("Get one step closer to complete the %s`0!",get_module_setting('name','sevenstar')),$from."star2");
		}
/*
		#Cinder Kitties A
		$sql = "SELECT * FROM " . db_prefix("cinder_santa") . " WHERE category='Code US' AND log=''";
		$result = db_query($sql);
		$num_a = db_num_rows($result);
		if ($num_a > 0){
			addnav(array("Cinder Kitty US (%s) Remaining!`0",$num_a),$from."cinder&code=US");
		} else {
			addnav("Cinder Kitty US (0) Remaining!`0",'');
		}
		#Cinder Kitties B
		$sql = "SELECT * FROM " . db_prefix("cinder_santa") . " WHERE category='Code EU' AND log=''";
		$result = db_query($sql);
		$num_a = db_num_rows($result);
		if ($num_a > 0){
			addnav(array("Cinder Kitty EU (%s) Remaining`0!",$num_a),$from."cinder&code=EU");
		} else {
			addnav("Cinder Kitty EU (0) Remaining`0!",'');
		}
		output("`n`nNote: The Cinder Kitten is a pet for the game 'World of Warcraft'. You need to have an active subscription there, else the pet is useless for you!`n");
*/		addnav("Nothing! Flee! Come back later!",$from."leave");
		break;
	case "nastyboy":
		$who=($session['user']['sex']==SEX_MALE?"boy":"girl");
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So you have been a nasty %s, weren't ya? And what do we do with nasty %s???\" `t... these words make you shiver in fear...`n`n`tAnd then, you hear him saying:",translate_inline($who),translate_inline($who));
		if ($session['user']['sex']==SEX_MALE) {
			output("`t\"`!Teeeen`1ten`g! We have a special customer here for you!`t\"");
			output("`n`nAnd then you see her... the ultimate, shining-like-a-christmas-star, the real best, versatile, feline, `lD`%ancing `lB`%eauty `!Ten`1ten`t!!! The sight would have been much prettier, if she wasn't wearing that nasty looking whip along with the nasty looking black leather clothes underneath her elf outfit... (or do you actually like it?)....");
		} else {
			output("`t\"`qAceeeeeeee`t! We have a special customer here for you!`t\"");
			output("`n`nAnd then you see him in his full elven suit... the ultimate, shining-like-a-christmas-star, the real best, versatile, masculine, `2C`@hrist`2ma`@s `2E`@lf`t `qAce`t!!! The sight would have been much prettier, if he wasn't wearing that nasty looking whip along with the nasty looking black leather clothes underneath his strange outfit... (or do you actually like it?)....");
		}
		output("`n`n... well, to sum the pain you endure up... you are beaten down and promise to be a better %s the next christmas... and you leave the place, having a bite more courage to be good in your heart.",$who);
		output("`n`nAmazingly... %s`t is still waiting for you.`n`n\"`gWell, it seems you have gotten the treatment you deserved... but all others will get something, so you don't need to be excluded...Sit down here on my lap and tell me what you long for!",$santa);
		if (is_module_active('alignment')) {
			require_once("modules/alignment/func.php");
			align(50);
		}
		addnav("Sit down",$from."sit");
		addnav("Flee from the lap!",$from."leave");
		break;
	case "goodboy":
		$who=($session['user']['sex']==SEX_MALE?"boy":"girl");
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`g So a good %s is here? Well then, sit down at my lap and we can talk about your wishes.`t\"`n`n`2What do you do?",translate_inline($who));
		addnav("Sit down",$from."sit");
		addnav("Flee from the lap!",$from."leave");
		break;
	case "rub": case "santa":
		$who=($session['user']['sex']==SEX_MALE?"boy":"girl");
		output("`t\"`vH`Qo`vH`Qo`vH`Qo !`v\" `tare the last words you expected to hear. `n`n\"`gNow who do we have here? Have you been a good %s? I hope so... or else...\"`n`n`tYou realize that this voice comes from a somewhat overweight man in a big red coat with a long white beard... and a very silly hat on his head... sitting on something that invites you to sit on his lap and speak about.. erm... `vChristmas`t somehow.`n`n `3Oh `4My `1GOD`t! Could this be %s`t???`n`n",translate_inline($who),$santa);
		if ($session['user']['sex']==SEX_MALE) {
			output("Beside this chair stands also somebody you never expected to be here... it is `4Gyu... errr... `lD`%ancing `lB`%eauty `!Ten`1ten`t!!!");
		} else {
			output("Beside this chair stands also somebody you never expected to be here... it is `4Vamp...`terrr....`2C`@hrist`2ma`@s `2E`@lf`t `qAce`t !!!");
		}
		output("`n`n\"`gNow... what is your answer? Have you been a good %s?",translate_inline($who));
		addnav("Been very good",$from."goodboy");
		addnav("Been very nasty",$from."nastyboy");
		addnav("Err...RUN (Flee)!",$from."leave");
		break;
	case "leap":
		output("`tYou scream like a wild animal and jump off to catch whatever evil lurks behind the rocks is certainly going to get a bearhug from you... and amazingly, behind the rocks is...`n`n");
		if ($session['user']['sex']==SEX_MALE) {
			output("`%Sakura`t in a cute christmas elf outfit`t, who boasts, \"`vDidn't your mom tell you not to hug everybody???`t\", right before she sends you into orbit with a hammer punch....");
			$session['user']['hitpoints']=1;
		} else {
			output("`~G`)aa`~r`)a `t, who is certainly not in a very playful mood after being hugged by you,  though he does not look very impressed... \"`gSabaku Taisoû!`t\" are the last words you hear before a shockwave of sands sends you into the far corner of the snowland...");
			$session['user']['hitpoints']=1;
		}
		addnav("Wake up and rub your head",$from."rub");
		break;
	case "rocks":
		output("`tWell, the reindeer that was behind the rocks runs away at full speed... strange it smelled like cinnamon... you might have wanted a bit of it... `v*yum yum*`n`n");
		$session['user']['specialinc'] = "";
		break;

	default:
		output("`tWhile you idle around, you notice something is watching you from behind some rocks... and so you decide to investigate a bit around... `vyou use all your skill to go near the rocks looking like you are only passing by, but with your superior skills you hear the clinging of some bells... and a smell of cinnamon is in the air... `n`n`2What do you do?`n`n");
		addnav("Leap over to the rocks",$from."leap");
		addnav("Throw some rocks",$from."rocks");
		addnav("Run away",$from."away");
		break;
	}
}

?>
