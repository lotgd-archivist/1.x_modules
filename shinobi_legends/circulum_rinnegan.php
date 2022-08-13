<?php

function circulum_rinnegan_getmoduleinfo(){
	$info = array(
	    "name"=>"Circulum Vitae - Rinnegan",
		"description"=>"Rinnegan",
		"version"=>"1.0",
		"author"=>"`LShinobiIceSlaeyer`~, based on work by, `4Oliver Brendel`0",
		"category"=>"Circulum Vitae",
		"download"=>"",
		"settings"=>array(
			"Circulum Vitae - Preferences,title",
				"maxstack"=>"How many times can this be stacked?,range,1,10,1|6",
			),		
		"prefs"=>array(
		    "Circulum Vitae - User prefs,title",
				"stack"=>"Number of CVs the player has chosen this benefit,int|0",
				"pathsused"=>"What paths has the user summoned today?,viewonly",
			),
		"requires"=>array(
			"circulum"=>"1.0|Circulum Vitae by `2Oliver Brendel",
			),
		);
    return $info;
}

function circulum_rinnegan_install() {
	module_addhook("circulum-items");
	module_addhook("biostat");
	module_addhook("circulum-chosen");
	module_addhook("traininggrounds");
	module_addhook("newday");
	module_addhook("dwellings");
	
	return true;
}

function circulum_rinnegan_uninstall() {
  return true;
}


function circulum_rinnegan_dohook($hookname, $args) {
	global $session;
	$stack=(int)get_module_pref('stack','circulum_rinnegan');
	$isactive=($stack>0?true:false);
	$canbechosen=$stack<get_module_setting('maxstack');
	$dojutsu=(int)get_module_pref('stack','circulum_sage');//+(int)get_module_pref('stack','circulum_hyuuga');
	if ($stack<get_module_setting('maxstack') && $dojutsu<1) {
		$canbechosen=true;
	} else $canbechosen=false;		
	switch($hookname) {
		case "newday":
			if ($isactive) {
				set_module_pref('failed',0);
				require_once('modules/rinnegan/dohook/newday.php');
			}
			break;
		case "dwellings":
			require_once("modules/rinnegan/functions.php");
			$paths = check_paths();
			if ($paths['human_path']) {
				addnav("Human Path");
				addnav("Get Information","runmodule.php?module=circulum_rinnegan&op=seek");
			}
			break;
		case "biostat":
			$stack=(int)get_module_pref('stack','circulum_rinnegan',$args['acctid']);
			if ($stack==0) break;
			$ranks=array (
				'`%One Path',
				'`%Two Paths',
				'`%Three Paths',
				'`%Four Paths',
				'`%Five Paths',
				'`%Six Paths',
				);
			if ($stack>count($ranks)) $stack=count($ranks);
			output("`xBorn as the Sage of %s`x.`0`n",$ranks[$stack-1]);
			break;
		case "circulum-items":
			$args[]=array(
				'modulename'=>'circulum_rinnegan',
				'nav'=>translate_inline('Rinnegan'),
				'category'=>'Sage Arts',
				'exclusive_in_category'=>true,
				'description'=>
					sprintf_translate("`xYou are born as the Sage of Six Paths, which means your body is carrying a unique Kekkei Genkai: The Legendary `%R`Vinnegan`x! A Dojutsu so rare it is thought by many to be nothing more than a myth. It lets you see Chakra, gives you mastery over all chakra types, and the ability to use the Six Paths of Pain, which when multiple bodies are used together, share their field of vision. The more often you choose this, the more powerful you will get.`n`nBenefits:`n `\$-You have the constant `%R`Vinnegan`\$ buff, and the ability to learn any specialty at any time. `3Visit the training grounds to gain more Chakra types. `\$`n-Six Paths of Pain Jutsu sets availible, each set is stronger than a normal jutsu set. `n`n`%This can be stacked up to %s times! You have chosen this already %s times. Note that you unlock a new Path of Pain each Reset!",
						get_module_setting('maxstack'),
						get_module_pref('stack'),
						translate_inline(($dojutsu>0?"`n`n`c`b`\$You have already chosen another dojutsu, you cannot choose this one!`b`c":"`n`n`c`b`\$You cannot choose this AND another dojutsu(like Sharingan)!`b`c"))
						),
				'active'=>1,
				);
			break;
		case "circulum-chosen":
			if ($args['chosen']==='circulum_rinnegan') {
				increment_module_pref('stack',1);
			}
			break;
		case "traininggrounds":
			if (!$isactive) break;
			addnav("Go to Nagato","runmodule.php?module=circulum_rinnegan");
			break;
	}
	return $args;
}

function circulum_rinnegan_run() {
	global $session;
	$op = httpget('op');
	require_once("modules/rinnegan/run/case_$op.php");
	page_footer();
}

?>
