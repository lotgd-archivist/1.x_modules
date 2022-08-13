<?php

function kg_reset_getmoduleinfo(){
	$info = array(
		"name"=>"Kekkei Genkai Reset",
		"author"=>"`LShinobiIceSlayer`~ based on work by `4Oliver Brendel`0",
		"version"=>"1.0",
		"download"=>"",
		"category"=>"Lodge",
		"settings"=>array(
			"Kekkei Genkai Reset Module Settings,title",
			"initialpoints"=>"How many donator points needed to get first reset?,int|5000",
			"extrapoints"=>"How many additional donator points needed for subsequent resets?,int|2000",
			"discount"=>"How many days are 'free' for a subsequent reset,int|540",
			"givenewday"=>"Give a newday upon reset?,bool|0",
		),
		"prefs"=>array(
			"Kekkei Genkai Reset User Preferences,title",
			"timespurchased"=>"How times has the reset been bought?,int|0",
			"dayssincelastchange"=>"Newdays (runonce) since last change?,int|0",
		),
		"requires"=>array(
			"circulum"=>"1.0|Circulum Vitae by `2Oliver Brendel",
			),
	);
	return $info;
}

function kg_reset_install(){
	module_addhook("lodge");
	module_addhook("pointsdesc");
	module_addhook("newday-runonce");
	return true;
}
function kg_reset_uninstall(){
	return true;
}

function kg_reset_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "pointsdesc":
		$args['count']++;
		$format = $args['format'];
		//Change this text!
		$str = translate("`^The ability to change your Kekkei Genkai resets. Costs %s points the first time, and an extra %s points every time thereafter.`0");
		$str = sprintf($str, get_module_setting("initialpoints"),
				get_module_setting("extrapoints"));
		output($format, $str, true);
		break;
	case "lodge":
		//Don't worry about showing to those with no resets.
		if (get_module_pref("circuli","circulum")==0) break;
		$times = get_module_pref("timespurchased");
		$discount = min(get_module_pref("dayssincelastchange"), get_module_setting("discount")*$times) / max(1,get_module_setting("discount")*$times);
		$discount = round($discount,1); //full 10%
		$discount = min(1,$discount); // max 100%
		$totalcost = get_module_setting("initialpoints") + (1-$discount) * $times * get_module_setting("extrapoints");
		$cost = ceil($totalcost); // no fractions 
		$available = $session['user']['donation']-$session['user']['donationspent'];
		addnav("Resets");
		if ($cost<=$available) {
				addnav(array("Kekkei Genkai Reset (%s points)", $cost),"runmodule.php?module=kg_reset&op=enter");
			} else {
				addnav(array("Kekkei Genkai Reset (%s points)", $cost),"");
			}
		break;
	case "newday-runonce":
		$update="UPDATE ".db_prefix('module_userprefs')."
				SET value = value + 1 
				WHERE modulename = 'kg_reset' 
				AND setting = 'dayssincelastchange';";
		db_query($update); 
		$select="SELECT acctid
				FROM ".db_prefix('accounts')."
				WHERE acctid not in (SELECT userid
									FROM module_userprefs
									WHERE modulename = 'savedays' 
									AND setting = 'daysmissed')";
		$result=db_query($select);
		$end=db_num_rows($result);
		if($end>0){
			$insert="INSERT 
					INTO ".db_prefix('module_userprefs')." (modulename, setting, userid, value)
					VALUES ";			
			while ($row=db_fetch_assoc($result)) {
				$userid=$row['acctid'];
				$insert.="('savedays','daysmissed',$userid,1),";
			}
			$insert = substr($insert,0,strlen($insert)-1);
			db_query($insert);
		}
		break;
	}
	return $args;
}

