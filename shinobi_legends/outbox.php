<?php
//this is mainly a copy of mail.php
//took a good look at cortalux friendlist to cope with the forced navs...
//define("OVERRIDE_FORCED_NAV",true);


function outbox_getmoduleinfo(){
	$info = array(
		"name"=>"Outbox",
		"override_forced_nav"=>true,
		"version"=>"1.03",
		"author"=>"`2Oliver Brendel`0 who used mainly mail.php",
		"category"=>"Mail",
		"download"=>"http://lotgd-downloads.com",
		"description"=>"Adds an outbox to the users YOM. Yet this does not change any mails. You can only view mails that are not already deleted by the recipient.",
		"settings"=>array(
			"Outbox - Preferences,title",
			"Note that this is no real outbox yet a -view from the recipient-,note",
			"if the recipient deleted the message... its gone. Also true if the sender deletes it,note",
			"allowdelete"=>"Allow users to delete sent mails (undo their sent in a way),bool|1",
			"this stores messages additionally in an extra table,note",
			"if active then the setting above will not delete the message from the recipients inbox,note",
			"realoutbox"=>"Use a real seperate outbox (uses space + cpu time),bool|0",
			"daystomove"=>"If used as a real outbox how many days after a mail get moved there from the current archive to the old archive?,int|3",
			"Note: This should be smaller than your mail expiration. If not it will be assumed so.,note",
			),
		);
	return $info;
}

function outbox_install(){
	module_addhook("mailfunctions");
	module_addhook("newday-runonce");
	$archive=array(
		'messageid'=>array('name'=>'messageid', 'type'=>'int(11) unsigned', 'extra'=>'auto_increment'), 
		'msgfrom'=>array('name'=>'msgfrom', 'type'=>'int(11) unsigned'),
		'msgto'=>array('name'=>'msgto', 'type'=>'int(11) unsigned'),
		'subject'=>array('name'=>'subject', 'type'=>'varchar(255)'),
		'body'=>array('name'=>'body', 'type'=>'text'),
		'sent'=>array('name'=>'sent', 'type'=>'datetime', 'default'=>DATETIME_DATEMIN),
		'seen'=>array('name'=>'seen', 'type'=>'tinyint(1)', 'default'=>'0'),
		'key-PRIMARY' => array('name'=>'PRIMARY', 'type'=>'primary key', 'unique'=>'1', 'columns'=>'messageid'),
		'key-one'=> array('name'=>'msgto', 'type'=>'key', 'unique'=>'0', 'columns'=>'msgto'),
		'key-two'=> array('name'=>'msgfrom', 'type'=>'key', 'unique'=>'0', 'columns'=>'msgfrom'),
		'key-three'=> array('name'=>'seen', 'type'=>'key', 'unique'=>'0', 'columns'=>'seen'),
	);
	require_once("lib/tabledescriptor.php");
	synctable(db_prefix("mailoutbox"), $archive, true);
	synctable(db_prefix("mailoutbox_archive"), $archive, true);
	return true;
}

function outbox_uninstall() {
  
	if(db_table_exists(db_prefix("mailoutbox"))){
		db_query("DROP TABLE ".db_prefix("mailoutbox"));
	}
   return true;
}


