<?php


function fuujinraijin_getmoduleinfo(){
	$info = array(
		"name"=>"Fuujin & Raijin",
		"author"=>"`2Oliver Brendel`0, based on tatmonster by Sichae",
		"version"=>"1.0",
		"category"=>"Forest Specials",
		"download"=>"",
		"settings"=>array(
			"Fuujin Raijin Settings,title",
			"mincharmloss"=>"Minimum charm loss upon death,int|1",
			"maxcharmloss"=>"Maximum charm loss upon death,int|10",
			"expgain"=>"Experience multiplier,floatrange,0,1,.1|.1",
			"hploss"=>"HP to take the monster down per tattoo on the user,int|10",
			"dk"=>"Under what DK do users get granted 'grace' Tattoos (makes monster easier),int|10",
			"grace"=>"How many tattoos to count as a 'grace' for low DK players,int|2",
			"name1"=>"Name of Monster 1,text|`yF`muujin",
			"name2"=>"Name of Monster 2,text|`yR`maijin",
		),
		"requires"=>array(
			"petra"=>"1.0| by Shannon Brown,Part of Core Download",
			"inventory"=>"1.0| Item System by XChrisX, modified by `2Oliver Brendel",
		),
	);
	return $info;
}

function fuujinraijin_install(){
	module_addeventhook("forest", "return 1;");
	return true;
}

function fuujinraijin_uninstall(){
	return true;
}

