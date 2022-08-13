<?php
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:curse_seal";
	$op = httpget('op');
	$five=get_module_setting("soundfive");
	$premembers=array('`RTa`&yu`Rya','`!Ji`%rou`!bou','`tK`gid`vou`gma`5ru','`)Sa`~kon','`~Uk`)on','`vKimi`tmaro');
	$mem=array_rand($premembers,count($premembers));
	$members=array();
	for ($i=0;$i<count($premembers);$i++) {
		array_push($members,$premembers[$mem[$i]]);
	}
	page_header("%s",sanitize($five));
	switch ($op) {
		case "investigate":
			output("`7You take some steps and see what will come your way...");
			switch(e_rand(0,5)) {
				case 0:
					output("You see %s`7 digging in the nose while %s`7 tries to do the same with a kunai!",$members[0],$members[1]);
					addnews("`7%s`7 interrupted %s`7 and %s`7 while nose digging and got roughed up!",$session['user']['name'],$members[0],$members[1]);
					output("`n`nThey don't look friendly... %s`7 is appearing beside you... and you can't even blink before being struck down and beaten up badly!",$members[2]);
					output("%s`7 is coming your way. He looks down at your beaten body and says, \"`2My, what a weak breed...`7\"...",$members[3]);
					$session['user']['hitpoints']*=0.1;
					$session['user']['specialinc']="";
					forest(true);
					break;
				case 1:
					output("You see %s`7 while hugging %s`7 and fondling around at nasty places!",$members[0],$members[1]);
					addnews("`7%s`7 interrupted %s`7 and %s`7 while hugging each other and got smooched up!",$session['user']['name'],$members[0],$members[1]);
					output("`n`nThey don't look friendly. %s`7 is appearing beside you... and you can't even blink before being struck down and beaten up badly!",$members[2]);
					output("%s`7 is coming your way. He looks down at your beaten body and says, \"`2My, what a leecher...`7\"...",$members[3]);
					$session['user']['hitpoints']*=0.15;
					$session['user']['specialinc']="";
					break;
				default:
					output("You see %s`7 and %s`7 sitting around something looking like a battleground... somewhat destroyed by Ninjutsu attacks as well as Taijutsu that deformed everything in range.",$members[0],$members[1]);
					output("%s`7 is coming around, saying, \"`2Oh? A fellow bearer of the %s`2... excellent. If you want, you can take a sparring round with me. I will teach you to go to Level 2...`7\"",$members[3],get_module_setting("name"));
					output("`n`nYou realize that this is the hidden training ground of the `b %s`7`b! You are not sure if you are strong enough... but this would perhaps enhance your skills...",get_module_setting("soundfive"));
					addnav("Actions");
					addnav("Back out",$from."op=leave");
					addnav(array("Sparring with %s",$members[3]),$from."op=sparring&who=".rawurlencode($members[3]));
					break;
				}
			break;
		case "sparring":
			$who=rawurldecode(httpget('who'));
			$memb=array_diff($members,array($who));
			$members=array();
			while ($m=array_shift($memb))
				$members[]=$m;
			addnews("`7%s`7 took some sparring rounds with %s`7!",$session['user']['name'],$who);
			output("`7You take some sparring rounds with %s`7...`n`n",$who);
			$k=0;debug($members);
			for ($i=0;$i<5;$i++) {
				$rand=e_rand(0,count($members)-1);
				switch(e_rand(0,6)) {
					case 0:
						output("%s`7 comes  your way and you can barely dodge the attacks of %s`7!`n",$members[0],$who);
						break;
					case 1:
						output("`7You attack %s`7 with a grim face and use all your strength!`n",$who);
						$k+=2;
						break;
					case 2:
						output("`7You timidly approach %s`7 and ask if you might place a hit or two...`n",$who);
						$k-=1;
						break;
					case 3:
						output("You attack %s`7 normally...`n",$who);
						$k+=1;
						break;
					case 4:
						output("You sneak behind %s`7 and place a soft attack!`n",$who);
						$k+=1;
						break;
					case 5:
						output("Oh my, you were too busy looking at %s`7 and missed a chance to attack!`n",$members[$rand]);
						$k-=1;
						break;
					case 6:
						output("There is no opening, %s`7 leaves you no chance to attack...`n",$who);
						break;
				}
				switch(e_rand(0,6)) {
					case 0:
						output("%s`7 comes at you with full speed and takes some bad wounds from your counterattack! You see %s`7 laugh...`7`n",$who,$members[$rand]);
						$k+=2;
						break;
					case 1:
						output("%s`7 attacks you with a grim face and uses all strength!`n",$who);
						$k-=2;
						break;
					case 2:
						output("%s`7 timidly approaches you and asks if a hit or two are OK. %s`7 seems to look very disgusted.`n",$who,$members[$rand]);
						$k+=1;
						break;
					case 3:
						output("%s`7 attacks you normally...`n",$who);
						$k-=1;
						break;
					case 4:
						output("%s`7 sneaks behind you and a kunai touches your ribs slightly!`n",$who);
						$k-=1;
						break;
					case 5:
						output("Oh my, %s`7 was too busy looking at %s`7 and missed the chance to attack!`n",$who,$members[$rand]);
						$k+=1;
						break;
					case 6:
						output("You leave %s`7 no opening, and offer no chance to attack...`n",$who);
						break;
				}
				output("`n");
			}
			if ($k>0) {
				output("`n`n`b`\$Yes!`b`7 Your sparring was successful!");
				increment_module_pref("sparring",1);
			} else {
				output("`n`n`b`\$No!`b`7 Your sparring was not successful at all!");
			}
			if (get_module_pref("sparring")>=get_module_setting("level2")) {
				output("`n`n%s`7 congratulates you, you have now attained `b%s Level 2`b!",$who,get_module_setting("name"));
				output("`n`n\"`2 Do not overuse this! It is very dangerous and it will erode your body! Take this as a `bwarning`b...`7\" reminds you %s`7.",$who);
				set_module_pref("hasseal",2);
			} else {
				output("`n`n%s`7 examines your progress carefully and says, \"`2You haven't learned enough, have you? Oh well. Come again and we can proceed with your training.`7\"",$who);
			}
			output("`n`nYou have used `ball your turns`b while sparring!");
			$session['user']['turns']=0;
			addnav("Continue",$from."op=leave");
			break;
		case "leave":
			output("`7You go back to the forest...");
			addnav("Return");
			addnav("Back to the forest","forest.php");
			$session['user']['specialinc']="";
			forest(true);
			break;
		default:
		output("`7As you pass some trees, you can hear weapons clashing and hear explosive tags detonating...`n`n");
		output("After some minutes of investigation, you are pretty sure that you saw %s`7 in the blink of an eye rushing towards the sounds...",$members[0]);
		output("`n`nWhat do you do?");
		addnav("Actions");
		addnav("Back to the forest",$from."op=leave");
		addnav("Investigate",$from."op=investigate");
		break;
	}
?>
