<?php
// translator ready
// addnews ready
// mail ready

function rockstack_getmoduleinfo(){
	$info = array(
		"name"=>"Akatsuchi's Rock Stacking",
		"version"=>"1.0",
		"author"=>"`LKurt Mills",
		"download"=>"",
		"category"=>"Village",
		"settings"=>array(
			"Rock Stacking Module Settings, title",
			"perday"=>"Tries at building per day, int|1",
			"cost"=>"How much to stack, int|50",
			"rockstackloc"=>"Village the Rock stack is in, location|Iwagakure", //Iwagakure
			"winner"=>"Who has the highest stack, int|0",
			"height"=>"How high is the tallest Stack, int|0",
		),
		"prefs"=>array(
			"Rock Stack User Prefs, title",
			"tries"=>"Times built today,int|0"
		),
	);
	return $info;
}

function rockstack_install(){
	module_addhook("village");
	module_addhook("newday");
	module_addhook("changesetting");
	return true;
}

function rockstack_uninstall(){
	return true;
}

function rockstack_dohook($hookname, $args){
	global $session;
	switch ($hookname){
	case "village":
		if ($session['user']['location'] == get_module_setting("rockstackloc")){
			tlschema($args['schemas']['marketnav']);
			addnav($args["marketnav"]);
			tlschema();
			addnav("Rock Stacking","runmodule.php?module=rockstack");
		}
		break;
	case "newday":
		set_module_pref("tries",0);
		break;
	case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("rockstackloc")) {
				set_module_setting("rockstackloc", $args['new']);
			}
		}
		break;
	}
	return $args;
}

function rockstack_run(){
	page_header("Rock Stacking");
	require_once("lib/villagenav.php");
	global $session;
	$cost=get_module_setting("cost");
	$perday=get_module_setting("perday");
	$tries=get_module_pref("tries");
	$op=httpget('op');
	if ($op==""){
		$winner=get_module_setting("winner");
		$height=get_module_setting("height");
		$sql = "SELECT name FROM ". db_prefix("accounts")." WHERE acctid='$winner'";
		$result = db_query_cached($sql, "snowmanwinner");
		$row = db_fetch_assoc($result);
		output("`TYou walk into a small flat area, covered in small stacks of rocks. Towering above the others is a giant tower, %s rocks high! A little sign at its base says tower was constructed by \"`&%s`T\".`n",$height,$row['name']);
		if ($tries<$perday){
			output("`TAs your gaze follows the high tower upwards, Akatsuchi approaches you.");
			output("`~\"Would you like to give it a try?\" `THe asks, tilting his head towards a nearby pile of rock.");
			output("As a tower at the corner of your eye starts to sway, you get the feeling Akatsuchi would like you to make a choice quickly.`n`n");
			if ($session['user']['gold']<$cost){
				output("`n`nIf only you had enough gold.");
				output("You shuffle back sadly as Akatsuchi goes off to save the poor soul about to be crushed by his falling rock tower.");
			}else{
				output("Akatsuchi's eyes shift towards a collection bucket, indictating this isn't a free event.");
				addnav(array("Stack Some Rocks (`^%s`0 gold)",$cost),
						"runmodule.php?module=rockstack&op=stack&height=1");
			}
		}else{
			output("`TAkatsuchi smiles as you approach, appearing to remember you from earlier, but moves off to help some other stackers.`n`n");
			output("As you turn to see the other stackers, you bump a small childs tower, causing it to sway wildly. Something tell you that you should get out of here quickly, before Akatsuchi notices.");
		}
		villagenav();
	}elseif ($op=="stack"){
		$height=httpget("height");
		if($height==1){
			$tries++;
			set_module_pref("tries",$tries);
			$session['user']['gold']-=$cost;
			debuglog("spent $cost gold to build a snowman.");
			output("`TOnce you drop some gold in the bucket, Akatsuchi leads you to a fresh pile of odd sized rocks, and even lays the first one down for you.");
			addnav("Stack Another Rock", "runmodule.php?module=rockstack&op=stack&height=2");
		} else {
			output("`TYou carefully place another rock on your tower, which sways slightly with the new additon.`n`n");
			$stable=e_rand(1,2);
			if($stable==1){
				output("The stack seems to settle for now, the swaying stopped. An impressive %s rocks high.",$height);
				addnav("Stop Stacking","runmodule.php?module=rockstack&op=stop&height=$height");
				$height++;				
				addnav("Stack Another Rock", "runmodule.php?module=rockstack&op=stack&height=$height");			
			} else {
				output("You step back, waiting for the tower to stop moving, when *CRASH* the whole stack comes down around you.`n`n");
				$result=e_rand(1,10);
				$hploss=round($session['user']['hitpoints']/10);
				if ($result==1 && $session['user']['hitpoints']>$hploss){
					output("One of the stones comes tumbling down on your toe, making it throb violently.`n");
					$session['user']['hitpoints']-=$hploss;
					output("`^You lose `\$%s `^hitpoints.",$hploss);
				} else {
					output("Akatsuchi gently pats you on the back, telling you do better next time.");
				}
				villagenav();
			}
		}
	} elseif($op='stop'){
		$height=httpget("height");
		output("`TYou step back, proudly admiring your creation. Akatsuchi himself joins you, counting the number of rocks in the great tower.`n`n");
		villagenav();
		if($height>get_module_setting('height')){
			output("After a lot of grumbling, finger counting, and a bit of head scratching, Akatsuchi proudly tells you, you have built the highest rock stack!`n");
			output("Grabbing a nearby rock, he smashes it open, revealing a glittering gem, which he hands over to you as a reward.");
			$session['user']['gems']++;
			set_module_setting('winner',$session['user']['acctid']);
			set_module_setting('height',$height);
		} else {
			output("Akatsuchi commends you on your fine effort this day!`n`n");
			$result=e_rand(1,5);
			switch($result){
				case 1:
					$reward=3*$cost;
					$session['user']['gold']+=$reward;
					output("He dips his hand into the collection bucket, and drops, %s gold into your hand as a nice reward.",$reward);
				break;
				case 2:
					$session['user']['gold']+=$cost;
					output("Reaching into his pocket, Akatsuchi hands you back your money from his own pocket.");
				break;
				case 3:
				case 4:
					output("You stare expectantly at Akatsuchi, but he just smiles and goes off to help another shinobi, who has walked over to a pile of rocks.");
				break;
				case 5:
					output("He pats you on the back, and tells you what a great job you've done. You feel Proud!");
					apply_buff('rockstackwin',array("name"=>"`TPride","rounds"=>10, "atkmod"=>1.02,"defmod"=>1.02, "schema"=>"module-rockstack"));
				break;
			}
		}
	}
	page_footer();
}
?>
