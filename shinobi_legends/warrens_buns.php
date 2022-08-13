<?php

function warrens_buns_getmoduleinfo(){
	$info = array(
		"name"=>"Warren's Bun Event",
		"version"=>"1.0",
		"author"=>"Warren",
		"category"=>"Forest Specials",
		"download"=>"",
	);
	return $info;
}

function warrens_buns_install(){
	module_addeventhook("forest","return 50;");
	return true;
}

function warrens_buns_uninstall(){
	return true;
}

function warrens_buns_dohook($hookname,$args){
	return $args;
}

function warrens_buns_runevent($type)
{
	require_once("lib/buffs.php");
	require_once("lib/commentary.php");
	addcommentary();

	global $session, $playermount;
	$from=$type.".php?";
	$session['user']['specialinc'] = "module:warrens_buns";

	$op = httpget('op');
	addnav("Actions");
	
	if ($op=="return_forreal") {
		$session['user']['specialmisc']="";
		$session['user']['specialinc']="";
		redirect($from);
	} elseif ($op=="return") {
		if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) {
			output("`7Although you find the sight most intriguing, you feel it isn't your place to interrupt their fun, thus choose to depart in the end. And yet despite having lingered just a moment, you find yourself feeling `2much better`7 than before.`n`n
			`2You are healed!");
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
		} else {
			output("`7Although you find the sight most intriguing, you feel it isn't your place to interrupt their fun, thus choose to depart in the end. As you go you find a drop of the spirited mood lingers with you, leaving you feeling `Lmore vigorous`7 than before.`n`n
			`LYou gain a turn!");
			$session['user']['turns']++;
		}
		addnav("Now off you go...",$from."op=return_forreal");
		return;
	}

	checkday();
	output("`n`c`#Something very much special, really, you're jumping out of your `\$pants!`# `c`n`n");
	//addnav("Return to the forest", $from . "op=return");

	switch ($op) {
		case "furry":
			output("`7Approaching with care, making a bit of noise to be easily spotted to not frighten the group, you introduce yourself and ask whether you could linger a little while to take a break from adventuring. Once permitted, you take a seat beside the three and engage in minor chit-chat while observing their doings.`n`n
It doesn't take you long to realize that despite the overcast weather you still feel as if sitting in straight sunshine, and unless you were imagining things the source of the sensation was the smallest of the trio. Quite the interesting little fellow in his green hoodie, reminding you of a diminutive rabbit hermit with the sleeked back ears atop of his hood, the `Qcarrot`7 emblem on the back and even a tail-like poof down below it.`n`n
`2\"Nishishi, loohkee\"`7`n`n
And yet for all the mystique, he turned out to be just a seemingly ordinary green-eyed boy after casting the hood back, even if unusually bright by disposition. His grin, golden hair and cheery demeanor made him feel almost like a piece of sunshine in human form, nearly making you squint at times.`n`n
`2\"Ish bun~\"`7`n`n
Quite the expert handler of the creatures too it appeared for before you could even respond he had already placed a rabbit onto your lap, the animal not protesting one bit; if anything it and he alike cast an expecting look up at you. `3'Well what harm could it be'`7 you thought...and were richly rewarded for your trust, for running your fingers along the back of the now squinty-eyed rabbit felt smoother than silk.`n`n
In the end you barely remember what the boy had even babbled to you about afterward due to his partly garbled speech, and yet even after you departed you still felt like a part of his shine lingered with you for a little while.`n`n`@You feel revitalized!");
			apply_buff('warren_buns_1',
				array(
					"name"=>"`lMemory of the Sun",
					"rounds"=>10,
					"wearoff"=>"The pleasant memory fades.",
					"defmod"=>1.1,
					"regen"=>$session['user']['level']*log($session['user']['dragonkills']),
					"roundmsg"=>"You feel energized by the sunshine!",
					"schema"=>"module-warrens_buns",
					)
				);
			addnav("Leave",$from."op=return_forreal");		
			break;
		case "ruffle":
			output("`7Approaching with care, making a bit of noise to be easily spotted to not frighten the group, you introduce yourself and ask whether you could linger a little while to take a break from adventuring. Once permitted, you take a seat beside the three and engage in minor chit-chat while observing their doings.`n`n
			Your attention is most drawn by the oldest of the group, a blonde early teen with his already messy short hair further toussled by the goggles he sported around his forehead. With a minor jest noting the plentiful fur all over his light grey attire, you mention the quite a grooming operation he appears to have going on, only for his blue eyes to light up like jewels as he begins explaining his newest invention. `n`n
			`3\"I recommend the third setting unless the fur is especially long\"`n`n
			`7An adjustable brush, for lack of a better name. Giving the handle a twist as per his instructions, you find the shape and placement of bristles rearranging to a whole different configuration. `n`n
			`3\"Give it a go, its quite safe I swear!\"`n`n
			`7Your initial skepticism is rewarded once you spare a careful brush along the back of the nearest inquisitive rabbit with the brush, and in mere moments a whole line of rabbits had formed as each awaited their turn to be groomed. You aren't sure whether the boy's grin is pride over the invention or having passed the workload onto you...but you find yourself enjoying the 'duty' regardless.`n`n
			`2When you later depart, one of the rabbits tags along to repay you!");
			apply_buff('warren_buns_2',
				array(
					"name"=>"`lWatchful Companion",
					"rounds"=>20,
					"wearoff"=>"The rabbit has grown tired, bidding you farewell before departing.",
					"defmod"=>1.3,
					"roundmsg"=>"Your furry companion warns you of incoming dangers.",
					"schema"=>"module-warrens_buns",
					)
				);
			addnav("Leave",$from."op=return_forreal");		
			break;
		case "feed":
			require_once("modules/inventory/lib/itemhandler.php");
			output("`7Approaching with care, making a bit of noise to be easily spotted to not frighten the group, you introduce yourself and ask whether you could linger a little while to take a break from adventuring. Once permitted, you take a seat beside the three and engage in minor chit-chat while observing their doings.`n`n
			Feeling a bit peckish you fetch your own rations from your pack, as you eat finding your attention drawn to the girl of the group who was similarly busy serving meal time for the animals. Quite the unusual individual, for while striped facial markings were nothing strange, you couldn't recall ever seeing a person with such pointy ears. Much less one sporting a dress thats entire upper section was made of what appeared to be striped, feathery fluff.`n`n
			`L\"Oh I do not think they could eat that, silly sir\"`7`n`n
			But stranger still was her voice, her laughter in particular at times like the fluttery song of a bird. A look down revealed what she meant; perhaps she thought you wished to join in by offering your own dry food to the rabbits. An innocent little mistake, one you didn't feel like correcting either when she held out a wrap of hay and a few leaves of fresh greens.`n`n
			`L\"Try these~\"`7`n`n
			For after holding out bits of both, you found the quiet, content munching of the critters surprisingly therapeutic to both watch and listen to. You wound up hardly even noticing the passage of time during the shared meal until every last bit had been eaten, and adventure called you once more. `n`n
			Only after bidding the group farewell and departing do you realize one of the rabbits had snuck something to your pocket. You dig around to discover a shiny stone(s) that appears quite valuable.`n`n");
			$roll_it = e_rand(0,100);
			if ($roll_it>90) {
				output("`5Realizing it is an actual gemstone, you keep it for yourself!");
				$session['user']['gems']++;
			} elseif ($roll_it>50) {
				output("`xYou find `3four gold nuggets (big ones)`x!");
				add_item_by_name("Gold Nugget (Lg)",4);				
			} else {
				output("`xYou find `3two gold nuggets (big ones)!`x");
				add_item_by_name("Gold Nugget (Lg)",2);
			}
			addnav("Leave",$from."op=return_forreal");
			break;
		case "kill":
			output("`~Ah but you do not really care for the brats and rodents, do you? Just as you ponder whether the three would have any gold or otherwise valuable things on them, you find hunger scraping at your gut as well. What a convenient timing, when beholding such a veritable buffet of meat! Drawing your weapon, you crack neck and move in for the kill and plunder.`n`n
			`2\"Supagin.\"`n`n
			`~Only to be stopped by a young boy's voice, as the smallest of the trio sporting a hoodie spoke up. Though about to brush off the momentary pause and charge, you find yourself unable to move as a chill races up your spine, the presence you sense having appeared behind your back unlike anything you've ever felt before.`n`n
			`2\"Mamotte choudai.\"`n`n
			`~With the eared hood hiding the features and disappointment thick in the voice, you can only ponder did you anger some guardian spirit of rabbits as you follow his gaze over your shoulder...`\$and promptly black out after a sharp, heavy blow to your head.`7`n`n
			You wake up some time later with dried blood on your face and the clearing empty besides yourself. Able to only barely remember the blurry shape of a giant rabbit, shrouded in chakra and with eyes like living flames, you decide it better not to tempt the fates again and depart in a hurry.`n`n");
			$session['user']['hitpoints']=max(10,$session['user']['hitpoints']*0.5);
			addnav("Leave",$from."op=return_forreal");
			break;
	
		default:
		output("`7During your search for enemies or unordinary things, you happen upon the latter quite unexpected. A grass covered clearing in the trees, one you could swear wasn't there before despite your prior visits to the area. On a closer look you notice it isn't empty either.`n`n
		Basking in the daylight at the middle appear to be `2three `3young`Lsters`7, surrounded by a `Qfluffle of rabbits`7 the trio seems quite busy feeding, petting or simply grooming otherwise. It'd be difficult to say which side enjoyed it more, the former with their racing and bouncy leaps around as if impatient for their turn, or the latter with mirth clear in their chatter.`n`n
		With their evident distraction it'd be easy to turn and depart, leave them to their peace...`2but perhaps it could be worthwhile to approach instead? A break does sound good right about now.");
		addnav("Embrace the fur",$from."op=furry");
		addnav("Assist with grooming",$from."op=ruffle");
		addnav("Share the food",$from."op=feed");
		addnav("`\$Reap the spoils",$from."op=kill");
		addnav("Leave",$from."op=return");				
		commentdisplay("`n`n`@Talk with the others lounging here.`n",
			"warrens_buns","Speak lazily",10);
	}
}

function warrens_buns_run(){
}
?>
