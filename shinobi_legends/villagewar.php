<?php


function villagewar_getmoduleinfo(){
	$info = array(
		"name"=>"War",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"PVP",
		"download"=>"",
		"settings"=>array(
				"Small Village War - Preferences, title",
				"killcounter"=>"Killcounter Serialized,viewonly",
				),

	);
	return $info;
}

function villagewar_install(){
	//module_addhook_priority("pvpmodifytargets", 100);
	//module_addhook("travel");
	module_addhook("village");
	//module_addhook("pvpwin");
	return true;
}

function villagewar_uninstall(){
	return true;
}


function villagewar_dohook($hookname,$args){
	global $session;
	//if (($session['user']['acctid']!=7 || $session['user']['acctid']!=1)  && $hookname!='pvpwin') return $args;
	$party=array(
		"Sand"=>"Wind Country",
		"Leaf"=>"Fire Country"
		);
	$defparty=array(
		"Mist"=>"Water Country"
		);
	$attacker=array_keys($party);
	$defender=array_keys($defparty);
	$attackercities=array_values($party);
	$defendercities=array_values($defparty);
	$u=&$session['user'];
	switch ($hookname) {
		case "pvpmodifytargets":
			$keys=array_keys($args);
			$noattack=array();
			if (!in_array($u['race'],$attacker) && !in_array($u['race'],$defender)) break;
			if (in_array($u['race'],$attacker)) $uattacker=1;
				else $uattacker=0; //defender
			foreach ($keys as $key) {
				//if ($u['location']!=$args[$key]['location']) continue;
				$p_race=$args[$key]['race'];
				if (!in_array($p_race,$attacker) && !in_array($p_race,$defender)) continue; //involved
				if ($uattacker && in_array($p_race,$attacker)) {
					//both attackers
					//$noattack[]=$args[$key]['name']."(".translate_inline($p_race,"races").")";
					$args[$key]['invalid']="`!Ally!";
				} elseif (!$uattacker && in_array($p_race,$defender)) {
					//both defenders
					//$noattack[]=$args[$key]['name']."(".translate_inline($args[$key]['race'],"races").")";
					$args[$key]['invalid']="Ally!";
				} else {
					//in fight, but not the race of the pvp target --> ENEMY
					$args[$key]['name'].=translate_inline("`\$(ENEMY!)");
					continue;
				}
			}
			//if ($noattack!=array()) output("The following nin are not attackable: %s",implode(",",$noattack));
			break;
		case "travel":
		/*	if (in_array($u['race'],$attacker)) {
				//enemies
				//he cannot enter
				foreach ($defendercities as $city) {
					blocknav("runmodule.php?module=cities&city=".urlencode($city),true);
					blocknav("runmodule.php?op=travel&module=cities&city=".($city),true);
					output("`c`b`4You cannot enter `\$%s`4 due to the current war and your allegiance to the opposing faction!`b`c`n`n",$city);
				}
			} elseif (in_array($u['race'],$defender)) {
				//enemies
				//he cannot enter
				foreach ($attackercities as $city) {
					blocknav("runmodule.php?module=cities&city=".urlencode($city),true);
					blocknav("runmodule.php?op=travel&module=cities&city=".($city),true);
					output("`c`b`4You cannot enter `\$%s`4 due to the current war and your allegiance to the opposing faction!`b`c`n`n",$city);
				}			
			}
			
*/
			break;
		case "village":
			//always
			output("`n`n`c`gCurrent Warcount:`n`n");
			$data=get_module_setting('killcounter');
			if ($data!='') $ar=unserialize($data);
				else $ar=array();
			$attackercount=array();
			foreach ($attacker as $race) {
				if (isset($ar[$race])) $attackercount[$race]=$ar[$race];
					else $attackercount[$race]=0;
			}
			$defendercount=array();
			foreach ($defender as $race) {
				if (isset($ar[$race])) $defendercount[$race]=$ar[$race];
					else $defendercount[$race]=0;
			}
			rawoutput("<center><table><tr><td>");
			output("`tFaction Wind Kills: %s",array_sum($attackercount));
			rawoutput("</td><td>");
			output("`!VS");
			rawoutput("</td><td>");
			output("`1Faction Mist Kills: %s",array_sum($defendercount));
			rawoutput("</td></tr><tr><td>");
			foreach ($attackercount as $race=>$kills) {
				output("`\$%s`4 has `^%s`4 kills!`n",$race,$kills);
			}
			rawoutput("</td><td></td><td>");
			foreach ($defendercount as $race=>$kills) {
				output("`!%s`4 has `^%s`4 kills!`n",$race,$kills);
			}
			rawoutput("</td></tr></table></center>");
			output_notl("`c`n`n");
			if (in_array($u['location'],$attackercities) || in_array($u['location'],$defendercities)) {
				//in war city
				//output("`\$This village is at war! `4You see ANBU everywhere patrolling the streets and villagers are busy carrying their goods to a safer place.`n`n");	
			}
			break;
		case "pvpwin":
			$badguy=$args['badguy'];
			$score=0;
			if ($badguy['race']!=$u['race']) {
				if (in_array($badguy['race'],$attacker) && in_array($u['race'],$defender)) {
					//hit
					$score=1;
				} elseif (in_array($badguy['race'],$defender) && in_array($u['race'],$attacker)) {
					//hit
					$score=1;
				}
				//war counter
				if ($score==0) break; //no count
				$k=get_module_setting('killcounter','villagewar');
				if ($k!='') $array=unserialize($k);
					else $array=array();
				$array[$u['race']]+=1;
				set_module_setting("killcounter",serialize($array),'villagewar');
			}
	}
	return $args;
}

function villagewar_run(){
}


?>
