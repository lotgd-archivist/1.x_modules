<?php
/*
1.0 Initial Version
*/


function erosennin_rasengan_getmoduleinfo() {
	$info = array(
		"name"=>"Ero Sennin - Rasengan Extension",
		"author"=>"`2Oliver Brendel",
		"version"=>"1.0",
		"category"=>"Forest",
		"download"=>"",
		"requires"=>array(
			"erosennin"=>"1.03|Ero Sennin by `2Oliver Brendel",
			"specialtysystem_rasengan"=>"1.0|Rasengan Specialty by `2Oliver Brendel",
			),
		"settings"=>array(
			"Ero Sennin Rasengan - Preferences, title",
			"Train with him and obtain the Rasengan,note",
			"needed"=>"How much favours to try for Rasengan?,int|100",
			"necessarychakra"=>"How much skillpoints costs one Rasengan to execute?,int|5",
			),
		"prefs"=>array(
			"stage"=>"What stage is the Rasengan gathering in?,int|0",
			"tries"=>"Tries for this stage,int|0",
			),
	);
	return $info;
}
function erosennin_rasengan_install() {
	module_addhook("erosennin_favours");
	module_addhook_priority("dragonkill",INT_MAX);
	return true;
}

function erosennin_rasengan_uninstall() {
	return true;
}

function erosennin_rasengan_dohook($hookname,$args) {
	global $session;
	switch ($hookname) {
		case "erosennin_favours":
			$stage=(int) get_module_pref('stage');
			if ($session['user']['dragonkills']<1) return $args; //no n00bs
			if ($stage>3) return $args; //stages complete
			if ($stage>0) {
				addnav("Resume Training","runmodule.php?module=erosennin_rasengan&op=try&resume=1");
			} elseif ($args['favour']>get_module_setting('needed')) {
				addnav(array("`\$Fall to your knees `gbefore %s",get_module_setting('name','erosennin')),"runmodule.php?module=erosennin_rasengan");
			}
			break;
		case "dragonkill":
		//let this one survive
			if (((int)get_module_pref('stage'))==4) {
				require_once("modules/specialtysystem/datafunctions.php");
				$module="specialtysystem_rasengan";
				$data=specialtysystem_getspecs($module);
				$data=array_shift($data);
				specialtysystem_set(array($module=>array('skillpoints'=>(int)get_module_setting('necessarychakra'),"noaddskillpoints"=>(int)get_module_setting('necessarychakra'))));
				set_module_pref("cache",'',"specialtysystem");			
			}
			break;
	}
	return $args;
}

