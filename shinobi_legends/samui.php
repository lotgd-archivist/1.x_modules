<?php 

function samui_getmoduleinfo() {
	$info = array(
		"name"=>"Team Samui",
		"author"=>"`LShinobiIceSlayer",
		"version"=>"1.0",
		"category"=>"Forest Specials",
		"download"=>"",
		"settings"=>array(
			"Team Samui - Settings, title",
			"experience"=>"Percentage: How many experience is lost/won after a fight,floatrange,0,100,5|10",
		),
	);
	return $info;
}

function samui_install(){
	module_addeventhook("forest", "return 100;");
	return true; 
}

function samui_uninstall(){
	return true;
}

function samui_dohook($hookname,$args){	
	return $args;
}

function samui_runevent($type,$link){
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:samui";
	$op = httpget('op');

	switch ($op){
		case "":
			output("`~You travel through the forest, when you hear something behind you, all of a sudden you find you are surrounded by three Shinobi, but they look different to any you have seen before in the way they dress and carry swords on their backs. You see in front of you two dark skinned Shinobi, a male and female, with a fair skinned female behind you.");
			output("`n`~The darker female runs at you shouting, `\$\"Tell us everything you know about Uchiha Sasuke!\" ");
			output("`n`~As she approaches you the Male tries to stop her. `7\"Wait a minute Karui! You can't just charge up to them! You might make them angry, they could kill us, or maybe we could start a war with their village!\"");
			output("`n`~The two start to argue when the fair female silences them and askes you, `1\"Do you have any knowledge of Uchiha Sasuke? We're are here on a mission from the Raikage to gather information on said subject.\"");
			output("`n`~You notice their forehead protectors have what appear to be clouds on them, and wonder, could these be Shinobi from mysterious land of Kumogakure?");
			addnav("Share your knowledge",$link."op=help");
			addnav("Refuse to help",$link."op=refuse");
			addnav("Attack them",$link."op=attack");
			addnav("Run Away",$link."op=run");
		break;
		case "help":
			$random=e_rand(1,2);
			if ($random==1){
				output("`~You tell them any information you have heard in your travels, and they thank you, then disappear back into the forest.");
				if (e_rand(1,2)==1){
					output("`n`~You find a small bag of gold at you feet, you smile, glad to have help your fellow Shinobi.");
					$session['user']['gold']+=100;
					output("`n`n`~You gain 100 gold.");
				}
			}if ($random==2){
				output("`~You tell them, somethings you have heard, even though you doubt they are true. Once you finish, the three Mysterious stare intently at you.");
				output("`n`1\"Why must you waste our time with these lies?\" `~The fair skinned Shinobi sighs, as she and her teammates disappear into the forest.");
				if (e_rand(1,2)==1 && $session['user']['turns']>0){
					$session['user']['turns']--;
					output("`n`n`~You a lose a turn while talking.");
				}
			}
			$session['user']['specialinc'] = "";
		break;
		case "refuse":
			output("`~You stand, arms crossed, saying you'll never reveal anything to the likes of them. They laugh, the dark skinned female asks, `\$\"Do you know what we do with Shinobi like you who don't comply with our requests?");
			if (e_rand(1,3)==2){
				output("`n`n`~The three close in on you, giving you no way to escape, you stand ready to defend yourself.");
				addnav("Defend yourself",$link."op=attack");
			} else{
				output("`n`n`~In fear you close your eyes... then after a few seconds you open them again, only to find yourself alone. In the distance you hear voices mocking you, doubting you even knew anything away.");
				$session['user']['specialinc'] = "";
			}
		break;
		case "run":
			output("`~Seeing the strangers, and fearing them to be the great Ninja of Kumogakure, who are among the elite of all Shinobi, you quickly find the closest openning a dash away.");
			$session['user']['specialinc'] = "";
		break;
		case "attack":
			output("`~The three stand ready to attack. ");
			$selection=0;
			require_once("lib/battle-skills.php");
			$stack = array();
			$karui = array(
					"creaturename"=>translate_inline("Karui"),
		            "creaturelevel"=>$session['user']['level']+1,
		            "creatureweapon"=>translate_inline("Big Mouth"),
					"creatureattack"=>$session['user']['level']+$session['user']['dragonkills']+3,
					"creaturedefense"=>$session['user']['defense']+1,
					"creaturehealth"=>($session['user']['level']*10+round(e_rand($session['user']['level'],($session['user']['maxhitpoints']-$session['user']['level']*10)))),
					"diddamage"=>0,);
			$omoi = array(
					"creaturename"=>translate_inline("Omoi"),
		            "creaturelevel"=>$session['user']['level']+1,
		            "creatureweapon"=>translate_inline("Anxiety"),
					"creatureattack"=>$session['user']['attack']+1,
					"creaturedefense"=>$session['user']['level']+$session['user']['dragonkills']+3,
					"creaturehealth"=>($session['user']['level']*10+round(e_rand($session['user']['level'],($session['user']['maxhitpoints']-$session['user']['level']*10)))),
					"diddamage"=>0,);
			$samui = array(
				 	"creaturename"=>translate_inline("Samui"),
		            "creaturelevel"=>$session['user']['level']+1,
		            "creatureweapon"=>translate_inline("Cool Headedness"),
					"creatureattack"=>$session['user']['level']+$session['user']['dragonkills']*3,
					"creaturedefense"=>$session['user']['level']+$session['user']['dragonkills'],
					"creaturehealth"=>($session['user']['level']*15+round(e_rand($session['user']['level'],($session['user']['maxhitpoints']-$session['user']['level']*10)))),
					"diddamage"=>0,);
			if ($session['user']['level']<6){
				$challenger=e_rand(1,2);
				if ($challenger==1){
					$badguy = $omoi;
					output("`~The Male steps forward to challenge you.");
				}else{
					$badguy = $karui;
					output("`~The darker female steps forward to challenge you.");
				}
				$stack[]=$badguy;
			}elseif ($session['user']['level']<11){
				output("`~The two darker shinobi rush at you together!");
				$stack[]=$karui;
				$stack[]=$omoi;
			}else{
				output("`~The whole team attacks you!");
				$stack[]=$karui;
				$stack[]=$omoi;
				$stack[]=$samui;
			}
			$attackstack = array(
				'enemies'=>$stack,
				'options'=>array('type'=>'lonestrider')
			);
			$session['user']['badguy']=createstring($attackstack);
			$op="combat";
			httpset('op', $op);
			case "combat": case "fight":
			include("battle.php");
			if ($victory){
				output("`~As you deliever your last blow, the team retreats together.");
				output("`n`\$\"Just you wait until we tell the Raikage about this!\" `~The dark female yells.");
				output("`n`7\"Don't say that Karui you fool! We're gonna be in a war for sure now! I must tell the Raikage I had nothing to do with it! You'll tell the Raikage right Samui, it was all her, not me!\" `~The male shouts as he disappears along with the darker female.");
				output("`n`1\"Well Shinobi, this isn't the last you will hear of us.\" `~Says the fair female as she slips away.");
				$expgain=round($session['user']['experience']*get_module_setting("experience")/100);
				$session['user']['experience']+=$expgain;
				output("`n`nYou gain %s exerience!",$expgain);
				$badguy=array();
				$session['user']['badguy']="";
				$session['user']['specialinc']="";
			}elseif ($defeat){
				output("`~You fall to the ground, to weak to keep fighting, waiting for the minute you will pushed off this life to the next by the hands of these strangers.");
				output("`n`7\"Samui, would it not be wiser to leave him alive? I mean we don't wanna start a war just yet do we.\"");
				output("`n`1\"You have a point Osoi, the Raikage's orders are strictly to find the Uchiha kid then...\" `~You pass out before you hear anymore.");
				output("`n`n`~You wake up not long after, only to find yourself alone, and with little strength, you try and seek for help.");
				$exploss=$session['user']['experience']*get_module_setting("experience")/100;
				$session['user']['experience']-=$exploss;
				output("`n`nYou lose %s exerience!",$exploss);
				$badguy=array();
				$session['user']['badguy']="";
				$session['user']['specialinc']="";
				$session['user']['hitpoints']=1;
			}else{
				require_once("lib/fightnav.php");
				$allow = true;
				fightnav($allow,false);
				if ($session['user']['superuser'] & SU_DEVELOPER) addnav("Escape to Village","village.php");
			}
		}
}		

function samui_run(){
}	

?>
