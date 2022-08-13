<?php
/*
 * Visa Login
 * Anybody with a visa (preference) can enter the site always
 * (even when full, not when login blocked by serversuspend etc)
 * You can use this together with a lodge module to buy access to your site
 * or/and can code a maxuser setting for the visa-owner too, using the hook
 * visalogin-visaused ...which gets called if the user can enter the page
 * (i.e. you can deduct logins there or count anew)
*/

function visalogin_getmoduleinfo(){
	$info = array(
		"name"=>"Visa Login (formerly Superuserlogin)",
		"version"=>"1.0",
		"author"=>"Catscradler, `2modified by Oliver Brendel",
		"category"=>"Administrative",
		"download"=>"http://dragonprime.net",
		"allowanonymous"=>true,
		"settings"=> array(
			"superuser"=>"All superuser do not need a visa to enter via the extra entrance,bool|1",
			),
		"prefs"=> array (
			"hasvisa"=>"Has this user a visa?,bool|0",
		),
	);
	return $info;
}

function visalogin_install(){
	module_addhook("header-home");
	module_addhook("check-login");
	return true;
}

function visalogin_uninstall(){
	return true;
}

function visalogin_dohook($hookname, $args){
	switch($hookname){

		case "header-home":
			addnav("Staff Entry");
			addnav("Entry for staff members","runmodule.php?module=visalogin");
			break;

		case "check-login":
			if (httppostisset("visalogin")){
				global $session;
				$sql="SELECT value FROM ".db_prefix('module_userprefs')." WHERE modulename='visalogin' AND setting='hasvisa' AND userid={$session['user']['acctid']};";
				$row=db_fetch_assoc(db_query($sql));
				$hasvisa=$row['value'];
				$sql="SELECT value FROM ".db_prefix('module_settings')." WHERE modulename='visalogin' AND setting='superuser';";
				$row=db_fetch_assoc(db_query($sql));
				$superuser=$row['value'];
				if (!$hasvisa && !($superuser && ($session['user']['superuser']>0 && $session['user']['superuser']!=SU_GIVE_GROTTO))){  //check for proper permissions, grotto-only user can't login
					$session['message'].=translate_inline("`4You do not have a visa. You must get one before you can sign on.`0`n"); //send naughty regular users back to their login page
					$session['user']=array();
					require_once("lib/redirect.php");
					redirect("index.php");
				}
			}
			modulehook("visalogin-visaused",array("user"=>$session['user']['acctid'],"superuser"=>$session['user']['superuser']));
			break;
	}
	return $args;
}

function visalogin_run(){
	page_header("Visa Owner Login");

	output("`c`b`\$Visa Owner Login`b`n");
	addnav("Back to index","index.php");
	//This is just a partial copy of login.php with two extra elements.
	rawoutput("<script language='JavaScript' src='lib/md5.js'></script>");
	rawoutput("<script language='JavaScript'>
	<!--
	function md5pass(){
		//encode passwords before submission to protect them even from network sniffing attacks.
		var passbox = document.getElementById('password');
		if (passbox.value.substring(0, 5) != '!md5!') {
			passbox.value = '!md5!' + hex_md5(passbox.value);
		}
	}
	//-->
	</script>");
	$uname = translate_inline("<u>U</u>sername");
	$pass = translate_inline("<u>P</u>assword");
	$butt = translate_inline("Log in");
	rawoutput("<form action='login.php' method='POST' onSubmit=\"md5pass();\">".templatereplace("login",array("username"=>$uname,"password"=>$pass,"button"=>$butt))."<input type=\"hidden\" name=\"visalogin\" value=\"visalogin\"/>");
	rawoutput("<input name='force' value='1' type='hidden'> </form>");	//needed if you have forgottenpasswordblocker module installed
	output_notl("`c");
	page_footer();
}

?>
