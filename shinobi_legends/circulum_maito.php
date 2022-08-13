<?php


function circulum_maito_getmoduleinfo(){
	$info = array(
	    "name"=>"Circulum Vitae - Hachimon Tonkou",
		"description"=>"Hachimon Tonkou",
		"version"=>"1.0",
		"author"=>"`4Oliver Brendel`0",
		"category"=>"Circulum Vitae",
		"download"=>"",
		"settings"=>array(
			"Circulum Vitae - Preferences,title",
				"maxstack"=>"How many times can this be stacked?,range,1,10,1|2",
			),		
		"prefs"=>array(
		    "Circulum Vitae - User prefs,title",
				"stack"=>"Number of CVs the player has chosen this benefit,int|0",
			),
		"requires"=>array(
			"circulum"=>"1.0|Circulum Vitae by `2Oliver Brendel",
			),
		);
    return $info;
}

function circulum_maito_install() {
	module_addhook("circulum-items");
	module_addhook("biostat");
	module_addhook("circulum-chosen");
	module_addhook("training-victory");
	module_addhook_priority("modify-weapon",INT_MAX);
	module_addhook("traininggrounds");
	module_addhook("newday");
	return true;
}

function circulum_maito_uninstall() {
  return true;
}


function circulum_maito_dohook($hookname, $args) {
	global $session;
	$stack=(int)get_module_pref('stack','circulum_maito');
	$isactive=($stack>0?true:false);
	$canbechosen=$stack<get_module_setting('maxstack');
	$taijutsu=(int)get_module_pref('stack','circulum_kaguya');
	if ($stack<get_module_setting('maxstack') && $taijutsu<1) {
		$canbechosen=true;
	} else $canbechosen=false;		
	switch($hookname) {
		case "newday":
			if ($isactive) set_module_pref('failed',0);
			break;
		case "biostat":
			$stack=(int)get_module_pref('stack','circulum_maito',$args['acctid']);
			if ($stack==0) break;
			$ranks=array (
				'`@Lee',
				'`@G`2ai',
				);
			if ($stack>count($ranks)) $stack=count($ranks);
			output("`xBorn as %s`x of the `%Lotus`0`n",$ranks[$stack-1]);
			break;
		case "modify-weapon":
			if ($isactive) {
				static $run;
				if (!$run) output("`\$As you are a Gouken User, you have little need for ordinary weaponry. Your bare fists deal damage enough.`n`n");
				$run=true;
				$args['unavailable']=true;
				//no weapons
			}
			break;			
		case "circulum-items":
			$args[]=array(
				'modulename'=>'circulum_maito',
				'nav'=>translate_inline('Hachimon Tonkō'),
				'category'=>'Taijutsu',
				'exclusive_in_category'=>true,
				'description'=>
					sprintf_translate("`!You are born as `xa dropout`!, which means you have had nothing but hardships in your life. Though, your potential as genius did survey once you got to training under `^Maito Gai`!, who taught you about the `@H`2achimon `@T`2onkou`!, the Lotus, and more. You are able to control a certain number of 'celestial gates' in your body which will allow you to gain power  beyond imagination for a short term. This is also dangerous to you. `n`nBenefits:`n`\$-You won't ever need to buy a weapon. Devoted to Taijutsu, you fight close-range mostly bare-handed or with items (Kunai). `3Visit the training grounds to improve yourself.`\$`n-Having chosen this way once you can control up to 5 gates (see wikipedia for their names, effects similar). The second and last time will grant you all. Means with 2 times this chosen you have everything.`n`n`%This can be stacked up to %s times! You have chosen this already %s times.",
						get_module_setting('maxstack'),
						get_module_pref('stack'),
						translate_inline(($taijutsu>0?"`n`n`c`b`\$You have already chosen another taijutsu, you cannot choose this one!`b`c":"`n`n`c`b`\$You cannot choose this AND another taijutsu(like Kaguya Ichizoku)!`b`c"))
						),
				'active'=>1,
				);
			break;
		case "circulum-chosen":
			if ($args['chosen']==='circulum_maito') {
				increment_module_pref('stack',1);
			}
			break;
		case "training-victory":
			if (!$isactive) break;
			if ($session['user']['weapondmg']<$session['user']['level']+1) {
				output("`xYour `tF`1ists`x improve! You gain one attack point!`n`n");
				$session['user']['weapondmg']++;
				$session['user']['attack']++;
			}
			break;
		case "traininggrounds":
			if (!$isactive) break;
			addnav("Go to Gai","runmodule.php?module=circulum_maito");
			break;
	}
	return $args;
}

