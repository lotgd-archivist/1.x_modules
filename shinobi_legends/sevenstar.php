<?php
/* note:
	if you use the item system by XChrisX, make sure to put an item with
	$hasink=get_item_by_name('Special Ink');
	the name "Special Ink" into your system, make it unique for player, drop it into the loot section if you want to let it drop by creatures
	and add a description to it, making it clear it is the ink for the star tattoo
	INSERT INTO `item` ( `class`, `name`, `description`, `gold`, `gems`, `weight`, `droppable`, `level`, `dragonkills`, `buffid`, `charges`, `link`, `hide`, `customvalue`, `execvalue`, `exectext`, `noeffecttext`, `activationhook`, `findchance`, `loosechance`, `dkloosechance`, `sellable`, `buyable`, `uniqueforserver`, `uniqueforplayer`, `equippable`, `equipwhere`) VALUES
('Quest Item', 'Special Ink', 'Hmm, some special ink... look like one used by tattoo artists. I wonder what it is good for.', 5, 0, 1, 1, 1, 8, 0, 0, '', 0, '', '', '', '', '0', 10, 70, 100, 0, 0, 0, 1, 0, '');
	you need the EXACT NAME "Special Ink" !!!!

*/
function sevenstar_getmoduleinfo(){
	$info = array(
		"name"=>"Seven Star Tattoo",
		"author"=>"Chris Vorndran",
		"version"=>"1.0",
		"category"=>"Extraordinary Abilities",
		"settings"=>array(
			"Seven Star Tattoo - Settings,title",
				"npc-name"=>"NPC's name `ifor Neji`i,text|`^Delighted `\$Petra",
				"chance"=>"Chance the tattoo is transferred?,range,0,100,5|10",
				"name"=>"Name of the Tattoo:,text|`&Seven `t`bS`vta`tr `vT`\$a`ttt`\$o`vo`b",
				"min-dk"=>"Minimum amount of dks to get the warrior,int|8",
		),
		"prefs"=>array(
			"Seven Star Tattoo - Preferences,title",
				"tattoo-stage"=>"What stage of the Tattoo is the player at?,enum
							,0,None,1,1 Star,2,2 Stars,3,3 Stars,4,4 Stars,5,5 Stars,6,6 Stars,7,Complete Set|0",
				"days"=>"Days left to heal,int|0",
				"hastat"=>"Does the player have the skin?,bool|0",
				"promise"=>"Is the player ready for the tattoo?,bool|0",
				"todaylevel2"=>"How often used the next level today?,int|0",
		),
		"requires"=>array(
			"petra"=>"1.0|Petra the Tattoo Artist",
			"inventory"=>"1.0|XChrisX",
		),
	);
	return $info;
}
function sevenstar_install(){
	module_addhook("newday");
	module_addhook("footer-petra");
	module_addhook("petracolor");
	module_addhook("tattoo_b_gone");
	module_addhook_priority("bioinfo",20);
	module_addhook_priority("fightnav-specialties",1);
	module_addhook("apply-specialties");
	module_addeventhook("forest","require_once(\"modules/sevenstar/chance.php\");return sevenstar_chance();");
	module_addhook_priority("runevent_ladyerwin",50,"sevenstar_specialdohook");
	module_addhook_priority("runevent_erosennin",50,"sevenstar_specialdohook");
	module_addhook_priority("runevent_stumble",50,"sevenstar_specialdohook");
	module_addhook_priority("runevent_fairy",50,"sevenstar_specialdohook");
	return true;
}
function sevenstar_uninstall(){
	return true;
}

function sevenstar_specialdohook($hookname,$args){
	global $session;
	if (get_module_pref("tattoo-stage")==0 || get_module_pref("tattoo-stage")==7) return $args;
	require_once("modules/inventory/lib/itemhandler.php");
	$item=check_qty_by_name("Special Ink");
	if ($item>0) return $args; //unique
	//require("modules/sevenstar/specialdohook/$hookname.php");
	switch ($hookname) {
		case "runevent_ladyerwin":
			if (e_rand(0,50)!=0) return $args; //random
			if (httpget('op')!='') return $args;
			addnav("Check out the vial","runmodule.php?module=sevenstar&origin=ladyerwin");
			output("`v`n`nYou also note a small little vial hidden in a bush.");
			break;
		case "runevent_erosennin":
			if (httpget('op')!='') return $args;
			addnav(array("Ask him about the %s`0",get_module_setting("name")),"runmodule.php?module=sevenstar&origin=erosennin");
			break;
		case "runevent_stumble":
			if (e_rand(0,55)!=0) return $args; //random
			if ($session['user']['hitpoints']<0) return $args;
			output("`v`n`nYou are `\$furious`v... and dig out that damn bunnies hideout... and you are very much suprise to find some `tink`v for the %s`v right beside some stored grass.",get_module_setting("name"));
			add_item_by_name("Special Ink");
			break;
		case "runevent_fairy":
			if (httpget('op')!='give' || e_rand(0,50)!=0 || ($session['user']['gems']<1)) return $args;
			output("`n`n`%The nice little fairy also notes your %s`% and gives you some `\$Special Ink`%!!!",get_module_setting("name"));
			add_item_by_name("Special Ink");
			break;
	}
	return $args;
}


