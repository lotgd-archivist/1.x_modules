<?php
//you can use this function if you want to search for a flirtpartner
function loveshack_fform($w,$whereto='runmodule.php?module=loveshack&op=loveshack&op2=flirt') {
	global $session;
	$whom = httppost("whom");
	rawoutput("<form action='$whereto&flirtitem=$w&stage=0' method='POST'>");
	addnav("","$whereto&flirtitem=$w&stage=0");
	if ($whom!="") {
		$string="%";
		for ($x=0;$x<strlen($whom);$x++){
			$string .= substr($whom,$x,1)."%";
		}
		if (get_module_setting('sg','loveshack')==1) {
			$sql = "SELECT login,sex,name,charm,acctid FROM ".db_prefix("accounts")." WHERE login LIKE '%$whom%' AND acctid<>".$session['user']['acctid']." ORDER BY level,login";
		} else {
			$sql = "SELECT login,sex,name,charm,acctid FROM ".db_prefix("accounts")." WHERE name LIKE '%$string%' AND acctid<>".$session['user']['acctid']." AND sex<>".$session['user']['sex']." ORDER BY level,login";
		}
		$result = db_query($sql);
		$charmlevel=get_module_setting('charmleveldifference','loveshack');
		$charmlevelup=get_module_setting('charmleveldifferenceup','loveshack');
		if (db_num_rows($result)!=0) {
			output("`@These users were found `^(click on a name`@):`n");
			rawoutput("<table cellpadding='3' cellspacing='0' border='0'>");
			rawoutput("<tr class='trhead'><td>".translate_inline("Name")."</td></tr>");
			for ($i=0;$i<db_num_rows($result);$i++){
				$row = db_fetch_assoc($result);
				if ($row['charm']>($session['user']['charm']+$row['charm']*$charmlevel/100) && get_module_setting('charmlevelactivate','loveshack')) {
				rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='$whereto&flirtitem=one&name=".urlencode($row['name'])."&stage=1&target=".$row['acctid']."'>");
				addnav("","$whereto&flirtitem=one&name=".urlencode($row['name'])."&stage=1&target=".$row['acctid']);
				} else if ($session['user']['charm']>($row['charm']+($session['user']['charm']*$charmlevelup/100)) && get_module_setting('charmlevelactivateup','loveshack')) {
				rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='$whereto&flirtitem=two&name=".urlencode($row['name'])."&stage=1&target=".$row['acctid']."'>");
				addnav("","$whereto&flirtitem=two&name=".urlencode($row['name'])."&stage=1&target=".$row['acctid']);				
				} else {
				rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='$whereto&flirtitem=$w&name=".urlencode($row['name'])."&stage=1&target=".$row['acctid']."'>");
				addnav("","$whereto&flirtitem=$w&name=".urlencode($row['name'])."&stage=1&target=".$row['acctid']);
				}
				output_notl($row['name']);
				rawoutput("</td></tr>");
			}
			rawoutput("</table>");
		} else {
			output("`c`@`bA user was not found with that name.`b`c");
		}
		output_notl("`n");
	}
	output("`^`b`cFlirting..`c`b");
	output("`nWho do you want to do that with?");
	if (get_module_setting('sg','loveshack')==1) {
		output("`nSame gender flirting is allowed.");
	} else {
		output("`nSame gender flirting is not allowed.");
	}
	output("`nName of user: ");
	rawoutput("<input name='whom' maxlength='50' value=\"".htmlentities(stripslashes($whom))."\">");
	$apply = translate_inline("Flirt");
	rawoutput("<input type='submit' class='button' value='$apply'></form>");
}
?>
