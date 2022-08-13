<?php
if ($resets > 0) {
	output("`^You earn one reset point each time you reset in Kekkei Genkai.");
	output("`n`nYou have `@%s`^ unspent reset points.", $resets);
	output("How do you wish to spend them?`n`n");
	output("Be sure that your allocations add up to your total reset points.`0");
	$text = "<script type='text/javascript' language='Javascript'>
	<!--
	function pointsLeft() {
			var form = document.getElementById(\"kgForm\");
	";
	foreach($labels as $type=>$label) {
		$head=explode(",",$label);
		if (count($head)>1) continue; //got a headline here
		$text .= "var $type = parseInt(form.$type.value);";
	}
	foreach($labels as $type=>$label) {
		$head=explode(",",$label);
		if (count($head)>1) continue; //got a headline here
		$text .= "if (isNaN($type)) $type = 0;";
	}
	$text .= "var val = $resets ";
	foreach($labels as $type=>$label) {
		$head=explode(",",$label);
		if (count($head)>1) continue; //got a headline here
		$text .= "- $type";
	}
	$text .= ";
			var absval = Math.abs(val);
			var points = 'points';
			if (absval == 1) points = 'point';
			if (val >= 0)
				document.getElementById(\"amtLeft\").innerHTML = \"<span class='colLtWhite'>You have </span><span class='colLtYellow'>\"+absval+\"</span><span class='colLtWhite'> \"+points+\" left to spend.</span><br /><br />\";
			else
				document.getElementById(\"amtLeft\").innerHTML = \"<span class='colLtWhite'>You have spent </span><span class='colLtRed'>\"+absval+\"</span><span class='colLtWhite'> \"+points+\" too many!</span><br /><br />\";";

	$kg_count = 0;
	foreach ($kekkei as $category => $cat) {
		$kg_count ++;
		$name = "category_".$kg_count;
		$atext = "
				var $name =";
		foreach ($cat as $kg){
			$val = $kg['modulename'];
			$module = $kg['nav'];
			//Yes this does give divide by Zero, but the way Javascript works makes it okay, and works how we want it to.
			$atext .= " $val/$val +";	
			$max_stack = get_module_setting("maxstack",$kg['modulename']);
			$mname = $val."_stacks";
			$btext = "
					if ($val > $max_stack)
						document.getElementById(\"$mname\").innerHTML = \"<span class='colLtRed'>You have put more resets in $module than the max stack allows! The max number of stacks is $max_stack resets.</span><br /><br />\";
					else
						document.getElementById(\"$mname\").innerHTML = \"\";";
			$text .= $btext;
		}
		$atext = substr($atext, 0, -2).";";
		$name2 = $name."_points";
		$text .= $atext;
		$text .= "
				if ($name > 1)
					document.getElementById(\"$name2\").innerHTML = \"<span class='colLtRed'>You have selected more than one $category Kekkei Genkai. You may only select one!</span><br /><br />\";
				else
					document.getElementById(\"$name2\").innerHTML = \"\";";
	}
	$text .= "}
	// -->
	</script>\n";
	debug($text);
	rawoutput($text);
	$link = appendcount("runmodule.php?module=kg_reset&op=confirm");
	rawoutput("<form id='kgForm' action='$link' method='POST'>");
	addnav("",$link);
	rawoutput("<br><table cellpadding='0' cellspacing='0' border='0' width='200'>");
	foreach($labels as $type=>$label) {
		$head=explode(",",$label);
		if (count($head)>1) {
			rawoutput("<tr><td colspan='2' nowrap>");
			output("`b`4%s`0`b`n",translate_inline($head[0])); //got a headline here
			rawoutput("</td></tr>");
			continue;
		}
		rawoutput("<tr><td nowrap>");
		output($label);
		output_notl(":");
		rawoutput("</td><td>");
		rawoutput("<input id='$type' name='$type' size='4' maxlength='4' value='{$pkgs[$type]}' onKeyUp='pointsLeft();' onBlur='pointsLeft();' onFocus='pointsLeft();'>");
		rawoutput("</td></tr>");
	}
	rawoutput("<tr><td colspan='2'>&nbsp;");
	rawoutput("</td></tr><tr><td colspan='2' align='center'>");
	$click = translate_inline("Spend");
	rawoutput("<input id='dksub' type='submit' class='button' value='$click'>");
	rawoutput("</td></tr><tr><td colspan='2'>&nbsp;");
	rawoutput("</td></tr><tr><td colspan='2' align='center'>");
	rawoutput("<div id='amtLeft'></div>");

	$count = 0;
	foreach($labels as $type=>$label) {
		$head=explode(",",$label);
		if (count($head)>1) {
			$count++;
			$name = "category_".$count."_points";
			rawoutput("<div id='$name'></div>");
			continue; //got a headline here
		}
		$mname = $type."_stacks";
		rawoutput("<div id='$mname'></div>");
	}
	rawoutput("</td></tr>");
	rawoutput("</table>");
	rawoutput("</form>");
	$count = 0;
	foreach($labels as $type=>$label) {
		$head=explode(",",$label);
		if (count($head)>1) continue; //got a headline here
		if ($count > 0) break;
		rawoutput("<script language='JavaScript'>document.getElementById('$type').focus();</script>");
		$count++;
	}
}else{
	//Just incase.
	output("`^You don't have any resets to switch.`0");
}
?>