function sevenstar_dohook($hookname,$args){
	global $session;
	require("modules/sevenstar/dohook/$hookname.php");
	return $args;
}
function sevenstar_runevent($type){
	global $session;
	require("modules/sevenstar/runevent.php");
}
function sevenstar_run(){
	global $session;
	$op = httpget('op');
	$stage = httpget('stage');
	$origin=httpget('origin');
	switch ($origin) {
		case "erosennin":
			page_header("Asking Ero-Sennin!");
			output_notl("`n");
			output("`3You are asking him about the `\$Special Ink`3 and whether he has some or not.`n`n");
			if (e_rand(0,55)==0) {
				output("\"`QOhhh... well, for the %s`Q is it not? .... You seem like a good person, so I will give you my very very last vial. Grow up to being a strong shinobi!",get_module_setting("name"));
				if ($session['user']['sex']==SEX_MALE) {
						output(" And don't forget to take a good look at some nice babes *twinkle* `3\"`n`n");
					} else {
						output(" And let your grapes ripe until they are good and well... harhar `3\"`n`n");
					}
				output("`3You get some `\$Special Ink`3 for your %s`3!",get_module_setting("name"));
				require_once("modules/inventory/lib/itemhandler.php");
				add_item_by_name("Special Ink");
				addnav("Return to the forest","forest.php");
				$session['user']['specialinc']='';
			} else {
				switch(e_rand(0,4)) {
					case 0:
						output("`3\"`QOhhh... well, for the %s`Q is it not? .... Sorry, I just gave my last vial to a real bijou! What a babe!`3\"`n`n",get_module_setting("name"));
						addnav("Return to the forest","forest.php");
						$session['user']['specialinc']='';
						break;
					case 1:
						output("`3\"`QOhhh... well, for the %s`Q is it not? .... Nah, you don't need it, you need more sex-appeal. Now go away!`3\"`n`n",get_module_setting("name"));
						addnav("Return to the forest","forest.php");
						$session['user']['specialinc']='';
						break;
					case 2:
						output("`3\"`QUhm, never heard of that one, sorry.`3\"`n`n",get_module_setting("name"));
						addnav("Return to the forest","forest.php");
						$session['user']['specialinc']='';
						break;
					case 3:
						output("`3\"`QOhhh... no. I have no idea what you are talking about. I just thought of some nice ripe.... ahh... nevermind.!`3\"`n`n",get_module_setting("name"));
						addnav("Return to the forest","forest.php");
						$session['user']['specialinc']='';
						break;
					case 4:
						output("`3\"`QIf you want some, you need to train more! Here is some extra training for you!`3\"`n`n",get_module_setting("name"));
						addnav("Oh oh...","forest.php?op=combat");
						output("A big frog warrior appears right before you... and attacks immediately.");
					 	$badguy = array(
						"creaturename"=>translate_inline("a Greater Frog Warrior"),
			            "creaturelevel"=>$session['user']['level']+1,
			            "creatureweapon"=>translate_inline("Two scimitars"),
						"creatureattack"=>$session['user']['level']+$session['user']['dragonkills']+3,
						"creaturedefense"=>$session['user']['defense']+$session['user']['dragonkills'],
						"creaturehealth"=>($session['user']['level']*13+round(e_rand($session['user']['level'],($session['user']['maxhitpoints']-$session['user']['level']*10)))),
						"diddamage"=>0,
						"hidehitpoints"=>true,
						);
						$session['user']['badguy'] = createstring($badguy);
						$session['user']['specialinc']='module:erosennin';
						break;
					}
				}
			break;
		case "ladyerwin":
			page_header("You are lucky!");
			output_notl("`n");
			output("`vYou are very very lucky... you examine the bottle and discover it is the much needed `\$Special Ink`v for your %s`v!`n`nYou immediately grab the vial and run away.`n`n`tAmazingly, you have an eerie feeling that this white little rat has something to do with it... *shiver*...",get_module_setting("name"));
			require_once("modules/inventory/lib/itemhandler.php");
			add_item_by_name("Special Ink");
			addnav("Return to the forest","forest.php");
			$session['user']['specialinc']='';
			break;
		default:
		require("modules/sevenstar/runpetra.php");
	}
	page_footer();
}
?>
