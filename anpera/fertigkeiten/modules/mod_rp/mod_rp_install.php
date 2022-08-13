<?php

function mod_rp_install_private(){
	$password=$_POST['pw'];
	if (!is_module_active('mod_rp')){
		output("`4Installing NPC - Blank Character for the Moderate-RP-Module.`n");
		if ($password){
		$sql = "INSERT INTO ".db_prefix("accounts")." (login,name,sex,specialty,level,defense,attack,alive,laston,hitpoints,maxhitpoints,password,emailvalidation,title,weapon,armor,race,loggedin,superuser) VALUES ('blank_char','','0','','0','1000','1000','0','".date("Y-m-d H:i:s")."','1000','1000','".md5(md5("$password"))."','','','','','TheVoice','0','2097408')";
		db_query($sql) or die(db_error(LINK));
			if (db_affected_rows(LINK)>0){
				output("`2Installed Blank Character!`n");
			}else{
				output("`4Blank Character install failed!`n");
			}
			$sql = "SELECT acctid FROM ".db_prefix("accounts")." where login = 'blank_char'";
			$result = mysql_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
			if ($row['acctid'] > 0){
				set_module_setting("id",$row['acctid']);
				output("`2Set Accout ID for Blank Character to ".$row['acctid'].".`n");
			}else{
				output("`4Failed to Set Account ID for Blank Character!`n");
			}
		}else{
			$sqlz = "SELECT acctid FROM ".db_prefix("accounts")." where login = 'blank_char'";
			$resultz = mysql_query($sqlz) or die(db_error(LINK));
			$rowz = db_fetch_assoc($resultz);
			if ($rowz['acctid'] > 0){
			}else{
				output("Blank Character's Login will be blank_char.`n");
				output("What would you like the password for Blank Character's account to be?`n");
				output("`\$(Please enter the password before activating the module!)`n");
				$linkcode="<form action='modules.php?op=install&module=mod_rp' method='POST'>";
				output("%s",$linkcode,true);
				$linkcode="<p><input type=\"text\" name=\"pw\" size=\"37\"></p>";
				output("%s",$linkcode,true);
				$linkcode="<p><input type=\"submit\" value=\"Submit\" name=\"B1\"><input type=\"reset\" value=\"Reset\" name=\"B2\"></p>";
				output("%s",$linkcode,true);
				$linkcode="</form>";
				output("%s",$linkcode,true);
				addnav("","modules.php?op=install&module=mod_rp");
			}
		}
	}else{
		debug("Updating Moderate Roleplay Module.");
	}
	module_addhook("insertcomment");
	module_addhook("village-desc");
	module_addhook("everyhit");
	return true;
}	
?>
