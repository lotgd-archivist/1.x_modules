<?php

function corona_virus_getmoduleinfo(){
	$info = array(
			"name"=>"Weekend Events",
			"author"=>"Oliver Brendel",
			"version"=>"1.0",
			"category"=>"Events",
			"download"=>"",
			"settings"=>array(
				"Settings,title",
				"pvpbonus"=>"PVP bonus fights,range,1,20,1|8",
				"atkbonus"=>"atk bonus in percent,range,1,100,1|15",
				"turnbonus"=>"Turn bonus,range,1,50,1|30",
				"goldbonus"=>"Gold bonus per level,range,1,500,5|500",
				"maxhp_pc"=>"Max HP Heal from Weekend Warrior in percent,range,0,100,2|15",
				)
		     );
	return $info;
}

function corona_virus_check() {
	$month_start = date("N",strtotime(date("Y-m-1"))); // get first weekday of the month as base calc
	$day_of_week = date("N");
	//if 5,6,7 then it's the weekend!
	if ($day_of_week < 5) {
		//return $day_of_week;
		return 2; //just forest for now
	} else {
		return false;
	}
}


function corona_virus_install(){
	module_addhook("newday");
	module_addhook("village-desc");
	return true;
}

function corona_virus_uninstall(){
	return true;
}

function corona_virus_dohook($hookname,$args){
	global $session;
	$event_up = corona_virus_check();
	switch($hookname){
		case "newday":
			//remove the last weekend warrior
			$remove=array("corona-warrior");
			strip_companion($remove);
			if ($event_up!==false) {
				output("`n`1Corona Support!`n");
				switch ($event_up) {
					case 1: //first weekend
						output("`4You get `!PVP support!`4`nYou get `\$%s more PVP fights!`4`n",get_module_setting('pvpbonus'));
						$session['user']['playerfights']+=get_module_setting('pvpbonus');
						break;
					case 2: //second weekend
						output("`4You get `tforest support!`4`nYou get `\$%s more forest fights!`4`n",get_module_setting('turnbonus'));
						$session['user']['turns']+=get_module_setting('turnbonus');
						break;
					case 3: //third weekend
						output("`4You get `xgolden support!`4`nYou receive `\$%s gold!`4`n",$session['user']['level']*get_module_setting('goldbonus'));
						$session['user']['gold']+=$session['user']['level']*get_module_setting('goldbonus');
						break;
					case 4: //fourth weekend
						output("`4You get `vfierce support!`4`nYou get `\$%s %% more attack power!`4`n",get_module_setting('atkbonus'));
						apply_buff('weekend_buff',
								array(
									"name"=>"`vFierce support!",
									"rounds"=>-1,
									"wearoff"=>"You feel a bit weaker after the weekend.",
									"atkmod"=>1+(get_module_setting('atkbonus')/100),
									"schema"=>"module-corona_virus",
								     )
							  );
						break;
					case 5: //fifth weekend
						output("`4You get `vBroSis support!`4`nYou get a helpful royal nin for a while!`4`n");
						$name = sprintf_translate("`@Weekend Warrior %s",$session['user']['sex']==SEX_FEMALE?"Princess":"Prince");
						apply_companion("corona-warrior" , array(
									"name"=>$name,
									"hitpoints"=>max($session['user']['maxhitpoints']/2,10),
									"maxhitpoints"=>max($session['user']['maxhitpoints']/2,10),
									"attack"=>floor($session['user']['attack']*2),
									"defense"=>floor($session['user']['defense']),
									"jointext"=>"\"I hope you're having a nice weekend!\"",
									"companionactive"=>1,
									"dyingtext"=>"`@\"Urgent matters call... you have to go on alone...\"... and the royalty vanishes.",
									"cannotbehealed"=>false,
									"expireafterfight"=>0,
									"ignorelimit"=>true,
									"abilities"=>array(
										"fight"=>1,
										"heal"=>max(5,floor($session['user']['maxhitpoints']/get_module_setting('maxhp_pc'))),
										"defend"=>1,
										),							
									), true);			
						break;
				}
			}
			break;
		case "village-desc":
			if ($event_up!==false) {
				output("`n`c`1Corona Support!`n");
				switch ($event_up) {
					case 1: //first weekend
						output("`4You get `!PVP support!`0");
						break;
					case 2: //second weekend
						output("`4You get `tforest support!`0");
						break;
					case 3: //third weekend
						output("`4You get `xgolden support!`0");
						break;
					case 4: //fourth weekend
						output("`4You get `vfierce support!`0");
						break;
					case 4: //fifth weekend
						output("`4You get `\$BroSis support!`0");
						break;
				}
				output("`c`n");
			}
			break;
	}	

	return $args;
}

function corona_virus_runevent($type){
}

function corona_virus_run(){
}

?>