function circulum_maito_run()	{
	global $session;
	page_header("Training Grounds");
	addnav("Navigation");
	addnav("Back to the Academy","train.php");
	addnav("Actions");
	output("`#`b`c`n`tG`1oken`x Training`0`c`b`n`n");
	$op = httpget('op');
	switch ($op) {
		case "improve":
			output("`4G`\$ai`x says, \"`tGood. Let's run around the city on our hands if we don't succeed here. Are your ready to train?`x\"");
			addnav("Choices");
			addnav("Yes","runmodule.php?module=circulum_maito&op=go");
			addnav("No","train.php");
			break;
		case "go":
			$chance=(int)(e_rand(0,2)>0);
			if ($chance==1) {
				output("`4G`\$ai`x says, \"`tExcellent! That's worth 100 laps around the town on our knees!`x\"`n`n`\$You gain one attack point!");
				$session['user']['weapondmg']++;
				$session['user']['attack']++;
			} else {
				$s=e_rand(1,100)*10;
				$p=e_rand(1,200)*10;
				output("`4G`\$ai`x says, \"`tToo bad! You obviously are not ready for the next step! Visit me again tomorrow after having done %s pushups and %s situps!`x\"`n`n",$s,$p);
				set_module_pref('failed',1);
			}
			if (get_module_pref('failed')!=1 && $session['user']['weapondmg']<16) {
				addnav("Improve your `tF`1ists`t further","runmodule.php?module=circulum_maito&op=improve");
			}
			break;
		case "chat":
			$heard=array(
				'I have heard there is a war against the Sound coming up... unsettling...',
				'Naruto should be headed for Sasuke right now, hopefully he will find him',
				'Sakura must have cut her hair again... Hinata has grown such a nice one.',
				'Chouji has started to eat Chakra. He says it is good for the liver. Poor boy, that won\'t reduce his weight.',
				'Lately, there has been no news.',
				'Somebody was in search for the ink required for the Seven Star Tattoo... interesting thingie... but a kid could paint it better...',
				'Orochimaru has no idea of style...',
				'Let\'s do 500 laps around the town brushing our teeth with one foot!',
				'Ino? Well, at least she knows how to wear her hair...',
				'If Neji would learn how to use his fists with more power... hilarious...',
				'The Sound Five? Well, Orochimaru simply had not enough people to form a \'Dirty Dozen\'.',
				'Tsunade... she is cute when she is asleep... ups...',
				'No, your suit has no style at all. Take one of my green collection here...',
				'Jiraiya... beneath the surface, there lies a sensible and lonely man...',
				'Shino... well, creepy buglover, but I said nothin\' at all.',
				);
			$heard=translate_inline($heard);
			$choice=array_rand($heard);
			output("`4G`\$ai`x thinks a bit and then says, \"`4%s`x\"",$heard[$choice]);
			break;
		default:
		output("`xYour master `4M`\$aito `4G`\$ai`x is here. What do you want to talk to him about?`n`n");
		if (get_module_pref('failed')!=1 && $session['user']['weapondmg']<16) {
			addnav("Improve your `tF`1ists","runmodule.php?module=circulum_maito&op=improve");
			addnav("Idle Chitchat","runmodule.php?module=circulum_maito&op=chat");
		} elseif ($session['user']['weapondmg']>15) {
			output("`%You have already learned what `4G`\$ai`% can teach you about your `tF`1ists`%. Now you have to develop it on your own.`n`n");
		} else {
			output("`7You have failed today at your exercises and feel there is no hope to improve your fighting skills today.`n`n");
		}
		break;
	}
	page_footer();
}

?>
