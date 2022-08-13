<?php
function friendlist_faq() {
	$c = translate_inline("Return to the Contents");
	output_notl("`#<strong><center><a href='petition.php?op=faq'>$c</a></center></strong>`0",true);
	addnav("","petition.php?op=faq");
	rawoutput("<hr>");
	output("`c`&`bQuestions about Friend Lists`b`c`n");
	output("`^1. Where is it?`n");
	output("`@Just click the link at the top of the mail page.`n`n");
	output("`^2. What is it for?`n");
	output("`@You can send requests to add someone to both of your lists.`nIf the other user accepts, you will be able to see their status, location, and whether or not they are logged in.`n`n");
	output("`^3. Anything else?`n");
	output("`@You can ignore players that harass you, to prevent them from sending you mail.`nYou can ignore Admin, however their mails will still come through.`n`n");
	if (get_module_setting('allowStat')) {
		output("`^4. Sure that's it?`n");
		output("`@Oh, I forgot!;) You can turn on a preference to see how many of your friends are online.");
	}
	rawoutput("<hr>");
	output_notl("`#<strong><center><a href='petition.php?op=faq'>$c</a></center></strong>`0",true);
}

function friendlist_search() {
	global $session;
	$n = httppost("n");
	rawoutput("<form action='runmodule.php?module=friendlist&op=search' method='POST'>");
	addnav("","runmodule.php?module=friendlist&op=search");
	if ($n!="") {
		$string="%";
		for ($x=0;$x<strlen($n);$x++){
			$string .= substr($n,$x,1)."%";
		}
		$sql = "SELECT name,dragonkills,acctid FROM ".db_prefix("accounts")." WHERE name LIKE '%$string%' AND acctid<>".$session['user']['acctid']." AND locked=0 ORDER BY level,dragonkills";
		$result = db_query($sql);
		if (db_num_rows($result)>0) {
			$ignored = explode('|',get_module_pref('ignored'));
			$friends = explode('|',get_module_pref('friends'));
			$request = explode('|',get_module_pref('request'));
			$iveignored = explode('|',get_module_pref('iveignored'));
			output("`@These users were found:`n");
			rawoutput("<table style='width:60%;text-align:center;' cellpadding='3' cellspacing='0' border='0'>");
			rawoutput("<tr class='trhead'><td>".translate_inline("Name")."</td><td>".translate_inline("Operations")."</td></tr>");
			for ($i=0;$i<db_num_rows($result);$i++){
				$row = db_fetch_assoc($result);
				rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
				output_notl($row['name']);
				rawoutput("</td><td>");
				if (in_array($row['acctid'],$ignored)) {
					$info = translate_inline("This user has ignored you.");
					$info .= " [<a href='runmodule.php?module=friendlist&op=ignore&ac=".$row['acctid']."' class='colDkGreen'>".translate_inline("Ignore")."</a>]";
					addnav("","runmodule.php?module=friendlist&op=ignore&ac=".$row['acctid']);
				} elseif (in_array($row['acctid'],$friends)) {
					$info = translate_inline("This user is already in your list.");
				} elseif (in_array($row['acctid'],$request)) {
					$info = translate_inline("This user has already requested to you.");
				} else {
					if (in_array($row['acctid'],$iveignored)) {
						$info = "[<a href='runmodule.php?module=friendlist&op=unignore&ac=".$row['acctid']."' class='colLtRed'>".translate_inline("Unignore")."</a>]";
						addnav("","runmodule.php?module=friendlist&op=unignore&ac=".$row['acctid']);
					} else {
						$info = "[<a href='runmodule.php?module=friendlist&op=ignore&ac=".$row['acctid']."' class='colDkGreen'>".translate_inline("Ignore")."</a>]";
						addnav("","runmodule.php?module=friendlist&op=ignore&ac=".$row['acctid']);
						$info .= " - [<a href='runmodule.php?module=friendlist&op=request&ac=".$row['acctid']."' class='colDkGreen'>".translate_inline("Request")."</a>]";
						addnav("","runmodule.php?module=friendlist&op=request&ac=".$row['acctid']);
					}
				}
				rawoutput("$info</td></tr>");
			}
			rawoutput("</table>");
		} else {
			output("`c`@`bA user was not found with that name.`b`c");
		}
		output_notl("`n");
	}
	output("`^`b`cFriend Search...`c`b");
	output("`nWho do you want to search for?");
	output("Name of user: ");
	rawoutput("<input name='n' maxlength='50' value=\"".htmlentities(stripslashes(httppost('n')))."\">");
	$apply = translate_inline("Search");
	rawoutput("<input type='submit' class='button' value='$apply'></form>");
}

