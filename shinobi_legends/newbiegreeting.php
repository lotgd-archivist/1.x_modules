<?php

function newbiegreeting_getmoduleinfo(){
	$info = array(
		"name"=>"Greet the n00bs",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Administrative",
		"download"=>"",
		"prefs"=>array(
			"Greet the n00bs User Preferences,title",
			"displayed"=>"Has the n00b message been displayed?,bool|0",
		),
	);
	return $info;
}

function newbiegreeting_install(){
	module_addhook("newday-intercept");
	return true;
}

function newbiegreeting_uninstall(){
	return true;
}

function newbiegreeting_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "newday-intercept":
			if ($session['user']['dragonkills']==0 && !get_module_pref('displayed')) {
				page_header("Welcome new shinobis!");
				newbiegreeting_display();
				set_module_pref("displayed",1);
				require_once("modules/inventory/lib/itemhandler.php");
				add_item_by_name("`4Kun`)ai");
				add_item_by_name("`4Kun`)ai");
				add_item_by_name("`2Apple");
				add_item_by_name("`QExplosive `qTag");
				add_item_by_name("`!Shuriken");
				add_item_by_name("`!Shuriken");
				addnav("","newday.php");
				$enter=translate_inline("Click here to enter the realm!");
				rawoutput("<br><br> <center><h3><a href='newday.php'>$enter</a></center>");
				page_footer();
			}
			break;
	}
	return $args;
}

function newbiegreeting_run(){
	return true;
}

function newbiegreeting_display(){
	output("`c`b`i`4Welcome new shinobi`i`b`c`n`n");
	output("`vI'd like to take the opportunity to give you a first impression on what kind of game you have to expect here and what part you can fit in. It's a general 'warm welcome' as well as a short summary of what the game is about. Be also sure to read `\$the FAQ`v at the left hand sidebar once you enter your home village.`n`n");
	output("`tKonoha, the city where `lNaruto`t resides, is the main 'capital' this world features. There are more villages around it, for the Sound, Mist, Leaf (=inhabitants of Konohagakure) and Sand shinobi... also there are rumours about other places you can go. But this is not something you, being an `lAcademy Student`t should do. ");
	output("`nYour primary aim is... to defeat others in combat and to gain strength... which is here displayed as you rise in Level... but this is only a part of it. Even with Level 15 (which is the highest achievable), you are still an `lAcademy Student`t... and probably you want to become Genin, Chuunin and even more... like `lNaruto`t, who wants to be `\$Hokage`t one day. This is hard work, believe me, but not something you cannot achieve. Though you won't really rule a country there (imagine 1 village for every player that has achieved a certain rank...), you will have some unique stuff you can currently only dream of.");
	output("`n`nWell then, you should know three places... `2the forest`t, `gShizune's Weaponshop`t and `@Kurenai's Armor`t. You can only access them at your home city... so don't stroll around too much if you want to buy new equipment. You can buy your armor and  weapon there... you can only have one main weapon and one main armor at a time, as usually shinobi just wear one set. Also, don't forget to visit Sakura in order to get healed ... or you'll perish soon to `\$Shinigami`t... `4where you will stay until a new day has begun.`t");
	output("`n`nYou will get a `vstarting pack including 2 sets of Kunai (3 Kunai each set), 2 sets of Shuriken (2 Shuriken each), an apple (nourishment) and one explosive tag`t when you leave the academy (which is where the game starts). Note that `^you also start with %s gold pieces`t, which is the currency here, along with the most precious gems who are scarcely found.",getsetting('newplayerstartgold',0));
	output("`n`nYour primary goal, even when you choose to be a Sound shinobi, is to defeat Orochimaru once your have reached the level to do so. Once you have done so, you will receive the next rank (genins and above require more Orochimaru Kills to get a new rank, yet they will receive a differently coloured rank).`n`n");
	output("`gThis game is meant to be a `\$role-playing game`g, which means you should talk and interact with the other users (not necessarily only kill them in the fields) in order to build friendships (and make enemies) and to get into a clan one day. Alone and 'young' you will soon find out the world is harsh...");
	output("`n`nJust one reminder: Please honour the server rules, the game should be fun for all, not for a few at the expense of the others. Thank you.");
	output("`n`n`2Some more informations:`n");
	output("Turn per day: %s`n",getsetting('turns',10));
	output("Length of a game day: %s hours`n",24/getsetting('daysperday',6));
	output("Fights against other players (PvP) per day: %s`n",getsetting('pvpday',1));
	output("`\$Make sure you login at least after %s days again if you have not killed Oro once, or your account will expire`2 (email notification 24h in advance coming)!",getsetting('expirenewact',10));
	output("`n`n`l`iStaff of Shinobilegends`i");	
	return;
}


?>
