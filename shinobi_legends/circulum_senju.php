<?php


function circulum_senju_getmoduleinfo(){
	$info = array(
	    "name"=>"Circulum Vitae - Mokuton",
		"description"=>"Mokuton",
		"version"=>"1.0",
		"author"=>"`4Oliver Brendel`0",
		"category"=>"Circulum Vitae",
		"download"=>"",
		"settings"=>array(
			"Circulum Vitae - Preferences,title",
				"maxstack"=>"How many times can this be stacked?,range,1,10,1|4",
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

function circulum_senju_install() {
	module_addhook("circulum-items");
	module_addhook("biostat");
	module_addhook("circulum-chosen");
	module_addhook("training-victory");
	module_addhook("traininggrounds");
	module_addhook("newday");
	return true;
}

function circulum_senju_uninstall() {
  return true;
}


function circulum_senju_dohook($hookname, $args) {
	global $session;
	$stack=(int)get_module_pref('stack','circulum_senju');
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
			$stack=(int)get_module_pref('stack','circulum_senju',$args['acctid']);
			if ($stack==0) break;
			$ranks=array (
				'`@Woodie',
				'`@Big`2w`@ood',
				'`2Y`@amat`2o',
				'`@S`2enju'
				);
			if ($stack>count($ranks)) $stack=count($ranks);
			output("`xBorn as %s`x of the `%Forest`0`n",$ranks[$stack-1]);
			break;
		case "circulum-items":
			$args[]=array(
				'modulename'=>'circulum_senju',
				'nav'=>translate_inline('Mokuton'),
				'category'=>'Elemental Jutsus',
				'exclusive_in_category'=>true,
				'description'=>
					sprintf_translate("`!You are born as a normal nin - doing your training regularly, until you discover that some of your DNA was mixed with Hokage DNA - enabling you to use the power of the forest to come to your aid. Though, your potential as Mokuton genius did survey once you got to training under `^Yamato`!, who taught you about manipulating wood of all kinds and even creating it out of nothing! `n`nBenefits:`n`\$-You have unique abilities to manipulate wood in general - enabling you to pull off amazing jutsus. `3Visit the training grounds to chat with Yamato(chitchat only).`\$`n-Having chosen this way once you can control all normal wood manipulations. The second and ongoing resets will grant you more and higher level jutsus. `n`n`%This can be stacked up to %s times! You have chosen this already %s times.",
						get_module_setting('maxstack'),
						get_module_pref('stack'),
						translate_inline(($taijutsu>0?"`n`n`c`b`\$You have already chosen another elemenatal jutsu, you cannot choose this one!`b`c":"`n`n`c`b`\$You cannot choose this AND another elemental ninjutsu(like Ice)!`b`c"))
						),
				'active'=>1,
				);
			break;
		case "circulum-chosen":
			if ($args['chosen']==='circulum_senju') {
				increment_module_pref('stack',1);
			}
			break;
		case "traininggrounds":
			if (!$isactive) break;
			addnav("Go to Yamato","runmodule.php?module=circulum_senju");
			break;
	}
	return $args;
}

function circulum_senju_run()	{
	global $session;
	page_header("Training Grounds");
	addnav("Navigation");
	addnav("Back to the Academy","train.php");
	addnav("Actions");
	output("`#`b`c`n`@Wood`y Training`0`c`b`n`n");
	$op = httpget('op');
	switch ($op) {
		case "chat":
			$heard=array(
				'Naruto recently leaked Kyuubi chakra at the Ramen Shop. Figure the mess it left behind. He is still cleaning up there.',
				'Chouji joined Weight Watchers. Poor boy, he took it literally and continues to eat like always.',
				'No news is good news.',
				'.... (scary face!)....',
				'Naruto has no idea of style...',
				'There is only one mortal enemy to me: Mr. Woodpecker!',
				'Seems Hinata finally copied one good thing about Ino... the hair length =)',
				'Sakura gave Naruto a punch that made him land in the country of the waves again. He must have said something about she getting bigger the longer Sasuke is gone...',
				'Whats it called when all Genin are in one spot? \'Kindergarten\'.',
				'Tsunade... her temper matches the one of Naruto... ups...',
				'No, I will NOT give you candy...',
				'Jiraiya... I heard of an ancient country where people liked to eat frog legs...',
				'Shino... he also has only one mortal enemy: The Bug Exterminator.',
				);
			$heard=translate_inline($heard);
			$choice=array_rand($heard);
			output("`@Y`2amato`x thinks a bit and then says, \"`4%s`x\"",$heard[$choice]);
			break;
		default:
		output("`xYour master `@Y`2amato`x is here. What do you want to talk to him about?`n`n");
		addnav("Idle Chitchat","runmodule.php?module=circulum_senju&op=chat");
		break;
	}
	page_footer();
}

?>