function friendlist_accept() {
	global $session;
	$ignored = explode('|',get_module_pref('ignored'));
	$friends = explode('|',get_module_pref('friends'));
	$request = explode('|',get_module_pref('request'));
	$ac = httpget('ac');
	if (in_array($ac,$ignored)) {
		$info = translate_inline("This user has ignored you.");
	} elseif (in_array($ac,$friends)) {
		$info = translate_inline("This user is already in your list.");
	} elseif (in_array($ac,$request)) {
		$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
		$result = db_query($sql);
		if (db_num_rows($result)>0) {
			$row=db_fetch_assoc($result);
			$friends[]=$ac;
			$info = translate_inline("%s`Q has been added to your list.");
			$info = str_replace('%s',$row['name'],$info);
			require_once("lib/systemmail.php");
			$t = translate_inline("`\$Friend Request Accepted");
			$mailmessage=array(translate_inline("%s`0`@ has accepted your Friend Request."),$session['user']['name']);
			systemmail($ac,$t,$mailmessage);
			$friends = implode("|", $friends);
			set_module_pref('friends',$friends);
			$friends = explode('|',get_module_pref('friends','friendlist',$ac));
			$friends[]=$session['user']['acctid'];
			$friends = implode("|", $friends);
			set_module_pref('friends',$friends,'friendlist',$ac);
			$request = array_diff($request, array($ac));
			$request = implode("|", $request);
			set_module_pref('request',$request);
		} else {
			$info = translate_inline("That user no longer exists...");
		}
	}
	output_notl($info);
}

function friendlist_deny() {
	global $session;
	$ignored = explode('|',get_module_pref('ignored'));
	$friends = explode('|',get_module_pref('friends'));
	$request = explode('|',get_module_pref('request'));
	$ac = httpget('ac');
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
	$result = db_query($sql);
	if (in_array($ac,$friends)) {
		$info = translate_inline("That user has been removed.");
		require_once("lib/systemmail.php");
		$t = translate_inline("`\$Friend List Removal");
		$mailmessage=array("%s`0`@ has deleted you from %s Friend List.",$session['user']['name'],($session['user']['sex']?translate_inline("her"):translate_inline("his")));
		$friends = array_diff($friends, array($ac));
		$friends = implode("|", $friends);
		set_module_pref('friends',$friends);
		$act = $session['user']['acctid'];
		$friends = explode('|',get_module_pref('friends','friendlist',$ac));
		$friends = array_diff($friends, array($act));
		$friends = implode("|", $friends);
		set_module_pref('friends',$friends,'friendlist',$ac);
	} else {
		$info = translate_inline("That user has been denied.");
		require_once("lib/systemmail.php");
		$t = translate_inline("`\$Friend Request Denied");
		$mailmessage=array(translate_inline("%s`0`@ has denied you your Friend Request."),$session['user']['name']);
		$request = array_diff($request, array($ac));
		$request = implode("|", $request);
		set_module_pref('request',$request);
	}
	if (db_num_rows($result)>0) {
		systemmail($ac,$t,$mailmessage);
		$row=db_fetch_assoc($result);
		$info = sprintf_translate("%s has been removed",$row['name']);
	}

	output_notl($info);
}

