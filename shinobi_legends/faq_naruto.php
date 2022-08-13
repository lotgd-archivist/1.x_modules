<?php


function faq_naruto_getmoduleinfo(){
	$info = array(
		"name"=>"FAQ for the Naruto Theme Server",
		"version"=>"1.0",
		"author"=>"`2 Oliver Brendel, based on FAQ Central Server",
		"category"=>"General",
		"download"=>"",
		"allowanonymous"=>true,
		"override_forced_nav"=>true,
	);
	return $info;
}

function faq_naruto_install(){
	module_addhook("faq-toc");			//show in the FAQ
	return true;
}

function faq_naruto_uninstall(){
	return true;
}

function faq_naruto_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "faq-toc":
		$t = translate_inline("`@Customs on this Server`0");
		output_notl("&#149;<a href='runmodule.php?module=faq_naruto&op=faq'>$t</a><br/>", true);
		$ct = translate_inline("`@Naruto FAQ extension(with Spoilers)");
		output_notl("&#149;<a href='runmodule.php?module=faq_naruto&op=faq_naruto'>$ct</a><br/>", true);
		break;
	}
	return $args;
}

function faq_naruto_run(){
	global $session;
	$op = httpget("op");
	if ($op == "faq") {
		faq_faq();
	} elseif ($op=="faq_naruto") {
		faq_naruto();
	} else {
		require_once("lib/forcednavigation.php");
		do_forced_nav(false, false);
	}
}

function faq_naruto() {
	tlschema("faq");
	popup_header("Naruto FAQ extension");
	$c = translate_inline("Return to Contents");
	rawoutput("<a href='petition.php?op=faq'>$c</a><hr>");
	output("`n`n`c`bShinobi Legends FAQ extension`b`c`n");
	output("`^Welcome to the Shinobi Legends Server located at `&http://shinobilegends.com`n`n");
	output("`^Q.What is the Purpose of a Creature/Mount?`n
`#A.It serves as a battling companion and aides you in battle, it can also give you some extra turns per new day and a littles extra Travels.`n
`n
`^Q.Where would you find a mount/creature?`n
`#A.In Bertold's Beastry in Kirigakure or Merick's Stables in Ninja Central`n
`n
`^Q. How do I become Genin?`n
`#A. Wait until level 15 and you'll see... if you're female and good looking maybe a nude visit to the admin helps.`n
`n
`^Q.What purpose does the battle arena serve?`n
`#A.This module is used to provide a fun battling experience without the consequence of death.`n
`n
`^Q. How can I make my comments coloured?`n
`#A. Go to Ichiraku in Konoha and pay... he will tell you.`n
`n
`^Q. What's the advantage of the Battle Arena`n
`#A. If you lose you get to keep your life, But if you win you live and get a hefty lot of gold, and if your lucky and never get hit you get to keep your 50 gold entry fee!`n
`n
`^Q.I need Friends. What do I do?`n
`#A.Socialize, Kid! Talk to someone At the bar, in your clan halls, or even in the calming gardens. If they like you and you like them go to your mail and click friend list. Then go to friend search and search their name, you should, then, be able to do the rest.`n`n
`^Q.How come I see so many level one Genins and level 6 Jounins and such?`n
`#A.Wait and you'll see =) the levels are not the only thing attainable here...`n`n
`^Q.I wanna be in a clan! How do I apply for one?`n
`#A.You can go to The Clan Halls and apply for one of the clans that seems most appealling to you, but only one! If your status exceeds or is satisfactory to the clan's requirments you will be accepted.`n`n
`^Q.What's a PVP?`n
`#A. A PVP is a player versus player battle, where two ACTUAL players can fight against each other. You get three each new day... Use them wisely XD.`n`n");
	output("`^Q.What is a DWELLING?`n
`#A.It's a private living home for you and two other mates[You have some keys you can give away!]`n
`n
`^Q.How come I can't make a dwelling?`n
`#A.You might have to be a Genin or higher, It takes discipline and hard work to build one.`n
`n
`^Q.What are those messages in my inbox telling me I was successful in_______ or I was unsuccessful in______?
`#A.Well, when your away your name get's posted on the roster of potential prey. Meaning in the \"Slay Players\" Option your name get's posted up.`n
`n
`^Q. Why can't our turns be saved up and used the next game day!?!`n
`#A. Because We're not friggin' Cingular-We don't have roll over turns. Just burn'em all up in one day! However, you can 'save' some turns of a missed game day. That is a little extra battery we grant you - however, it has its limit (not logging in for two months won't give you tons of turns).`n
`n
`^Q.What's a Kabuto Kill?`n
`#A. It's something you will see when you grow in levels...`n
`n
`^Q.I want to request something for LON [Legend of Naruto] Where can I submit a proposal?`n
`#A. Well you can visit us in the Naruto forum at : http://forum.shinobilegends.com`n A link is also available at the lower left hand corner of the home page screen`n
`n
`^Q. I love you dudes! In fact, enough to pay you. Where can I donate to you?`n
`#A. On the lower right hand corner you see two buttons Click the one that says Donate to Site Admin. Mind you, you'll need a paypal account.`n
`n
`^Q.I know how to program php/sql and/oder specific lotgd (core) code. How can I help?
`#A. You can contact Neji at the forementioned forum and talk to him, and if he likes your offer, he might consider it.`n`n");
	output("`^Once again, welcome, and good luck in your quest!`n`n");
	rawoutput("<hr><a href='petition.php?op=faq'>$c</a>");
	popup_footer();
}


function faq_faq() {
	tlschema("faq");
	popup_header("Customs on this Server");
	$c = translate_inline("Return to Contents");
	rawoutput("<a href='petition.php?op=faq'>$c</a><hr>");
	output("`n`n`c`bRules on this Server`b`c`n");
	output("`^Welcome to the Naruto Server located at `&http://shinobilegends.com`n`n");
	output("`@While you're here there's a few customs that we (the Staff) hope players will be aware of and abide by.");
	output("These customs are in place to keep the playing experience enjoyable for most (not all, unfortunately, since we can't please everybody) of the people who come across our little realm on the Internet.");
	output("Follow them and we can all have a good time.`n`n");
	output("Without further ado, here they are:`n");
	output("`^1. `#Don't be a jerk.`n");
	output("`^2. `#No circumventing the language filter.`n");
	output("`^3. `#Don't give away game secrets.");
	output("This is a game of exploration, so don't spoil it for everyone.");
	output("If it's covered in the FAQ, it's free knowledge of course, as well as a few obvious things that were added since the FAQ was written.`n");
	output("`^4. `#People of all ages play here, so keep that in mind.`n");
	output("`^5. `#Since you've read the rest of the FAQ up to this point there's no need to repeat the part about NO CHATSPEAK, is there?`n");
	output("`^6. `#Play along with the story at the top of the page (eg. only role-playing in the Gardens, only beta stuff in the Beta Pavilion, the others are mostly general.)`n");
	output("`^7. `#You may have more than one character, but they shouldn't interact.");
	output("Don't attack each other, talk to each other, place bounties on each other, or refer your own alts.");
	output("That sort of cheating is just in bad taste.");
	output("If you share a computer with another player we will assume there is only one person at the keyboard.`n");
	output("`^8. `#Listen to the admins and other staff.");
	output("If you don't, you are risking your access to the game.`n");
	output("`@That's about it for the summary.");
	output("All of the staff can delete comments, so if one of your posts disappears consider it a warning.`n");
	output("`^Once again, welcome, and good luck in your quest!`n`n");

	rawoutput("<hr><a href='petition.php?op=faq'>$c</a>");
	popup_footer();
}
?>
