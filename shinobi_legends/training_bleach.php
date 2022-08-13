<?php

function training_bleach_getmoduleinfo() {
	$info = array
		(
		"name"=>"Training Grounds (Bleach)",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Training",
		"download"=>"",
		"requires"=>array(
			"specialtysystem"=>"1.0|Specialty System Core by `2Oliver Brendel",
			),
		);
	return $info;
}

function training_bleach_install(){
	module_addhook("footer-train");
	return true;
}

function training_bleach_uninstall(){
	return true;
}

function training_bleach_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
	case "footer-train":
		$op=httpget('op');
		if ($op!='' && $op!='question') break;
		addnav("Training");
		addnav("Training Grounds","runmodule.php?module=training_bleach");
		break;
	}
	return $args;
}

function training_bleach_run() {
	global $session;
	page_header("Training Grounds");
	addnav("Navigation");
	addnav("Back to the Main Grounds","train.php");
	output("`#`b`c`n`2Training Grounds`0`c`b`n`n");
	require_once("modules/specialtysystem/datafunctions.php");
	$op = httpget('op');
	$cost=array(0,48,225,585,990,1575,2250,2790,3420,4230,5040,5850,6840,8010,9000,10350,11500,13775,15850,17030,18270,20020,21150,22500,25550,30000,32000);	
	$multi=75;
	$gold=$session['user']['level']*$multi;
	modulehook("traininggrounds",array());
	switch ($op) {
	case "mountsummonexecute":
		$action = httpget('action');
		$who=httpget('who');
		$gold=round($gold/2,0);
		if ($session['user']['gold']<$gold) {
			output("`3Shame on you! You do not have enough gold with you!");
			break;
		}
		$session['user']['gold']-=$gold;
		require_once("lib/battle-skills.php");
		switch ($action) {
		case 1:
			output("`%%s`3 intonates some strange syllables... you join in and together you call your mount back from its rest...",$who);
			output_notl("`n`n");
			unsuspend_buff_by_name('mount','`3You feel full of new inspiration along with your mount.');
			break;
		case 0:
			output("`%%s`3 intonates some strange syllables... you join in and together you send your mount at rest for some time...",$who);
			output_notl("`n`n");
			suspend_buff_by_name('mount','`3You will certainly miss your fellow comrade...');
			break;
		}
		break;
	case "mountsummon":
		global $playermount;
		$gold=round($gold/2,0);
		addnav("Back to the training grounds","runmodule.php?module=training_bleach");
		$who=array("Zaraki Kenpachi", "Madarame Ikkaku","Hisagi Shuuhei","Matsumoto Rangiku (^^)","Abarai Renji","Kira Izuru","Kuchiki Rukia");
		$rand=array_rand($who);
		$who=$who[$rand];
		$action=httpget('action');
		$actionword=($action==1?translate_inline("Summon"):translate_inline("Unsummon"));
		output("`3You decide to %s your permanent mount... you could do this on your own, too, but it is always convenient to have somebody helping you.",($action==1?translate_inline("summon"):translate_inline("unsummon")));
		output("`n`nKnowing it would take also more time to do this all by yourself, you decide to go to `%%s`3 to ask for help.`n`n",$who);
		output("`3\"`tSo... let me see... you want to %s `v%s`t? No big deal, this won't take much  time, so it costs only `^%s gold`t to relieve me from duty.`3\".`n`n",($action==1?translate_inline("summon"):translate_inline("unsummon")),$playermount['mountname'],$gold);
		addnav("Actions");
		addnav(array("%s your mount",$actionword),"runmodule.php?module=training_bleach&op=mountsummonexecute&action=$action&who=$who");
		break;
	case "setspecialty":
		if ($session['user']['gold']<$gold) {
			output("`3Shame on you! You do not have enough gold with you!");
			break;
		}
		output("`3\"`tYou are now working for your new specialty...good luck!`3\"");
		output_notl("`n`n");//debug(httppost('ssystem'));
		specialtysystem_set(array("active"=>httppost('ssystem')));
		if ($session['user']['specialty']!='SS') $session['user']['specialty']='SS';
		$session['user']['gold']-=$gold;
		break;
	case "specialty":
		addnav("Back to the training grounds","runmodule.php?module=training_bleach");
		$who=array("Zaraki Kenpachi", "Madarame Ikkaku","Hisagi Shuuhei","Matsumoto Rangiku (^^)","Abarai Renji","Kira Izuru","Kuchiki Rukia");
		$rand=array_rand($who);
		$who=$who[$rand];
		output("`3You decide to change your specialty... you want to work for a new kind of jutsu from now on.");
		output("`n`nKnowing it would take time to do this all by yourself, you decide to go to `%%s`3 to ask for help.`n`n",$who);
		output("`3\"`tSo... let me see... you want to switch your jutsus... I can help you. But for my time you need to pay me off for my other duties at this time. Currently that would be `^%s gold pieces`t. If you want, select your new kind of jutsu and we can keep going.`3\".`n`n",$gold);
		output("You ponder about that offer... what are you going to do?");
		output_notl("`n`n");
		rawoutput("<form action='runmodule.php?module=training_bleach&op=setspecialty' method='POST'>");
		addnav("","runmodule.php?module=training_bleach&op=setspecialty");
		$specs=specialtysystem_getspecs();//debug($specs);
		if ($specs==array()) {
			output("Sorry, I have no registered specialties for you here...");
			break;
		}
		rawoutput("<select name='ssystem'>");
		$active=specialtysystem_get("active");
		foreach ($specs as $key=>$data) {//debug($data);
			$name=translate_inline($data['spec_name']);
			if ($data['dragonkill_minimum_requirement']>$session['user']['dragonkills']) continue;
			if (((int)$data['dragonkill_minimum_requirement'])==-1) continue;
			if ($data['modulename']==$active) continue;
			rawoutput("<option value='{$data['modulename']}'>$name</option>");
		}
		rawoutput("</select>");
		$submit=translate_inline("Submit");
		rawoutput("<br><br><input type='submit' value='$submit'></form>");
		output("`n`n`lPS: You still retain the knowledge of your current specialty/specialties. You simply get new skillpoints in the new specialty you select here.");
		break;
	case "trainoffensive":
		addnav("Back to the training grounds","runmodule.php?module=training_bleach");
		addnav("Actions");
		$who=array("Zaraki Kenpachi", "Madarame Ikkaku","Hisagi Shuuhei","Matsumoto Rangiku (^^)","Abarai Renji","Kira Izuru","Kuchiki Rukia");
		output("`c`b`1~~~ `\$Offensive Training`1 ~~~`c`n`n");
		$rand=array_rand($who);
		$who=$who[$rand];
		$lev=$session['user']['weapondmg'];
		$dummy=modulehook("training-costs-o",array("user"=>$session['user'],"cost"=>$cost));
		$cost=$dummy['cost'];
		switch(httpget('action')) {
			case "train":
				$session['user']['gold']-=$cost[$lev];
				$session['user']['weapondmg']=$lev+1;
				$session['user']['attack']++;
				output("`1You have successfully gained `%one attack point`1 due to harsh and rigorous training!`n`n");
				debuglog("trained and got +1 attack, now has ".$session['user']['attack']." points, paid ".$cost[$lev]." gold.");
				if (e_rand(0,10)==10) {
					output("`2You also feel you have satisfied your zanpakutou pretty well!");
					output("`n`~(You gain 20 favours)");
					$session['user']['deathpower']+=20;
				}
				break;
			default:
			output("`3You look for somebody who has enough time to teach you something about how to improve your offensive skills... %s`3 is currently free.`n`n",$who);
			output("\"`tWell, if you are up for a little training... it will cost you `^%s gold`t to improve your current skills who rank at level %s currently.`3\"",$cost[$lev],$lev);
			$link='';
			if ($cost[$lev]<=$session['user']['gold']) $link="runmodule.php?module=training_bleach&op=trainoffensive&action=train";
			addnav("Train Yourself",$link);
		}
		
		break;
		
	case "traindefensive":
		addnav("Back to the training grounds","runmodule.php?module=training_bleach");
		addnav("Actions");
		$who=array("Unohana Retsu", "Shunsui Kyouraku","Komamura Saijin","Ukitake Jyuushirou","Kusajishi Yachiru","Kuchiki Rukia");
		output("`c`b`1~~~ `\$Defensive Training`1 ~~~`c`n`n");
		$rand=array_rand($who);
		$who=$who[$rand];
		$lev=$session['user']['armordef'];
		$dummy=modulehook("training-costs-d",array("user"=>$session['user'],"cost"=>$cost));
		$cost=$dummy['cost'];
		switch(httpget('action')) {
			case "train":
				$session['user']['gold']-=$cost[$lev];
				$session['user']['armordef']=$lev+1;
				$session['user']['defense']++;
				output("`1You have successfully gained `%one defense point`1 due to harsh and rigorous training!`n`n");
				debuglog("trained and got +1 attack, now has ".$session['user']['defense']." points, paid ".$cost[$lev]." gold.");
				if (e_rand(0,10)==10) {
					$fav=e_rand(2,20);
					output("`2You also feel you have satisfied your zanpakutou pretty well!");
					output("`n`~(You gain %s favours)",$fav);
					$session['user']['deathpower']+=$fav;
				}
				break;
			default:
			output("`3You look for somebody who has enough time to teach you something about how to improve your offensive skills... %s`3 is currently free.`n`n",$who);
			output("\"`tWell, if you are up for a little training... it will cost you `^%s gold`t to improve your current skills who rank at level %s currently.`3\"",$cost[$lev],$lev);
			$link='';
			if ($cost[$lev]<=$session['user']['gold']) $link="runmodule.php?module=training_bleach&op=traindefensive&action=train";
			addnav("Train Yourself",$link);
		}
		break;
		case "wiseman":
			$array=array(
				"Resolve is hard like a diamond, sharper than steel and clearer than the sun in the sky... either you do the crushing, or you are crushed.",
				"You need to grow more.",
				"Death is only the beginning.",
				"Don't eat too late.",
				"Treat other souls with respect.",
				"Wash your hands after visiting the restroom.",
				"Don't prey on the weak. Imagine they become strong one day.",
				"Being self-sufficient is good when out alone in the desert. But also think about leaving this desert some day.",
				"We all are actors in a gigantic stage.",
				"Life is just a game. But with great graphics...",
			);
			$array=translate_inline($array);
			$cnt=date("d")%count($array);
			output_notl("`\$".$array[$cnt]);
			break;
	default:
		if (is_module_active('addimages')) output_notl("`c<IMG SRC=\"modules/addimages/header-train.gif\">`c<BR>\n",true);
		output("`3You enter the vast training grounds you know about.`n`n");
		output("Many shingami novices and also higher ranked are training there to improve themselves or simply to be able to complete their respective tasks perfectly to go even higher in rank.");
		$who=array("Zaraki Kenpachi", "Madarame Ikkaku","Hisagi Shuuhei","Matsumoto Rangiku (^^)","Abarai Renji","Kira Izuru","Kuchiki Rukia","the Training Master","the toothless floorcleaner");
		$rand=array_rand($who);
		$who=$who[$rand];
		output("`nThough many do not seem to notice you, `%%s`3 gives you a short glance and nods.",$who);
		output("`n`n`vWhat do you want to do?");
		training_bleachnav();
		addnav("Wise Man");
		addnav("Ask...","runmodule.php?module=training_bleach&op=wiseman");
		break;
	}
	page_footer();
}

function training_bleachnav() {
	global $session;
	addnav("Actions");
	if (is_module_active("specialtysystem")) addnav("Switch your specialty","runmodule.php?module=training_bleach&op=specialty");
	if (has_buff('mount')) {
		if ($session['bufflist']['mount']['suspended']) {
			$sw=1;
			$action=translate_inline("Summon");
		} else {
			$sw=0;
			$action=translate_inline("Unsummon");
		}
		addnav(array("%s your mount",$action),"runmodule.php?module=training_bleach&op=mountsummon&action=$sw");
	}
	$lev=$session['user']['weapondmg']+$session['user']['armordef'];
	if ($lev<30) {
		if ($session['user']['weapondmg']<25) {
		addnav("Train your Offensive Zanpakutou Skills","runmodule.php?module=training_bleach&op=trainoffensive");
		}
		if ($session['user']['armordef']<25) {
			addnav("Train your Defensive Zanpakutou Skills","runmodule.php?module=training_bleach&op=traindefensive");
		}
	}
}


?>