function friendlist_list() {
	global $session;
	$friends = explode('|',get_module_pref('friends'));
	$request = explode('|',get_module_pref('request'));
	$ignored = explode('|',get_module_pref('ignored'));
	$iveignored = explode('|',get_module_pref('iveignored'));
	output("`b`@Friends:`b`n");
	rawoutput("<table style='width:60%;text-align:center;' cellpadding='3' cellspacing='0' border='0'>");
	rawoutput("<tr class='trhead'><td>".translate_inline("Name")."</td><td>".translate_inline("Logged In")."</td><td>".translate_inline("Location")."</td><td>".translate_inline("Alive")."</td><td>".translate_inline("Operations")."</td></tr>");
	$last = date("Y-m-d H:i:s", strtotime("-".getsetting("LOGINTIMEOUT", 900)." sec"));
	$x=0;
	foreach ($friends as $ac) {
		if ($ac!='') {
			$sql = "SELECT name,login,laston,alive,loggedin,location FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
			$result = db_query($sql);
		}
		if (db_num_rows($result)>0&&$ac!='') {
			$x++;
			$row = db_fetch_assoc($result);
			rawoutput("<tr class='".($x%2?"trlight":"trdark")."'>");
			rawoutput("<td><a href='mail.php?op=write&to=".rawurlencode($row['login'])."'>".appoencode("`&".$row['name'],false)."</a></td>");
			addnav("","mail.php?op=write&to=".rawurlencode($row['login']));
			$loggedin=$row['loggedin'];
			if ($row['laston']<$last) {
				$loggedin=false;
			}
			$loggedin = translate_inline($loggedin?"`^Yes`0":"`%No`0");
			rawoutput("<td>".appoencode($loggedin,false)."</td>");
			rawoutput("<td><span class='colLtYellow'>".htmlentities($row['location'])."</span></td>");
			$alive = translate_inline($row['alive']?"`@Yes`0":"`\$No`0");
			rawoutput("<td>".appoencode($alive,false)."</td>");
			$ops = "[<a href='runmodule.php?module=friendlist&op=deny&ac=$ac' class='colDkGreen'>".translate_inline("Remove")."</a>] - [<a href='runmodule.php?module=friendlist&op=ignore&ac=$ac' class='colDkGreen'>".translate_inline("Ignore")."</a>]";
			addnav("","runmodule.php?module=friendlist&op=deny&ac=$ac");
			addnav("","runmodule.php?module=friendlist&op=ignore&ac=$ac");
			rawoutput("<td>$ops</td></tr>");
		} else {
			$friends = array_diff($friends, array($ac));
		}
	}
	if ($x==0) {
		rawoutput("<tr class='trlight'><td colspan='5'>");
		output("`^You have no friends");
		rawoutput("</td></tr>");
	}
	rawoutput("</table>");
	$friends = implode("|", $friends);
	set_module_pref('friends',$friends);
	output("`n`b`@Friend Requests:`b`n");
	rawoutput("<table style='width:60%;text-align:center;' cellpadding='3' cellspacing='0' border='0'>");
	rawoutput("<tr class='trhead'><td>".translate_inline("Name")."</td><td>".translate_inline("Operations")."</td></tr>");
	$x=0;
	foreach ($request as $ac) {
		if ($ac!='') {
			$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
			$result = db_query($sql);
		}
		if (db_num_rows($result)>0&&$ac!='') {
			$x++;
			$row = db_fetch_assoc($result);
			rawoutput("<tr class='".($x%2?"trlight":"trdark")."'>");
			rawoutput("<td>".appoencode($row['name'],false)."</td>");
			$ops = "[<a href='runmodule.php?module=friendlist&op=accept&ac=$ac' class='colDkGreen'>".translate_inline("Accept")."</a>] - [<a href='runmodule.php?module=friendlist&op=deny&ac=$ac' class='colDkGreen'>".translate_inline("Deny")."</a>] - [<a href='runmodule.php?module=friendlist&op=ignore&ac=$ac' class='colDkGreen'>".translate_inline("Ignore")."</a>]";
			addnav("","runmodule.php?module=friendlist&op=accept&ac=$ac");
			addnav("","runmodule.php?module=friendlist&op=deny&ac=$ac");
			addnav("","runmodule.php?module=friendlist&op=ignore&ac=$ac");
			rawoutput("<td>$ops</td></tr>");
		} else {
			$request = array_diff($request, array($ac));
		}
	}
	if ($x==0) {
		rawoutput("<tr class='trlight'><td colspan='2'>");
		output("`^You have no requests");
		rawoutput("</td></tr>");
	}
	rawoutput("</table>");
	$request = implode("|", $request);
	set_module_pref('request',$request);
	output("`n`b`@Ignored You:`b`n");
	rawoutput("<table style='width:60%;text-align:center;' cellpadding='3' cellspacing='0' border='0'>");
	rawoutput("<tr class='trhead'><td>".translate_inline("Name")."</td><td>".translate_inline("Operations")."</td></tr>");
	$x=0;
	foreach ($ignored as $ac) {
		if ($ac!='') {
			$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
			$result = db_query($sql);
		}
		if (db_num_rows($result)>0&&$ac!='') {
			$x++;
			$row = db_fetch_assoc($result);
			rawoutput("<tr class='".($x%2?"trlight":"trdark")."'>");
			rawoutput("<td>".appoencode($row['name'],false)."</td>");
			if (!in_array($ac,$iveignored)) {
				$ops = "[<a href='runmodule.php?module=friendlist&op=ignore&ac=$ac' class='colDkGreen'>".translate_inline("Ignore")."</a>]";
				addnav("","runmodule.php?module=friendlist&op=ignore&ac=$ac");
			} else {
				$ops = appoencode("`i[".translate_inline("Nothing")."]`i",false);
			}
			rawoutput("<td>$ops</td></tr>");
		} else {
			$ignored = array_diff($ignored, array($ac));
		}
	}
	if ($x==0) {
		rawoutput("<tr class='trlight'><td colspan='2'>");
		output("`^No one has ignored you");
		rawoutput("</td></tr>");
	}
	rawoutput("</table>");
	$ignored = implode("|", $ignored);
	set_module_pref('ignored',$ignored);
	output("`n`b`@You've Ignored:`b`n");
	rawoutput("<table style='width:60%;text-align:center;' cellpadding='3' cellspacing='0' border='0'>");
	rawoutput("<tr class='trhead'><td>".translate_inline("Name")."</td><td>".translate_inline("Operations")."</td></tr>");
	$x=0;
	foreach ($iveignored as $ac) {
		if ($ac!='') {
			$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
			$result = db_query($sql);
		}
		if (db_num_rows($result)>0&&$ac!='') {
			$x++;
			$row = db_fetch_assoc($result);
			rawoutput("<tr class='".($x%2?"trlight":"trdark")."'>");
			rawoutput("<td>".appoencode($row['name'],false)."</td>");
			$ops = "[<a href='runmodule.php?module=friendlist&op=unignore&ac=$ac' class='colLtRed'>".translate_inline("Unignore")."</a>]";
			addnav("","runmodule.php?module=friendlist&op=unignore&ac=$ac");
			rawoutput("<td>$ops</td></tr>");
		} else {
			$iveignored = array_diff($iveignored, array($ac));
		}
	}
	if ($x==0) {
		rawoutput("<tr class='trlight'><td colspan='2'>");
		output("`^You've haven't ignored anyone");
		rawoutput("</td></tr>");
	}
	rawoutput("</table>");
	$iveignored = implode("|", $iveignored);
	set_module_pref('iveignored',$iveignored);
}

