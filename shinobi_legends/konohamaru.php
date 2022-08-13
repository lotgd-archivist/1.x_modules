<?php
function konohamaru_getmoduleinfo(){
	$info = array(
		"name"=>"Konohamaru",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Village Specials",
		"download"=>"",
		"settings"=>array(
			"seen"=>"overall time seen this?,int|0",
			"konohamaru"=>"Name of Konohamaru,text|`xK`yono`2ha`ymaru",
			),
		"prefs"=>array(	
			"prank"=>"Pranked by whom today,viewonly",
			),
		"requires"=>array(
			"bingobook"=>"Bingobook",
			"alignment"=>"Core Alignment",
			),
	);
	return $info;
}

function konohamaru_install(){
	module_addhook("newday");
	module_addeventhook("village","return 25;");
	module_addhook("village-Konohagakure");
	return true;
}

function konohamaru_uninstall(){
	return true;
}

function konohamaru_dohook($hookname,$args){
	global $session;
	$name=get_module_setting('konohamaru');
	$gems=2;
	switch($hookname){
	case "newday":
		$do=unserialize(get_module_pref('prank'));
		if (!is_array($do)) break;
		$names=implode(',',$do);
		$id=array();
		if ($do!=array()) {
			$sql="SELECT acctid,name FROM ".db_prefix('accounts')." WHERE acctid IN ($names)";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				$id[$row['acctid']]=$row['name'];
				}
		}
		$target=$session['user']['name'];
		$turndone=0;
		
		foreach ($do as $origin) {
		// make him pay
			$rand=e_rand(0,100);
			if ($rand>90 && $session['user']['gems']>$gems) {
				//lose gems
				$gems=e_rand(1,$gems);
				$session['user']['gems']-=$gems;
				output("`4You find your `%gem purse slightly lighter by %s pieces`4...`n`n %s`4 attached a note \"Don't mess with %s!`4\"",$gems,$name,$id[$origin]);
				$body=array("`xWe inform you that %s`x is now %s gems lighter...",$target,$gems);
				debuglog(sanitize($name)." took $gems gems from this player, origin player $do");
			} elseif ($rand>80 &&  $session['user']['gold']>3000) {
				$take=e_rand(500,$session['user']['gold']/2)+1;
				$session['user']['gold']-=$take;
				output("`4You find your `^gold purse slightly lighter by %s pieces...`n`n`4 %s`4 attached a note \"Don't mess with %s!`4\"",$take,$name,$id[$origin]);
				$body=array("`xWe inform you that %s`x is now %s gold pieces lighter...",$target,$take);
				debuglog(sanitize($name)." took $take gold from this player, origin player $do");				
			} elseif ($rand >40 && $turnsdone<2) {
				$turnsdone++;
				$take=e_rand(3,$session['user']['turns']/3)+1;
				$session['user']['turns']-=$take;
				output("`4You find your legs bound together with barbwire and your feet glued together! You `\$lose %s turns!`4...`n`n %s`4 attached a note \"Don't mess with %s!`4\"",$take,$name,$id[$origin]);
				$body=array("`xWe inform you that %s`x lost %s turns as the feet were glued together...",$target,$take);
				debuglog(sanitize($name)." took $take turns from this player, origin player $do");	
			} elseif ($rand>0 ) {
				$take=e_rand(3,min($session['user']['charm']/3,30))+1;
				$session['user']['charm']-=$take;
				output("`4You find yourself tied up in a pool of already digested chicked food, crazy symbols painted on your along with a video camera pointed at you, broadcasting your misery all over the town! You `\$lose %s charmpoints!`4",$take);
				output("`n`nYou see that `4 %s`4 attached a note to the rope \"Don't mess with %s!`4\"",$name,$id[$origin]);
				$body=array("`xWe inform you that %s`x lost %s charmpoints as we displayed the ugliness pretty realistic...",$target,$take);
				debuglog(sanitize($name)." took $take charm from this player, origin player $do");					
			}
			output_notl("`n`n");
			
		// notify originator
			require_once("lib/systemmail.php");
			$subject="Prank executed by %s";
			systemmail($origin,array($subject,$name),$body);
			
		}
	
		set_module_pref('prank','');
		break;
	case "village-Konohagakure":
		$seen=get_module_setting('seen');
		if ($session['user']['dragonkills']<1 && $session['user']['hitpoints']<10) break;
		if ($seen>3000) {
			$array=array(
				"drinking tea",
				"playing shougi",
				"drinking alcohol-free sake",
				"telling the story of how he almost defeated Uzumaki Naruto",
				"whispering hidden words in hidden ears",
				"smoking a smoke-free pipe",
				"hiding in hiding",
				"throwing dice",
				);
			$array=translate_inline($array);
			$get=date("d")%count($array);
			output("`n%s`4 has now finally done enough pranks for now and settles down for a while.`nYou see him on the right hand side of the road with his sidekicks, %s.",$name,$array[$get]);
		} else if ($seen>300) {
			output("`n%s`4 is following you... almost hidden. You `\$ lose one hitpoint`4 due to a rock thrown at you...",$name);
			$session['user']['hitpoints']--;
			if ($seen>1200) {
				output(" and immediately a second one,  vast and huge, ... ouch! Another `\$hitpoint lost`4.");
				$session['user']['hitpoints']--;
			}
			if ($session['user']['hitpoints']<0) {
				output("`\$`n`nOops... you were not in a too good shape, and his rocks `bKILLED YOU`b...`n`gSadly, you now loose 20% of your experience...");
				$session['user']['experience']*=0.8;
				global $navbysection;
				$navbysection = array();
				addnav("Navigation");
				addnav("Return to the shades","shades.php");
				page_footer();
			}
			output_notl("`n");
		} 
			
		break;
	}
	return $args;
}

