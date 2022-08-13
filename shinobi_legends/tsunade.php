<?php
// mail ready
// addnews ready
// translator ready
function tsunade_getmoduleinfo(){
	$info = array(
		"name"=>"Tsunade's Field Trip",
		"version"=>"1.0",
		"author"=>"`LShinobiIceSlayer",
		"category"=>"Forest Specials",
		"download"=>"",
	);
	return $info;
}

function tsunade_install(){
	module_addeventhook("forest", "return 100;");
	return true;
}

function tsunade_uninstall(){
	return true;
}

function tsunade_dohook($hookname,$args) {
	return $args;
}

function tsunade_runevent($type,$link) {
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:tsunade";
	$op = httpget('op');
	
	switch ($op) {
		
		case "":
			output("`@You are walking through the forest when you see two females in the distance. As you get closer, you see `RSakura`@, who appears to be picking berries and various plants. The other one might be `2Tsunade`@, from her incredibly large... eyes. She is reading a book while overseeing the girl.");
			addnav("Approach Tsunade",$link."op=tsunade");
			addnav("Help Sakura",$link."op=sakura");
			addnav("Ignore Them",$link."op=leave");
		break;
		case "tsunade":
			$chance=e_rand(1,2);
			if ($chance==1) {
				$attack=e_rand(1,2);
				if ($attack==1) {
					output("`2Tsunade `@is startled by your appearance, and hits you with her incredibly powerful punch, sending you flying through the forest.");
					$hploss=ceil($session['user']['hitpoints']/2);
					$session['user']['hitpoints']-=$hploss;
					output("`@You lose `4%s `@hitpoints.",$hploss);
				} else {
					output("`2Tsunade `@barely glances up as she informs you she is busy, and asks you to continue on your way.");
				}
				$session['user']['specialinc'] = "";
			} elseif ($chance==2) {
				output("`2Tsunade `@looks up, `2\"Hey there stranger, I'm pretty bored, how about a quick game? Unless your scared that is?\" `2She pulls out a cup and dice, and awaits your responce.");
				addnav("Play Her Game",$link."op=play");
				addnav("Decline",$link."op=decline");
			} 
		break;
		case "play":
			output("`@You sit down with `2Tsunade `@and play a game with her, she rolls the dice and... `n`n");
			$outcome=e_rand(1,4);
			switch ($outcome) {
				case 1:
					if ($session['user']['gold']==0) {
						$session['user']['hitpoints']=1;
						output("`2Tsunade `@wins! She cheers for Victory... until she finds you have no money, and beats you until your an inch from death.");
					} else {
						$session['user']['gold']=0;
						output("`2Tsunade `@wins! You hand her all your gold, knowing that if you don't the alternative would be much worse.");
					}
				break;
				case 2:
					output("`2Tsunade `@wins! She dances around and keeps saying how great she is at this game, but while she's distracted you quickly sneak away before paying up.");
				break;
				case 3:
					$goldgain=($session['user']['level']*50);
					$session['user']['gold']+=$goldgain;
					output("`@You win! `2Tsunade `@sighs and hands over %s gold.",$goldgain);
				break;
				case 4:
					$hplose=ceil($session['user']['hitpoints']/2);
					$session['user']['hitpoints']-=$hplose;
					output("`@You win! As you dance and cheer, Tsunade gets angry, when you ask for your money she gets flustered then smacks you in the face, sending you flying, causing %s damage.",$hplose);
				break;
			}			
			$session['user']['specialinc'] = "";
		break;
		case "decline":
			output("`@You decline her offer, and head back out for some adventure!");
			$session['user']['specialinc'] = "";
		break;
		case "sakura":
			output("`@You head over to the bushes that `RSakura `@is picking from. `n`n");
			$sakura=e_rand(1,4);
			if ($sakura==1) {
				output("`@You bend down to help, when `RSakura `@thinks you try and look at her Inappropriately, making her start screaming her head off. You quickly leave before she gets her hands on you.");
				$session['user']['specialinc'] = "";
			} elseif ($sakura==2) {
				output("`RSakura `@smiles at you as you start picking berries with her, hungry you take one and eat, smiling back before vomiting all over her. `R\"You ate a poisonous berry you fool!\" Sakura `@ yells. Slowly you stumble off still sick.");
				apply_buff('poison',
								array(
									"name"=>"`%Poison",
									"rounds"=>10,
									"wearoff"=>"The vomiting passes.",
									"minioncount"=>1,
									"mingoodguydamage"=>1*$session['user']['level'],
									"maxgoodguydamage"=>5*$session['user']['level'],
									"effectmsg"=>"The Poison does {damage} damage to you.",
									"schema"=>"module-tsunade",
								));
				$session['user']['specialinc'] = "";
			} else {
				output("`RSakura `@sees you and says, `R\"Hi there, I'm working on a new Elixir, would you like to be the first to try it?\"");
				addnav("Test the Elixir",$link."op=elixir");
				addnav("Decline",$link."op=decline");
			}
		break;
		case "elixir":
			output("`@You accept `RSakura's `@offer and take a small bottle of her new Elixir, and after a few seconds of fear, you quickly drain the bottle of it's contents. `n`n");
			$elixir=e_rand(1,10);
			switch ($elixir) {
				case 1:
					$session['user']['charm']-= e_rand(1,3);
					output("`@You wonder if the Elixir did anything, feeling no effect until you see `RSakura's`@. Wondering what is wrong you catch your reflection in the bottle, which shows a horrible wart covered face. Feeling ashamed you flee from `RSakura's `@sight.");
					output("`n`n`@You feel less charming.");
				break;
				case 2:
            				if ($session['user']['turns']>0)
						$session['user']['turns']--;
					output("`@Your eyes begin to feel heavy, then you promptly pass out. You awake alone and in the dark.");
					output("`n`n`@You lose a Forest Fight.");
				break;
				case 3:
					apply_buff('poison',
								array(
									"name"=>"`%Poison",
									"rounds"=>10,
									"wearoff"=>"The Poison wears off.",
									"minioncount"=>1,
									"mingoodguydamage"=>1*$session['user']['level'],
									"maxgoodguydamage"=>5*$session['user']['level'],
									"effectmsg"=>"The Poison does {damage} damage to you.",
									"schema"=>"module-tsunade",
								));
					output("`RSakura `@smiles until she looks at the ingredients put in the Elixir. `R\"Oh no... I used the wrong berry...\" `@Your skin turns `%Purple`@, and you are racked with burning pain throughout your body.");
				break;
				case 4:
					$session['user']['alive']=false;
					$session['user']['hitpoints']=0;
					output("`@The last thing you remember is `RSakura's `@face.");
					output("`n`n`@You have died.");
					addnav("Daily News","news.php");
				break;
				case 5:
					apply_buff('plusatk',
								array(
									"name"=>"`4Elixir Strength",
									"rounds"=>10,
									"minioncount"=>1,
									"roundmsg"=>array("`4You feel strong from the Elixir."),
									"wearoff"=>"The Elixir wears off.",
									"tempstat-attack"=>1,
									"schema"=>"module-tsunade",
								));
					output("`@As you drink, your body becomes more toned, and built. `RSakura `@seems quite pleased with her work!");
					output("`n`n`@You feel strong!");
				break;
				case 6:
					$session['user']['hitpoints']+=30;
					output("`@You feel nice and healthy! `RSakura `@pats you on the back and sends you back on your way.");
					output("`n`n`@You gain 30 Hitpoints.");
				break;
				case 7:
					$session['user']['spirits']=2;
					output("`@You start to feel happy, really really happy! You wave to `RSakura `@and leave with a giant grin on your face!");
					output("`n`n`@You have High Spirits!");
				break;
				case 8:
					$session['user']['gold']+=300;
					output("`@The Elixir gives you web toes, so `RSakura `@gives you some money to get them fixed.");
					output("`n`n`@You gain 300 gold.");
				break;
				case 9: case 10:					
					output("`@Nothing seems to happen, and `RSakura `@busily goes to work, trying to find what went wrong.");
				break;
			}
			$session['user']['specialinc'] = "";
		break;
		case "leave":
			output("`@You decide that they look rather 'busy' and leave them to their work.");
			$attack=e_rand(1,4);
			if ($attack==1) {
				output("`n`n`@As you leave you step on a branch, making them both jump, and before you know it two very angry and startled women send you flying across the forest.");
				$hploss=ceil($session['user']['hitpoints']/2);
				$session['user']['hitpoints']-=$hploss;
				output("`n`n`@You lose %s hit points.",$hploss);
			}
			$session['user']['specialinc'] = "";
		break;
		
	}
	
}

function tsunade_run(){
}
?>
