<?php


function staffyomblock_getmoduleinfo(){
	$info = array(
		"name"=>"Staff YOM Blcok",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Mail",
		"download"=>"http://dragonprime.net",
		"override_forced_nav"=>true,
		"settings"=>array(
			"Staff YOM Block - Settings,title",
			"staff"=>"`^Staff Acctids of persons who will not get ANY yom from a non-admin user seperated by comma,text|7",		),

	);
	return $info;
}


function staffyomblock_install(){
	module_addhook("mailfunctions");
	return true;
}

function staffyomblock_uninstall(){
	return true;
}

function staffyomblock_dohook($hookname,$args){
	global $session,$battle;
	switch($hookname){
		case "mailfunctions":
			if (httpget('op')!='send') break;
			$staff=get_module_setting("staff","staffyomblock");
			$staff=explode(",",$staff);
			$target=addslashes(httppost('to'));
			$sql="SELECT acctid FROM ".db_prefix('accounts')." where login = '".$target."';";
			$result=db_query($sql);
			$row=db_fetch_assoc($result);
			$target=$row['acctid'];
			if (!in_array($target,$staff)) break;debug($target);
			if ($target==0) break;
			//found one and test
			$staff=staffyomblock_test_staff($session['user']['superuser']);
			if (!($staff)) {
				output_notl("`c`^[`%");
				$t = translate_inline("Back to your Mail");
				rawoutput("<a href='mail.php'>$t</a>");
				output_notl("`^]`c`Q`n");
				$info = translate_inline("You cannot mail to {name}`Q directly, please use the petition if you have a game problem.");
				$sql="SELECT name FROM ".db_prefix('accounts')." WHERE acctid=".$target;
				$result=db_query($sql);
				$row=db_fetch_assoc($result);
				$info = str_replace('{name}',$row['name'],$info);
				output_notl($info);
				popup_footer();
				die();
			}
		break;
	}
	return $args;
}

function staffyomblock_test_staff($su) {
	if ($su > 0 && ($su & SU_NEVER_EXPIRE)!=SU_NEVER_EXPIRE && ($su & SU_GIVE_GROTTO)!= SU_GIVE_GROTTO) return true;
	return false;

}

function staffyomblock_run(){
	global $session;
	$op = httpget('op');
}
?>
