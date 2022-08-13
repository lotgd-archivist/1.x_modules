<?php

function halloween_xp_getmoduleinfo(){
	$info = array(
		"name"=>"Halloween XP (timelocked)",
		"version"=>"1.0",
		"author"=>"Oliver Brendel",
		"category"=>"Holidays|Halloween",
		"download"=>"",
		"settings"=>array(
			"XP bonus, title",
			"xpmod"=>"Percentage of the xp bonus,range,0,100,5|10",
			"partystart"=>"Start Date MM-DD,string|10-29",
			"partyend"=>"End Date MM-DD,string|11-02",
			"Buff Settings,title",
						"buffname"=>"What is the buff name?,text|Happy Halloween",
						"buffcolor"=>"What is the buff name with color?,text|`\$H`)appy `\$H`)appy Halloween",
						"buffwearoff"=>"Text for the wearoff message of the buff.,text|Halloween is over!",
		)
	);
	return $info;
}

function halloween_xp_install(){
	module_addhook("forest-victory-xp");
	module_addhook("newday");
	return true;
}

function halloween_xp_uninstall(){
	return true;
}

function halloween_xp_dohook($hookname,$args){
	global $session;
	$start = strtotime(date("Y")."-".get_module_setting("partystart")." 00:00:00");
	$end = strtotime(date("Y")."-".get_module_setting("partyend")." 00:00:00");
	$now = time();
	if ($start <= $now && $end >= $now) {
		//we are fine
	} else {
		//we are not in time 
		return $args;
	}
	$xpmod=get_module_setting("xpmod");
	switch ($hookname) {
	case "forest-victory-xp":
		if ($args['experience']>0 ) {
			$bonus = round($args['experience']*($xpmod/100))+1;
			output("`\$H`)appy `\$H`)appy Halloween! Base: %s, XP Bonus: %s points!`n`0",$args['experience'],$bonus);
			$args['experience']+=$bonus;
		}
		break;
	case "newday":
		case "newday":
	$buffname = get_module_setting('buffname');
				$buffturns = -1;
				$buffcolor = get_module_setting('buffcolor');
				$buffwearoff = get_module_setting('buffwearoff');
	apply_buff($buffname,
					array(
						"name"=> $buffcolor,
						"rounds"=> $buffturns,
						"wearoff"=> $buffwearoff,
						"schema"=>"modules-donation",
						)
					);
					break;
	}
	return $args;
}

function halloween_xp_run(){
}

