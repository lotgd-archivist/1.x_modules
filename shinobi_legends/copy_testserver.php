<?php
function copy_testserver_getmoduleinfo(){
	$info = array(
			"name"=>"Copy to Testserver (Sync/Delete)",
			"version"=>"1.0",
			"author"=>"Oliver Brendel",
			"category"=>"Testserver",
			"download"=>"",
			"settings"=>array(
				"Copy to Testserver (DB Settings for Test),title",
				"testserver_url"=>"URL of the testserver,text|URL",
				"host"=>"Hostname,text|localhost",
				"db"=>"Database Testserver DESTINATION,text|Database",
				"original_db"=>"Database Original SOURCE, text|Database_Source",
				"user"=>"Username,text|User",
				"pass"=>"Password,text|Password",
				"givestuff"=>"Hand yout DKs etc to boost,bool|0",
				),
		     );
	return $info;
}

function copy_testserver_install(){
	module_addhook("footer-prefs");
	return true;
}

function copy_testserver_uninstall(){
	return true;
}

function copy_testserver_dohook($hookname,$args){
	global $session;
	$user = $session['user'];
	switch ($hookname) {
		case "footer-prefs":
			addnav("Testserver Copy");
			addnav("Copy Char to Testserver","runmodule.php?module=copy_testserver");
			break;
	}
	return $args;
}

function copy_testserver_run(){
	global $session;
	$user_acctid = $session['user']['acctid'];
	page_header("Character Copy / Sync to Testserver");
	$testserver_url = get_module_setting("testserver_url");
	$host = get_module_setting("host");
	$db = get_module_setting("db");
	$original_db = get_module_setting("original_db");
	$user = get_module_setting("user");
	$pass = get_module_setting("pass");

	$fullsync = 0;
	$givestuff = get_module_setting('givestuff');	

	$op = httpget('op');

	villagenav();
	addnav("Home");
	addnav("Main","runmodule.php?module=copy_testserver");
	addnav("Testserver");
	addnav("Shinobi Beta","https://".$testserver_url,false,true,"");
	addnav("Copy");

	switch (httpget('sub')) {
		case "full":
			//get all data incl. prefs
			$fullsync=1;
			break;
	}
	switch ($op) {

		case "do":
			// do the magic
			//$user="root";
			//$pw="LkRxCv7HvFfyppU";
			$accsync=1;
			$link=mysqli_connect($host,$user,$pass);
			if (!$link) {
				output('DB Connection not possible, please try again later');
				page_footer();
			}
			// insert account that is missing on DEV
			copy_testserver_insert($link,$original_db,$db,"accounts","acctid",$user_acctid);

			// remove emailaddresses as they are personal data
			$sql = "UPDATE ".$db.".".db_prefix('accounts')." SET emailaddress='NONE,DEV',replaceemail='NONE,dev'";
			mysqli_query($link,$sql);


			if ($fullsync)	{
				output("Deleting Preferences and Inventory from Testserver and copying current values over...`n");
				sync($link,$original_db,$db,"module_userprefs","userid","Module Userprefs",$user_acctid);
				sync($link,$original_db,$db,"inventory","userid","Inventory",$user_acctid);
			}

			if ($accsync) {
				output("Syncing specified fields to Testserver...`n");
				//	accsync($link,$original_db,$db,"accounts","emailaddress","email address","acctid",$user_acctid);
				accsync($link,$original_db,$db,"accounts","password","user password","acctid",$user_acctid);
				//	accsync($link,$original_db,$db,"accounts","replaceemail","internal email address","acctid",$user_acctid);
				accsync($link,$original_db,$db,"accounts","emailvalidation","internal email address 2","acctid",$user_acctid);
				accsync($link,$original_db,$db,"accounts","forgottenpassword","forgotten password field","acctid",$user_acctid);
				accsync($link,$original_db,$db,"accounts","age","character age","acctid",$user_acctid);
				accsync($link,$original_db,$db,"accounts","title","character title","acctid",$user_acctid);
				accsync($link,$original_db,$db,"accounts","ctitle","character custom title","acctid",$user_acctid);
				accsync($link,$original_db,$db,"accounts","name","character name","acctid",$user_acctid);
				accsync($link,$original_db,$db,"accounts","playername","internal character name","acctid",$user_acctid);
				accsync($link,$original_db,$db,"accounts","dragonkills","Orochimaru Kills","acctid",$user_acctid);
			}

			if ($givestuff) {
				output("Handing out free stuff...");
				givestuff($link,$db,"accounts","goldinbank",20000,"acctid",$user_acctid);
				givestuff($link,$db,"accounts","dragonkills",200,"acctid",$user_acctid);
				givestuff($link,$db,"accounts","donation",2000,"acctid",$user_acctid);
				givestuff($link,$db,"accounts","maxhitpoints",100,"acctid",$user_acctid);
				givestuff($link,$db,"accounts","charm",200,"acctid",$user_acctid);
				givestuff($link,$db,"accounts","gold",200,"acctid",$user_acctid);
			}
			break;
		default:
			output("Do you want to sync to the testserver (at %s)?`nIf you already have a char there, you will only set the login credentials, name, email and names.",$testserver_url);
			output("`n`n`\$If you go 'with all stats' you will wipe your inventory on the test server as well as any EMS,KG,Rasengan, ANBU ... so if you have played your test char a lot and want to keep those on him/her, don't do it.");
			addnav("Do it!","runmodule.php?module=copy_testserver&op=do");
			addnav("Do it with all stats!","runmodule.php?module=copy_testserver&op=do&sub=full");
	}
	page_footer();
}

