<?php
	global $session;

	checkday();
	page_header("Battle Arena");
	output("`c`b`&Battle Arena of `%%s`0`b`c`n`n",$session['user']['location']);

	$fee=get_module_setting('fee');
	$op = httpget('op');
	$homecity=get_module_pref("homecity", "cities");
	$home = $session['user']['location']==$homecity;
	$city = getsetting("villagename", LOCATION_FIELDS);
	$capital = $session['user']['location']==$city;
	$town=$session['user']['location'];
	$gladiators=array();
	require_once("modules/battlearena/gladiators.php");

	switch ($op) {

	case "lounge":
		output("`c`b`&Veteran's Lounge`0`b`c`n`n");
		require_once("lib/commentary.php");
		addcommentary();
		viewcommentary("battlearena","Boast here",20,"boasts");
		addnav("Back to the Arena","runmodule.php?module=battlearena");
		break;

	case "rank":
		battlearena_showrank($session['user']['location']);
		addnav("Continue","runmodule.php?module=battlearena");
		break;

	case "pay":
		$ubattlepoints = get_module_objpref(addslashes($session['user']['location']),$session['user']['acctid'],'battlepoints');
		if (!$ubattlepoints) $ubattlepoints=0;
		$session['user']['gold']-=$fee;
		$session['user']['turns']-=1;
		output("`cYou must choose your Opponent.`c");
		addnav("Choose your opponent");
		while (list($key,$val) = each ($gladiators))
			{
			if ($ubattlepoints>=$val['battlepoints'] && $session['user']['dragonkills']>=$val['dks']) addnav(array("%s) %s`^ Level %s`0`n",$key+1,$val['name'],$val['level']),"runmodule.php?module=battlearena&op=prepare&who=$key");
			}
		villagenav();
		break;

	case "win":
		$number = httpget('who');
		output("`&Congratulations! You have beaten %s`&!  You have been awarded %s %s!`n",$gladiators[$number]['name'],$number+1,translate_inline(($number==0?"battlepoint":"battlepoints")));
		$winnings=e_rand(75+($number*15),100+($number*75));
		if ($number>7) $winnings+=e_rand(0,200); //little extra for the though ones
		output("Your winnings total %s gold!`n",$winnings);
		$session['user']['gold']+=$winnings;
		$winnings = e_rand(75,100);
		$oldhp=get_module_pref('entryhealth');
		if ($session['user']['hitpoints']<$oldhp) {
			output("`#The Arena healers restores your body to the point you entered the arena.`n");
			$session['user']['hitpoints']=$oldhp;
			$comment=sprintf_translate("/me`2 has beaten %s`2 in the Battle Arena of `%%s!",$gladiators[$number]['name'],$session['user']['location']);
		} else { //nothing lost or healed
			$bonus=e_rand(50,$winnings);
			output("`4Perfect Fight! You get a bonus of %s gold!`n",$bonus);
			$session['user']['gold']+=$bonus;
			$comment=sprintf_translate("/me`2 has beaten %s`2 in the Battle Arena of `%%s `tperfectly!",$gladiators[$number]['name'],$session['user']['location']);
		}
		require_once("lib/commentary.php");
		injectrawcomment("battlearena-news", $session['user']['acctid'], $comment);	increment_module_objpref(addslashes($session['user']['location']),$session['user']['acctid'], 'battlepoints',$number+1);
		increment_module_pref("battlepoints",$number+1);
		invalidatedatacache("battleleader-$town");
		invalidatedatacache("battleleader");
		addnav("Continue","runmodule.php?module=battlearena");
		break;

	case "loose":
		$number = httpget('who');
		$loss=$number+1;
		output("`&You have lost to %s`& and lose therefore %s battlepoints!`n",$gladiators[$number]['name'],$loss);
		$comment=sprintf_translate("/me`2 has lost to %s`2 at the Battle Arena in `%%s.",$gladiators[$number]['name'],$session['user']['location']);
		require_once("lib/commentary.php");
		injectrawcomment("battlearena-news", $session['user']['acctid'], $comment);
		output("`#The Arena healers restores your body to the point you entered the arena.`n");
		increment_module_objpref(addslashes($session['user']['location']),$session['user']['acctid'], 'battlepoints',-$loss);
		increment_module_pref("battlepoints",-$loss);
		invalidatedatacache("battleleader-$town");
		invalidatedatacache("battleleader");
		$oldhp=get_module_pref('entryhealth');
		if ($oldhp>$session['user']['hitpoints']) $session['user']['hitpoints']=$oldhp;
		addnav("Continue","runmodule.php?module=battlearena");
		break;
	case "prepare":
		require("modules/battlearena/battlearena_prepare.php");
	case "fight":
		$battle=true;
		break;
	case "news":
		villagenav();
		addnav("Back to the Arena","runmodule.php?module=battlearena");
		require_once("lib/commentary.php");
		addcommentary();
		commentdisplay("`n`n`@Look at the news.`n","battlearena-news","",30,"converses");
		break;
	default:
		$ubattlepoints = get_module_objpref(addslashes($session['user']['location']),$session['user']['acctid'],'battlepoints');
		if (!$ubattlepoints) $ubattlepoints=0;
		set_module_pref('health',$session['user']['hitpoints']);
		set_module_pref('newfight',true);
		villagenav();
		output("`3The Battle Arena is full of spectators, the noise is deafening.  There are warriors ");
		output("fighting in the center arena. There is a door marked Veteran's Lounge. You notice a plaque on the wall.`n");
		output_notl("`n");
		$plaque = battlearena_getleader($session['user']['location']);
		output("`7On the plaque it says the local Battle Arena Champion is currently ");
		if ($plaque){
			output("%s`7.`n",$plaque['name']);
		}else{
			output("no one.`n");
		}
		output_notl("`n");
		if (!$home && !$capital) {
				output("`#At the registration table, a polite woman tells you: \"`\$Sorry, but you cannot fight here as this is not your home town. Only born citizens here can fight in this arena. Please go to the capital or your home town.`#\".`n");
				output_notl("`n");
				battlearena_showrank($session['user']['location']);
				break;
		}
		output("`#At the registration table you see that you can fight the following warriors.`n");
		while (list($key,$val) = each ($gladiators))
			{
			if ($ubattlepoints>=$val['battlepoints'] && $session['user']['dragonkills']>=$val['dks'])
				output("%s`^ Level %s`0`n",$val['name'],$val['level']);
			}
		output("`n`#Battling at the arena takes 1 turn.`n`n");
		output("`3It is recommended you be in your best condition when you battle.`n");
		output("`#Additionally, your hitpoints will be restored up to the amount you enter the arena, not more, not less. We don't want to be used as cheap healing opportunity.`n`n");
		output("`#If you lose against a fighter here, you will also lose battlepoints.`n`n");
		output("`#You are required to pay an entry fee to battle in the arena.`n");
		if ($session['user']['gold'] < 1) output("However you notice that your pockets are empty.`n");
		if ($session['user']['gold'] > 0 and $session['user']['gold'] <$fee) output("However you notice that you don't have enough gold.`n");
		if ($session['user']['gold'] >= $fee and $session['user']['turns'] > 0) addnav(array("Pay Entry Fee (%s gold)",$fee),"runmodule.php?module=battlearena&op=pay");
		if ($ubattlepoints > 120 and $session['user']['dragonkills'] > 4) addnav("Veterans Lounge","runmodule.php?module=battlearena&op=lounge");
		addnav("Rankings","runmodule.php?module=battlearena&op=rank");
		addnav("Arena News (who beat whom)","runmodule.php?module=battlearena&op=news");
		break;
	}

	if ($battle){
		require("modules/battlearena/battlearena_battle.php");
	}
	page_footer();
?>