function konohamaru_runevent($type) {
	global $session;
	$gems=e_rand(2,round(sqrt($session['user']['dragonkills']),0)+1);
	$name=get_module_setting('konohamaru');	
	$session['user']['specialinc'] = "module:konohamaru";
	$seen=get_module_setting('seen');
	$op = httpget('op');
	switch($op) {
		case "pay":
			$target=httpget('target');
			$session['user']['gems']-=httpget('gems');
			output("`7\"`\$Thanks... you will get shinobi mail from us...`7\"... with these words he hides ... well... tries to.... a shadow and leaves...`n`n`4You wonder if it will work out like you hope...");
			$array=unserialize(get_module_pref('prank','konohamaru',$target));
			debug($target);debug($array);
			if (!is_array($array)) $array=array();
			debug($array);
			$array[]=$session['user']['acctid'];
			set_module_pref('prank',serialize($array),'konohamaru',$target);
			break;
		case "prank":
			require_once("modules/bingobook/func.php");
			$bingo = bingobook_massgetfull();
			$rand=e_rand(0,count($bingo)-1);
			$row=$bingo[$rand];
			$targetname=$row['bingoname'];
			$targetid=$row['bingoid'];
			k_addimage('action.jpg');

			output("`7%s`7 nods, \"`\$Okay`x... so we play a prank on somebody... you don't like...best on ...`7 \" `4- in an unreal instant he has your `qbingo book `4 in his hands, browsing through! - ",$name);
			if ($targetname=='') {
				output("`7\"`\$Errr`x you should first make some entries... ah, I can't work with that... see you around!`n`n");
				$session['user']['specialinc'] = "";
				break;
			}
				
			output("`7\"`\$%s`x I presume.`n`n`\$We`x play the prank we want... we steal gems, slow down, make more ugly, well... you can't really choose as we inspect our target first and then decide what to do... but we'll notify you what we done and tell him also your name... a matter of honour, you know.",$targetname);
			
			output("`n`n`\$This`x will cost you `% %s gems`x. Decide if it is worth that for you...`7\"`n`n",$gems);
			
			if ($session['user']['gems']<$gems) output("`y`iSadly, you don't have the necessary funds...`i");
			
			addnav_notl(sanitize($name));
			if ($session['user']['gems']>=$gems) addnav("`\$Go ahead...and pay","village.php?op=pay&target=$targetid&gems=$gems");
			addnav("Take your leave","village.php?op=no");
			break;
		case "no":
			output("`7You don't have time for that fella and take your leave. May others kick him around.`n");
			if (e_rand(0,3)==0) {
				$who=($session['user']['sex']==SEX_MALE?"chicks":"lads");
				output("You feel others might agree with you... even the hot `\$%s`7 around here. Your `%charm`7 increases!",translate_inline($who));
				$session['user']['charm']++;
			}
			output_notl("`n`n");
			$session['user']['specialinc'] = "";
			break;
		case "beat":
			output("`7He had it coming!`n");
			k_addimage('beatup.jpg');
			$session['user']['specialinc'] = "";
			break;
		case "yes":
			output("`7He seems somewhat surprised: \"`\$Errr`x... well, what *cough* prank do you want to have played... hehehe... of course we don't *cough* hurt people really... mostly... well... you know....`7\"");
			output("`n`nYou note him opening his hand, so it means this is not free.");
			addnav_notl(sanitize($name));
			addnav(array("`1`iAsk `4for`& `~a `gPrank`i`0"),"village.php?op=prank");
			addnav("Take your leave","village.php?op=no");
			break;
		default:
			$gender=translate_inline($session['user']['sex']==SEX_MALE?"onii-san":"onee-san");
			output("`7While you idle around, a `~darkly `)clothed figure `7 approaches you.`n`n");
			output("\"`&Hey, %s, want to get rid of somebody?`i`7\"",$gender);
			output_notl("`n`n");
			if ($seen>800) {
				output("You remember that little fella! `\$Hot`& Prankster `4%s`7!",$name);
				addnav("Beat him up...","village.php?op=beat");
			}
			
			increment_module_setting("seen");
			addnav("Get rid...");
			addnav("Yes","village.php?op=yes");
			addnav("No","village.php?op=no");
			break;
	}
}
function k_addimage($args) {
	if (!get_module_pref('user_addimages','addimages')) return;
	output_notl("`c<img src=\"modules/konohamaru/".$args."\">`c<br>\n",true);
}
?>