function erosennin_rasengan_run() {
	global $session;
	$watername='`1Wa`!te`Jr Bo`!tt`1le';
	$ballname="`\$Rubber ball";
	$ballonname='`!B`@a`#l`$l`%o`^o`&n';
	$session['user']['specialinc']=''; //clear away the old stuff
	$link='runmodule.php?module=erosennin_rasengan&';
	$op = httpget('op');
	$erosennin_rasengan=get_module_setting('name','erosennin');
	page_header("%s",sanitize($erosennin_rasengan));
	switch ($op) {
	case "":
		if (is_module_active('alignment')) {
			require_once('modules/alignment/func.php');
			if (is_evil()) {
				$reason=translate_inline('destroy');
			} else {
				$reason=translate_inline('protect');
			}
		} else {
			$reason=translate_inline("stand against");
		}
		if (is_module_active('curse_seal')) {
			if ((int) get_module_pref('hasseal')!=0) {
				output("`3He looks sharply at you... '`QSomebody who has a filthy seal on his hide won't receive any training... now go!`3'`n`n");
				addnav("Just leave...",$link."op=walk");
				break;
			}
		}
		output("`3Fall to your knees, bow your head and shout: '`\$Great Master! Let me be your student. I need power to %s people!`3'`n`n",$reason);
		output("'`QShhh... get your own hole!... erm? You, be my student? Hmmm... I know you... hmm... well...`3'`n`n");
		output("`vWhat do you do to convince him?");
		if (is_module_active('specialtysystem_basic')) {
			require_once('modules/specialtysystem/functions.php');
			$chakra=specialtysystem_availableuses();
			if ($chakra>0 && $session['user']['sex']==SEX_MALE) {
				//basic ninjutsu installed & everybody should have it.
				addnav("Transform into a beautiful girl",$link."op=henge");
			} else {
				addnav("I am a beautiful girl!",$link."op=show");
			}
		}
		addnav("Offer gold",$link."op=gold");
		addnav("Offer gems",$link."op=gems");
		addnav("Walk away",$link."op=walk");
		break;
	case "gold": case "gems":
		$what=translate_inline($op);
		output("`3He takes a `\$very`3 good look at the %s... and raises an eyebrow.`n`n",$what);
		output("'`QOh... Only that ... shhh, shsss, go away and annoy somebody else.`3'");
		addnav("Walk away",$link."op=walk");
		break;
	case "walk":
		output("`3You continue on your journey.`n`n");
		if (httpget('how')=="sad") output("Sad... very sad... it should have worked out... damn...`n`n");
		addnav("Continue","forest.php");
	break;
	case "henge":
		output("`3You walk towards him... he doesn't seem be impressed...`n");
		output("You utter loudly: '`@Henge No Jutsu!`3'`n`n");
		output("`c`~P`qoo`~F`c`n`n");
		rawoutput("<center><img src='modules/erosennin/images/henge.jpg'></center><br>");
		output("He seems to be very surprised ...`3`n`n");
		switch (e_rand(0,3)) {
			case 0:
				rawoutput("<center><img src='modules/erosennin/images/henge.jpg'></center><br>");
				output("He seems to be very surprised ...`3`n`n");
				rawoutput("<center><img src='modules/erosennin/images/wow.jpg'></center><br>");
				output("'`QOh... *nice guy pose* I like that! You can train... but only if you remain in that form!`3'`n`n");
				rawoutput("<center><img src='modules/erosennin/images/approved-2.jpg'></center><br>");
				output("`3... somehow you don't know if this was a good idea or not....`n`n");
				set_module_pref("stage",1);
				addnav("Begin Training",$link."op=stage1");
				break;
			default:
				rawoutput("<center><img src='modules/erosennin/images/showno.jpg'></center><br>");
				output("He seems to be very surprised ...`3`n`n");			
				output("'`QOh... And here I thought something good will happen... you need to do a bit more homework... *that* fat lady isn't my type... now go away and let me... investigate...`3'`n`n");
				addnav("Leave",$link."op=walk&how=sad");
				break;
		}
		break;
	case "show":
		output("`3You walk towards him... he doesn't seem be impressed...`n");
		output("You set up your cutest look,  throw your hair behind you and whisper: '`@Awww... pleeeasseeee!`3' with a finger on your lips.`n`n");

		switch (e_rand(0,3)) {
			case 0:
			rawoutput("<center><img src='modules/erosennin/images/show.jpg'></center><br>");
			output("He seems to be very surprised ...`3`n`n");
			rawoutput("<center><img src='modules/erosennin/images/wow.jpg'></center><br>");
				output("'`QOh... *nice guy pose* I like that! You can train... but only if you stay like that... Hehehe!`3'`n`n");
				rawoutput("<center><img src='modules/erosennin/images/approved-2.jpg'></center><br>");
				output("`3... somehow you don't know if this was a good idea or not....`n`n");
				set_module_pref("stage",1);
				addnav("Begin Training",$link."op=stage1");
				break;
			default:
				rawoutput("<center><img src='modules/erosennin/images/showno.jpg'></center><br>");
				output("He seems to be very surprised ...`3`n`n");			
				output("'`QOh... And here I thought something good will happen... you need to do a bit more homework... *that* is not my type... now go away and let me... investigate...`3'`n`n");
				$session['user']['charm']--;
				addnav("Leave",$link."op=walk&how=sad");
				break;
		}
		break;
	case "stage1":
		$tries=httpget('tries');
		if ($tries==0) {
			output("'`QAh... where were we? Ah yes... I know... the basic training....Okay, to obtain this rather unique technique... you have to do *this*`3'`n`n");
			output("He inflates a balloon with water and holds it in one hand. Then, he concentrates... the balloon remains calm... nothing happens....`n`n");
			output("'`@Erm, ok, what's the point? Nothing happens, do you want to mock me?`3'`n");
			output("'`QHehe, you don't get a thing, do you? Here is what happens inside...`3'`n`n");
			output("He holds up his second hand and within the palm a ball of whirling blue lights forms! You don't believe what you see... it's pure compressed chakra!`n`n");
			rawoutput("<center><img src='modules/erosennin/images/full_rasengan.jpg'></center><br>");
			output("'`QYou need to do the following... *whisper whisper*`3'`n`n");
			output("`n`nThe first exercise is to establish control...`n");
			output("The second is to build up power...`n");
			output("The third is to be able to control and release the power you built up... this is the hardest of all steps...`n`n");
			if (is_module_active("inventory")) {
				require_once("modules/inventory/lib/itemhandler.php");
				$haswater=check_qty_by_name($watername);debug("Has water? ($haswater)");
				$hasballon=check_qty_by_name($ballonname);debug("Has balloon? ($hasballon)");
				if ($haswater>0 && $hasballon>0) {
					output("'`QGreat! You have a water bottle and a ballon with you. So go ahead.`3'`n`n");
					output("You begin to understand... and give it a try...");
					addnav("Go for it",$link."op=go");
				} else {
					output("'`QWell... you need water AND a balloon... sorry, I have none to spare, some imprudent student of mine wrecked all of them in his attempts to master this jutsu...`3'");
					addnav("Leave",$link."op=walk&how=sad");
				}
			} else {
				output("You begin to understand... and give it a try...");
				addnav("Go for it!",$link."op=try");
			}
		} else {
			output("'`@I am ready to resume my training, Master!`3'`n");
			output("`n`n`'`QAhh... good, show me the results of your training!`3'`n`n");
			addnav("Go for it!",$link."op=try");
		}
		break;
	
	case "try":
		$stage=(int) get_module_pref('stage');
		if (httpget('resume')==1) {
			output("`3Again, you stand before %s`3 and are ready to show him the improvements you made while he was... investigating... stuff. You know...`n`n",$erosennin_rasengan);
			switch ($stage) {
				case 1:
					if (is_module_active("inventory")) {
						require_once("modules/inventory/lib/itemhandler.php");
						$haswater=check_qty_by_name($watername);debug("Has water? ($haswater)");
						$hasballon=check_qty_by_name($ballonname);debug("Has balloon? ($hasballon)");
						if ($haswater>0 && $hasballon>0) {
							output("'`QGreat! You have a water bottle and a ballon with you. So go ahead.`3'`n`n");
							output("You begin to understand... and give it a try...");
							addnav("Go for it",$link."op=go");
						} else {
							output("'`QWell... you need water AND a balloon... sorry, I have none to spare, some imprudent student of mine wrecked all of them in his attempts to master this jutsu...`3'");
							addnav("Leave",$link."op=walk&how=sad");
							page_footer();
						}
					}
					break;
				case 2:
					if (is_module_active("inventory")) {
						require_once("modules/inventory/lib/itemhandler.php");
						$hasball=check_qty_by_name($ballname);
						if ($hasball>0) {
							output("'`QGreat! You have a rubber ball. So go ahead.`3'`n`n");
							output("You begin to understand... and give it a try...`n`n");
							addnav("Go for it",$link."op=go");
						} else {
							output("'`QWell... you need a rubber ball... sorry, I have none to spare, some imprudent student of mine wrecked all of them in his attempts to master this jutsu...`3'");
							addnav("Leave",$link."op=walk&how=sad");
							page_footer();
						}
					}
					break;
				case 3:
				addnav("Go for it",$link."op=go");
				break;
			}
		} else {
			output("You begin to understand... and give it a try...");
			addnav("Go for it",$link."op=go");
			}
		
		break;
	case "go":
		$stage=(int) get_module_pref('stage');
		$try=e_rand(0,4);
		if ($stage==3) $try=e_rand(0,5);
		switch ($stage) {
			case 1:
				rawoutput("<center><img src='modules/erosennin/images/watertry.jpg'></center><br>");
				break;
			case 2:
				$k=e_rand(1,2);
				rawoutput("<center><img src='modules/erosennin/images/rubbertry$k.jpg'></center><br>");
				break;
			case 3:
				rawoutput("<center><img src='modules/erosennin/images/rasengantry.jpg'></center><br>");
				break;
		}
		switch ($try) {
			case 0:
				output("`@You... succeed!`3 ");
				switch ($stage) {
					case 1:
						output("The balloon pops and the water spills out!");
						rawoutput("<center><img src='modules/erosennin/images/water.jpg'></center><br>");
						break;
					case 2: 
						output("The rubber balloon explodes! The blast blows you across the clearing!");
						rawoutput("<center><img src='modules/erosennin/images/boom.jpg'></center><br>");
						break;
					case 3:
						output("You perform a perfect rasengan for the quarter of a second.");
						rawoutput("<center><img src='modules/erosennin/images/rasengan.jpg'></center><br>");
						break;
				}
				output("`n`n'`QAhh... very very good, you've done a good job!`3'`n`n");
				if ($stage==3) {
					output("'`QYou seem not to be able to master this technique until now... its power is decreased until you figure out a way to make it go off smoothly. Or until you completely master it. I cannot help you more than that, you have to do this on your own.`3'");
					require_once("modules/specialtysystem/datafunctions.php");
					$module="specialtysystem_rasengan";
					$data=specialtysystem_getspecs($module);
					$data=array_shift($data);
					specialtysystem_set(array($module=>array('skillpoints'=>(int)get_module_setting('necessarychakra'),"noaddskillpoints"=>(int)get_module_setting('necessarychakra'))));
					set_module_pref("cache",'',"specialtysystem");
					addnav("Continue","forest.php");					
				} elseif ($stage==2) {
					output("'`QWell, let's go to the next stage...you have to combine the steps one and two... focus on building up power and maintaining it... let me show it one more time... and then begone... my investigations...`3'`n`n");
					rawoutput("<center><img src='modules/erosennin/images/full_rasengan.jpg'></center><br>");
					output("After a short demonstration, you are on your own again.");
					require_once("modules/inventory/lib/itemhandler.php");
					remove_item_by_name($ballname);
					addnav("Continue","forest.php");
				} elseif ($stage==1) {
					output("'`QWell, let's go to the next stage...you have to build up enough power to set the jutsu off... focus on building up power and release it... you have to it with this rubber ball... put chakra into it and make it explode... but it's harder than with water, you cannot sense the rotation at all... let me show it one more time... and then begone... my investigations...`3'`n`n");
					output("'`QAh, I forgot, please bring some rubber ball with you, please.`3'`n`n");
					output("After a short demonstration, you are on your own again.");
					addnav("Continue","forest.php");					
					require_once("modules/inventory/lib/itemhandler.php");
					remove_item_by_name($watername);
					remove_item_by_name($ballonname);
				}
				increment_module_pref('stage');
				break;
			
			default:
				output("`\$You... fail!`3 Nothing happens... you try to extract chakra and control the reaction... but the reaction goes out of control and your power drops to zero....");
				output("`n`n'`QWell... I have work to do... you know... books don't write themselves... see you when you are better...`3'`n`n");
				switch ($stage) {
					case 1:
						output("`\$You have lost the %s`\$ and the %s`\$.",$watername,$ballonname);
						require_once("modules/inventory/lib/itemhandler.php");
						remove_item_by_name($watername);
						remove_item_by_name($ballonname);
						break;
					case 2:
						output("`\$You have lost the poor little and in fact innocent %s`\$.",$ballname);
						require_once("modules/inventory/lib/itemhandler.php");
						remove_item_by_name($ballname);
						break;
				}
				addnav("Walk away","forest.php");
			break;
		}
		break;
	}
	page_footer();
}



?>
