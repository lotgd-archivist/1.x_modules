<?php
/*
Meet Ero-Sennin in the woods...

you find him peeping at some bathing girls...

v1.01 minor fixes
v1.02 fix in the gain of attackpoints (forgot to add)
v1.0.3 added hook

*/

function erosennin_getmoduleinfo() {
	$info = array(
		"name"=>"Ero Sennin - The Perverted Hermit",
		"author"=>"`2Oliver Brendel",
		"version"=>"1.02",
		"category"=>"Forest Specials",
		"download"=>"http://lotgd-downloads.com",
		"settings"=>array(
			"Ero Sennin - Preferences, title",
			"Meet him and maybe the frog boss,note",
			"name"=>"Name (coloured) of Ero-Sennin,text|`QEro-`gSennin",
			"charme"=>"Charm value for female players to get stalked,int|30",
			"experienceloss"=>"Percentage: How many experience is lost/won after a fight,floatrange,1,100,1|10",
			),
		"prefs"=>array(
			"favour"=>"Favours with Ero-Sennin,int|0",
			),
	);
	return $info;
}
function erosennin_install() {
	module_addeventhook("forest", "return 100;");
	return true;
}

function erosennin_uninstall() {
	return true;
}

function erosennin_dohook($hookname,$args) {
	return $args;
}

