<?php

function circulum_sage_getmoduleinfo(){
	$info = array(
	    "name"=>"Circulum Vitae - Sennin Mōdo",
		"description"=>"Sage Mode",
		"version"=>"1.0",
		"author"=>"`LShinobIceSlayer `~based on work by `4Oliver Brendel`0",
		"category"=>"Circulum Vitae",
		"download"=>"",
		"settings"=>array(
			"Circulum Vitae  - Preferences,title",
				"maxstack"=>"How many times can this be stacked?,range,1,10,1|4",
			),		
		"prefs"=>array(
		    "Circulum Vitae Sage Mode - User prefs,title",
				"stack"=>"Number of CVs the player has chosen this benefit,int|0",
				"clone"=>"Does the user have a clone at the academy?,bool|0",
				"bonus"=>"Has the user already recieved the Sage Mode chakra boost today?,bool|0",
			),
		"requires"=>array(
			"circulum"=>"1.0|Circulum Vitae by `2Oliver Brendel",
			),
		);
    return $info;
}

function circulum_sage_install() {
	module_addhook("circulum-items");
	module_addhook("biostat");
	module_addhook("circulum-chosen");
	module_addhook("traininggrounds");
	module_addhook("newday");
	return true;
}

function circulum_sage_uninstall() {
  return true;
}


function circulum_sage_dohook($hookname, $args) {
	global $session;
	static $run;
	$stack=(int)get_module_pref('stack','circulum_sage');
	$isactive=($stack>0?true:false);
	$ninjutsu=(int)get_module_pref('stack','circulum_rinnegan');//+(int)get_module_pref('stack','circulum_senju');
	if ($stack<get_module_setting('maxstack') && $ninjutsu<1) {
		$canbechosen=true;
	} else $canbechosen=false;
	switch($hookname) {
		case "newday":
			if ($isactive) {
				if (!isset($session['user']['companions'])) apply_companion("toad_gamakiri",'',true); //Fixes the Reset issue.
				$remove=$toads = array("toad_gamatatsu", "toad_gamakichi", "toad_gamahiro", "toad_gamaken", "toad_gamabunta", "toad_shima", "toad_fukasaku", "toad_gamakiri");;
				strip_companion($remove);
/*				$companions=unserialize($session['user']['companions']);
				if (is_array($companions)) {
					foreach($companions as $name=>$data){
						if (in_array($name,$remove)) unset($companions[$name]);
					}
					$session['user']['companions']=serialize($companions);
				}
*/				set_module_pref("bonus",0,"circulum_sage");
			}
			break;
			
		case "biostat":
			$stack=(int)get_module_pref('stack','circulum_sage',$args['acctid']);			
			if ($stack==0) break;
			$ranks=array (
				'`@C`2ontract `@S`2igner',
				'`2Bo`@ss',
				'`@E`2lder',
				'`2S`@age',
				'`@Great `2Toad `TSage',
				'`2Child `@of `tProphecy',
				);
			if ($stack>count($ranks)) $stack=count($ranks);
			output("`xBorn as %s`x to the `TToads `xof `2My`4ōb`2ok`4uz`2an.`0`n",$ranks[$stack-1]);
			break;
		case "circulum-items":
			$args[]=array(
				'modulename'=>'circulum_sage',
				'nav'=>translate_inline('Sennin Mōdo'),
				'category'=>'Sage Arts',
				'exclusive_in_category'=>true,
				'description'=>
					sprintf_translate("`@You sign a contract with the `TToads `xof `2My`4ob`2ok`4uz`2an`@, which means you can summon these Mighty Toads in battle, and even perform Jutsu with them. Along with this, you can learn their ancient art of Sage Mode Which will greatly increase your physical strength, and Jutsu. The more often you choose this, the more powerful you will get.`n`nBenefits:`n`@You learn to summon Toads in Battle, after Three Resets you begin to learn Sage Mode. `n`n`%This can be stacked up to %s times! You have chosen this already %s times. Every stacking makes the Toads and Jutsus stronger!",
						get_module_setting('maxstack'),
						get_module_pref('stack'),
						translate_inline(($doujutsu>0?"`n`n`c`b`\$You have already chosen another kekkei genkai, you cannot choose this one!`b`c":"`n`n`c`b`\$You cannot choose this AND another kekkei genkai!`b`c"))
						),
				'active'=>1,
				);
			break;
		case "circulum-chosen":
			if ($args['chosen']==='circulum_sage') {
				increment_module_pref('stack',1);
			}
			break;
		case "traininggrounds":
			if (!$isactive) break;
			addnav("Visit `2My`4ōb`2ok`4uz`2an","runmodule.php?module=circulum_sage");
			break;
	}
	return $args;
}