function friendlist_ignore() {
	global $session;
	$iveignored = explode('|',get_module_pref('iveignored'));
	$friends = explode('|',get_module_pref('friends'));
	$request = explode('|',get_module_pref('request'));
	$ac = httpget('ac');
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
	$result = db_query($sql);
	if (db_num_rows($result)>0&&in_array($ac,$friends)) {
		$row=db_fetch_assoc($result);
		require_once("lib/systemmail.php");
		$t = translate_inline("`\$Friend List Ignore");
		$mailmessage=array(translate_inline("%s`0`@ has added you to %s ignore list."),$session['user']['name'],($session['user']['sex']?translate_inline("her"):translate_inline("his")));
		systemmail($ac,$t,$mailmessage);
		$friends = array_diff($friends, array($ac));
	}
	$friends = implode("|", $friends);
	set_module_pref('friends',$friends);
	$ignored = explode('|',get_module_pref('ignored','friendlist',$ac));
	$ignored[]=$session['user']['acctid'];
	$ignored = implode("|", $ignored);
	set_module_pref('ignored',$ignored,'friendlist',$ac);
	$act = $session['user']['acctid'];
	$friends = explode('|',get_module_pref('friends','friendlist',$ac));
	$friends = array_diff($friends, array($act));
	$friends = implode("|", $friends);
	set_module_pref('friends',$friends,'friendlist',$ac);
	if (in_array($ac,$request)) {
		$request = array_diff($request, array($ac));
		$request = implode("|", $request);
		set_module_pref('request',$request);
	}
	$iveignored[]=$ac;
	$iveignored = implode("|", $iveignored);
	set_module_pref('iveignored',$iveignored);
	rawoutput(translate_inline("You have ignored that user, they can no longer YoM you"));
}

