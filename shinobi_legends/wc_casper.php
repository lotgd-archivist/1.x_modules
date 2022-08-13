<?php
#	Casper RSP  20April2005
#	Author: Robert of Maddrio dot com
#	Converted from an 097 mod for use in Graveyard
#   Casper 1.2 (Winter Castle System) 
#   Coder: KainStrider
#   Incorporated, edited and Cloned 15October 2011
# v1.1 corrects download link

function wc_casper_getmoduleinfo(){
	$info = array(
	"name"=>"Casper RPS Game - Haunted House",
	"version"=>"1.2",
	"author"=>"`2Robert - Incorporated and Edited by `7KainStrider",
	"category"=>"Winter",
	"download"=>"EORPG",
	);
	return $info;
}

function wc_casper_install(){
    module_addhook("shades");
    return true;
}
function wc_casper_uninstall(){
	return true;
}

function wc_casper_dohook($hookname,$args){
	switch($hookname){
		case "shades":
		//addnav("Places");
		//addnav("Spirit Game","runmodule.php?module=casper");
		break;  
    }
return $args;
}

function wc_casper_run(){
    global $session;
    $op = httpget('op');
    $from = "runmodule.php?module=wc_casper&";
$who="`7Casper";
$money = 0;
$cost = 0;
$a="`6Rock";
$b="`&Paper";
$c="`2Scissors";
$d="You throw";
$e="has thrown";
$lmsg="Better luck next time!";
$wmsg="Wow, that was fun!";
if ($op == "playgame"){
	page_header("Casper's Games");
output_notl("`c`&Casper wants to play a game of Rock, Paper, Scissors!`n`nDo you want to play with him?`c`n",true);
addnav("Choose your weapon!");
addnav("(R) Rock",$from."op=1");
addnav("(P) Paper",$from."op=2");
addnav("(S) Scissors",$from."op=3");
addnav("Ask Casper the Rules"); 
addnav("(G) Game Rules",$from."op=rule");
addnav("Back Away from Casper");
addnav("(X) Exit Game","runmodule.php?module=wintercastle&op=hbleftroom");
  }  
	if ($op==""){
    output_notl("`n`n You challenge $who `&to a few rounds of a friendly game of $a`3, $b`3, $c`3."); 
    output_notl("`n You know, $who is `ialways`i glad to play a friendly game with you.");
	addnav("Go Back");
	addnav("Return to Casper","runmodule.php?module=wintercastle&op=hbleftroom");
}
if ($op=="1"){ 
		page_header("Rock, Paper, Scissors!");
		addnav("Go Back");
	addnav("Return to Casper","runmodule.php?module=wintercastle&op=hbleftroom");
		switch(e_rand(1,3)){
		case 1:	output_notl("`n`n`3 $d $a`3- $who $e $a`3 - it's a draw!"); addnews("%s `@was locked in battle with `7Casper`@ in the `lW`Linter `)Castle`@! No clear winner was declared.`0",$session['user']['name']); break;
		case 2:	output_notl("`n`n`3 $d $a`3- $who $e $b`n $b `3covers $a`3, `\$ You Lose`3! "); 
		if ($money == 1){
			output_notl("`n You pass $cost gold coins to $who");
			$session['user']['gold']-=$cost;
		}else{ output_notl("`n $lmsg ");} 
		break;
		case 3:	output_notl("`n`n`3 $d $a`3- $who $e $c`n $a `3dulls $c`3, `^ You Win`3! "); addnews("%s `@was locked in battle with `7Casper`@ in the `5Winter Castle`@ and `^WON`@!`0",$session['user']['name']);
		if ($money == 1){
			output_notl("`n $who passes $cost gold coins to you "); $session['user']['gold']+=$cost;
		}else{ output_notl("`n $wmsg "); }break;
	}
}
if ($op=="2"){
	page_header("Rock, Paper, Scissors!");
	addnav("Go Back");
	addnav("Return to Casper","runmodule.php?module=wintercastle&op=hbleftroom");
	switch(e_rand(1,3)){
		case 1:	output_notl("`n`n`3 $d $b`3- $who $e $a `n $b `3covers $a`3, `^ You Win`3! "); addnews("%s `@was locked in battle with `7Casper`@ in the `5Winter Castle`@ and `^WON`@!`0",$session['user']['name']);
		if ($money == 1){
			output_notl("`n $who passes $cost gold coins to you "); $session['user']['gold']+=$cost;
		}else{ output_notl("`n $wmsg "); }
		break;
		case 2:	output_notl("`n`n`3 $d $b`3- $who $e $b `3 - it's a draw! "); addnews("%s `@was locked in battle with `7Casper`@ in the `5Winter Castle`@! No clear winner was declared.`0",$session['user']['name']); break;
		case 3:	output_notl("`n`n`3 $d $b`3- $who $e $c `n $c `3cuts $b, `\$ You Lose`3 ");
		if ($money == 1){
			output_notl("`n You pass $cost gold coins to $who");
			$session['user']['gold']-=$cost;
		}else{ output_notl("`n $lmsg ");}
		break;
	}
}
if ($op=="3"){
	page_header("Rock, Paper, Scissors!");
	addnav("Go Back");
	addnav("Return to Casper","runmodule.php?module=wintercastle&op=hbleftroom");
	switch(e_rand(1,3)){
		case 1:	output_notl("`n`n`3 $d $c`3- $who $e $a`n $a `3dulls $c`3, `\$ You Lose`3! "); addnews("%s `@was locked in battle with `7Casper`@ in the `5Winter Castle`@ and was pwned!`0",$session['user']['name']);
		if ($money == 1){
			output_notl("`n You pass $cost gold coins to $who");
			$session['user']['gold']-=$cost;
		}else{ output_notl("`n $lmsg ");}
		break;
		case 2:	output_notl("`n`n`3 $d $c`3- $who $e $b`n $c `3cuts $b`3, `^ You Win`3! "); addnews("%s `@was locked in battle with `7Casper`@ in the `5Winter Castle`@ and `^WON`@!`0",$session['user']['name']);
		if ($money == 1){
			output_notl("`n $who passes $cost gold coins to you "); $session['user']['gold']+=$cost;
		}else{ output_notl("`n $wmsg "); }
		break;
		case 3:	output_notl("`n`n`3 $d $c`3- $who $e $c`3 - it's a draw! "); addnews("%s `\$was locked in battle with `7Casper`@ in the `5Winter Castle`@! No clear winner was declared.`0",$session['user']['name']); break;
	}
}
if ($op=="rule"){
	page_header("The Rules of Rock, Paper, Scissors");
	output_notl("`n`n$a`3, $b`3, $c `3is a very common and easy game to play.`n`n");
	output_notl("You select 1 of the 3 choices: $a`3, $b `3or $c`3.`n");
	output_notl("Your opponent will select either: $a`3, $b `3or $c`3.`n`n");
	output_notl("`^Who is the winner?`n");
	output_notl("`3If both are the same; it's a draw, no one wins`n");
	output_notl("$a `3wins over $c `3because $a `3dulls $c`n");
	output_notl("$b `3wins over $a `3because $b `3can cover $a`n");
	output_notl("$c `3wins over $b `3because $c `3can cut $b`n");
	addnav("Go Back");
	addnav("Return to Casper","runmodule.php?module=wintercastle&op=hbleftroom");
}
page_footer();
}
?>