function circulum_sage_run()	{
	global $session;
	page_header("Training Grounds");
	addnav("Navigation");
	addnav("Back to the Academy","train.php");
	addnav("Actions");
	output("`#`b`c`n`2My`4ōb`2ok`4uz`2an`0`c`b`n`n");
	$op = httpget('op');
	switch ($op) {
		case "chat":
			$heard=array(
				'I have heard there is a war against the Mist coming up... unsettling...',
				'Naruto should be headed for me right now, he will never get me...',
				'Sakura must have cut her hair again... Hinata should be her example as hers is long and healthy.',
				'Chouji has started to eat Chakra. He says it is good for the liver. Poor boy, that won\'t reduce his weight.',
				'Lately, there have been no news.',
				'Somebody was in search for the ink required for the Seven Star Tattoo... interesting thingie... but a kid could paint it better...',
				'Orochimaru has no idea of style... at all...',
				'Ino? Well, at least she knows how to wear her hair...',
				'If Rock Lee would learn how to use chakra... hilarious...',
				'The Sound Five? Well, Orochimaru simply had not enough people to form a \'Dirty Dozen\'.',
				'Mitarashi Anko... she is cute when she is asleep... ups...',
				'No, your underwear has no style at all.',
				'Jiraiya... beneath the surface, there lies a sensible and lonely man...',
				'Shino... well, we are somewhat alike. Except for the bugs and the silence...',
				);
			$heard=translate_inline($heard);
			$choice=array_rand($heard);
			output("`YF`ju`@k`jas`@a`jk`Yu`x thinks a bit and then says, \"`4%s`x\"",$heard[$choice]);
			break;
		case "clone":
			output("`YF`ju`@k`jas`@a`jk`Yu`x chuckles, did you know you can leave a clone here to gather Natural Chakra? Well you can, then you can make it *Poof* when you need to enter Sage Mode, cool huh? It does cost you a bit of Chakra though, and it takes some time for it to charge, would you like to?");
			require_once("modules/specialtysystem/functions.php");
			if (specialtysystem_availableuses()>4) $link="runmodule.php?module=circulum_sage&op=activate";
			else $link="";
			addnav("Create Clone (5)",$link);
			break;
		case "activate":
			increment_module_pref("uses",5,"specialtysystem");
			set_module_pref('clone',1,'circulum_sage');
			apply_buff('kekkei_genkai_sage_clone',array(
				"startmsg"=>"`@Your clone sits at the Toad Mountain, gathering Natural Chakra.",
				"name"=>"`@Clone Gathering",
				"rounds"=>40,
				"wearoff"=>"`@Your clone is fully charged, and ready for use.",
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_sage"
			));
			output("`xYour form some Hand Seals `j'Kage Bushin no Jutsu!' `xA clone of yourself goes and starts Gathering Natural Chakra, though it will be some time before you can use it.");
			break;
		default:
		output("`xYou disappear in a puff of smoke, being reverse summoned to the secret Toad Mountain. You see your follow Toads around. `n");
		output("`xThe Elder Toad `YF`ju`@k`jas`@a`jk`Yu`x is here. What do you want to talk to him about?`n`n");
		addnav("Idle Chitchat","runmodule.php?module=circulum_sage&op=chat");
		if (get_module_pref('clone','circulum_sage')) output("`xYour clone sits under the Toad Oil Fountain, Gathering Natural Chakra.`n");
		elseif (!get_module_pref('clone','circulum_sage') && get_module_pref('stack','circulum_sage')>4) addnav("Ask about Clones","runmodule.php?module=circulum_sage&op=clone");		
		break;
	}
	page_footer();
}

?>
