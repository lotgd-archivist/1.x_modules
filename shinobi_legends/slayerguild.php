<?php
function slayerguild_getmoduleinfo(){
	$info = array(
		"name"=>"Dark Slayer's Guild",
		"author"=>"Chris Vorndran",
		"version"=>"1.43",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/users/Sichae/slayerguild.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"User can collect the souls of creatures, in order to trade them in and be booned.",
		"settings"=>array(
			"Dark Slayer's Guild General Settings,title",
			"hploss"=>"How much HP is lost if innocent is killed,int|1",
			"maxhold"=>"Max souls that can be held by a person,int|30",
			"mult"=>"Multiply by souls to produce gold take from bank at Newday,int|100",
			"Soul Pricings,title",
			"special"=>"Souls to increase specialty uses,int|3",
			"gems"=>"Souls to forge a gem,int|7",
			"atkdef"=>"Souls to increase Attack/Defense/Hitpoints,int|20",
			"hpgain"=>"How much HP is gained at trade-in,int|5",
			"Player Access, title",
			"mindk"=>"How many DKs do you need before the guild is available?,int|0",
			"cost"=>"How many points do you need before the guild is available?,int|0",
			"Dark Slayer's Guild Location,title",
			"slayerloc"=>"Where does Slayer's Guild appear,location|".getsetting("villagename", LOCATION_FIELDS)
		),
		"user_prefs"=>array(
		"Akatsuki Preferences,title",
		"user_showsoul"=>"Do you wish to see how many Souls you have,bool|1",
		),
		"prefs"=>array(
		"Dark Slayer's Guild Preferences,title",
			"manygems"=>"How many gems did Leon take when joining,int|0",
			"holding"=>"Souls currently held by person,int|0",
			"apply"=>"Has person applied to the Slayer's guild,bool|0",
			"atk"=>"Has user built up attack?,bool|0",
			"def"=>"Has user built up defense?,bool|0",
			"hitpoint"=>"Has user built up HP?,bool|0",
			"user_showsoul"=>"Do you wish to see how many Souls you have,bool|1",
		)
		);
	return $info;
}
function slayerguild_install(){
	module_addhook("village");
	module_addhook("pointsdesc");
//	module_addhook("battle-victory");
	module_addhook("pvpwin");
	module_addhook("bioinfo");
	module_addhook("newday");
//	module_addhook("charstats");
	module_addhook("gypsy");
	module_addhook("dragonkilltext");
	return true;
}
function slayerguild_uninstall(){
	return true;
}
function slayerguild_dohook($hookname,$args){
	global $session;
	$holding = get_module_pref("holding");
	$mult = get_module_setting("mult");
	$maxhold = get_module_setting("maxhold");
	$cost = get_module_setting("cost");
	$apply = get_module_pref("apply");
	switch ($hookname){
	case "gypsy":
		if (get_module_pref("user_showsoul")){
			if (get_module_pref("apply","slayerguild")){
			output("`n`n`c`%Akatsuki`c`n`5Oh, and by the way, I sense you have %s souls stored.`n`n",get_module_pref("holding"));
			}else{
			}
		}
		
		break;
	case "charstats":
		if (get_module_pref("user_showsoul")){
				$title = translate_inline("Personal Info");
				$name = translate_inline("Souls");
			if (!get_module_pref("apply","slayerguild")){
				$amnt = translate_inline("Not a Member");
			}else{
				$amnt = get_module_pref("holding");
			}
				setcharstat($title,$name,$amnt);
		}
		break;
	case "pointsdesc":
      if ($cost > 0){
         $args['count']++;
         $format = $args['format'];
         $str = translate("The Dark Slayers Guild is available upon reaching %s Dragon Kills and %s points.");
         $str = sprintf($str, get_module_setting("mindk"),$cost);
         output($format, $str, true);
      }
		break;
	case "village":
        if ($session['user']['acctid']!=9340)
	        if ($session['user']['dragonkills'] < get_module_setting("mindk")) {
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav("Dark Slayer's Guild","");
			break;
		}
        if ($session['user']['location'] == get_module_setting("slayerloc")){
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav("Dark Slayer's Guild","runmodule.php?module=slayerguild&op=enter");
		}
        break;
	case "pvpwin":
		if (get_module_pref("apply","slayerguild")){
			slayerguild_endpvp($args);
		}
	case "battle-victory":
		require_once("modules/slayerguild/endbattle.php");
		if ($args['type']=='forest'){
			slayerguild_endbattle();
		}
		break;
	case "dragonkilltext":
		set_module_pref("atk",0);
		set_module_pref("def",0);
		set_module_pref("hitpoint",0);
		break;
	case "newday":
		if ($apply==1){
			if ($holding>=1){
				$take = $holding*$mult;
				if ($take >= $session['user']['goldinbank']) {
					output("`n<h3>`)The freakishness of the souls has caused `^%s `)gold in damages to the town, which will be taken from your bank account.</h3>`n",$take,true);
					$session['user']['goldinbank']-=$take;
					debuglog("took $take as payment for slayers guild");
				} else {
					output("`n<h3>`)The freakishness of the souls has caused `^%s `)gold in damages to the town, but you are to poor to pay for all... so your bank account will be reduced to what's left of it.</h3>`n",$take,true);
					if ($session['user']['goldinbank']>0) $session['user']['goldinbank']=0;
				}
			}
		}
		break;
	case "bioinfo":
		if (get_module_pref("apply","slayerguild",$target['acctid'])){
			output_notl("`n");
			output("`&%s `7is a member of the `)Dark Slayer's Guild.`n",$args['name']);
		}
		break;
	}
	return $args;
}
function slayerguild_run(){
	global $session;
	require("modules/slayerguild/run.php");
	addnav("Leave");
	villagenav();
	page_footer();
}

function slayerguild_endpvp($args) {
	global $session,$badguy;
	$hploss = get_module_setting("hploss");
	$apply = get_module_pref("apply");
	$holding = get_module_pref("holding");
	$maxhold = get_module_setting("maxhold");
	if (is_module_active('alignment')) {
		$align=get_module_pref('alignment','alignment',$args['badguy']['acctid']);
		$goodalign = get_module_setting('goodalign','alignment');
		$evilalign = get_module_setting('evilalign','alignment');
		if ($align>=$goodalign && $holding<$maxhold) {
			output("`n`b`)You have rended their soul!`b`n`n");
			increment_module_pref("holding",1);
		} elseif ($align<=$evilalign) {
			output("`n`b`&You have spilt the blood of a fellow evil shinobi!`b`n`n");
			$session['user']['hitpoints']-=$hploss;
			if ($session['user']['hitpoints']<=$hploss){
				debuglog("died from spilling the blood of an shinobi");
				$session['user']['hitpoints']=0;
				$session['user']['alive']=false;
				redirect("runmodule.php?module=slayerguild&op=dead&op2=forest");
			}
		}
	}
	return;
}


?>
