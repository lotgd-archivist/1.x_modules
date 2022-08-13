<?php

$total_resets = 0;
$check = true;

output("`^Are you sure you want to change your resets to this form?. ");
output("There is no going back if you do not like the changes, only going through this process again. ");
output("If you are sure, then press the confirm button. Otherwise start again, or return to the Mission HQ.`0");
$link = appendcount("runmodule.php?module=kg_reset&op=finish");
rawoutput("<form id='kgForm' action='$link' method='POST'>");
rawoutput("<br><table cellpadding='0' cellspacing='0' border='0' width='200'>");
foreach ($kekkei as $category => $cat) {
	$cat_total = 0; //sanity check if somebody injects more than 1 kg in a category
	rawoutput("<tr><td colspan='2' nowrap>");
	output("`b`4%s`0`b`n",translate_inline($category)); 
	rawoutput("</td></tr>");
	foreach ($cat as $kg){
		$points = (int)httppost($kg['modulename']);
		$total_resets += $points;
		rawoutput("<tr><td nowrap>");
		output($kg['nav']);
		rawoutput("</td><td>");
		$name = $kg['modulename'];
		rawoutput("<input id='$name' readonly='readonly' name='$name' size='4' maxlength='4' value='$points''>");
		rawoutput("</td></tr>");
		if ($points > get_module_setting('maxstack',$kg['modulename'])) $check = false;
		if ($points != 0) $cat_total += $points/$points; //increase counter
	}
	if ($cat_total > 1) $check = false;//selected or injected more than 1 from a category
}



if (!$check) {
	output("`\$Too many in one category, try again.`n`n`0");
} else {
	if ($total_resets != $resets) $check = false;
}

if ($check) {
	addnav("",$link);
	rawoutput("<tr><td colspan='2'>&nbsp;");
	rawoutput("</td></tr><tr><td colspan='2' align='center'>");
	$click = translate_inline("Confirm");
	rawoutput("<input id='dksub' type='submit' class='button' value='$click'>");
	rawoutput("</td></tr><tr><td colspan='2'>&nbsp;");
	rawoutput("</td></tr><tr><td colspan='2' align='center'>");
} else {
	if ($total_resets > $resets) output("`\$You've put too many resets in, try again.`n`n`0");
	elseif ($total_resets < $resets) output("`\$You haven't put enough resets in, try again.`n`n`0");
}
rawoutput("</table>");
rawoutput("</form>");

?>