<?php

function mod_rp_main_run_private($args=false){
	
	global $session;
	require_once("lib/villagenav.php");
	$loc = $session['user']['location'];
	$op = httpget('op');
	page_header("Describtion in $loc");
	if ($op=="change") {
		output("`n`c`b`&Describtion in %s`b`c", $loc);
		output("`n`7(The describtion entered here will be shown in the village describtion. To remove it, simply save an empty textbox.)`n`n`0");
		$text = get_module_setting($loc);
		rawoutput("<form action='runmodule.php?module=mod_rp&op=save' method='POST'>");
		addnav("","runmodule.php?module=mod_rp&op=save");
		global $output;
		$output.="<textarea name='vildesc' class='input' rows='6' cols='70'>".$text."</textarea>";
		$save = translate_inline("Save Describtion");
		rawoutput("<br><br><input type='submit' class='button' value='$save'></form>");
		villagenav();
	} elseif ($op=="save") {
		$vildesc = httppost('vildesc');
		set_module_setting($loc,$vildesc);
		redirect("runmodule.php?module=mod_rp&op=change");
	}
	page_footer();
}
?>
