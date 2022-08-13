<?php

function chinesenewyear_getmoduleinfo(){
	$info = array(
		"name"=>"Meet the Kirin (Chinese New Year)",
		"author"=>"`2Oliver Brendel",
		"version"=>"1.0",
		"category"=>"Forest Specials",
		"settings"=>array(
			"Kirin - Settings,title",
		),
		"prefs"=>array(
			"Chinese New Year - Prefs,title",
				"hadevent"=>"Has the user had this event,bool|0",
		),
	);
	return $info;
}
function chinesenewyear_install(){
	module_addeventhook("forest","return 
(get_module_pref('hadevent','chinesenewyear')==1?0:100);");
	return true;
}
function chinesenewyear_uninstall(){
	return true;
}
function chinesenewyear_runevent($type){
	global $session;
	$op = httpget('op');
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:chinesenewyear";
	output_notl("`n");
	switch ($op){
		case "":
			output("`@You see something `greflecting `@the `tsunlight `@along a less 
travelled path... you also feel a bit tired right now... do you want 
to...");
			addnav("Lie down for a bit and then investigate",$from."op=investigate");
			addnav("Leave",$from."op=continue");
			break;
		case "continue":
			output("`@It might just be nothing but a piece of `lbroken glass. `@You 
shrug and continue on your way.");
			$session['user']['specialinc']='';
			break;
		case "investigate":
			output("`@Deciding that you wish to take a little rest, you stop off in a 
clearing.");
			output("`@It is a `Lbeautiful day`@ with `gwind`@ rustling through the 
`gleaves`@.");
			output("`@After a while, you decide to leave and make your way through an 
overgrown path where you have spotted the `greflection`@. Your eyes widen 
when you see the `2huge scale`@ almost the size of your palm, lying on the 
ground.");
			output("`n`nWhat do you do?");
			addnav("Pick it up",$from."op=pickup");
			addnav("Leave",$from."op=leave");
			break;
		case "leave":
			output("`@You decide not to touch the `2unknown scale `@and make your way 
back to the path that you are familiar with.");
			$session['user']['specialinc']='';
			break;
		case "pickup":
			output("`@As you were about to bend over to pick up the `2mysterious 
scale`@, a creature with the head of a `\$dragon`@, the antlers of a 
`)deer`@, the skin and scales of a `1fish`@, the hooves of an `Qox`@ and 
tail of a `#lion`@ suddenly appears out of nowhere and attacks you! `QIt's 
the legendary creature that only comes from it's lair during `\$Chinese New 
Year`Q, the creature known as `gKi`tri`gn`Q!");
			addnav("Defend yourself!",$from."op=defend");
			break;
		case "hilfeichbineinadminholtmichhierraus":
			output("Due to your powers as a god you teleport yourself out of it.");
			$session['user']['specialinc'] = "";
			break;
		case "defend":
			$kirin = array(
				"creaturename"=>"`gKi`tri`gn`0",
				"creatureweapon"=>"`\$flaming `4h`%oo`)v`4es`0",
				"creaturelevel"=>$session['user']['level'],
				"creatureattack"=>($session['user']['attack']+$session['user']['dragonkills']/2),
				"creaturedefense"=>($session['user']['defense']),
				"creaturehealth"=>($session['user']['maxhitpoints']+e_rand($session['user']['dragonkills'],$session['user']['dragonkills']*9)),
				"schema"=>"module-chinesenewyear",
			);
			$flames	 = array(
				"startmsg"=>"`n`^The `gKi`tri`gn`^ starts starts breathing fire!`n",
				"name"=>"`vKi`)r`vin `\$Flames",
				"rounds"=>-1,
				"wearoff"=>"The flames begin to disappear.",
				"minioncount"=>$session['user']['level'],
				"mingoodguydamage"=>1,
				"maxgoodguydamage"=>log($session['user']['dragonkills']+exp(1))^2+log($session['user']['level']),
				"effectmsg"=>"Flames surround you, dealing {damage} damage.",
				"effectnodmgmsg"=>"Jumping high, you are able to clear the fire.",
				"activate"=>"roundstart",
				"schema"=>"module-chinesenewyear",
			);
			$session['user']['badguy'] = createstring($kirin);
			apply_buff("kirin-flames",$flames);
			$op = "fight";
			httpset('op',$op);
		case "fight":
			rawoutput("<center><img 
src='modules/chinesenewyear/Nian.jpg'></center><br>");
			include("battle.php");
			if ($victory){
				strip_buff("kirin-flames");
				set_module_pref("hadevent",1);
				$session['user']['specialinc'] = "";
				output("`QThe `gKi`tri`gn`Q disappears into `\$flames`Q, leaving a 
`\$red packet `Qon the spot where it once stood. You curiously pick up the 
`\$red packet `Qand open it...`n`n");
				switch(e_rand(0,5)) {
					case 0:
						output("... you find `^one thousand gold pieces`g! `\$Happy Chinese 
New Year!");
						$session['user']['gold']+=1000;
						break;
					case 1:
						$gems=e_rand(2,4);
						output("... lucky! You find `% %s`g gems! `\$Happy Chinese New 
Year!",$gems);
						$session['user']['gems']+=$gems;
						break;
					case 2:
						if (!is_module_active("inventory")) {
							output("... nothing!");
							break;
						}
						require_once("modules/inventory/lib/itemhandler.php");
						$number=e_rand(2,4);
						add_item_by_name("Health Elixir $number");
						output("... a health elixir %s! `\$Happy Chinese New Year!",$number);
						break;
					case 3:
						if (!is_module_active("inventory")) {
							output("... nothing!");
							break;
						}
						require_once("modules/inventory/lib/itemhandler.php");
						add_item_by_name("Talisman of Defense");
						output("...a Talisman of Defense! `\$Happy Chinese New Year!");
						break;
                    case 4:
						if (!is_module_active("inventory")) {
							output("... nothing!");
							break;
						}
						require_once("modules/inventory/lib/itemhandler.php");
						add_item_by_name("Talisman of Attack");
						output("...a Talisman of Attack! `\$Happy Chinese New Year!");
						break;
					case 5:
						if (!is_module_active("inventory")) {
							output("... nothing!");
							break;
						}
						require_once("modules/inventory/lib/itemhandler.php");
						$number=e_rand(2,4);
						add_item_by_name("Specialty Elixir");
						output("... a Specialty Elixir! `\$Happy Chinese New Year!");
						break;
				}
				addnews("%s`^ survived an encounter with the legendary beast 
`gKi`tri`gn`^.",$session['user']['name']);
			}elseif($defeat){
				strip_buff("kirin-flames");
				set_module_pref("hadevent",1);
				$session['user']['gold'] = 0;
				$session['user']['hitpoints']=1;
				output("`n`n`@Realizing that you wouldn't be able to defeat the beast, 
you run for your life. Unfortunately, you lost your pouch during your 
escape.");
				$session['user']['specialinc'] = "";
				addnav("Flee to the village","village.php");
				addnews("%s`^ barely escaped the wraith of the legendary beast 
`gKi`tri`gn`^.",$session['user']['name']);
			}else{
				fightnav(true,false);
				if ($session['user']['superuser'] & SU_DEVELOPER) addnav("Escape to 
Village",$from."op=hilfeichbineinadminholtmichhierraus");
			}

	}

}
?>
