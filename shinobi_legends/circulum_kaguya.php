<?php


function circulum_kaguya_getmoduleinfo(){
	$info = array(
	    "name"=>"Circulum Vitae - Kaguya Ichizoku",
		"description"=>"Kaguya Clan",
		"version"=>"1.0",
		"author"=>"`4Gyururu, based on work by `2Oliver Brendel`0",
		"category"=>"Circulum Vitae",
		"download"=>"",
		"settings"=>array(
			"Circulum Vitae GiveTurns - Preferences,title",
				"maxstack"=>"How many times can this be stacked?,range,1,10,1|2",
			),		
		"prefs"=>array(
		    "Circulum Vitae GiveTurns- User prefs,title",
				"stack"=>"Number of CVs the player has chosen this benefit,int|0",
				"failure"=>"Has he failed the Kaguya training today?,bool|0",
			),
		"requires"=>array(
			"circulum"=>"1.0|Circulum Vitae by `2Oliver Brendel",
			),
		);
    return $info;
}

function circulum_kaguya_install() {
	module_addhook("circulum-items");
	module_addhook("biostat");
	module_addhook("circulum-chosen");
	module_addhook("training-victory");
	module_addhook_priority("modify-weapon",INT_MAX);
	module_addhook("traininggrounds");
	module_addhook("newday");
	return true;
}

function circulum_kaguya_uninstall() {
  return true;
}


function circulum_kaguya_dohook($hookname, $args) {
	global $session;
	static $run;
	$stack=(int)get_module_pref('stack','circulum_kaguya');
	$isactive=($stack>0?true:false);
	$taijutsu=(int)get_module_pref('stack','circulum_maito');
	if ($stack<get_module_setting('maxstack') && $taijutsu<1) {
		$canbechosen=true;
	} else $canbechosen=false;		
	switch($hookname) {
		case "newday":
			if ($isactive) set_module_pref('failed',0);
			break;
			
		case "modify-weapon":
			if ($isactive) {
				if (!$run) output("`~As you are a Shikotsumyaku User, you have little need for ordinary weaponry. Your bones are deadly enough.`n`n");
				$run=true;
				$args['unavailable']=true;
				//no weapons
			}
			break;
		case "biostat":
			$stack=(int)get_module_pref('stack','circulum_kaguya',$args['acctid']);
			debug($stack);
			if ($stack==0) break;
			$ranks=array (
				'`7Foot Soldier',
				'`4W`$a`4r`7l`jo`7rd',
				);
			if ($stack>count($ranks)) $stack=count($ranks);
			output("`~Born as %s`x of the `%Kaguya Ichizoku`0`n",$ranks[$stack-1]);
			break;
		case "circulum-items":
			$args[]=array(
				'modulename'=>'circulum_kaguya',
				'nav'=>translate_inline('Kaguya Ichizoku'),
				'category'=>'Taijutsu',
				'exclusive_in_category'=>true,
				'description'=>
					sprintf_translate("`~You are born as `7Kaguya`t, which means your body is carrying a unique Kekkei Genkai: The ability to perform the `%Shi`7ko`%tsu`7mya`%ku`~, which allows you to manipulate your bones at will. You can protrude your bones out through your skin, change the density of your bones, and could even completely regenerate a lost bone. The more often you choose this, the more powerful you will get.`n`nBenefits:`n`\$-You won't ever need to buy a weapon. Kaguya fight by turning their bones into deadly weapons. `3Visit the training grounds to improve yourself.`\$`n-Specialty \"Shikotsumyaku Techniques\" is available. This one is stronger than a normal jutsu set.`n`n`%This can be stacked up to %s times! You have chosen this already %s times. Every stacking makes the Jutsus stronger!",
						get_module_setting('maxstack'),
						get_module_pref('stack'),
						translate_inline(($taijutsu>0?"`n`n`c`b`\$You have already chosen another taijutsu, you cannot choose this one!`b`c":"`n`n`c`b`\$You cannot choose this AND another taijutsu(like Hachimon Tonkō)!`b`c"))
						),
				'active'=>1,
				);
			break;
		case "circulum-chosen":
			if ($args['chosen']==='circulum_kaguya') {
				increment_module_pref('stack',1);
			}
			break;
		case "training-victory":
			if (!$isactive) break;
			if ($session['user']['weapondmg']<$session['user']['level']+1) {
				output("`~Your `%Shi`7ko`%tsu`7mya`%ku`~ improves! You gain one attack point!`n`n");
				$session['user']['weapondmg']++;
				$session['user']['attack']++;
			}
			break;
		case "traininggrounds":
			if (!$isactive) break;
			addnav("Go to Kimimaro","runmodule.php?module=circulum_kaguya");
			break;
	}
	return $args;
}

