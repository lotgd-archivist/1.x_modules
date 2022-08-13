<?php
function callneji_getmoduleinfo(){
	$info = array(
		"name"=>"Admin Call",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Administrative",
		"settings"=>array(
			"Settings for Call Neji,title",
			"lastcaller"=>"Who called last,viewonly",
			"timestamp"=>"Last time called,viewonly",
			"adminmail"=>"Email to mail,text|oliverbrendel@o2online.de",
			),
		"download"=>"",
	);
	return $info;
}

function callneji_install(){
	module_addhook("superuser");
	return true;
}

function callneji_uninstall(){
	return true;
}

function callneji_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
	case "superuser":
		if ($session['user']['superuser'] > 0) {
			addnav("Emergencies");
			addnav("`\$Call Neji","runmodule.php?module=callneji");
		}

	break;
	}
	return $args;
}

function callneji_run(){
	global $session;
	$op=httpget('op');
	$subop=httpget('subop');
	require_once("lib/superusernav.php");
	superusernav();
	page_header("Call Neji");
	addnav("Actions");
	addnav("Refresh","runmodule.php?module=callneji&order=".httpget('order'));
	switch ($op) {
		default:
			$lastcall=get_module_setting('lastcaller');
			$timestamp=get_module_setting('timestamp');
			if ($lastcall!='') output("`4The last Call to Neji was made by %s`4 on timestamp %s.`n`n",$lastcall,$timestamp);
			switch ($subop) {
				case "callneji":
					$body=httppost('body');
					$subject=httppost('subject');

					$success=mail(get_module_setting('adminmail'),$subject,$body);
					set_module_setting('lastcaller',$session['user']['login']);
					set_module_setting('timestamp',date("Y-m-d H:i:s"));
					if ($success) {
						output("`\$Neji has been notified.");
					} else {
						output("`\$Attention`t, an error occurred, the mail was NOT sent... please see to remove any bad code from subject or mailbody...`n`n");
					}

					page_footer();
					break;
			}
			output("`xThis is an emergency call to me, Neji. I will get an SMS to my cell phone including the subject you write, so use 'Advertising' or similar as first word to let me know what's it about and if I need to get up from my sleep or not... ^^ don't abuse this.`n`n`4If you see somebody called me minutes ago - don't mail as it was possibly the same thing. Mail me ingame, I'll get online.`n`n`\$I want you to call me when there are for example people spamming advertisements about other lotgd games in their mail or sexual harassment that has to stop and banning won't work. I can block them out by email or other means.`n`n");
			$subject=translate_inline("Subject:");
			$body=translate_inline("Body:");
			$submit=translate_inline("Notify Neji Now!!");
			rawoutput("<form action='runmodule.php?module=callneji&subop=callneji' method='POST'>");
			addnav("","runmodule.php?module=callneji&subop=callneji");
			output_notl("`n".$subject);
			rawoutput("<input type='input' class='input' name='subject'>");
			output_notl("`n".$body);
			rawoutput("<br><textarea cols='50' rows='10' name='body'></textarea><br>");
			rawoutput("<br><input type='submit' class='button' value=$submit></form>");
			require_once("lib/commentary.php");
			addcommentary();

			commentdisplay("`n`n`@Enter here the notes for your calls with hints and persons.`n","blocker_emails","",40,"says");
	}
	page_footer();
}


?>