function erosennin_runevent($type,$link) {
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:erosennin";
	$op = httpget('op');
	$erosennin=get_module_setting("name");
	$charmmax=get_module_setting("charme");
	switch ($op) {
	case "":
		output("`3You walk along a peaceful road... after you walked a few minutes, you see a little bath house built near a hot spring right beside the road.");
		output(" You decide to get a bit closer... since it's on your way though.");
		$adj=($session['user']['sex']?translate_inline("disgusting"):translate_inline("interesting"));
		output("`n`nOh! That's %s... an old man sits right at a wooden fence and peeks through a hole!",$adj);
		output("`n`nWhat do you want to do?");
		addnav("Call To Order",$link."op=disturb");
		addnav("Peek Together",$link."op=peek");
		modulehook("erosennin_favours",array("favour"=>get_module_pref('favour')));
		addnav("Walk away",$link."op=walk");
		break;
	case "peek":
		$rand=e_rand(1,2);
		require_once("modules/addimages/addimages_func.php");
		addimage("erosennin/render/0.jpg");
		//provide a hook for more options, like rasengan, if ero-sennin is pleased
		//end
		output("`3You ask silently if you can take a look too... the old man realizes your presence and takes a look at you.`n`n");
		output("'`QShhh... get your own hole! I am gathering data right now!`3'");
		if ($session['user']['sex'] && $charmmax<$session['user']['charm']) {
			increment_module_pref("favour",1);
			output("`n`nHe takes a `\$very`3 good look at you... and starts to drool.");
			output("'`QOh... what ripe fruits you have brought with you... you have some awesome things...`3'");
			output(" You feel somehow ill to see that old geezer gaze at you.");
			output("`n`nYou start to run away, but he is after you... and won't leave your trace for a while.");
			apply_buff('senninpeek1',
				array(
					"name"=>"`QEro-Sennin",
					"rounds"=>100,
					"wearoff"=>"You seem to have lost him. Finally.",
					"atkmod"=>0.8,
					"defmod"=>0.9,
					"minioncount"=>1,
					"survivenewday"=>1, //he keeps following :D
					"roundmsg"=>"`)'`QWhooow, what a nice rear! Show it to me, baby!`)'",
					"schema"=>"module-erosennin",
					));
					$session['user']['specialinc'] = "";
			forest(true);
			}
		output(" You take a short glance through the hole, but sadly you can't cling long enough on it to really see something, as the old man politely shifts you to the other side...`n`n`4You have to take a more... daring... approach.`n`n`gBeing a shinobi, you are trained in stealth, and can hide even in broad daylight...`n`n");
		addnav("Peek",$link."op=peeking&s=1");
		increment_module_pref("favour",1);
		break;
	case "peeking":
		$stage=httpget('s');
		$success=(e_rand(0,50)<=($session['user']['dexterity']+$session['user']['wisdom']+$session['user']['intelligence'])?1:0);
		if ($success=1) {
			$sucess=e_rand(0,20);
			//if you would automatically succeed, make a small failure possible
		}
		require_once("modules/addimages/addimages_func.php");
		if ($success>=1) {
			output("Alright! One more clear... you take a good look...`n`n");
			addimage("erosennin/".$stage.".jpg");
			if ($stage<=7) {
				output("`n`n`gDo you want to sneak into a better position?");
				addnav("Peek",$link."op=peeking&s=".($stage+1));
				addnav("Chicken Out",$link."op=chicken");
			}	else {
				output("`\$`bAlright!`b`j You have seen enough, it's time to take your leave...");
				output("You are amazed... nice bodies... freshly riped... you feel `%energized`3!`n`n");
				apply_buff('senninpeek2',
				array(
					"name"=>"`QEro-Sennin Peek",
					"rounds"=>30,
					"wearoff"=>"Your memory fades away.",
					"atkmod"=>1.2,
					"defmod"=>1.1,
					"minioncount"=>1,
					"roundmsg"=>"You remember the nice female bodies!",
					"schema"=>"module-erosennin",
					));
				if (get_module_pref('favour')>100) {
					output("`lAah, what the heck, one last look...`n`n");
					addimage("erosennin/mm.jpg");
				}
				$gendercall=(!$session['user']['sex']?translate_inline("boy"):translate_inline("cutie"));
				if (e_rand(1,3)==1 && $randomchance<>3)	{
					increment_module_pref("favour",-1);
					output("'`QHey, %s, I like your style. I will teach you a secret to let you gain some offensive power.`3'",$gendercall);
					output("`n`nYou ask him: '`@And what is your name?`3'... and it takes a few moments...");
					output(" then he says: '`QThank you for asking! I am the `!Gama-Sennin`Q from the Myouboku Mountain!`3'`n`n");
					output("You ponder about him... and realize you have heard of him before: '`@You are no Gama-Sennin (frog hermit)! You are the legendary %s`@!!!'`3",$erosennin);
					output("`n`nAfter a few hours of argument, you leave the place with some new secrets in your brain.");
					output("`n`nYou `^gain`3 `$ two `3temporary attackpoints! (will vanish after the DK)");
					$session['user']['attack']+=2;
				}
				addnav("Leave",$link."op=leave");
			}
		} else {
			//ouch
			increment_module_pref("favour",-1);
			output("Oh my! You little oaf! You got too greedy!");
			output("The ladies are now a bit angry... and the old man is gone!");
			addimage("modules/erosennin/kill.jpg");
			output("`n`n`$ You are beaten to a pulp by the bathing ladies!`n`n");
			addnews("%s`^ was beaten to a pulp for peeking by half-naked ladies!",$session['user']['name']);
			$session['user']['hitpoints']=e_rand(1,$session['user']['hitpoints']/2);
			$session['user']['specialinc'] = "";
			}
		break;
	case "leave":
			output("You are finished here...`n`n");
			$session['user']['specialinc'] = "";
		break;
	case "chicken":
			output("You chicken out... and silently sneak away...`n`n");
			$session['user']['specialinc'] = "";
		break;
	
	case "walk":
		output("`3You don't mind the old man peeping... and continue on your journey.`n`n");
		$session['user']['specialinc'] = "";
	break;
	case "disturb": //players who try to harm her have to fight against her protector ;) and they receive no mercy
		increment_module_pref("favour",-1);
		output("`3You walk towards him... he doesn't seem to realize anything except for nudity...`n");
		output("You utter loudly: '`@What are you doing here, old man? Peeking is a crime, you know?`3'");
		output(" He seems to be very surprised and turns around... he has some sad look in his eyes... but now he seems to be angry!`3`n`n");
		output("'`QBaka baka baka... You scared the nice ladies away... you need to be taught a lesson!`3'`n`n");
		output("`^Inu...Ii.. Tori... Saru... O-hitsuji... Ninpou Kuchiyose no Jutsu!`3`n`n");
		$selection=0;
		require_once("lib/battle-skills.php");
		if ($session['user']['level']<5)
		 {
		 output("A small frog warrior appears right before you... and attacks immediately.");
		 	$badguy = array(
			"creaturename"=>translate_inline("a small Frog Warrior"),
						"creaturelevel"=>$session['user']['level']+1,
						"creatureweapon"=>translate_inline("Frog Kiss"),
			"creatureattack"=>$session['user']['level']+$session['user']['dragonkills']+1,
			"creaturedefense"=>$session['user']['defense'],
			"creaturehealth"=>($session['user']['level']*10+round(e_rand($session['user']['level'],($session['user']['maxhitpoints']-$session['user']['level']*10)))),
			"diddamage"=>0,);
		 } elseif ($session['user']['level']<10) {
			output("A big frog warrior appears right before you... and attacks immediately.");
		 	$badguy = array(
			"creaturename"=>translate_inline("a Greater Frog Warrior"),
						"creaturelevel"=>$session['user']['level']+1,
						"creatureweapon"=>translate_inline("Two scimitars"),
			"creatureattack"=>$session['user']['level']+$session['user']['dragonkills']+3,
			"creaturedefense"=>$session['user']['defense']+1,
			"creaturehealth"=>($session['user']['level']*10+round(e_rand($session['user']['level'],($session['user']['maxhitpoints']-$session['user']['level']*10)))),
			"diddamage"=>0,);
		 } else {
			$id = $session['user']['hashorse'];
			$sql = "SELECT mountname,mountbuff FROM ".db_prefix("mounts")." WHERE mountid=$id";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$mname = sanitize($row['mountname']);
			if (stristr($mname,"Gamabunta") && !$session['bufflist']['mount']['suspended'] )
				{
				output("Oh no! It seems that he summoned the frog boss! It's `^%s`3!",$row['mountname']);
				suspend_buff_by_name("mount",array("`b`n`nWell, %s`3 has disappeared from your side... and is now loyal to the strange old man... you have to fight against your own mount!`b`0",$row['mountname']));
				$badguy = array(
				"creaturename"=>$row['mountname'],
				"creaturelevel"=>$session['user']['level']+1,
				"creatureweapon"=>translate_inline("Suiton Teppoudama"),
				"creatureattack"=>$session['user']['level']+$session['user']['dragonkills']+5,
				"creaturedefense"=>$session['user']['defense']+$session['user']['dragonkills'],
				"creaturehealth"=>($session['user']['level']*10+50+round(e_rand($session['user']['level'],($session['user']['maxhitpoints']-$session['user']['level']*10)))),
				"diddamage"=>0,);
				} else {
				output("Oh no! It seems that he summoned the frog boss! It's `^%s`3!",translate_inline("Gamabunta"));
				$badguy = array(
				"creaturename"=>translate_inline("Gamabunta"),
				"creaturelevel"=>$session['user']['level']+1,
				"creatureweapon"=>translate_inline("Suiton Teppoudama"),
				"creatureattack"=>$session['user']['level']+$session['user']['dragonkills']+5,
				"creaturedefense"=>$session['user']['level']+$session['user']['dragonkills'],
				"creaturehealth"=>($session['user']['level']*10+50+round(e_rand($session['user']['level']+50,($session['user']['maxhitpoints']-$session['user']['level']*10)))),
				"diddamage"=>0,);
				}

		 }
	 	$battle=true;
	$session['user']['badguy'] = createstring($badguy);
	$op = "combat";
	httpset('op', $op);
	case "combat": case "fight":
	include("battle.php");
	if ($victory){ //no exp at all for such a foul act
		output("`n`n`@...%s`^ dies by your hand. You have managed to survive...somehow.",$badguy['creaturename']);
		addnews("%s`^ survived an encounter with %s`^.",$session['user']['name'],$erosennin);
		$session['user']['specialinc'] = "";
		if ($exploss>0) output(" You gain `^%s percent`@	experience!",get_module_setting("experienceloss"));
			$exploss = $session['user']['experience']*get_module_setting("experienceloss")/100;
			$session['user']['experience']+=$exploss;
			$badguy=array();
			$session['user']['badguy']="";
			$id = $session['user']['hashorse'];
			$sql = "SELECT mountname FROM ".db_prefix("mounts")." WHERE mountid=$id";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$mname = sanitize($row['mountname']);
			if (stristr($mname,"Gamabunta")) {
				output("");
				unsuspend_buff_by_name("mount",array("`b`n`n%s`@ vanishes... and after a few minutes reappear at your side... as loyal and healthy as ever!`b`0",$row['mountname']));
			}
		}elseif ($defeat){ //but a loss of course if you die
			$id = $session['user']['hashorse'];
			$sql = "SELECT mountname FROM ".db_prefix("mounts")." WHERE mountid=$id";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$mname = sanitize($row['mountname']);
			$exploss = $session['user']['experience']*get_module_setting("experienceloss")/100;
			output("`n`n`@You are dead... stroke down by %s `@.`n",$badguy['creaturename']);
			if ($exploss>0) output(" You lose `^%s percent`@	of your experience and all of your gold.",get_module_setting("experienceloss"));
			$session['user']['experience']-=$exploss;
			$session['user']['gold']=0;
			debuglog("lost $exploss experience and all gold to Ero-Sennin.");
			addnews("%s`^ was killed by %s`^ sent out %s`^.",$session['user']['name'],$badguy['creaturename'],$erosennin);
			if (stristr($mname,"Gamabunta"))
				{
				output("");
				unsuspend_buff_by_name("mount",array("`b`n`n%s`@ will wait for you in the mortal world again.`b`0",$row['mountname']));
				}
			addnav("Return");
			addnav("Return to the Shades","shades.php");
			$session['user']['specialinc'] = "";
			$badguy=array();
			$session['user']['badguy']="";
		}else{
			require_once("lib/fightnav.php");
			$allow = true;
			fightnav($allow,false);
			if ($session['user']['superuser'] & SU_DEVELOPER) addnav("Escape to Village","village.php");
		}
	}
}

function erosennin_run(){
}

?>