function circulum_kaguya_run()	{
	global $session;
	page_header("Training Grounds");
	addnav("Navigation");
	addnav("Back to the Academy","train.php");
	addnav("Actions");
	output("`c`%Shi`7ko`%tsu`7mya`%ku`~ Training`0`c`b`n`n");
	$op = httpget('op');
	switch ($op) {
		case "improve":
			output("`%Kimimaro`~ says, \"`VGood. Training will help you use your Shikotsumyaku powers more effectively. A strong bone can thrust right through an opponent, but it is unlikely you can do that. Yet working on your Shikotsumyaku powers will make your bones stronger. Are your ready to train?`~\"");
			addnav("Choices");
			addnav("Yes","runmodule.php?module=circulum_kaguya&op=go");
			addnav("No","train.php");
			break;
		case "go":
			$chance=(e_rand(0,2)>0?1:0);
			if ($chance==1) {
				output("`%Kimimaro`~ says, \"`VExcellent! Your bones are much stronger now!`~\"`n`n`\$You gain one attack point!");
				$session['user']['weapondmg']++;
				$session['user']['attack']++;
				if (get_module_pref('failed')!=1 && $session['user']['weapondmg']<16) {
					addnav("Improve your `%Shi`7ko`%tsu`7mya`%ku","runmodule.php?module=circulum_kaguya&op=improve");
				}
			} else {
				output("`%Kimimaro`~ says, \"`VToo bad! You obviously are not ready for the next step! Visit me again tomorrow after your meditation with a fresh mind ready to improve!`~\"`n`n");
				set_module_pref('failed',1);
			}
			break;
		case "chat":
			$heard=array(
				'Orochimaru-sama WILL succeed in destroying Konoha Gakure next time!',
				'I will show Orochimaru-sama that I am better than that Uchiha kid...',
				'Drink 8 glasses of milk dairy to keep your bones strong and healthy.',
				'It gross me out, watching Ukon and Sakon sticking to each other like a couple all the time.',
				'Lately, there have been no news.',
				'Somebody was in search for the ink required for the Seven Star Tattoo... interesting thingie... but a kid could paint it better...',
				'Orochimaru-sama banzai!',
				'Need a hand? Ask Kidomaru...he has 8...',
				'Jiroubo is like a huge walking potato.',
				'The Sound Four? How dare they leave me out of the Orochimaru Groupie!',
				'Hmmm...Tayuya isn\'t really my type, but she is the only girl left after Kin is scarified.',
				'I heard Kabuto\'s glasses are only for show...',
				'I count my bones during my free time...',
				'I am so jealous of Sasuke!',
				);
			$heard=translate_inline($heard);
			$choice=array_rand($heard);
			output("`%Kimimaro `~thinks a bit and then says, \"`V%s`~\"",$heard[$choice]);
			break;
		default:
		output("`~Your master `7Kaguya `%Kimimaro`~ is here. What do you want to talk to him about?`n`n");
		if (get_module_pref('failed')!=1 && $session['user']['weapondmg']<16) {
			addnav("Improve your `%Shi`7ko`%tsu`7mya`%ku","runmodule.php?module=circulum_kaguya&op=improve");
			addnav("Idle Chitchat","runmodule.php?module=circulum_kaguya&op=chat");
		} elseif ($session['user']['weapondmg']>15) {
			output("`4You have already learned what `%Kimimaro `4can teach you about your `%Shi`7ko`%tsu`7mya`%ku`4. Now you have to develop it on your own.`n`n");
		} else {
			output("`7You have failed today at your exercises and feel there is no hope to improve your fighting skills today.`n`n");
		}
		break;
	}
	page_footer();
}

?>
