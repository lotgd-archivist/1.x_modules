<?php


function circulum_hyuuga_getmoduleinfo(){
	$info = array(
	    "name"=>"Circulum Vitae - Hyuuga Ichizoku",
		"description"=>"Hyuuga Clan",
		"version"=>"1.0",
		"author"=>"`4Oliver Brendel`0",
		"category"=>"Circulum Vitae",
		"download"=>"",
		"settings"=>array(
			"Circulum Vitae GiveTurns - Preferences,title",
				"maxstack"=>"How many times can this be stacked?,range,1,10,1|4",
			),		
		"prefs"=>array(
		    "Circulum Vitae GiveTurns- User prefs,title",
				"stack"=>"Number of CVs the player has chosen this benefit,int|0",
				"failure"=>"Has he failed the Hyuuga training today?,bool|0",
			),
		"requires"=>array(
			"circulum"=>"1.0|Circulum Vitae by `2Oliver Brendel",
			),
		);
    return $info;
}

function circulum_hyuuga_install() {
	module_addhook("circulum-items");
	module_addhook("biostat");
	module_addhook("circulum-chosen");
	module_addhook("training-victory");
	module_addhook_priority("modify-weapon",INT_MAX);
	module_addhook("traininggrounds");
	module_addhook("newday");
	return true;
}

function circulum_hyuuga_uninstall() {
  return true;
}


function circulum_hyuuga_dohook($hookname, $args) {
	global $session;
	static $run;
	$stack=(int)get_module_pref('stack','circulum_hyuuga');
	$isactive=($stack>0?true:false);
	$canbechosen=$stack<get_module_setting('maxstack');
	$doujutsu=(int)get_module_pref('stack','circulum_uchiha');
	if ($stack<get_module_setting('maxstack') && $doujutsu<1) {
		$canbechosen=true;
	} else $canbechosen=false;	
	switch($hookname) {
		case "newday":
			if ($isactive) set_module_pref('failed',0);
			break;
			
		case "modify-weapon":
			if ($isactive) {
				if (!$run) output("`\$As you are a Jyūken User, you have little need for ordinary weaponry. Your bare fists deal damage enough.`n`n");
				$run=true;
				$args['unavailable']=true;
				//no weapons
			}
			break;
		case "biostat":
			$stack=(int)get_module_pref('stack','circulum_hyuuga',$args['acctid']);
			if ($stack==0) break;
			$ranks=array (
				'`$Initiate',
				'`$Honoured Member',
				'`$M`4aster',
				'`$G`4rand `$M`4aster',
				);
			if ($stack>count($ranks)) $stack=count($ranks);
			output("`xBorn as %s`x of the `%Hyuuga Ichizoku`0`n",$ranks[$stack-1]);
			break;
		case "circulum-items":
			$args[]=array(
				'modulename'=>'circulum_hyuuga',
				'nav'=>translate_inline('Hyūga Ichizoku'),
				'category'=>'Doujutsu',
				'exclusive_in_category'=>true,
				'description'=>
					sprintf_translate("`tYou are born as `xHyūga`t, which means your body is carrying a unique Kekkei Genkai: The ability to perform the `%B`Ryakugan`t, which enforces your vision to see through walls, bodies and barriers. Along with that comes the gift of seen the others chakra flow, enabling you to attack not physically but with Chakra directly their internal chakra system. The more often you choose this, the more powerful you will get.`n`nBenefits:`n`\$-You won't ever need to buy a weapon. Hyūgas fight close-range mostly bare-handed or with items (Kunai). `3Visit the training grounds to improve yourself.`\$`n-Specialty \"Hyūga Techniques\" is available. This one is stronger than a normal jutsu set.`n`n`%This can be stacked up to %s times! You have chosen this already %s times. Every stacking makes the Jutsus stronger!",
						get_module_setting('maxstack'),
						get_module_pref('stack'),
						translate_inline(($doujutsu>0?"`n`n`c`b`\$You have already chosen another dōjutsu, you cannot choose this one!`b`c":"`n`n`c`b`\$You cannot choose this AND another dōjutsu!`b`c"))
						),
				'active'=>1,
				);
			break;
		case "circulum-chosen":
			if ($args['chosen']==='circulum_hyuuga') {
				increment_module_pref('stack',1);
			}
			break;
		case "training-victory":
			if (!$isactive) break;
			if ($session['user']['weapondmg']<$session['user']['level']+1) {
				output("`xYour `tJ`1yūken`x improves! You gain one attack point!`n`n");
				$session['user']['weapondmg']++;
				$session['user']['attack']++;
			}
			break;
		case "traininggrounds":
			if (!$isactive) break;
			addnav("Go to Neji","runmodule.php?module=circulum_hyuuga");
			break;
	}
	return $args;
}

