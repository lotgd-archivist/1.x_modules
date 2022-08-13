<?php

if (!defined("OVERRIDE_FORCED_NAV")) define("OVERRIDE_FORCED_NAV",true);

function gdpr_getmoduleinfo(){
	$info = array(
			"name"=>"Data Privacy Module and Downloader",
			"override_forced_nav"=>true,		
			"version"=>"1.0",
			"author"=>"`2Oliver Brendel",
			"category"=>"Data Privacy",
			"download"=>"",
			"settings"=>array(
				"Data Privacy Helper Module,title",
				"servername"=>"Server Name,text|https://shinobilegends.com",
				),
		     );
	return $info;
}

function gdpr_install(){
	require_once("lib/tabledescriptor.php");
	$acc_never_restore = array(
		'accountid'=> array('name'=>'accountid', 'type'=>'bigint(20) unsigned'),
		'date'=> array('name'=>'date', 'type'=>'date', 'default'=>'1970-00-00'),
		'key-PRIMARY' => array('name'=>'PRIMARY', 'type'=>'primary key',
			'unique'=>'1', 'columns'=>'accountid'));
	synctable(db_prefix("accounts_never_restore"), $acc_never_restore, true);

	//begin hooks
	module_addhook("village");
	module_addhook("footer-creationaddon");
	output("You will need SQL triggers for this module to use. I can't input them for you, there is no sync function with triggers in LOTGD. So, please execute this SQL code AND ADAPT IT FOR YOUR NEEDS. I don't know what modules you use and what personal info you store. This is adapted for my lotgd installation with some tables you might not have:`\$");
	rawoutput("
			<p>
			DELIMITER $$</br>
			CREATE DEFINER=`root`@`localhost` PROCEDURE `clean_helper_exists`(IN `del_acctid` BIGINT, OUT `result` INT)</br>
			READS SQL DATA</br>
			BEGIN</br>
			declare done int default false;</br>
			declare account_test int default 0;</br>
			declare pointer_accounts CURSOR for select acctid from accounts where acctid=del_acctid;</br>
			declare continue handler for not found set done = true;</br>
			</br>
			set result = false;</br>
			</br>
			open pointer_accounts;</br>
			</br>
			</br>
			read_loop: LOOP </br>
			fetch pointer_accounts into account_test;</br>
			if done then</br>
			leave read_loop;</br>
			end if;</br>
			if account_test = del_acctid then</br>
				set result = true;</br>
					leave read_loop;</br>
					end if;</br>
					end loop;</br>
					</br>
					close pointer_accounts;</br>
					end$$</br>
					DELIMITER ;</br>
					</br>
					DELIMITER $$</br>
					CREATE DEFINER=`root`@`localhost` PROCEDURE `clean_privacy_forgotten`()</br>
					MODIFIES SQL DATA</br>
					BEGIN</br>
					declare test_acctid bigint;</br>
					declare done INT default false;</br>
					declare test_account INT default 0;</br>
					declare pointer_forget cursor for select accountid from accounts_never_restore;</br>
					DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = true;</br>
					</br>
					</br>
					open pointer_forget;</br>
					</br>
					-- loop through all to-be-forgotten and delete if necessary</br>
					</br>
					read_loop: loop</br>
					fetch pointer_forget into test_acctid;</br>
					if done then</br>
						leave read_loop;</br>
							end if;</br>
							</br>
							-- check if found </br>
							CALL clean_helper_exists(test_acctid,@test_account);</br>
							select concat(\"E: \",test_acctid,@test_account);</br>
							-- @test_account = 1 if it was found - then remove + delete!</br>
							if @test_account = 1 then</br>
								select name from accounts where acctid=test_acctid;</br>
									select modulename from module_userprefs where userid=test_acctid;</br>
									select messageid from mail where msgfrom=test_acctid;</br>
									select author from commentary where author=test_acctid;</br>
									select messageid from mail where msgfrom=test_acctid;</br>
									select messageid from mailarchive where msgfrom=test_acctid;</br>
									select messageid from mailoutbox where msgfrom=test_acctid;</br>
									select messageid from mailoutbox_archive where msgfrom=test_acctid;</br>
									select target from debuglog where target=test_acctid;</br>
									select target from debuglog_archive where target=test_acctid;</br>
									select userid from inventory where userid=test_acctid;</br>
									end if;</br>
									end loop;</br>
									</br>
									close pointer_forget;</br>
									END$$</br>
									DELIMITER ;</br>
									</p>
									");
	output_notl("`0");
	return true;
}

function gdpr_uninstall(){
	return true;
}

function gdpr_dohook($hookname,$args){
	global $session;
	$op=httpget('op');

	switch ($hookname) {
		case "footer-creationaddon":
			addnav("GDPR");
			addnav("Get Personal Data","runmodule.php?module=gdpr");
			addnav("Never Restore My Char","runmodule.php?module=gdpr&op=never_restore");
			break;
		case "village":
			addnav("GDPR");
			addnav("Get Personal Data","runmodule.php?module=gdpr");
			addnav("Never Restore My Char","runmodule.php?module=gdpr&op=never_restore");
			break;
	}
	return $args;
}

function gdpr_run(){
	global $session;
	$op=httpget('op');
	page_header("Data Privacy");
	addnav("Navigation");
	villagenav();
	addnav("Actions");
	addnav("Grab personal data","runmodule.php?module=gdpr&op=grab");
	addnav("Never Restore My Char","runmodule.php?module=gdpr&op=never_restore");
	switch($op) {
		case "never_restore":
			output("`2Okay, so you want to invoke your right to `b`\$permanently and irrevocably`b`2 delete your character.`n`nI understand.`n`nPlease consider, however, that your character `\$can never be restored`2 once your execute this. Never means never. No backups anymore, nothing. So, handle with care.`n`nIf you're in a state of agitation, please take a moment to consider what you're doing.`n`nThank you. And tread carefully...`n`n");
			addnav("Confirm Permanency");
			addnav("Yes, I am sure","runmodule.php?module=gdpr&op=yes_never_restore");
			$sql = "SELECT * FROM ".db_prefix('accounts_never_restore')." WHERE accountid=".$session['user']['acctid'];
			$result = db_query($sql);
			if(db_num_rows($result)>=1) {
				$row = db_fetch_assoc($result);
				output("`!We have already registered your wish. You can now delete your character.`n");
			}
			break;	
		case "yes_never_restore":
			$sql = "INSERT IGNORE INTO ".db_prefix('accounts_never_restore')." VALUES (".$session['user']['acctid'].",'".date("Y-m-d")."');";
			$result = db_query($sql);
			if ($result) { 
				output("`\$SUCCESS!`n`n`2We have recognized your wish and this account won't ever be restored. You can now safely delete your account.`n`nWe hate to see you go, but wish you the very best!");
				rawoutput("<form action='prefs.php?op=suicide&userid={$session['user']['acctid']}' method='POST'>");
				$deltext = translate_inline('Delete Character');
				$conf = translate_inline("Are you sure you wish to PERMANENTLY delete your character?");
				rawoutput("<table class='noborder' width='100%'><tr><td width='100%'></td><td style='background-color:#FF00FF' align='right'>");
				rawoutput("<input type='submit' class='button' value='$deltext' onClick='return confirm(\"$conf\");'>");
				rawoutput("</td></tr></table>");
				rawoutput("</form><br>");
				addnav("","prefs.php?op=suicide&userid={$session['user']['acctid']}");
			} else {
				output("Hmmm, a most bogus error occurred. Please petition this... =(");
			}
			break;
		case "grab":
			/* GET USER ACC INFO */
			$spacer = "\r\n";//chr(10).chr(13);
			$out_text="";

			/* GET USER DATA */
			$sql = "SELECT * from ".db_prefix('accounts')." WHERE acctid=".$session['user']['acctid'];

			$result = db_query($sql);

			$row = db_fetch_assoc($result);

			$out_text.="Username: ".sanitize($row['name']).$spacer;
			$out_text.="Login: ".sanitize($row['login']).$spacer;
			$out_text.="Email: ".sanitize($row['emailaddress']).$spacer;

			/* GET PETITIONS */
			$sql = "SELECT * from ".db_prefix('petitions')." WHERE author=".$session['user']['acctid'];

			$result = db_query($sql);

			$out_text.=$spacer.$spacer; // spacer
			$header=sprintf_translate("Petitions from and to the user @ %s",get_module_setting('servername'));
			$out_text.="$header".$spacer.$spacer;

			while ($row = db_fetch_assoc($result)) {
				$out_text.="Date: ".sanitize($row['date']).$spacer;
				$out_text.="IP: ".sanitize($row['ip']).$spacer;
				$out_text.="ID: ".sanitize($row['id']).$spacer;
				$out_text.="Petitiontext: ".$spacer.color_sanitize(str_replace("\n","\r\n",$row['body'])).$spacer;
			}

			/* GET MAILS */
			$section="%";
			$mail=db_prefix('mailoutbox');

			$sql="SELECT a.*, b.name as name_from, c.name as name_to FROM $mail AS a LEFT JOIN ".db_prefix('accounts')." AS b ON a.msgfrom=b.acctid LEFT JOIN ".db_prefix('accounts')." AS c ON a.msgto=c.acctid WHERE msgfrom=".$session['user']['acctid']." OR msgto=".$session['user']['acctid']." 
				UNION SELECT a.*, b.name as name_from, c.name as name_to FROM $mail AS a LEFT JOIN ".db_prefix('accounts')." AS b ON a.msgfrom=b.acctid LEFT JOIN ".db_prefix('accounts')." AS c ON a.msgto=c.acctid WHERE msgfrom=".$session['user']['acctid']." OR msgto=".$session['user']['acctid']."  ORDER BY messageid ASC;";


			$result=db_query($sql);

			$rows=array();
			while ($row=db_fetch_assoc($result)) {
				array_unshift($rows,$row);
			}


			$deleted=translate_inline("Deleted User");		
			$out_text.=$spacer.$spacer; // spacer
			$header=sprintf_translate("Mails from and to the user @ %s",get_module_setting('servername'));
			$out_text.="$header".$spacer.$spacer;
			foreach ($rows as $row) {
				$from=sanitize($row['name_from']);
				if ($from=='') $from=$deleted;
				$to=sanitize($row['name_to']);
				if ($to=='') $to=$deleted;
				$body = unserialize($row['body']);
				if ($body!==false) {
					//ok,was serialized
				} else {
					//was just a string
					$body = $row['body'];
				}
				$out_text.="From: ".$from." To: ".$to." Sent: ".$row['sent']." Subject: ".$row['subject'].$spacer;
				$out_text.=$body.$spacer.$spacer;
			}


			/* GET CHATS */
			$section="%";
			$mail=db_prefix('commentary');

			$sql="SELECT a.*, b.name as name FROM $mail AS a LEFT JOIN ".db_prefix('accounts')." AS b ON a.author=b.acctid WHERE section like '$section' AND a.author=".$session['user']['acctid']." ORDER BY section, commentid DESC;";

			$result=db_query($sql);

			$rows=array();
			$sectioncounter=0;
			$sectioncheck="";
			while ($row=db_fetch_assoc($result)) {
				if ($row['section']!=$sectioncheck) $sectioncounter++;
				array_unshift($rows,$row);
			}


			$u=&$session['user'];
			$name=sanitize($u['name']);
			$deleted=translate_inline("Deleted User");		
			$sectioncheck="";
			foreach ($rows as $row) {
				if ($row['section']!=$sectioncheck) {
					$out_text.=$spacer.$spacer; // spacer
					$header=sprintf_translate("Protocol for section %s @ %s",$row['section'],get_module_setting('servername'));
					$out_text.="$header".$spacer.$spacer;
				}
				$from=sanitize($row['name']);
				if ($from=='') $from=$deleted;
				$out_text.=gdpr_convert($row);
			}

			/* GET DEBUG LOG */

			$sql="	SELECT * FROM ".db_prefix('debuglog'). " WHERE actor=".$session['user']['acctid']." 
				UNION 
				SELECT * FROM ".db_prefix('debuglog_archive')." WHERE actor=".$session['user']['acctid']." ORDER BY date DESC;";

			$result=db_query($sql);

			$rows=array();
			while ($row=db_fetch_assoc($result)) {
				array_unshift($rows,$row);
			}


			$u=&$session['user'];
			$name=sanitize($u['name']);
			$out_text.=$spacer.$spacer; // spacer
			$header=sprintf_translate("Debug info the user %s",$name);
			foreach ($rows as $row) {
				$out_text.="$header".$spacer.$spacer;
				$out_text.=$row['date']." -- ".$row['message']." (".$row['field'].")(".$row['value'].")".$spacer;
			}





			// send as text to browser
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=shinobilegends_data.txt');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . strlen($out_text));
			ob_clean();
			flush();
			echo $out_text;
			exit;
			break;	
		default:
			output("`c`2Data Privacy Information`0`c`n`n");
			output("`qThe following personal data is being stored on this website:`n");
			output("-> your email address`n");
			output("-> cookie(s) in your local browser to identify your settings while being logged out`n");
			output("-> any messages you send to other users`n");
			output("-> any public or private chats on the site (i.e. village, houses other locations)`n");
			output("-> any personal information you put into your bio or other publically available preferences field`n");
			output("`n`n");
			output("You can at any time request a copy of that data by clicking the link 'Get me my personal data' on the left hand side.`n You can download a file which contains these entries.");
			output("`n`n");
			output("You can request for all your personal data to be deleted. This means we will wipe all your character infos, achievements, messages and other entries off our record. Your character can `\$never be restored`q. We will remove any character copies and backups we have technically made. Upon restoration of complete server backups (which may yet include this data) will be cleaned up before data is extracted.");
			output("");
			break;
	}
	page_footer();


}

function gdpr_convert($row) {
	$row['name']=sanitize($row['name']);
	$row['comment']=full_sanitize($row['comment']);
	//$row['name']=appoencode($row['name']."`0");
	//$row['comment']=appoencode($row['comment']."`0");
	if (substr($row['comment'],0,2)=="::") {
		$row['comment']=$row['name']." ".substr($row['comment'],2);
	} elseif (substr($row['comment'],0,1)==":") {
		$row['comment']=$row['name']." ".substr($row['comment'],1);
	} elseif (substr($row['comment'],0,3)=="/me") {
		$row['comment']=$row['name']." ".substr($row['comment'],3);
	} elseif (substr($row['comment'],0,5)=="/game") {
		$row['comment']=substr($row['comment'],5);
	} else {
		$row['comment']=$row['name']." says, \"".$row['comment']."\"";
	}
	return $row['comment']."\n";

}
?>
