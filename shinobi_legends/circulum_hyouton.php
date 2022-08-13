<?php


function circulum_hyouton_getmoduleinfo(){
	$info = array(
	    "name"=>"Circulum Vitae - Hyouton",
		"description"=>"Hyouton",
		"version"=>"1.0",
		"author"=>"`LShinobiIceSlaeyer`~, based on work by, `4Oliver Brendel`0",
		"category"=>"Circulum Vitae",
		"download"=>"",
		"settings"=>array(
			"Circulum Vitae - Preferences,title",
				"maxstack"=>"How many times can this be stacked?,range,1,10,1|4",
			),		
		"prefs"=>array(
		    "Circulum Vitae - User prefs,title",
				"stack"=>"Number of CVs the player has chosen this benefit,int|0",
				"lastchange"=>"Date of the last Gender Change.,text|",
			),
		"requires"=>array(
			"circulum"=>"1.0|Circulum Vitae by `2Oliver Brendel",
			),
		);
    return $info;
}

function circulum_hyouton_install() {
	module_addhook("circulum-items");
	module_addhook("biostat");
	module_addhook("circulum-chosen");
	module_addhook("traininggrounds");
	module_addhook("newday");
	
	set_module_pref("lastchange",date("m/d/y",strtotime("-1 week -1 day")));
	return true;
}

function circulum_hyouton_uninstall() {
  return true;
}


function circulum_hyouton_dohook($hookname, $args) {
	global $session;
	$stack=(int)get_module_pref('stack','circulum_hyouton');
	$isactive=($stack>0?true:false);
	$canbechosen=$stack<get_module_setting('maxstack');
	$ninjutsu=(int)get_module_pref('stack','circulum_senju');
	if ($stack<get_module_setting('maxstack') && $ninjutsu<1) {
		$canbechosen=true;
	} else $canbechosen=false;		
debug($ninjutsu);
debug($canbechosen);
	switch($hookname) {
		case "newday":
			if ($isactive) set_module_pref('failed',0);
			break;
		case "biostat":
			$stack=(int)get_module_pref('stack','circulum_hyouton',$args['acctid']);
			if ($stack==0) break;
			$ranks=array (
				'`LSnowflake',
				'`jF`Lrosty',
				'`jIce `LPop',
				'`LH`ja`Lk`ju',
				);
			if ($stack>count($ranks)) $stack=count($ranks);
			output("`xBorn as %s`x of the `%Ice`0`n",$ranks[$stack-1]);
			break;
		case "circulum-items":
			$args[]=array(
				'modulename'=>'circulum_hyouton',
				'nav'=>translate_inline('Hyouton'),
				'category'=>'Elemental Jutsus',
				'exclusive_in_category'=>true,
				'description'=>
					sprintf_translate("`!You are born as a normal nin - doing your training regularly, until you discover that some of your DNA was mixed with Haku's DNA - enabling you to combine Wind and Water Chakra to form Ice. Though, your potential as a Hyouton genius did survey once you got to training under `^Haku`!, who taught you about manipulating Ice of all kinds and even creating it out of nothing! `n`nBenefits:`n`\$-You have unique abilities to manipulate Ice in general - enabling you to pull off amazing jutsus. `\$`n-Having chosen this way once you can control all normal Ice manipulations. The second and ongoing resets will grant you more and higher level jutsus. `n`n`%This can be stacked up to %s times! You have chosen this already %s times.",
						get_module_setting('maxstack'),
						get_module_pref('stack'),
						translate_inline(($ninjutsu>0?"`n`n`c`b`\$You have already chosen another elemenatal jutsu, you cannot choose this one!`b`c":"`n`n`c`b`\$You cannot choose this AND another elemental ninjutsu(like Mokuton)!`b`c"))
						),
				'active'=>1,
				);
			break;
		case "circulum-chosen":
			if ($args['chosen']==='circulum_hyouton') {
				increment_module_pref('stack',1);
			}
			break;
		case "traininggrounds":
			if (!$isactive) break;
			addnav("Go to Haku","runmodule.php?module=circulum_hyouton");
			break;
	}
	return $args;
}

function circulum_hyouton_run()	{
	global $session;
	page_header("Training Grounds");
	addnav("Navigation");
	addnav("Back to the Academy","train.php");
	addnav("Actions");
	output("`#`b`c`n`jH`Lyouton`x Training`0`c`b`n`n");
	$op = httpget('op');
	switch ($op) {
		case "chat":
			$heard=array(
				'I have heard there is a war the Sound coming up... unsettling...',
				'Naruto should be headed for Sasuke right now, hopefully he will find him',
				'Sakura must have cut her hair. Hinata has grown such a nice one.',
				'Chouji has started to eat Chakra. He says it is good for the liver. Poor boy, that won\'t reduce his weight.',
				'Lately, there have been no news.',
				'Somebody was in search for the ink required for the Seven Star Tattoo... interesting thingie... but a kid could paint it better...',
				'Orochimaru has no idea of style...',
				'When a person has something important they want to protect, that\'s when they can become truly strong.',
				'Ino? Well, at least she knows how to wear her hair...',
				'If Neji would learn how to use his fists with more power... hilarious...',
				'The Sound Five? Well, Orochimaru simply had not enough people to form a \'Dirty Dozen\'.',
				'Tsunade... she is cute when she is asleep... ups...',
				'Can you understand? Not having a dream, not being needed by anyone, the pain of merely being alive',
				'Jiraiya... beneath the surface, there lies a sensible and lonely man...',
				'Shino... well, creepy buglover, but I said nothin\' at all.',
				);
			$heard=translate_inline($heard);
			$choice=array_rand($heard);
			output("`LH`ja`Lk`ju`x thinks a bit and then says, \"`4%s`x\"",$heard[$choice]);
			break;
		case "gender":
			$olddate=strtotime(get_module_pref("lastchange"));
			$date=strtotime("now");
			$week=strtotime(date("m/d/y", strtotime('-1 week')));
			if ($olddate>$week){
				output("`LH`ja`Lk`ju `xshakes his head at you, still not having the strength to change you.");
			} else {
				output("`LH`ja`Lk`ju `xsmiles at you, `l\"So, you want to make some... changes? Well I can help you out, but it takes a lot of my skill to do such things, and I'm not sure how long it will be until I can reverse them if you want me to.\"");
				addnav("Switch Gender","runmodule.php?module=circulum_hyouton&op=switch");
			}
		break;
		case "switch":
			output("`LH`ja`Lk`ju `xsmiles, and hands you a new set of clothes, and points you towards a changing room. Wondering what changing clothes will do you head to the room.`n");
			$gender=$session['user']['sex'];
			if ($session['user']['sex'] == 1){
				$session['user']['sex'] = 0;
			}else{
				$session['user']['sex'] = 1;
			}
			$newgender=$session['user']['sex'];
			set_module_pref("lastchange",date("m/d/y"));
			$gen="";
			if ($session['user']['sex'] == 0) $gen="manly";
			else $gen="womanly";
			output("`xAs you change, you notice a magical change with in you. You step out and look in a mirror, and as you stare at your reflection you notice you are more %s.",$gen);
		break;
		default:
		output("`xYour master `LH`ja`Lk`ju`x is here. What do you want to talk to him about?`n`n");
		addnav("Idle Chitchat","runmodule.php?module=circulum_hyouton&op=chat");
		addnav("Discuss Gender","runmodule.php?module=circulum_hyouton&op=gender");
		break;
	}
	page_footer();
}

?>