function circulum_hyuuga_run()	{
	global $session;
	page_header("Training Grounds");
	addnav("Navigation");
	addnav("Back to the Academy","train.php");
	addnav("Actions");
	output("`#`b`c`n`tJ`1yuuken`x Training`0`c`b`n`n");
	$op = httpget('op');
	switch ($op) {
		case "improve":
			output("`%Neji`x says, \"`tGood. Training will help you use your Jyuuken powers more effectively. A full hit will disable an opponent, but in combat it is unlikely you can do that. Yet every scratch counts more with Jyuuken. Are your ready to train?`x\"");
			addnav("Choices");
			addnav("Yes","runmodule.php?module=circulum_hyuuga&op=go");
			addnav("No","train.php");
			break;
		case "go":
			$chance=(int)(e_rand(0,2)==0);
			if ($chance==1) {
				output("`%Neji`x says, \"`tExcellent! Your chakra control is much better now!`x\"`n`n`\$You gain one attack point!");
				$session['user']['weapondmg']++;
				$session['user']['attack']++;
			} else {
				output("`%Neji`x says: \"`tToo bad! You obviously are not ready for the next step! Visit me again tomorrow after your meditation with a fresh mind ready to improve!`x\"`n`n");
				set_module_pref('failed',1);
			}
			if (get_module_pref('failed')!=1 && $session['user']['weapondmg']<16) {
				addnav("Improve your `tJ`1yuuken","runmodule.php?module=circulum_hyuuga&op=improve");
			}
			break;
		case "chat":
			$heard=array(
				'I have heard there is a war against the Mist coming up... unsettling...',
				'Naruto should be headed for Sasuke right now, hopefully he will find him',
				'Sakura must have cut her hair again... I should be her example as mine is long and healthy.',
				'Chouji has started to eat Chakra. He says it is good for the liver. Poor boy, that won\'t reduce his weight.',
				'Lately, there have been no news.',
				'Somebody was in search for the ink required for the Seven Star Tattoo... interesting thingie... but a kid could paint it better...',
				'Orochimaru has no idea of style...',
				'Ino? Well, at least she knows how to wear her hair...',
				'If Rock Lee would learn how to use chakra... hilarious...',
				'The Sound Five? Well, Orochimaru simply had not enough people to form a \'Dirty Dozen\'.',
				'Mitarashi Anko... she is cute when she is asleep... ups...',
				'No, your underwear has no style at all.',
				'Jiraiya... beneath the surface, there lies a sensible and lonely man...',
				'Shino... well, we are somewhat alike.',
				);
			$heard=translate_inline($heard);
			$choice=array_rand($heard);
			output("`xNeji thinks a bit and then says, \"`4%s`x\"",$heard[$choice]);
			break;
		default:
		output("`xYour master `4Hyuuga `%Neji`x is here. What do you want to talk to him about?`n`n");
		if (get_module_pref('failed')!=1 && $session['user']['weapondmg']<16) {
			addnav("Improve your `tJ`1yuuken","runmodule.php?module=circulum_hyuuga&op=improve");
			addnav("Idle Chitchat","runmodule.php?module=circulum_hyuuga&op=chat");
		} elseif ($session['user']['weapondmg']>15) {
			output("`%You have already learned what Neji can teach you about your `tJ`1yuuken`%. Now you have to develop it on your own.`n`n");
		} else {
			output("`7You have failed today at your exercises and feel there is no hope to improve your fighting skills today.`n`n");
		}
		break;
	}
	page_footer();
}

?>