function givestuff($link,$db,$table,$field,$value,$index,$user_acctid) {
	$sql = "UPDATE $db.$table SET $field=$field+$value WHERE $index=$user_acctid;";
	$result = mysqli_query($link,$sql);
	if (!$result) {
		output("Mysql Error ($sql): ".mysqli_error());
		page_footer();
	} else output("Setting additional $value for $field...`n");
}

function copy_testserver_insert($link,$db_from,$db_to,$table,$index,$user_acctid) {
	$sql = "SELECT * FROM ".$db_from.".".db_prefix($table)." WHERE $index=$user_acctid";
	$result = db_query($sql);

	$dataset = array();
	while ($row = db_fetch_assoc($result)) {
		foreach ($row as $key=>$val) {
			//		if ($key=="badguy" || $key=="bufflist" || $key=="dragonpoints") continue;
			$dataset[$key]=mysqli_real_escape_string($link,$val);
		}

		//got the set, try insert ignore
		$sql = "INSERT IGNORE INTO ".$db_to.".".db_prefix($table)." (".implode(",",array_keys($dataset)).") VALUES ('".implode("','",array_values($dataset))."');";
		$result2 = mysqli_query($link,$sql);
		debug($sql);
		if (!$result2) {
			output("Mysql Error ($sql):".mysqli_error($link));
			page_footer();
		}
	}
	return true;			
}

function accsync($link,$db_from,$db_to,$table,$field,$field_desc,$index,$user_acctid) {

	$sql = "SELECT $field,$index FROM $db_from.$table where $index=$user_acctid;";
	$result = db_query($sql);
	if (!$result) {
		output("Mysql Error ($sql):".mysqli_error($link));
		page_footer();
	}
	else {
		output("Field $field_desc sync select with ".mysqli_num_rows($result)." rows ...");
		debuglog("Field $field_desc sync select with ".mysqli_num_rows($result)." rows ...");
	}
	while ($row = db_fetch_assoc($result)) {	

		$sql = "UPDATE $db_to.$table SET $field='".mysqli_real_escape_string($link,$row[$field])."' WHERE $index='".$row[$index]."'";
		$result2 = mysqli_query($link,$sql);
		if (!$result2) {
			output("Mysql Error ($sql): ".mysqli_error($link));
			page_footer();
		}
	}
	output("`n");
}	

function sync($link,$from_db,$to_db,$table,$id_field,$label,$id_value) {

	// delete old user prefs and update from live

	$sql = "DELETE FROM $to_db.$table WHERE $id_field=$id_value";

	$result=mysqli_query($link,$sql);

	if (!$result) {
		output("Mysql Error ($sql): ".mysqli_error($link));
		page_footer();
	} else output("Old $label deleted for existing accounts`n");

	$result = copy_testserver_insert($link,$from_db,$to_db,$table,$id_field,$id_value); //copy entire datasets over

	/* done by above line
	   $sql = "INSERT IGNORE INTO $to_db.$table SELECT * FROM $from_db.$table WHERE $id_field=$id_value";

	   $result=mysqli_query($link,$sql);

	   output("     -db $label result: ".$result."`n");
	 */
	if (!$result) {
		output("Mysql Error: ".mysqli_error($link));
		page_footer();
	} else output("Inserted updated $label for existing accounts.\n");
}

