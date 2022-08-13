<?php
switch ($op) {
case "send":
	output("To whom would you like to send your gift to?`n");
	rawoutput("<form action='runmodule.php?module=".$shope."&op=send2&op2=".rawurlencode($gift)."&price=$price' method='POST'>");
	rawoutput("<p><input type='text' name='whom' size='37'></p>");
	rawoutput("<input type='submit' value='".translate_inline("Submit")."' name='B1' class='button'>   <input type='reset' value='".translate_inline("Reset")."' name='B2' class='button'>");
	rawoutput("</form>");
	addnav("","runmodule.php?module=".$shope."&op=send2&op2=".rawurlencode($gift)."&price=$price");
	addnav("Go Back","runmodule.php?module=".$shope);
	break;

case "send2":
	$sql = "SELECT login,name,level,acctid FROM ".db_prefix("accounts")." WHERE login LIKE '".$rawwhom."' and acctid <> '".$session['user']['acctid']."' ORDER BY level,login LIMIT 100";
	$result = db_query($sql);
	if (db_num_rows($result)!=1) {
		$sql = "SELECT login,name,level,acctid FROM ".db_prefix("accounts")." WHERE name LIKE '".$whom."' and acctid <> '".$session['user']['acctid']."' ORDER BY level,login LIMIT 500";
		$result = db_query($sql);
	}
	if (db_num_rows($result) < 1) output("No on matching that name found.");
	output("Choose who to send your gift to:`n");
	rawoutput("<table cellpadding='3' cellspacing='0' border='0'>");
	rawoutput("<tr class='trhead'><td>".translate_inline("Name")."</td><td>".translate_inline("Level")."</td></tr>");
	$i=0;
	while ($row = db_fetch_assoc($result)) {
		$i++;
		rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='runmodule.php?module=".$shope."&op=send3&op2=".rawurlencode($gift)."&price=$price&name=".HTMLEntities($row['acctid'])."'>");
		output_notl($row['name']);
		rawoutput("</a></td><td>");
		output_notl($row['level']);
		rawoutput("</td></tr>");
		addnav("","runmodule.php?module=".$shope."&op=send3&op2=".rawurlencode($gift)."&price=$price&name=".$row['acctid']);
	}
	rawoutput("</table>");
	output_notl("`n");
	addnav("Go Back","runmodule.php?module=".$shope);
	break;

case "send3":
	if (in_array(httpget('name'),explode('|',get_module_pref('ignored','friendlist')))) {
		output("`vSorry, but `\$this shinobi has ignored you`v. We cannot send a gift from you to %s`v.`n`n");
		addnav("Go Back","runmodule.php?module=".$shope);
		break;
	}
	$match=translate_inline("card");
	if (strchr(strtolower($gift),$match)){
		output("Write a message in the Card.`n");
	}else{
		output("Fill in the Note Card that goes with the gift.`n");
	}
	output("Leave blank for no note.");
	rawoutput("<form action='runmodule.php?module=".$shope."&op=send4&op2=".rawurlencode($gift)."&price=$price&name=$name' method='POST'>");
	rawoutput("<p><input type='text' name='mess' size='37'></p>");
	rawoutput("<p><input type='submit' value='".translate_inline("Submit")."' name='B1' class='button'><input type='reset' value='".translate_inline("Reset")."' name='B2' class='button'></p>");
	rawoutput("</form>");
	addnav("","runmodule.php?module=".$shope."&op=send4&op2=".rawurlencode($gift)."&price=$price&name=$name");
	addnav("Go Back","runmodule.php?module=".$shope);
	break;

case "send4":
	
	$match=translate_inline("card");
	$mess=httppost('mess');
	$session['user']['gold']-=$price;
	$mailmessage.="`v%s`v has sent you a gift.  When you open it you see it is a `6%s ";
	$mailmessage.="`v from %s`v's Ye Old Gifte Shope.";
	if ($mess <> ""){
		if (strchr(strtolower($gift),$match)){
			$replacement="`n`nInside the card it says \"%s`v.\"";
		}else{
			$replacement="`n`nThe attached note says \"%s`v.\"";
		}
	}
	$mailmessage.=$replacement;
	$content=sprintf_translate($mailmessage,$session['user']['name'], $gift,get_module_setting('gsowner'), $mess);
	require_once("lib/systemmail.php");
	systemmail($name,array("`2You have received a gift!`2"),$content);
	output("Your gift of a %s has been sent!",$gift);
	addnav("Continue","runmodule.php?module=".$shope);
	break;

default:
	output("You walk into the gift shop and see many unique items for sale.`n");
	output("%s is behind the counter, %s smiles politely at you.`n",get_module_setting('gsowner'),get_module_setting('gsheshe'));
	output("You see a sign on the wall that says \"Free Delivery and gift wrapping.\"`n");
	output("This shop specializes in gifts for your %s, things you can afford...`n`n",get_module_setting('special'));
	output_notl("`3");
	for ($i=1;$i<13;$i+=1){
		$currentgift = "gift".$i;
		$currentprice = "gift".$i."price";
		if ($session['user']['gold'] >= get_module_setting($currentprice)){
			rawoutput("<a href='runmodule.php?module=".$shope."&op=send&op2=".rawurlencode(get_module_setting($currentgift))."&price=". get_module_setting($currentprice)."'><span style='color: rgb(0, 204, 204);'>".get_module_setting($currentgift)." - ".get_module_setting($currentprice).translate_inline(" gold")."</span></a><br>");
		addnav("","runmodule.php?module=".$shope."&op=send&op2=".rawurlencode(get_module_setting($currentgift))."&price=".get_module_setting($currentprice));
		}
	}
	output_notl("`0");
	addnav("Back to Village","village.php");
	break;
}
page_footer();
?>
