<?php
/*
Points Tranfser
File:   pointstransfer.php
Author: Red Yates aka Deimos
Date:   03/12/2005

Idea from Macarn of dragoncat and JT.
A module to allow players to transfer their donation points to other players.
Allows for an anonymous transfer, and notification via YOM with an optional
note.

*/

function pointstransfer_getmoduleinfo(){
	$info = array(
		"name"=>"Points Transfer",
		"version"=>"1.2",
		"author"=>"`\$Red Yates, modified by `2Oliver Brendel",
		"category"=>"Lodge",
		"download"=>"",
		"settings"=>array(
			"Points Transfer Settings,title",
			"mint"=>"Minimum transfer,int|25",
		),
		"prefs"=>array(
			"Points Transfer Form Holders, title",
			"amount"=>"Amount sending,int|",
			"target"=>"Recipient of points|",
			"anon"=>"Send these points anonymously,bool|",
			"note"=>"Note to send|",
			"multisend"=>"Send how often to a multichar,int",
			"multilog"=>"Multilog,viewonly",
			"log"=>"Log of sendings,viewonly",
		),
	);
	return $info;
}

function pointstransfer_install(){
	module_addhook("lodge");
	module_addhook("superuser");
	return true;
}

function pointstransfer_uninstall(){
	return true;
}

function pointstransfer_dohook($hookname, $args){
	global $session;
	switch ($hookname){
		case "lodge":
			addnav("Transfer Points");
			addnav("Transfer Points","runmodule.php?module=pointstransfer");
			set_module_pref("amount","");
			set_module_pref("target","");
			set_module_pref("anon",0);
			set_module_pref("note","");
		break;
		case "superuser":
			if (($session['user']['superuser']&SU_MEGAUSER)==SU_MEGAUSER) {
				addnav("Mechanics");
				addnav("Donation Point Multitransfers","runmodule.php?module=pointstransfer&op=supershow");
			}
	}
	return $args;
}

function pointstransfer_pointscheck(){
	global $session;
	$tpoints=0;
	if (is_module_active("titlechange")){
		$titles=get_module_pref("timespurchased","titlechange");
		if ($titles) $tpoints=get_module_setting("initialpoints","titlechange")+($titles-1)*get_module_setting("extrapoints","titlechange");
	}
	return min($session['user']['donation']-$session['user']['donationspent'],$session['user']['donation']-$tpoints); //Thanks Booger.
}

