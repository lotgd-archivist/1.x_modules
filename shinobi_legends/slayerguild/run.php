<?php
	$maxhold = get_module_setting("maxhold");
	$special = get_module_setting("special");
	$gems = get_module_setting("gems");
	$atkdef = get_module_setting("atkdef");
	$holding = get_module_pref("holding");
	$apply = get_module_pref("apply");
	$atk = get_module_pref("atk");
	$def = get_module_pref("def");
	$op = httpget('op');
	$op2 = httpget('op2');
	page_header("Dark Slayer's Guild");

	switch ($op){
		case "enter":
			output("`)You walk into a dark and dank chamber, in the middle of the square.");
			output(" `)Looking around, you see a tall figure, cloaked in a black raiment.");
			output(" `)The Figure floats right next to you and places a cold hand on your shoulder.");
			output(" `)It bends down and whispers, it's voice chilling to the bone.`n`n");
			if (!$apply) {
				output("`)\"`7So, you have come to join the `)Dark Slayer's`7...?`)\" a male's voice is emitted.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=slayerguild&op=yes");
				addnav("Not Today","runmodule.php?module=slayerguild&op=no");
			}else{
				output("`)\"`7Welcome back to the `)Dark Slayer's Guild`7...`)\" Leon says.");
				addnav("Proceed");
				addnav("Inner Hollows","runmodule.php?module=slayerguild&op=inner");
				addnav("Give up Dark Slayerhood","runmodule.php?module=slayerguild&op=giveup");
				blocknav("runmodule.php?module=slayerguild&op=no");
				blocknav("runmodule.php?module=slayerguild&op=yes");
			}
			break;
		case "yes":
			$take = (int)($session['user']['gems']/2);
			output("`)The Dark Figure takes a look at you and draws back his hood.");
			output(" `)A handsome Elf is looking back at you, smirking with a devlish mouth.`n`n");
			output("`)\"`7Welcome to the Dark Slayer's Guild. I am your Lord and Master, Leon Valian,`)\" he sets out his hand.");
			output(" He whisks half of your gems, `^%s `)gems, into his stores.",$take);
			$session['user']['gems']-=$take;
			if (is_module_active('alignment')) {
				output(" You can feel that something is draining from you...");
				require_once("./modules/alignment/func.php");
				align("-50");
				demeanor("50");
			}
			output("`\$You be aware that keeping souls will be charged... check your next new day pages and be aware of any \"bank drain\" you are not aware of currently. It is not cheap to be in there.`)");
			increment_module_pref("holding"); //give the bozo one soul so he SEES what happens. We DO hate petitions from morons who won't read anything
			debuglog("took $take gems for joining slayer guild");
			set_module_pref("manygems",$take);
			set_module_pref("apply",1);
			break;
		case "no":
			output("`)The Dark Figure sweeps it's arm and points a retched finger through it's sleeve.");
			output(" `)A low growl comes forthwith, \"`7Leave now...`)\"");
			break;
		case "dead":
			if ($op2=="forest")	output("`&You have spilt the blood of an fellow evil shinobi...`n`n");
			if ($op2=="give") output("`&You have tried to leave, and the souls that you couldn't provide, sealed your death.`n`n");
			output("`)You have paid for your transgressions...");
			output(" `)The ultimate punishment...`\$death`), was avoided.");
			/*addnav("Continue","shades.php");
			blocknav("village.php");*/
			addnav("Continue","village.php");
			blocknav("runmodule.php?module=slayerguild&op=inner");
			break;
		case "giveup":
			output("`)Leon crosses you, \"`7So, you wish to leave the Guild?`)\"`n`n");
			addnav("Yes","runmodule.php?module=slayerguild&op=giveupconfirm&t=0");
			addnav("No","runmodule.php?module=slayerguild");
			break;
		case "giveupconfirm":
			$r=translate_inline("really");
			$t=httpget('t');
			for ($i=0;$i<=$t;$i++) {
				$really.=" ".$r;
			}
			output("`)Leon crosses you, \"`7So, you `\$`b%s`b`7  wish to leave the Guild?`)\"`n`n",$really);
			if ($t<5) {
				addnav("Yes","runmodule.php?module=slayerguild&op=giveupconfirm&t=".($t+1));
			} else {
				addnav("Yes","runmodule.php?module=slayerguild&op=giveupyes");
			}
			addnav("No","runmodule.php?module=slayerguild");
			break;
		case "giveupyes":
			output("`)You nod and talk about how you don't wish to be a bijuu hunter...");
			output(" `)You  also talk about how you do not want to lose your good name, by becoming evil, in the eyes of society.");
			output("`n`n`)Leon nods, and rolls up your shirt sleeve.");
			output(" `)He tears a tattoo that had been put on there from your initiation, and tosses your `^%s `)gems to you.",get_module_pref("manygems"));
			if ($holding>=1){
				output(" He also takes all of the souls that you have, and uses them to spare you the pain of death.");
				set_module_pref("apply",0);
				set_module_pref("holding",0);
			}else{
				output(" He shakes his head, noticing you have no souls left over... the removal of the tattoo destroys your fragile body.");
				$charm=10;
				$exp=10;
				output("`n`nSadly, you will be marked forever, losing `% %s charm points`)!`nNot being enough, you also lose %s experience!`n`nHe adds that death-cheaters are the worst scum, and ... let's you stay alive...`n`n",$charm,$exp);
				require_once("lib/systemmail.php");
				systemmail($session['user']['acctid'],array("Membership..."),array("For leaving our brotherhood, sadly, you will be marked forever, losing `% %s charm points`)!`nNot being enough, you also lose %s experience!`n`nHe adds that death-cheaters are the worst scum, and ... let's you stay alive...`n`n",$charm,$exp));
				$session['user']['charm']-=$charm;
				$exp/=10;
				$session['user']['experience']*=(1-($exp/100));
				set_module_pref("apply",0);
				set_module_pref("holding",0);
				$session['user']['hitpoints']=1;
				addnav("Death...almost","runmodule.php?module=slayerguild&op=dead&op2=give");
				blocknav("village.php");
			}
			$session['user']['gems']+=get_module_pref("manygems");
			debuglog("got ".get_module_pref("manygems")." gems for leaving the slayer guild");
			blocknav("runmodule.php?module=slayerguild&op=inner");
			break;
		case "inner":
			output("`)You follow Lord Leon deep into the hollows of the Slayer's Guild.");
			output(" `)He speaks, \"`7The purpose of the Dark Slayer's Guild, is to rend the souls of evil, and use them for our own good.");
			output(" `7Such creatures have broken from the Graveyard and walk amongst the forest.");
			output(" `7You shall know which is which... and the Gods are watching over the innocents...");
			output(" `7It is our duty to destroy them... but I shall give a little gift to those that rend souls.");
			output(" `7Now, go from this place, and come back with the souls of the damned.`)\"`n`n");
			output("`)You pick up your `&%s`), and look towards the door and the forest.",$session['user']['weapon']);
			output(" `)Leon casts a look at you and smiles, as he has brought another Dark Slayer into the world.");
			output("`n`n`)Leon notes that you currently have `&%s `)souls.",$holding);
			if (!$holding) output("`)You have no use being here... might as well leave.");
			addnav("Spend Souls");
			if ($holding>=$special) addnav(array("Increase Specialty Uses - %s Souls",$special),"runmodule.php?module=slayerguild&op=special");
			if ($holding>=$gems) addnav(array("Forge a Gem - %s Souls",$gems),"runmodule.php?module=slayerguild&op=gems");
			if ($holding>=$atkdef){
				if (!$atk) addnav(array("Increase Attack - %s Souls",$atkdef),"runmodule.php?module=slayerguild&op=atkdef&op2=atk");
				if (!$def) addnav(array("Increase Defense - %s Souls",$atkdef),"runmodule.php?module=slayerguild&op=atkdef&op2=def");
				if (!get_module_pref("hitpoint")) addnav(array("Increase Hitpoints - %s Souls",$atkdef),"runmodule.php?module=slayerguild&op=atkdef&op2=hp");
			}
			if ($atk && $def && get_module_pref("hitpoint")){
				output("`n`nLeon looks at you and arches a brow.");
				output(" You have already built up attack, hitpoints and defense for this time through... try some other time.");
			}
			break;
		case "special":
			$specialty = modulehook("specialtynames");
			$color = modulehook("specialtycolor");
			$spec = $specialty[$session['user']['specialty']];
			$ccode = $color[$session['user']['specialty']];
			output("`)Leon walks over and carresses his Ragnorok in it's holster and grins.`n`n");
			output(" `)He speaks, \"`7So, you have decided to increase your skills in %s%s`7.",$ccode,$spec);
			output(" `7I commend you for rending `&%s`7 souls so far, but I must take `&%s`7 to increase your Specialty.`)\"",$holding,$special);
			output("`n`n`)Leon takes your %s souls, and throws pure energy into your soul.",$special);
			require_once("lib/increment_specialty.php");
			if ($session['user']['specialty']=='SS') {
				increment_specialty("`^");
			} else {
				for ($i=0;$i<3;$i++) increment_specialty("`^");
			}
			$new = $holding-$special;
			set_module_pref("holding",$new);
			break;
		case "gems":
			output("`)Leon walks over to you, smirking evilly.`n`n");
			output(" `)\"`7I am quite proud of you.. for rending `&%s `7souls thus far.",$holding);
			output(" `7But to forge this gem you requested, I will need to take `&%s `7souls.`)\"",$gems);
			output("`n`n`)Leon takes the %s souls from you, and forges a gem.",$gems);
			output("With a burst of light, Leon hands you a single gem.");
			$new = $holding-$gems;
			set_module_pref("holding",$new);
			$session['user']['gems']++;
			break;
		case "atkdef":
			switch ($op2){
				case "atk":
					output("`)Leon looks you onceover, and smirks down at your `&%s`).",$session['user']['weapon']);
					output(" `)Taking in each dimension, and every nook and ding on it, he smiles.");
					output("`)\"`7I commend you thus far, for rending `&%s `7souls.",$holding);
					output(" And I see that your `&%s `7has taken much damage... perhaps you are now wielding it with enough strength.",$session['user']['weapon']);
					output(" I will need `&%s `7souls to increase your strength.`)\"",$atkdef);
					output("`n`n`)Leon takes the %s souls from you, and taps his blade to your arm.",$atkdef);
					output("With a burst of light, you feel a bit more stronger.");
					$new = $holding-$atkdef;
					set_module_pref("holding",$new);
					$session['user']['attack']++;
					set_module_pref("atk",1);
					break;
				case "def":
					output("`)Leon looks you over, and smiles at your `&%s`).",$session['user']['armor']);
					output(" `)Looks over his glasses and stares deep into your body.");
					output("\"`7You truly lack the fortitude to back your `&%s`7 up.",$session['user']['armor']);
					output(" `7It has been desecrated... but I can increase your resilience with the use of `&%s `7souls.`)\"",$atkdef);
					output("`n`n`)Leon takes the %s souls from you, and taps his blade to your chest.",$atkdef);
					output("With a burst of light, you feel a bit more adamant.");
					$new = $holding-$atkdef;
					set_module_pref("holding",$new);
					$session['user']['defense']++;
					set_module_pref("def",1);
					break;
				case "hp":
					output("`)Leon looks over at you, almost measuring your stamina.`n`n");
					output("`)\"`7You must be quite weak to seek the aid of souls to increase your stamina.");
					output(" `7But, you aren't so weak... I see that you have rended `&%s `7so far, quite admirable.",$holding);
					output(" `7But, to increase your stamina, I will need to take `&%s `7souls from your holding.`)\"",$atkdef);
					output("`n`n`)Leon takes the %s souls from you, and taps his blade to your heart.",$atkdef);
					output("With a burst of light, you feel a bit more intense.");
					output("You gained `\$%s `)Maximum Hitpoints!",get_module_setting("hpgain"));
					$new = $holding-$atkdef;
					set_module_pref("holding",$new);
					set_module_pref("hitpoint",1);
					$session['user']['maxhitpoints']+=get_module_setting("hpgain");
					break;
			}
			break;
		}
		if ($holding>=1 && $op != "inner" && $op != "enter"){
			addnav("Continue");
			addnav("Proceed to the Inners","runmodule.php?module=slayerguild&op=inner");
		}
?>