function kg_reset_run(){
	global $session;
	$op = httpget("op");
	$resets = get_module_pref("circuli","circulum");
	
	//Borrowed this from you. 
	$args=modulehook('circulum-items',array());
	$kekkei=array();
	foreach ($args as $kg) {
		if (isset($kg['category']) && $kg['category']!='') {
			$kekkei[$kg['category']][$kg['modulename']]=$kg;
		} else {
			$kekkei['No Category'][$kg['modulename']]=$kg;
		}
	}	
	foreach (array_keys($kekkei) as $category) {
		ksort($kekkei[$category]);
	}
	$labels = array();
			foreach ($kekkei as $category => $cat) {
				$labels[]=$category.",title";
				foreach ($cat as $kg){
					$labels[$kg["modulename"]]=$kg["nav"];
				}
			}
	$times = get_module_pref("timespurchased");
	$discount = min(get_module_pref("dayssincelastchange"), get_module_setting("discount")*$times) / max(1,get_module_setting("discount")*$times);
	$discount = round($discount,1); //full 10%
	$discount = min(1,$discount); // max 100%
	$totalcost = get_module_setting("initialpoints") + (1-$discount) * $times * get_module_setting("extrapoints");
	$cost = ceil($totalcost); // no fractions
	
	page_header("Mission HQ");
	addnav("Navigation");
	switch ($op) {
		case "enter":
			output("`7`bKekkei Genkai Reset`b`0`n`n");
			output("`^Because you have earned enough points, you may switch the Kekkei Genkai points you have reset in.`n`n");
			output("`\$This is a re-distribution, so be careful what you choose. It cannot be made un-done.`n`n");
			output("By doing so, you will lose the abilities of any Kekkei Genkai you give up, and gain those of one you choice to replace them with.`0");
			output("`n`n`^Here is a list of the current KG you have inherited in your blood(`\$%s points in total`^):`n`n",get_module_pref("circuli","circulum"));
			rawoutput("<table>");
			foreach($kekkei as $category=>$entries) {
				$count=0;
				rawoutput("<tr class='trhead'><td colspan=2>");
				output_notl("%s",$category);
				rawoutput("</td></tr>");
				foreach ($entries as $kg) {
				$count=!$count;
				$class=($count?'light':'dark');
				rawoutput("<tr class='tr$class'><td>");
				output_notl("%s",$kg["nav"]);
				rawoutput("</td><td>");
				output_notl("%s",get_module_pref("stack",$kg["modulename"]));
				$stacks +=get_module_pref("stack",$kg["modulename"]);
				rawoutput("</tr>");
				}
			}
			rawoutput("</table>");
			if (get_module_pref('circuli','circulum')!=$stacks) {
				//we have an error
				output("`\$ERROR: Your stacks don't add up. Please petition for help.");
				rawoutput("</table>");
				addnav("H?Return to the HQ","lodge.php");
				break;
				
			}
			output_notl("`n");
			$class='dark';
			output("The price calculates like:`n`n");
			rawoutput("<table>");
			rawoutput("<tr class='trhead'><td colspan=2>");
			output("Calculation Costs (time-depend)");
			rawoutput("</td></tr>");
			$class=($class=='dark'?'light':'dark');
			rawoutput("<tr class='tr$class'><td>"); 
			output("Base Cost");
			rawoutput("</td><td>");
			output_notl("%s",get_module_setting("initialpoints"));
			rawoutput("</td></tr>");
			$class=($class=='dark'?'light':'dark');
			rawoutput("<tr class='tr$class'><td>"); 
			output("Times * Extra Cost (%s * %s points)",$times,get_module_setting('extrapoints'));
			rawoutput("</td><td>");
			output_notl("%s",$times*get_module_setting("extrapoints"));
			rawoutput("</td></tr>");
			$class=($class=='dark'?'light':'dark');
			rawoutput("<tr class='tr$class'><td>"); 
			output("Discount (For %s server newdays after reset 1)",get_module_pref('dayssincelastchange'));
			rawoutput("</td><td>");
			output_notl("%s points Deduction (equals %s%%)",$discount * $times * get_module_setting("extrapoints"),$discount*100);
			rawoutput("</td></tr>");
			$class=($class=='dark'?'light':'dark');
			rawoutput("<tr class='tr$class'><td colspan=2>"); 
			output_notl("--------------------------------------------------");
			rawoutput("</td></tr>");
			$class=($class=='dark'?'light':'dark');
			rawoutput("<tr class='tr$class'><td>"); 
			output("Total Cost");
			rawoutput("</td><td>");
			output_notl("%s + %s - %s == %s (not rounded: %s)",get_module_setting('initialpoints'),$times * get_module_setting("extrapoints"),$discount * $times * get_module_setting("extrapoints"),$cost,$totalcost);
			rawoutput("</td></tr>");
			rawoutput("</table>");
	
			output("`2Note: `n`nYou have `\$%s`2 days since reset 1. You get for 1 reset a bonus of 100%% deduction for that reset every `\$%s`2 days.",get_module_pref('dayssincelastchange'),get_module_setting('discount'));

			addnav("H?Return to the HQ","lodge.php");
			addnav("Actions");
			addnav(array("Reset your Kekkei Genkai (%s points)",$cost),"runmodule.php?module=kg_reset&op=switch");
			break;
		case "switch":			
			addnav("H?Return to the HQ","lodge.php");
			addnav("Actions");
			require("modules/kg_reset/switch.php");
			break;
		case "confirm":			
			addnav("H?Return to the HQ","lodge.php");
			addnav("Actions");
			require("modules/kg_reset/confirm.php");
			addnav("Start Again","runmodule.php?module=kg_reset&op=switch");
			break;
		case "finish":
			require("modules/kg_reset/finish.php");
			break;
	}
	
	
	page_footer();
}
?>