function pointstransfer_run(){
	global $session;
	require_once("lib/systemmail.php");
	page_header("Hunter's Lodge");
	$op = httpget("op");
	$mint=get_module_setting("mint");
	addnav("Navigation");
	addnav("L?Return to the Lodge","lodge.php");
	$user = &$session['user'];
	switch ($op) {
		/** ADMIN SECTION */
		
		case "supershow":
			$sql="SELECT a.name,a.acctid,b.value FROM ".db_prefix('accounts')." AS a RIGHT JOIN ".db_prefix('module_userprefs')." AS b ON a.acctid=b.userid WHERE b.modulename='pointstransfer' AND b.setting='multisend' ORDER BY b.value+0 desc";
			$result=db_query($sql);
			//debug($sql);
			output("`2Users who transferred points to their possible Multis, in fact the same uniqueid...`n`n");
			
			rawoutput("<center><table>");
			rawoutput("<tr class='trhead'><td>".translate_inline("Name")."</td><td>".translate_inline("# Operations")."</td><td>".translate_inline("People who sent this char DP")."</td></tr>");
			while ($row=db_fetch_assoc($result)) {
				$class=($class=='light'?'dark':'light');
				rawoutput("<tr class='tr$class'><td>");
				output_notl($row['name']);
				rawoutput("</td><td>");
				output_notl("%s transfers",$row['value']);
				rawoutput("</td><td>");
				//
				$log=get_module_pref("multilog","pointstransfer",$row['acctid']);
				rawoutput("<ul>");
				if ($log!='') $log=unserialize($log); //is an array if >1
					else $log=array();
				foreach ($log as $offender=>$transfers) {
					$sql2="SELECT name FROM ".db_prefix('accounts')." WHERE acctid=".$offender;
					//debug($sql2);
					$result2=db_query($sql2);
					$row2=db_fetch_assoc($result2);
					rawoutput("<li>");
					output("`4%s`2 sent a total of %s points in %s transfers to this char.`n",sanitize($row2['name']),$transfers['dp'],$transfers['times']);
					rawoutput("</li>");
				}
				rawoutput("</ul></td></tr>");
				//
			}
			rawoutput("</table></center>");
			addnav("Navigation");
			require_once("lib/superusernav.php");
			superusernav();
			blocknav("lodge.php");
			addnav("More");
			addnav("Refresh","runmodule.php?module=pointstransfer&op=supershow");
		
		break;

		/** USER SECTION **/
		case "confirm":
			$amount = abs((int)httppost("amount"));
			$target=httppost("target");
			$anon=httppost("anon");
			$note=stripslashes(preg_replace("/[`][bic]/", "",httppost("note")));
			set_module_pref("amount",$amount);
			set_module_pref("target",$target);
			set_module_pref("anon",$anon);
			set_module_pref("note",$note);
			if (!$amount){
				output("`7J. C. Petersen gives you an odd look.");
				output("`n`n\"`&Why would you give someone zero points?");
				output("Perhaps you should try again when you're thinking more clearly?`7\"");
				addnav("Try Again","runmodule.php?module=pointstransfer");
			}elseif ($amount < $mint){
				output("`7J. C. Petersen gives you an odd look.");
				output("`n`n\"`&I'm sorry, but you need to donate at least `@%s`& points.", $mint);
				output("Perhaps you should try again, giving more?`7\"");
				addnav("Try Again","runmodule.php?module=pointstransfer");
			}elseif ($amount > pointstransfer_pointscheck()){
				output("`7J. C. Petersen gives you an odd look.");
				output("`n`n\"`&I'm sorry, but you don't have `@%s`& points to give.", $amount);
				output("Perhaps you should try again with less, or donate more?`7\"");
				addnav("Try Again","runmodule.php?module=pointstransfer");
			}else{
				$newtarget = "";
				$sql="SELECT acctid,name FROM ".db_prefix("accounts")." WHERE (login LIKE '".addslashes("%".$target."%")."' OR login LIKE '".addslashes($target)."') AND locked=0";
				$result=db_query($sql);
				if (db_num_rows($result)==0) {
					for ($x=0; $x<strlen($target); $x++) {
						$newtarget.=substr($target,$x,1)."%"; //Eric rocks. 
					}
					$sql="SELECT acctid,name FROM ".db_prefix("accounts")." WHERE (name LIKE '".addslashes($newtarget)."' OR login LIKE '".addslashes($newtarget)."') AND locked=0";
					$result=db_query($sql);
				}
				if (!db_num_rows($result)){
					output("`7J. C. Petersen gives you an odd lock.");
					output("`n`n\"`&I'm sorry, but I don't know anyone by that name.");
					output("Perhaps you should try again?`7\"");
					addnav("Try Again","runmodule.php?module=pointstransfer");
				}elseif (db_num_rows($result)>300){
					output("`7J. C. Petersen gives you an odd lock.");
					output("`n`n\"`&I'm sorry, but there's way too many people who might go by that name.");
					output("Perhaps you should narrow it down, next time?`7\"");
					addnav("Try Again","runmodule.php?module=pointstransfer");
				}elseif (db_num_rows($result)>1){
					rawoutput("<form action='runmodule.php?module=pointstransfer&op=send' method='POST'>");
					addnav("","runmodule.php?module=pointstransfer&op=send");
					addnav("Start Over","runmodule.php?module=pointstransfer");
					output("`7J. C. Petersen looks at you.");
					output("`n`n\"`&There's a few people I know by that name.");
					output("Tell me which one you mean, and I'll send those points right off.`7\"");
					output("`n`nPoints: `@%s`7",$amount);
					output("`n`nRecipient: ");
					rawoutput("<select name='target'>");
					while ($row=db_fetch_assoc($result)) {
						rawoutput("<option value='{$row['acctid']}'>".full_sanitize($row['name'])."</option>");
					}
					rawoutput("</select>");
					output("`n`nAnonymous Transfer: `&%s`7",($anon?"Yes":"No"));
					output("`n`nOptional Note: `&%s`7",$note);
					output_notl("`n`n");
					$send=translate_inline("Send");
					rawoutput("<input type='submit' class='button' value='$send'>");
					rawoutput("</form>");
				}else{
					addnav("Start Over","runmodule.php?module=pointstransfer");
					$row=db_fetch_assoc($result);
					$name=$row['name'];
					output("`7J. C. Petersen smiles at you.");
					output("`n`n\"`&This all looks to be in order to me.");
					output("This is what you meant, right?`7\"");
					output("`n`nPoints: `@%s`7",$amount);
					output("`n`nRecipient: `&%s`7",$name);
					output("`n`nAnonymous Transfer: `&%s`7",($anon?"Yes":"No"));
					output("`n`nOptional Note: `&%s`7",($note?$note:"`inone`i"));
					output_notl("`n`n");
					rawoutput("<form action='runmodule.php?module=pointstransfer&op=send' method='POST'>");
					addnav("","runmodule.php?module=pointstransfer&op=send");
					rawoutput("<input type='hidden' value='{$row['acctid']}' name='target'>");
					$send=translate_inline("Send");
					rawoutput("<input type='submit' class='button' value='$send'>");
					rawoutput("</form>");
				}
			}
			break;
		case "send":
			$amount=get_module_pref("amount");
			$target=httppost("target");
			$sql="SELECT name FROM ".db_prefix("accounts")." WHERE acctid='$target'";
			$result=db_query($sql);
			$row=db_fetch_assoc($result);
			$targetid=$target;
			$target=$row['name'];			
			
			if ($targetid==$session['user']['acctid']){
				output("`7J. C. Petersen gives you a weird look and puts down his pen.");
				output("`n`n\"`&Why would you ever want to transfer points to yourself?");
				output("Perhaps you should try again when you're thinking more clearly?`7\"`n`n");
			}else{
				$anon=get_module_pref("anon");
				$note=stripslashes(get_module_pref("note"));
				$note=translate_inline($note?"`n`nThey also added this note:`n".$note:"");
	

				/** MULTI **/
				$sql="SELECT uniqueid FROM ".db_prefix('accounts')." WHERE acctid=".$targetid;
				$result=db_query($sql);
				$row=db_fetch_assoc($result);
				if ($row['uniqueid']==$session['user']['uniqueid'] ) {
					//offender!
					debuglog(sanitize($session['user']['name'])." has sent these points to possible MULTICHAR!");
					set_module_pref("multisend",get_module_pref("multisend","pointstransfer",$targetid)+1,"pointstransfer",$targetid);
					$text=get_module_pref("multilog","pointstransfer",$targetid);
					if ($text=="") $text=array();
						else $text=unserialize($text);
					//array
					if (!is_array($text[$user['acctid']])) {
						$text[$user['acctid']]=array(
							"times"=>1,
							"dp"=>$amount
							);
					}else {
						//exists
						$text[$user['acctid']]['times']+=1;
						$text[$user['acctid']]['dp']+=$amount;
					}
					set_module_pref('multilog',serialize($text),"pointstransfer",$targetid);
					
					output("`7\"`\$I am terribly sorry, but this server forbids to transfer points between characters who have the same owner.`n`n Note that for instance we can not distinguish people who sit in front of the same computer, open up the same browser on the same computer account.`n`nYour try has been noted and the admins been notified.`7\", are the last words you hear before you are asked to leave...`n`n");
					blocknav("lodge.php");
					addnav("Leave in shame...","village.php");
					page_footer();
				}
				
				/** **/
				if ($targetid != $session['user']['acctid']) {
					$sql="UPDATE ".db_prefix("accounts")." SET donation=donation+$amount WHERE acctid=$targetid";
					$result=db_query($sql);
					$session['user']['donation']-=$amount;
				}
				$note = str_replace ("\"","\\\"",$note);
				if ($anon){
					systemmail($targetid, array("`@Donator Points Transfer`0"),array('`2Someone has gifted you with `@%s`2 donator points. %s', $amount, $note));
				}else{
					systemmail($targetid,array("`@Donator Points Transfer`0"),array('`&%s`2 has gifted you with `@%s`2 donator points. %s', $session['user']['name'], $amount, $note));
				}
				debuglog(sanitize($session['user']['name'])." sent $amount donator points to ".sanitize($target).($anon?" anonymously.":"."));
				debuglog(sanitize($session['user']['name'])." sent $amount donator points to ".sanitize($target).($anon?" anonymously.":"."),false,$targetid);
				addnav("Send To Someone Else","runmodule.php?module=pointstransfer");
				output("`7J. C. Petersen finishes recording the transfer.");
				if ($result) {
					output("`n`n\"`\$Okay, the points have been sent.`n`n");
					// SUCCESS
					$log=get_module_pref('log');
					if ($log!="") $log.="|";
					$log.=sprintf("%s received %s points from you.",sanitize($target),$amount);
					set_module_pref('log',$log);
				} else {
					output("`n`n\"`&Whoops, some error occurred, I was not able to credit the points! I credited them to you again.");
					$session['user']['donation']+=$amount;
					debuglog($session['user']['name']." was not able to send the points and received them back.");
				}
				output("Have a nice day.`7\"`n`n");
			}
		default:
			$allowed=pointstransfer_pointscheck();
			if($allowed<$mint){
				output("`7.J. C. Petersen smiles at your generosity, but leaves the forms where they are.");
				$sallowed=($allowed>0?"`@":"`\$").$allowed;
				if (is_module_active("titlechange")){
					output("`n`n\"`&I'm sorry, but counting any points used towards title changes, you have %s`& points available, which isn't enough for a transfer.",$sallowed);
				}else{
					output("`n`n\"`&I'm sorry, but you have %s`& points available, which isn't enough for a transfer.",$sallowed);
				}
				if ($mint){
					output("You need at least `@%s`& points available.`7\"",$mint);
				}else{
					output_notl("`7\"");
				}
			}else{
				output("`7J. C. Petersen smiles at your generosity, and pulls out a form.");
				if (is_module_active("titlechange")){
					$times=get_module_pref("timespurchased","titlechange");
					$locked=get_module_setting("initialpoints","titlechange")+($times-1)*get_module_setting("extrapoints","titlechange");
					output("`n`n\"`&Including any points used towards title changes (which are %s), you have `@%s`& points available.",$locked,$allowed);
					if ($times>0) {
						output("`n`nYou have purchased `^%s time(s)`& a new title, which means that now a total of `\$%s points of your points are LOCKED for the meaning of transfers. You cannot send them away. You can only spend them. This is the difference between your TOTAL points and the ones you CAN TRANSFER.`&`n`n",$times,$locked);
					}
				}else{
					output("`n`n\"`&You have `@%s`& points available.",$allowed);
				}
				if ($mint) output("You have the `@%s`& points needed for a minimum transfer.",$mint);
				output("How many points would you like to transfer, and to whom?`7\"");
				$amount=get_module_pref("amount");
				$target=get_module_pref("target");
				$anon=get_module_pref("anon");
				$note=stripslashes(get_module_pref("note"));
				$target=color_sanitize($target);
				rawoutput("<form action='runmodule.php?module=pointstransfer&op=confirm' method='POST'>");
				addnav("","runmodule.php?module=pointstransfer&op=confirm");
				output("`n`nPoints: ");
				rawoutput("<input name='amount' width='8' value=$amount>");
				output("`n`nRecipient: ");
				rawoutput("<input name='target' value=$target>");
				output("`n`nAnonymous Transfer: ");
				rawoutput("<select name='anon'>");
				$no=translate_inline("No");
				$yes=translate_inline("Yes");
				rawoutput("<option value='0'".($anon==0?" selected":"").">$no</option>");
				rawoutput("<option value='1'".($anon==1?" selected":"").">$yes</option>");
				rawoutput("</select>");
				output("`n`nOptional Note:");
				rawoutput("<input size='75' name='note' value=$note>");
				output_notl("`n`n");
				$click = translate_inline("Confirm");
				rawoutput("<input type='submit' class='button' value='$click'>");
				rawoutput("</form>");
				$log=get_module_pref('log');
				if ($log!="") {
					output("`n`n`4On another note, here is what I have in my books about your recent sendings of donation points:`n`n");
					$log=explode("|",$log);
					foreach ($log as $entry) {
						output("`2%s`n",$entry);
					}
				}
			}
			break;
	}
	page_footer();
}
?>
