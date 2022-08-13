<?php


function keykeeper_getmoduleinfo()
{
	$info = array(
			"name"=>"Keeper of the Keys",
			"author"=>"`LShinobiIceSlayer",
			"version"=>"1.0",
			"category"=>"Forest Specials",
			"download"=>"",
			"settings"=>array(
				"Keeper of the Keys - Preferences, title",
				"Meet him and have a chance to gain a Key.,note",
				"name"=>"Name (coloured) of the Key Keeper,text|`4K`~eeper `4o`~f `4t`~he `4K`~eys",
				"experienceloss"=>"Percentage: How many experience is lost after a fight,floatrange,1,100,1|10",
				"maxkeys"=>"The Total number of key one player may have at one time.,int|7",
				),
		     );
	return $info;
}
function keykeeper_install()
{
	// module_addeventhook("forest", "return (\$session['user']['acctid']==7?100:0);");
	module_addeventhook("forest", "return 20;");

	return true;
}
function keykeeper_uninstall()
{
	return true;
}
function keykeeper_dohook($hookname,$args)
{
	return $args;
}
function countkeys($keyarray)
{
	global $session;
	$count=0;
	require_once("modules/inventory/lib/itemhandler.php");
	foreach($keyarray as $keyindex=>$keyname)
	{
		$hasitem=check_qty_by_name($keyname,$session['user']['acctid']);
		if ($hasitem>0) $count++;
	}
	return $count;
}
function keykeeper_runevent($type,$link)
{
	global $session;
	require_once("modules/inventory/lib/itemhandler.php");
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:keykeeper";
	$op = httpget('op');
	$keykeeper=get_module_setting("name");
	$keys=array(
			"red"=>"`\$Red Key",
			"black"=>"`~Black Key",
			"orange"=>"`qOrange Key",
			"blue"=>"`1Blue Key",
			"green"=>"`2Green Key",
			"gray"=>"`)Gray Key",
			"purple"=>"`5Purple Key",
		   );
	switch ($op)
	{
		case "":	
			output("`&You are walking through the forest, when you come across the ruins of a great castle.");
			output(" You step inside the once grand doors and enter into a large open space, when a blast of cold wind hits you.");
			output("`nYou look ahead to see a huge staircase that leads to a strange man wearing a hooded cloak, and a necklace of keys around his neck.");
			output("`nTurning you see a door to a side room, and you remember the door from which you entered.");
			addnav("Options.");
			addnav("Climb the Stairs",$link."op=keeper");
			addnav("Check the side room",$link."op=door");
			addnav("Run away",$link."op=run");
			break;
		case "run":
			output("`&You leave this strange place.`n`n");
			$session['user']['specialinc'] = "";
			break;
		case "door":
			output("You take the door to the side and head down a small hallway.");
			$randomchance=e_rand(1,4);
			switch ($randomchance)
			{
				case "1":
					output("`n`n`&You open the first door you find and enter the room, only to find it is an exit back to the forest.");
					output("`nYou turn and look back at the ruins, then continue on your way.");
					$session['user']['specialinc'] = "";
					break;
				case "2":
					output("`n`n`&You walk along the hallway and finally enter one of the doors.");
					output("`nYou step throgh the door slowly and find an old bedroom. As you inch closer to the bed you see a frail figure lying in it.");
					output("`n`n`7\"Could you help me out of bed dear child?.\" `&He asks. You pull back the covers of the bed and scream as you see things no person should ever see.");
					output("`n`n`&So you quickly flee this place and you feel your eyes burning from the images that haunt your mind.");
					apply_buff('oldmancurse',
							array(
								"name"=>"`7Blindness",
								"rounds"=>20,
								"wearoff"=>"You forget the old man.",
								"atkmod"=>0.8,
								"roundmsg"=>"You are haunted by the image of the old man.",
								"schema"=>"module-keykeeper",
							     ));
					$session['user']['specialinc'] = "";
					break;
				case "3":
					output("`n`n`&You head down the hallway you stop to tie your shoe. As you bend down you notice some kind of trap door in the floor.");
					output(" You pull on the hidden door, which reveals a passageway to another small room.");
					output(" As you enter you a blinded by a sudden bright light. When your eyes adjust to the light, you see the room is full of all kinds of treasure.");
					output("`nYour eyes light up as you try to grab as much treasure as your hands can old,");
					output(" Then you hear a footsteps coming from behind, afraid you grab as much as your hands can hold and you flee quickly.");
					$goldgain=$session['user']['level']*e_rand(1,20);
					$session['user']['gold']+=$goldgain;
					$gemsgain=e_rand(0,2);
					$session['user']['gems']+=$gemsgain;
					if ($gemsgain>0) {
						output("You flee with %s Gems, and %s Gold.",$gemsgain,$goldgain);
					} else { 
						output("You flee with %s Gold.",$goldgain);
					}
					$session['user']['specialinc'] = "";
					break;
				case "4":
					output("`n`n`&You run down the hallway, and push through the door at the end. You come out in a large dinning hall,");
					output(" You look around when you feel chill from set of doors on the other side of the room.");
					output(" As you get closer to the doors, you can see a glow shinning through the gaps. Excited think you've found treasure, you rush into the room.");
					output("`nYou gasp loudly as the hooded figure is standing there, the keys around his neck glowing, making it feel as though you cannot flee.");
					addnav("Fight",$link."op=keeper");
					break;
			}
			break;
		case "key":
			$maxkeys=get_module_setting("maxkeys");
			$totalkeys=countkeys($keys);
			output("`&You reach down to take one of the keys from the hooded being.");
			$randomchance=e_rand(1,7);
			switch ($randomchance)
			{
				case "1":
					$key="red";
					break;
				case "2":
					$key="black";
					break;
				case "3":
					$key="orange";
					break;
				case "4":
					$key="blue";
					break;
				case "5":
					$key="green";
					break;
				case "6":
					$key="gray";
					break;
				case "7":
					$key="purple";
					break;
			}
			$keyname=$keys[$key];
			$haskey=check_qty_by_name($keyname,$session['user']['acctid']);
			if ($haskey==0){
				if ($totalkeys<$maxkeys){
					output("`n`nYou grab a %s `&as the figure moves, and you quickly flee with just the one key.",$keyname);
					$result=add_item_by_name($keyname,1);
				}else{
					output("`n`nYou go to take a key, but you feel you have too many already.");
				}
			}else{
				output("`n`nAs you reach for a key, a whithered hand grabs you. Frightened, you flee before he can attack you again.");
			}	
			$session['user']['specialinc'] = "";
			break;
		case "keeper":
			$op = "fight";
			httpset("op",$op);
			output("`&The hooded Figure stands, and the the keys glow brightly around his neck.");
			$weapon=$session['user']['weapon'];
			output(" You feel their power draw you in, and you can't help but lunge at the figure with your %s",$weapon);
			require_once("lib/battle-skills.php");
			$name=get_module_setting("name");
			$badguy = array(
					"creaturename"=>$name,
					"creaturelevel"=>$session['user']['level']+1,
					"creatureweapon"=>"Power of the Keys",
					"creatureattack"=>$session['user']['level']+2*$session['user']['dragonkills'],
					"creaturedefense"=>$session['user']['level']+2*$session['user']['dragonkills'],
					"creaturehealth"=>50+($session['user']['level']*20),
					"diddamage"=>0,);
			$battle=true;	
			$session['user']['badguy'] = createstring($badguy);
			break;
	}
	if ($op == "fight"){
		$battle = true;
	}
	if (isset($battle) && $battle){
		include("battle.php");
		if ($victory){
			output("`&You look down at the lifeless body of the figure, the Keys still glowing around his neck.");
			addnav("Take a Key",$link."op=key");
			addnav("Leave him",$link."op=leave");			
		}elseif($defeat){ 
			$exploss = $session['user']['experience']*get_module_setting("experienceloss")/100;
			output("`n`n`@You are dead... stroke down by %s `@.`n",$badguy['creaturename']);
			if ($exploss>0) output(" You lose `^%s percent`@  of your experience and all of your gold.",get_module_setting("experienceloss"));
			$session['user']['experience']-=$exploss;
			$session['user']['gold']=0;
			debuglog("lost $exploss experience and all gold to Key Keeper.");
			addnav("Return");
			addnav("Return to the Shades","shades.php");
			$session['user']['specialinc'] = "";
			$badguy=array();
			$session['user']['badguy']="";
		}else{
			require_once("lib/fightnav.php");
			if ($type == "forest"){
				fightnav(true,false);
			}else{
				fightnav(true,false,$link);
			}
		}	
	}	
}

function keykeeper_run(){
}

?>
