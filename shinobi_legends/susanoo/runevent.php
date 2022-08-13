<?php
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:susanoo";
	$op = httpget('op');
	$member_name=get_module_setting("gyururu"); //set only one member at the moment
	$premembers=array($member_name);//array('`RFi`&ma`Rgo','`!Le`%ga`!su','`tMo`gge`vk`ga`5ru','`)Re`~yon','`~Il`)su','`vBeli`tmaro');
	$mem=array_rand($premembers,count($premembers));
	$members=array();
	for ($i=0;$i<count($premembers);$i++) {
		array_push($members,$premembers[$mem[$i]]);
	}
	$suslevel = get_module_pref('hasseal');
	page_header("%s",sanitize($member_name));
	switch ($op) {
		case "investigate":
			output("`7You take some steps and see what will come your way...");
			switch(e_rand(0,5)) {
				case 0:
					output("You see `4%s `7washing herself in a stream nearby!",$member_name);
					addnews("`7%s`7 peeked at `4%s `7while she was bathing and got roughed up!",$session['user']['name'],$member_name);
					output("`n`nYou were unable to take your eyes off her... `4%s `7suddenly appears beside you... and you can't even blink before being struck down and beaten up badly!",$member_name);
					output("`4%s `7is coming your way. She looks down at your beaten body and says, \"`\$My, enjoyed the view?`7\"...",$member_name);
					$session['user']['hitpoints']*=0.1;
					$session['user']['specialinc']="";
					forest(true);
					break;
				case 1:
					output("You see `4%s `7while she is changing her clothes!",$member_name);
					addnews("`7%s`7 peeked at `4%s `7while she was changing and got smooched up!",$session['user']['name'],$member_name);
					output("`n`nYou were unable to take your eyes off her... `4%s `7suddenly appears beside you... and you can't even blink before being struck down and beaten up badly!",$member_name);
					output("`4%s `7is coming your way. She looks down at your beaten body and says, \"`\$My, liked what you saw?`7\"...",$member_name);
					$session['user']['hitpoints']*=0.15;
					$session['user']['specialinc']="";
					break;
				default:
					output("You see `4%s `7sitting around something looking like a battleground... somewhat destroyed by Ninjutsu attacks as well as Taijutsu that deformed everything in range.",$member_name);
					output("`4%s `7is coming around, saying, \"`\$Oh? A bearer of the %s`\$... excellent. If you want, you can take a sparring round with me. I will teach you to go to Level %s...`7\"",$member_name,get_module_setting("name"),$suslevel+1);
					output("`n`nYou realize that this is the hidden training ground of the legendary `4%s`7! You are not sure if you are strong enough... but this would perhaps enhance your skills...",get_module_setting("gyururu"));
					addnav("Actions");
					addnav("Back out",$from."op=leave");
					addnav(array("Sparring with %s",$member_name),$from."op=sparring&who=".rawurlencode($member_name));
					break;
				}
			break;
		case "sparring":
			$who = $member_name;
/*			$who=rawurldecode(httpget('who'));
			$memb=array_diff($members,array($who));
			$members=array();
			while ($m=array_shift($memb))
				$members[]=$m;
//not used atm, only with more guys
*/			addnews("`7%s`7 took some sparring rounds with `4%s`7!",$session['user']['name'],$member_name);
			output("`7You take some sparring rounds with `4%s`7...`n`n",$member_name);
			$k=0;
			for ($i=0;$i<5;$i++) {
				switch(e_rand(0,6)) {
					case 0:
						output("`4%s`7 comes your way and you can barely dodge her attacks!`n",$member_name);
						break;
					case 1:
						output("`7You attack `4%s`7 with a grim face and use all your strength!`n",$member_name);
						$k+=2;
						break;
					case 2:
						output("`7You timidly approach `4%s`7 and ask if you might place a hit or two...`n",$member_name);
						$k-=1;
						break;
					case 3:
						output("You attack `4%s`7 normally...`n",$member_name);
						$k+=1;
						break;
					case 4:
						output("You sneak behind `4%s`7 and place a soft attack!`n",$member_name);
						$k+=1;
						break;
					case 5:
						output("Oh my, you were too busy looking at `4%s`7's goods and missed a chance to attack!`n",$member_name);
						$k-=1;
						break;
					case 6:
						output("There is no opening, `4%s`7 leaves you no chance to attack...`n",$member_name);
						break;
				}
				switch(e_rand(0,6)) {
					case 0:
						output("`4%s`7 comes at you with full speed and takes some bad wounds from your counterattack!`n",$member_name);
						$k+=2;
						break;
					case 1:
						output("`4%s`7 attacks you with a grim face and uses all her strength!`n",$member_name);
						$k-=2;
						break;
					case 2:
						output("`4%s`7 timidly approaches you and asks if a hit or two are OK.`n",$member_name);
						$k+=1;
						break;
					case 3:
						output("`4%s`7 attacks you normally...`n",$member_name);
						$k-=1;
						break;
					case 4:
						output("`4%s`7 sneaks behind you and a kunai touches your ribs slightly!`n",$member_name);
						$k-=1;
						break;
					case 5:
						output("`4%s`7 was too busy fixing her hair and missed the chance to attack!`n",$member_name);
						$k+=1;
						break;
					case 6:
						output("You leave `4%s`7 no opening, and offer no chance to attack...`n",$member_name);
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
				output("`n`n%s`7 congratulates you, you have now attained `b%s Level %s`b!",$who,get_module_setting("name"),$suslevel+1);
				output("`n`n\"`\$ Do not overuse this! It is very dangerous and it will erode your body! Take this as a `bwarning`b...`7\" %s`7 reminds you.",$who);
				set_module_pref("sparring",0); //reset
				increment_module_pref("hasseal",1);
			} else {
				output("`n`n%s`7 examines your progress carefully and says, \"`\$You haven't learned enough, have you? Oh well. Come again and we can proceed with your training.`7\"",$who);
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
		output("`7As you pass some trees, you can hear trees snapping and rocks breaking...`n`n");
		output("After some minutes of investigation, you are pretty sure that you saw `4%s`7 in the blink of an eye rushing towards the sounds...",$member_name);
		output("`n`nWhat do you do?");
		addnav("Actions");
		addnav("Back to the forest",$from."op=leave");
		addnav("Investigate",$from."op=investigate");
		break;
	}
?>