function friendlist_unignore() {
	global $session;
	$ac = httpget('ac');
	$ignored = explode('|',get_module_pref('ignored','friendlist',$ac));
	$iveignored = explode('|',get_module_pref('iveignored'));
	if (in_array($ac,$iveignored)) {
		$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
		$result = db_query($sql);
		if (db_num_rows($result)>0) {
			$row=db_fetch_assoc($result);
			$friends[]=$ac;
			$info = translate_inline("%s`Q has been removed from your list.");
			$info = str_replace('%s',$row['name'],$info);
			require_once("lib/systemmail.php");
			$t = translate_inline("`\$Ignore List Removal");
			$mailmessage=array(translate_inline("%s`0`@ has removed you from %s ignore list."),$session['user']['name'],($session['user']['sex']?translate_inline("her"):translate_inline("his")));
			systemmail($ac,$t,$mailmessage);
		} else {
			$info = translate_inline("That user no longer exists...");
		}
	}
	$ignored = array_diff($ignored, array($session['user']['acctid']));
	$ignored = implode("|", $ignored);
	set_module_pref('ignored',$ignored,'friendlist',$ac);
	if (in_array($ac,$iveignored)) {
		$iveignored = array_diff($iveignored, array($ac));
		$iveignored = implode("|", $iveignored);
		set_module_pref('iveignored',$iveignored);
	}
	output_notl($info);
}

function friendlist_request() {
	global $session;
	$ac = httpget('ac');
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$ac AND locked=0";
	$result = db_query($sql);
	if (db_num_rows($result)>0) {
		$row=db_fetch_assoc($result);
		$friends[]=$ac;
		$info = translate_inline("You have successfully sent your request to %s`Q.");
		$info = str_replace('%s',$row['name'],$info);
		require_once("lib/systemmail.php");
		$t = translate_inline("`\$Friend Request Sent");
		$mailmessage=array(translate_inline("%s`0`@ has sent you a Friend Request.`nIf this user has been spamming you with this, ignore them from your search function."),$session['user']['name']);
		systemmail($ac,$t,$mailmessage);
	} else {
		$info = translate_inline("That user no longer exists...");
	}
	$request = explode('|',get_module_pref('request','friendlist',$ac));
	$request[]=$session['user']['acctid'];
	$request = implode("|", $request);
	set_module_pref('request',$request,'friendlist',$ac);
	output_notl($info);
}
?>