function fuujinraijin_runevent($type,$link){
	global $session;

	// Handle the case where Petra gets deactivated.
	if (!is_module_active("petra")) {
		output("You hear a rustling in the underbrush, which dies away after a few moments.`n`n");
		output("When nothing at all happens after a couple of minutes, you continue on your way.");
		return;
	}
	$food=array('`$Red `2Apple','`2Cabbage','`qDried `2Fruit','`$F`@ruit `$B`@owl','`vRa`tmen `$Bowl','`1Seafood `vRa`tmen','`$Red `2Apple','`QOrange','`2Apple','`4S`2h`4i`2s`4h K`2a`4b`2o`4b','`!Instant `vRa`tmen');	
	$name1=get_module_setting('name1');
	$name2=get_module_setting('name2');
	$op = httpget('op');
	require_once("lib/partner.php");
	$partner = get_partner();
	$session['user']['specialinc'] = "module:fuujinraijin";
	$battle = false;

	switch ($op){
	case "":
	case "search":
		output("`3Walking down a deserted trail, you hear a rustling sound coming from the bushes.");
		output("You can smell something burning, and hear something churning.");
		output("You have no idea if you should check it out, but your curiosity is getting the upper hand.");
		output("`3As you step closer and closer to the bush, the burning scent gets more pronounced and the churning grows louder and louder.`n`n");
		if (get_module_pref("tatnumber", "petra") > 0)
			output("You feel a brief burning sensation from the tattoos on your arm, as if they are reacting to something!`n`n");
		output("`3There is a feeling of dread, deep in your bones.");
		output("Do you want to wait and see what is making the noise, or flee?");
		addnav("W?Wait", $link. "op=wait");
		addnav("R?Run", $link. "op=flee");
		break;
	case "flee":
		$charmloss = e_rand(get_module_setting("mincharmloss")*2,
				get_module_setting("maxcharmloss") *2);
		output("`3Turning around, you hasten back the way you came.");
		output("With a glance backwards, you see a stray cat come out of the trees.`n`n");
		output("Face red with shame, you don't know if you'll ever be able to let %s`3 know that you got scared by a cat!`n`n",$partner);
		output("You lose %s charm from the shame of your cowardice.",
				$charmloss);
		debuglog("lost $charmloss charm from cowardice to the fuujinraijin");
		$session['user']['charm'] -= $charmloss;
		if ($session['user']['charm'] < 0)
			$session['user']['charm'] = 0;
		$session['user']['specialinc'] = "";
		break;
	case "wait":
		rawoutput("<center><img src='modules/fuujinraijin/fuujinraijin.jpg' alt='Baka Kyoudai'></center>");
		output("`3A large man suddenly leaps out of the bushes yelling, \"`4Food---------!!!`3\". `nYou jump back avoid him but bump into the belly of another large man.`n`n\"`4Give us food---------!!!`3\", he yells as he tries to grab you. `n`n");
		output("You manage to avoid being grabbed, but now you find yourself being surrounded by The Legendary Stupid Brothers, %s`3 and %s`3.",$name1,$name2);
		output("Circling around you, they block your escape!`n");
		addnav("Fight",$link . "op=pre");
		require_once("modules/inventory/lib/itemhandler.php");
		foreach ($food as $single) {
			$amount=(int)check_qty_by_name($single);	
			if ($amount>0) {
				addnav("Offer food from your backpack",$link."op=offer");
				break;
			}
		}
		break;
	case "offer":
		require_once("modules/inventory/lib/itemhandler.php");
		$inv=fuujinraijin_getfood($food);
		$amount=array_sum($inv);
		rawoutput("<center><img src='modules/fuujinraijin/fuujinraijin.jpg' alt='Baka Kyoudai'></center>");	
		switch (httpget('action')) {
			case "full":
				output("`3As you tell them you offer them all your food while handing over your rations in your backpack, they instantly start to drool and lose all interest in you...");
				if ($amount>10) {
					output("they are munching your entire food stock in... and you know it's better to run before they might want to bite into something... shinobi-like...`n");
					fuujinraijin_eat($inv,round($amount));
					$session['user']['specialinc']='';
				} else {
					output("but somehow it does not seem to be enough... they look at you with `\$appetite`3...");
					addnav("Fight",$link . "op=pre");
				}
				
				break;
			case "half":
				output("`3You talk to them about being only a poor nin and having so little to eat, but you can spare half your meals with them... obviously they don't get all you can say... but a \"`4Hand over foooooooood`3\" convinces you to simply pass it over.`n`n");
				$luck=e_rand(0,1);
				if ($amount<20) $luck=0; //muhahaha
				switch ($luck) {
					case 1:
						output("You are lucky... it was enough and they start to eat and forget about you... you can espace safely from these monsters...`n`n");
						fuujinraijin_eat($inv,round($amount/2));
						$session['user']['specialinc']='';
						break;
					case 0: 
						output("Somehow, you don't think that was the right amount to share... they seem to be more hungry now... and get combat-ready...`n`n");
						addnav("Fight",$link . "op=pre");
						break;
				}
				break;
			case "quarter":
				output("`3You talk to them about being only a poor nin and having so little to eat, but you can spare a quarter of your meals with them... obviously they don't get all you can say... but a \"`4Hand over foooooooood`3\" convinces you to simply pass it over.`n`n");
				$luck=e_rand(0,1);
				if ($amount<40) $luck=0; //muhahaha
				switch ($luck) {
					case 1:
						output("You are lucky... it was enough and they start to eat and forget about you... you can espace safely from these monsters...`n`n");
						fuujinraijin_eat($inv,round($amount/4));
						$session['user']['specialinc']='';
						break;
					case 0: 
						output("Somehow, you don't think that was the right amount to share... they seem to be more hungry now...`n`n");
						addnav("Fight",$link . "op=pre");
						break;
				}
				break;
			default:
				debug("Am:".$amount);
				debug($inv);
				output("`3They hesitate as you cram down your backpack, telling them you will give them a big snack... you check your inventory on edible stuff...`n`n");
				output("You have:`n`^");
				require_once("modules/inventory/lib/itemhandler.php");
				foreach ($inv as $single=>$am) {
					if ($am>0) {
						output("%s `%%s`0.`n",$single,$am);
					}
				}

				if ($amount>=4) {
					addnav("Offer them about a quarter of your food",$link."op=offer&action=quarter");
				}
				if ($amount>=1) {
					addnav("Offer them about half your food",$link."op=offer&action=half");
				}
				addnav("Offer all your food",$link."op=offer&action=full");
		}
		break;
	case "pre":
		$op = "fight";	
		httpset("op",$op);

		// accommodate for data left from older versions of petra
		require_once("modules/petra.php");
		petra_calculate();

		// Lets build the Tat Monster NOW!
		$numtats = get_module_pref("tatpower", "petra");
		if ($session['user']['dragonkills'] <= get_module_setting("dk"))
			$numtats += get_module_setting("grace");

		$hpl = get_module_setting("hploss")*$numtats;

		// the test needs to be changed so that it no longer
		// either assumes that one can only obtain ten tattoos,
		// or that $numtats is an integer
		// JT: changed to 8.4 so existing behaviour was preserved.
		if (floor($numtats) == $session['user']['dragonkills'] ||
				$numtats >= 8.4) {
			$monhp = round($session['user']['maxhitpoints']*1.1)-$hpl;
			$monatk = round($session['user']['attack']*1.05);
			$mondef = round($session['user']['defense']*1.05);
		}else{
			$monhp = round($session['user']['maxhitpoints']*1.5)-$hpl;
			$monatk = round($session['user']['attack']*1.15);
			$mondef = round($session['user']['defense']*1.15);
		}
		// If we have too small hp, then just set the monster = to
		// the players hitpoints + 20 %.
		if ($monhp <= 10)
			$monhp = round($session['user']['maxhitpoints'] * 1.2);

		// even out his strength a bit
		if ($session['user']['level'] > 9) $monhp*=1.05;

		$fuujin = array(
			"creaturename"=>get_module_setting("name1"),
			"creatureweapon"=>translate_inline("`gSlobbering Mouth"),
			"creaturelevel"=>$session['user']['level']+1,
			"creaturehealth"=>round($monhp),
			"creatureattack"=>$monatk+3,
			"creaturedefense"=>$mondef+3,
			"noadjust"=>1,
			"diddamage"=>0,
			"creatureaiscript"=>"require('modules/fuujinraijin/fuujin.php');",
			"type"=>"fuujin",
		);
		$raijin = array(
			"creaturename"=>get_module_setting("name2"),
			"creatureweapon"=>translate_inline("`MCrushing Fist"),
			"creaturelevel"=>$session['user']['level']+1,
			"creaturehealth"=>round($monhp+100),
			"creatureattack"=>$monatk,
			"creaturedefense"=>$mondef,
			"noadjust"=>1,
			"diddamage"=>0,
			"creatureaiscript"=>"require('modules/fuujinraijin/raijin.php');",
			"type"=>"fuujin",
		);
		$stack=array($fuujin,$raijin);
		$attackstack=array('enemies'=>$stack,'options'=>array('type'=>'fuujinraijin'));
		$session['user']['badguy'] = createstring($attackstack);
		break;
	}

	if ($op == "fight"){
		$battle = true;
	}

	if ($battle){
		$enemies = @unserialize($session['user']['badguy']);
		if (count($enemies)==2) {
			$pic='fuujinraijin';
		} elseif ($enemies[0]['dead']) {
			$pic='raijin';
		} elseif ($enemies[1]['dead']) {
			$pic='fuujin';
		} else {
			$pic='defeat';
		}
		rawoutput("<center><img src='modules/fuujinraijin/$pic.jpg' alt='Baka Kyoudai'></center>");
		include("battle.php");
		if ($victory){
			output("`n`n`3You have overcome the Legendary Baka Kyoudai!");
			output("The sensation in your arms slowly fades, and you return to normal.`n`n");
			output("You approach these knocked-out monstrosities, to ensure that they truly are dead.");
			output("As you near them, one of the heads slowly opens an eye!");

			if (get_module_pref("tatnumber", "petra") > 0) {
				output("One of them catches sight of the tattoos on your arms and recoils in horror!");
			}
			output("The head twitches some more, and you realize that you have done well even to subdue them, and you had best not remain to give them another chance when they recover.`n`n");
			if ($session['user']['hitpoints'] <= 0) {
				output("`^With the last of your energy, you press a piece of cloth to your wounds, stopping your bloodloss before you are completely dead.`n");
				$session['user']['hitpoints'] = 1;
			}
			$exp = round($session['user']['experience'] *
					get_module_setting("expgain"));

			
			// even out the gain a bit... it was too huge at the top and pathetic at the bottom
			if ($session['user']['level'] > 9) $exp *= 0.8;
			if ($session['user']['level'] < 6) $exp *= 1.2;
			if ($session['user']['level'] == 1) $exp += 20; // to stop people sometimes gaining 2 xp
			$exp = round($exp);

			output("`3Achieving this grand feat, you receive `^%s `3experience.`0",$exp);
			$session['user']['experience']+=round($exp);
			$badguy=array();
			$session['user']['badguy'] = "";
			$session['user']['specialinc'] = "";
		}elseif($defeat){
			$badguy=array();
			$session['user']['badguy'] = "";
			$session['user']['specialinc'] = "";
			output("`n`n`3With one final crushing blow, they level you.");
			if ($session['user']['gold'] > 10) {
				output("As the blood escapes your body, your purse splits and yields some of your gold.");
				$lost = round($session['user']['gold']*0.2, 0);
				$session['user']['gold'] -= $lost;
				debuglog("lost $lost gold to the fuujinraijin");
			}
			$exp = round($session['user']['experience'] *
					get_module_setting("expgain"));
			output("Feeling the pain of loss, you lose `^%s `3experience.`0",
					$exp);
			$session['user']['experience']-=$exp;
			if (e_rand(0,2) == 2) {
				$charmloss = e_rand(get_module_setting("mincharmloss"),
						get_module_setting("maxcharmloss"));
				output("The brothers scratch you all over as they search you for food.... leaving a long, jagged scar on your skin, causing you to lose `5%s `3charm. Also they are eating up ALL your edible inventory items!`0",$charmloss);
				fuujinraijin_eat($inv,$amount);
				$session['user']['charm']-=$charmloss;
				debuglog("lost $charmloss charm to the fuujinraijin");
				if ($session['user']['charm'] < 0)
					$session['user']['charm'] = 0;
			}
			output("You are able to cling to life... but just barely.`0");
			$session['user']['hitpoints']=1;
		}else{
			require_once("lib/fightnav.php");
			if ($type == "forest"){
				fightnav(true, false);
			}else{
				fightnav(true, false, $link);
			}
		}
	}
}

function fuujinraijin_getfood($food) {
	$inv=array();
	foreach ($food as $single) {
		$amount=(int)check_qty_by_name($single);	
		if ($amount>0) {
			$inv[$single]=$amount;
		}
	}	
	return $inv;
}

function fuujinraijin_eat($food,$amount) {
	$decrease=array();
	for ($i=0;$i<$amount;$i++) {
		$key=array_rand($food);
		$decrease[$key]+=-1;
		if (($food[$key]+$decrease[$key])==0) $food=array_diff_key($food,array($key=>'whatever')); //kick out empty positions.
		//debug("Food $key: ".$food[$key]." / ".$decrease[$key]);
	}
	foreach ($decrease as $what=>$am) {
		output("`tYou lose `\$%s`t of your stock position '`4%s`t'`n",-$am,$what);
		remove_item_by_name($what,-$am);
	}
	return;
}
?>