function outbox_dohook($hookname, $args) {
	global $session;
	switch ($hookname) {
	case "mailfunctions":
		$outbox = translate_inline("Outbox");
		array_push($args, array("runmodule.php?module=outbox", $outbox));
		addnav ("","runmodule.php?module=outbox");
		$atable=db_prefix('accounts');
		if (get_module_setting('realoutbox')) {
			$op=httpget('op');
			if ($op=="send") {
				$to = httppost('to');
				$sql = "SELECT acctid FROM " . $atable . " WHERE login='$to'";
				$result = db_query($sql);
				if (db_num_rows($result)>0){
					$row1 = db_fetch_assoc($result);
					$sql = "SELECT count(messageid) AS count FROM " . db_prefix("mail") . " WHERE msgto='".$row1['acctid']."' AND seen=0";
					$result = db_query($sql);
					$row = db_fetch_assoc($result);
					$sql2 = "SELECT count(messageid) AS count FROM " . db_prefix("mailoutbox") . " WHERE msgfrom='".$session['user']['acctid']."' AND seen=0";
					$result2 = db_query($sql2);
					$row2 = db_fetch_assoc($result2);
					if ($row['count']>=getsetting("inboxlimit",50)) {
						//do nothing in this module
						output("Sorry, this mail won't be saved in your outbox. You have to delete mails there.");
					} else {
						$subject =  str_replace("`n","",httppost('subject'));
						$body = str_replace("`n","\n",httppost('body'));
						$body = str_replace("\r\n","\n",$body);
						$body = str_replace("\r","\n",$body);
						$body = addslashes(mb_substr(stripslashes($body),0,(int)getsetting("mailsizelimit",1024)));
						$sql = "INSERT INTO " . db_prefix("mailoutbox") . " (msgfrom,msgto,subject,body,sent) VALUES ('".(int)$session['user']['acctid']."','".(int)$row1['acctid']."','$subject','$body','".date("Y-m-d H:i:s")."')";
						db_query($sql);
					}
				}
			}
		}
		if ($op=="read") {
			$id=httpget('id');
			$sql = "SELECT " . db_prefix("mail") . ".*,". $atable. ".name FROM " . db_prefix("mail") ." LEFT JOIN " . $atable . " ON ". $atable . ".acctid=" . db_prefix("mail"). ".msgfrom WHERE msgto=\"".$session['user']['acctid']."\" AND messageid=\"".$id."\"";
			$result = db_query($sql);
			$row=db_fetch_assoc($result);
			$sql = "SELECT " . db_prefix("mailoutbox") . ".*,". $atable. ".name FROM " . db_prefix("mailoutbox") ." LEFT JOIN " . $atable . " ON ". $atable . ".acctid=" . db_prefix("mailoutbox"). ".msgfrom WHERE msgto='".$session['user']['acctid']."' AND subject='".addslashes($row['subject'])."' AND body='".addslashes($row['body'])."';";
			$result = db_query($sql);
			if (db_num_rows($result)>0){
				$row = db_fetch_assoc($result);
				$sql = "UPDATE " . db_prefix("mailoutbox") . " SET seen=1 WHERE  msgto=\"".$session['user']['acctid']."\" AND messageid=\"".$row['messageid']."\"";
				if (!$row['seen']) db_query($sql);
			}
		}
		break;

	case "newday-runonce":
		$days=(int)get_module_setting('daystomove');
		$oldmail=(int)getsetting("oldmail",14);
		if ($days>$oldmail) $days=$oldmail; //else we won't delete anything
		db_query("LOCK TABLE mailoutbox WRITE ,mailoutbox_archive WRITE");
		$sql = "INSERT INTO " . db_prefix('mailoutbox_archive') . " SELECT * FROM ". db_prefix("mailoutbox") . " WHERE sent<'".date("Y-m-d H:i:s",strtotime("-".$days."days"))."'";
		db_query($sql); 
		$sql = "DELETE FROM " . db_prefix("mailoutbox") . " WHERE sent<'".date("Y-m-d H:i:s",strtotime("-".$days."days"))."'";
		db_query($sql); 
		$sql = "DELETE FROM " . db_prefix("mailoutbox_archive") . " WHERE sent<'".date("Y-m-d H:i:s",strtotime("-".$oldmail."days"))."'";
		db_query("UNLOCK TABLES");
		db_query($sql); //do this here because this won't be called often
		break;
	default:

		break;
	}
	return $args;
}

