<?php

function training_getmoduleinfo() {
	$info = array
		(
		"name"=>"Training Grounds",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Training",
		"download"=>"",
		);
	return $info;
}

function training_install(){
	module_addhook("footer-train");
	return true;
}

function training_uninstall(){
	return true;
}

function training_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
	case "footer-train":
		$op=httpget('op');
		if ($op!='' && $op!='question') break;
		addnav("Training");
		addnav("Training Grounds","runmodule.php?module=training");
		break;
	}
	return $args;
}

function training_run() {
	global $session;
	page_header("Training Grounds");
	addnav("Navigation");
	addnav("Back to the Academy","train.php");
	output("`#`b`c`n`2Training Grounds`0`c`b`n`n");
	require_once("modules/specialtysystem/datafunctions.php");
	$op = httpget('op');
	$multi=75;
	$gold=$session['user']['level']*$multi;
	addnav("Actions");
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
		addnav("Back to the training grounds","runmodule.php?module=training");
		$who=array("Kurenai", "Kakashi","Iruka","Asuma","Shikamaru","Gai","Baki");
		$rand=array_rand($who);
		$who=$who[$rand];
		$action=httpget('action');
		$actionword=($action==1?translate_inline("Summon"):translate_inline("Unsummon"));
		output("`3You decide to %s your permanent mount... you could do this on your own, too, but it is always convenient to have somebody helping you.",($action==1?translate_inline("summon"):translate_inline("unsummon")));
		output("`n`nKnowing it would take also more time to do this all by yourself, you decide to go to `%%s`3 to ask for help.`n`n",$who);
		output("`3\"`tSo... let me see... you want to %s `v%s`t? No big deal, this won't take much  time, so it costs only `^%s gold`t to relieve me from duty.`3\".`n`n",($action==1?translate_inline("summon"):translate_inline("unsummon")),$playermount['mountname'],$gold);
		addnav("Actions");
		addnav(array("%s your mount",$actionword),"runmodule.php?module=training&op=mountsummonexecute&action=$action&who=$who");
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
		addnav("Back to the training grounds","runmodule.php?module=training");
		$who=array("Kurenai", "Kakashi","Iruka","Asuma","Shikamaru","Gai","Baki");
		$rand=array_rand($who);
		$who=$who[$rand];
		output("`3You decide to change your specialty... you want to work for a new kind of jutsu from now on.");
		output("`n`nKnowing it would take time to do this all by yourself, you decide to go to `%%s`3 to ask for help.`n`n",$who);
		output("`3\"`tSo... let me see... you want to switch your jutsus... I can help you. But for my time you need to pay me off for my other duties at this time. Currently that would be `^%s gold pieces`t. If you want, select your new kind of jutsu and we can keep going.`3\".`n`n",$gold);
		output("You ponder about that offer... what are you going to do?");
		output_notl("`n`n");
		rawoutput("<form action='runmodule.php?module=training&op=setspecialty' method='POST'>");
		addnav("","runmodule.php?module=training&op=setspecialty");
		$specs=specialtysystem_getspecs();//debug($specs);
		if ($specs==array()) {
			output("Sorry, I have no registered specialties for you here...");
			break;
		}
		$active=specialtysystem_get("active");
		$options='';
		restore_buff_fields(); //tempstats out
		foreach ($specs as $key=>$data) {//debug($data);
			$name=translate_inline($data['spec_name']);
			if ($data['dragonkill_minimum_requirement']>$session['user']['dragonkills']) continue;
			if (((int)$data['dragonkill_minimum_requirement'])==-1) continue;
			if ($data['modulename']==$active) continue;
			if ($first) output_notl("`~~~~~~~~~~~~~`2`n`n");
			$first=true;
			$spec=$data['spec_colour'].translate_inline($data['spec_name'],"module-".$data['modulename']);
			output_notl("`b%s:`n`n",$spec);
			$available=true;
			if (isset($data['stat_requirements']) && $data['stat_requirements']!='') {
				//check if the stats are ok
				output("`4Minimum Requirements:`n");
				$unserialized=unserialize($data['stat_requirements']);
				if (!is_array($unserialized)) {
					output("None`n");
				} else {
					foreach ($unserialized as $stat=>$value) {
						$ok=($session['user'][$stat]>=$value?1:0);
						if ($ok) $k="`2";
							else $k="`\$";
						if (!$ok) $available=false;
						//deliberately translatable
						$stat_trans=translate_inline($stat,"stats_specialtysystem");
						output("%s%s (Minimum %s needed)`n",$k,$stat_trans,$value);
					}
				}
				output_notl("`n`n");				
			}
			if ($available) $options.="<option value='{$data['modulename']}'>$name</option>";
		}
		calculate_buff_fields();
		rawoutput("<select name='ssystem'>");
		rawoutput($options);
		rawoutput("</select>");
		$submit=translate_inline("Submit");
		rawoutput("<br><br><input type='submit' class='button' value='$submit'></form>");
		output("`n`n`lPS: You still retain the knowledge of your current specialty/specialties. You simply get new skillpoints in the new specialty you select here.");
		break;
	default:
		if (is_module_active('addimages')) output_notl("`c<IMG SRC=\"modules/shinobiimages/header-train.gif\">`c<BR>\n",true);
		output("`3You enter the vast training grounds you know about.`n`n");
		output("Many academy students and also higher ranked nins are training there to improve themselves or simply to be able to complete the respective exam to go even higher in rank.");
		$who=array("Kurenai", "Naruto","Shikamaru","Asuma","Chouji","Sakura","Kiba","Hinata","Neji");
		$rand=array_rand($who);
		$who=$who[$rand];
		output("`nThough many do not seem to notice you, `%%s`3 gives you a short glance and nods.",$who);
		output("`n`n`vWhat do you want to do?");
		training_nav();
		break;
	}
	page_footer();
}

function training_nav() {
	global $session;
	addnav("Actions");
	if (is_module_active("specialtysystem")) addnav("Switch your specialty","runmodule.php?module=training&op=specialty");
	if (has_buff('mount')) {
		if ($session['bufflist']['mount']['suspended']) {
			$sw=1;
			$action=translate_inline("Summon");
		} else {
			$sw=0;
			$action=translate_inline("Unsummon");
		}
		addnav(array("%s your mount",$action),"runmodule.php?module=training&op=mountsummon&action=$sw");
	}
}


?>