function outbox_run(){
	global $session;
	$op=httpget('op');
	$id = httpget('id');
	require_once("lib/http.php");
	popup_header("Ye Olde Poste Office");
	rawoutput("<table width='50%' border='0' cellpadding='0' cellspacing='2'>");
	rawoutput("<tr><td>");
	$t = translate_inline("Back to the Ye Olde Poste Office");
	$o = translate_inline("Back to the Outbox");
	rawoutput("<a href='mail.php'>$t</a></td><td>");
	rawoutput("<a href='runmodule.php?module=outbox'>$o</a>");
	addnav("","runmodule.php?module=outbox");
	rawoutput("</td></tr></table>");
	output_notl("`n`n");
	$realoutbox=(int)get_module_setting('realoutbox');
	$allowdelete=(int)get_module_setting('allowdelete');
	$table=($realoutbox?"mailoutbox":"mail"); //set the table
	$ptable= db_prefix($table);
	$archivetable= db_prefix("mailoutbox_archive");
	$atable= db_prefix("accounts");
	switch ($op) {
		case "delown":
			$sql = "DELETE FROM " . $ptable . " WHERE msgfrom='".$session['user']['acctid']."' AND messageid='$id'";
			db_query($sql);
			invalidatedatacache("mail-".httpget('rec'));
			header("Location: mail.php");
			exit();
			break;
		case "readown":
			$sql = "SELECT " . $ptable . ".*,". $atable. ".name,". $atable. ".acctid FROM " . $ptable ." LEFT JOIN " . $atable . " ON ". $atable . ".acctid=" . $ptable. ".msgto WHERE msgfrom=\"".$session['user']['acctid']."\" AND messageid=\"".$id."\"";
			$result = db_query($sql);
			$archive=0;
			if (db_num_rows($result)==0) {
				//check archive
				$sql = "SELECT " . $archivetable . ".*,". $atable. ".name,". $atable. ".acctid FROM " . $archivetable ." LEFT JOIN " . $atable . " ON ". $atable . ".acctid=" . $archivetable. ".msgto WHERE msgfrom=\"".$session['user']['acctid']."\" AND messageid=\"".$id."\"";
				$archive=1;
			}
			$result = db_query($sql);
			if (db_num_rows($result)>0){
				$row = db_fetch_assoc($result);
				$mail=$row;
				if (!$row['seen']) output("`b`#Not yet read by the recipient`b`n");
				else output_notl("`n");
				$tot=translate_inline("To: ");
				output_notl("`b`2$tot`b `^%s`n",$row['name']);
				output("`b`2Subject:`b `^%s`n",$row['subject']);
				output("`b`2Sent:`b `^%s`n",$row['sent']);
				
				
				//prev next del start
				$del = translate_inline("Delete");
				if ($allowdelete && !$realoutbox) output("`i`0Note: If you delete this message, the recipient won't see it anymore.`i");
				rawoutput("<table width='50%' border='0' cellpadding='0' cellspacing='5'><tr>");
				if ($allowdelete) rawoutput("<td><a href='runmodule.php?module=outbox&op=delown&archive=$archive&id={$row['messageid']}&rec={$row['acctid']}' class='motd'>$del</a></td>");
				rawoutput("</tr><tr>");
				addnav("","runmodule.php?module=outbox&op=delown&archive=$archive&id={$row['messageid']}&rec={$row['acctid']}");
				if (!$archive) $sql = "SELECT messageid FROM ".$ptable." WHERE msgfrom='{$session['user']['acctid']}' AND messageid < '$id' ORDER BY messageid DESC LIMIT 1";
					else $sql = "SELECT messageid FROM ".$archivetable." WHERE msgfrom='{$session['user']['acctid']}' AND messageid < '$id' ORDER BY messageid DESC LIMIT 1";
				$result = db_query($sql);
				if (db_num_rows($result)==0 && !$archive) {
					$sql = "SELECT messageid FROM ".$archivetable." WHERE msgfrom='{$session['user']['acctid']}' AND messageid < '$id' ORDER BY messageid DESC LIMIT 1";
					$result = db_query($sql);
				}
				if (db_num_rows($result)>0){
					$row = db_fetch_assoc($result);
					$pid = $row['messageid'];
				}else{
					$pid = 0;
				}
				if (!$archive) $sql = "SELECT messageid FROM ".$ptable." WHERE msgfrom='{$session['user']['acctid']}' AND messageid > '$id' ORDER BY messageid  LIMIT 1";
					else $sql = "SELECT messageid FROM ".$archivetable." WHERE msgfrom='{$session['user']['acctid']}' AND messageid > '$id' ORDER BY messageid  LIMIT 1";
				$result = db_query($sql);
				if (db_num_rows($result)==0 && $archive) {
					$sql = "SELECT messageid FROM ".$ptable." WHERE msgfrom='{$session['user']['acctid']}' AND messageid > '$id' ORDER BY messageid  LIMIT 1";
					$result = db_query($sql);
				}
				if (db_num_rows($result)>0){
					$row = db_fetch_assoc($result);
					$nid = $row['messageid'];
				}else{
					$nid = 0;
				}
				$prev = translate_inline("< Previous");
				$next = translate_inline("Next >");
				rawoutput("<td nowrap='true'>");
				if ($pid > 0) {
					rawoutput("<a href='runmodule.php?module=outbox&op=readown&id=$pid' class='motd'>".htmlentities($prev)."</a>");
					addnav("","runmodule.php?module=outbox&op=readown&id=$pid");
					}
				else rawoutput(htmlentities($prev));
				rawoutput("</td><td nowrap='true'>");
				if ($nid > 0) {
					rawoutput("<a href='runmodule.php?module=outbox&op=readown&id=$nid' class='motd'>".htmlentities($next)."</a>");
					addnav("","runmodule.php?module=outbox&op=readown&id=$nid");
					}
				else rawoutput(htmlentities($next));
				rawoutput("</td>");
				rawoutput("</tr></table>");			
				//end prev next del
				output_notl("<img src='images/uscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
				output_notl(str_replace("\n","`n",sanitize_mb($mail['body'])));
				output_notl("`n<img src='images/lscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);

			}else{
				output("Eek, no such message was found!");
			}
			break;
		case "process":
			$msg = httppost('msg');
			if (!is_array($msg) || count($msg)<1){
			$session['message'] = "`\$`bYou cannot delete zero messages!  What does this mean?  You pressed \"Delete Checked\" but there are no messages checked!  What sort of world is this that people press buttons that have no meaning?!?`b`0";
				header("Location: mail.php");
			}else{
				$sql = "DELETE FROM " . db_prefix("mailoutbox") . " WHERE msgfrom='".$session['user']['acctid']."' AND messageid IN ('".join("','",$msg)."')";
				db_query($sql);
				$sql = "DELETE FROM " . db_prefix("mailoutbox_archive") . " WHERE msgfrom='".$session['user']['acctid']."' AND messageid IN ('".join("','",$msg)."')";
				db_query($sql);
				header("Location: mail.php");
				exit();
			}
			break;
		default:
			output("`b`iMail Box`i`b");
			
			if (isset($session['message'])) {
				output($session['message']);
			}
			$session['message']="";
			
			$sortorder=httpget('sortorder');
			if ($sortorder=='') $sortorder='date';
			switch ($sortorder) {
				case "subject":
					$order="subject";
					break;
				case "name":
					$order="name";
					break;
				default: //date
					$order="sent";
			}
			$sorting_direction=(int)httpget('direction');
			if ($sorting_direction==0) $direction="DESC";
				else $direction="ASC";
			$newdirection=(int)!$sorting_direction;
			
			if (!$realoutbox) $sql = "SELECT subject,messageid," . $atable . ".name,msgto,msgfrom,seen,sent FROM " . $ptable . " LEFT JOIN " . $atable . " ON " . $atable . ".acctid=" . $ptable . ".msgto WHERE msgfrom=\"".$session['user']['acctid']."\" ORDER BY $order $direction";
				else $sql = "SELECT subject,messageid," . $atable . ".name,msgto,msgfrom,seen,sent FROM " . $ptable . " LEFT JOIN " . $atable . " ON " . $atable . ".acctid=" . $ptable . ".msgto WHERE msgfrom=\"".$session['user']['acctid']."\" 
				UNION
				SELECT subject,messageid," . $atable . ".name,msgto,msgfrom,seen,sent FROM " . $archivetable . " LEFT JOIN " . $atable . " ON " . $atable . ".acctid=" . $archivetable . ".msgto WHERE msgfrom=\"".$session['user']['acctid']."\" 
				ORDER BY $order $direction";

			$result = db_query($sql);
			if (db_num_rows($result)>0){
				$i=-1;
				$subject = translate_inline("Subject");
				$from = translate_inline("Sender");
				$date = translate_inline("SendDate");
				$arrow = ($sorting_direction?"arrow_down.png":"arrow_up.png");
				rawoutput("<form action='runmodule.php?module=outbox&op=process' method='POST'><table>");
				rawoutput("<tr class='trhead'><td></td>");
				rawoutput("<td>".($sortorder=='subject'?"<img src='images/shapes/$arrow' alt='$arrow'":"")."<a href='runmodule.php?module=outbox&sortorder=subject&direction=".($sortorder=='subject'?$newdirection:$sorting_direction)."'>$subject</a></td>");
				rawoutput("<td>".($sortorder=='name'?"<img src='images/shapes/$arrow' alt='$arrow'":"")."<a href='runmodule.php?module=outbox&sortorder=name&direction=".($sortorder=='name'?$newdirection:$sorting_direction)."'>$from</a></td>");
				rawoutput("<td>".($sortorder=='date'?"<img src='images/shapes/$arrow' alt='$arrow'":"")."<a href='runmodule.php?module=outbox&sortorder=date&direction=".($sortorder=='date'?$newdirection:$sorting_direction)."'>$date</a></td>");
				rawoutput("</tr>");				
				addnav("","runmodule.php?module=outbox&op=process");
					while ($row = db_fetch_assoc($result)) {
					$i++;
					output_notl("<tr>",true);
					output_notl("<td nowrap><input id='checkbox$i' type='checkbox' name='msg[]' value='{$row['messageid']}'><img src='images/".($row['seen']?"old":"new")."scroll.GIF' width='16' height='16' alt='".($row['seen']?"Old":"New")."'></td>",true);
					output_notl("<td><a href='runmodule.php?module=outbox&op=readown&id={$row['messageid']}'>",true);
					if (trim($row['subject'])=="")
						output("`i(No Subject)`i");
					else
						output_notl($row['subject']);
					output_notl("</a></td><td><a href='runmodule.php?module=outbox&op=readown&id={$row['messageid']}'>",true);
					addnav("","runmodule.php?module=outbox&op=readown&id={$row['messageid']}");
					output_notl($row['name']);
					output_notl("</a></td><td><a href='runmodule.php?module=outbox&op=readown&id={$row['messageid']}'>".date("M d, h:i a",strtotime($row['sent']))."</a></td>",true);
					addnav("","runmodule.php?module=outbox&op=readown&id={$row['messageid']}");
					output_notl("</tr>",true);
				}
				output_notl("</table>",true);
				$checkall = htmlentities(translate_inline("Check All"));
				$out="<input type='button' value=\"$checkall\" class='button' onClick='";
				for ($i=$i;$i>=0;$i--){
					$out.="document.getElementById(\"checkbox$i\").checked=true;";
				}
				$out.="'>";
				output_notl($out,true);
				$delchecked = htmlentities(translate_inline("Delete Checked"));
				if ($allowdelete) output_notl("<input type='submit' class='button' value=\"$delchecked\">",true);
				output_notl("</form>",true);

			}else{
				output("`iAww, you have sent no mail, how sad.`i");
			}
			if ($realoutbox) {
				output ("`n`n`iYou currently have %s messages in your %s outbox.",db_num_rows($result),translate_inline("real"));
				} else {
				output ("`n`n`iYou currently have %s messages in your %s outbox.",db_num_rows($result),translate_inline("virtual"));
				output("`nMessages who are deleted by the recipients can no longer be shown by the system.");
				}
			output("`nMessages are automatically deleted (read or unread) after %s days.",getsetting("oldmail",14));
			break;
		}
popup_footer();
}

?>
