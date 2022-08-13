<?php

if (!defined("OVERRIDE_FORCED_NAV")) define("OVERRIDE_FORCED_NAV",true);


function mailarchive_getmoduleinfo(){
	$info = array(
		"name"=>"Mailarchive",
		"override_forced_nav"=>true,
		"version"=>"1.01",
		"author"=>"`2Oliver Brendel`0",
		"category"=>"Mail",
		"download"=>"",
		"description"=>"Adds an mailarchive to the users YOM. Yet this does not change any mails. You can only view mails that are not already deleted by the recipient.",
		"settings"=>array(
			"Mailarchive - Preferences,title",
			"archivesize"=>"Allow users to archive received mails until what count,int|200",
			"This stores messages in an extra table safe from expiration,note",
			"store"=>"Only used when deleted,viewonly",
			),
		"prefs"=>array(
			"Mailarchive,title",
			"user_category0"=>"Name of Category 1,text|Category 1",
			"user_category1"=>"Name of Category 2,text|Category 2",
			"user_category2"=>"Name of Category 3,text|Category 3",
			"user_category3"=>"Name of Category 4,text|Category 4",
			"user_category4"=>"Name of Category 5,text|Category 5",
			"user_category5"=>"Name of Category 6,text|Category 6",
			"user_category6"=>"Name of Category 7,text|Category 7",
			),
		);
	return $info;
}

function mailarchive_install(){
	module_addhook("mailfunctions");
	module_addhook("mailform");
	module_addhook("delete_character");
	module_addhook("header-mail");
	$archive=array(
		'messageid'=>array('name'=>'messageid', 'type'=>'int(11) unsigned'), //comes from mail table
		'msgfrom'=>array('name'=>'msgfrom', 'type'=>'int(11) unsigned'),
		'msgto'=>array('name'=>'msgto', 'type'=>'int(11) unsigned'),
		'subject'=>array('name'=>'subject', 'type'=>'text'),
		'body'=>array('name'=>'body', 'type'=>'text'),
		'sent'=>array('name'=>'sent', 'type'=>'datetime','default'=>DATETIME_DATEMIN),
		'category'=>array('name'=>'category', 'type'=>'tinyint', 'default'=>0),
		'seen'=>array('name'=>'seen', 'type'=>'tinyint','default'=>0),
		'key-PRIMARY' => array('name'=>'PRIMARY', 'type'=>'primary key', 'unique'=>'1', 'columns'=>'messageid'),
		'key-one'=> array('name'=>'msgto', 'type'=>'key', 'unique'=>'0', 'columns'=>'msgto'),
		'key-three'=> array('name'=>'msgfrom', 'type'=>'key', 'unique'=>'0', 'columns'=>'msgfrom'),
	);
	require_once("lib/tabledescriptor.php");
	synctable(db_prefix("mailarchive"), $archive, true);
	return true;
}

function mailarchive_uninstall() {
	if(db_table_exists(db_prefix("mailarchive"))){
		db_query("DROP TABLE ".db_prefix("mailarchive"));
	}
	return true;
}


function mailarchive_dohook($hookname, $args){
	global $session;
	//if ($session['user']['acctid']!=7) return $args;
	switch ($hookname)	{
		case "delete_character":
			$oldacctid = $args['acctid'];
			$oldowner = $args['name'];
			$sql="SELECT * FROM ".db_prefix('mailarchive')." WHERE msgto=$oldacctid;";
			$result=db_query($sql);
			while ($row=db_fetch_assoc($result)) {
				if ($fields=='') $fields=array_keys($row);
				$save.="INSERT INTO ".db_prefix('mailarchive')." (".implode(",",$fields)." VALUES ('".addslashes(implode("','",$row))."');";
			}
			set_module_pref("store",$store,"mailarchive",$oldacctid);
			break;
		case "header-mail":

			if (httppost('move_mails')) {
				$msg=httppost('msg');
				$args['done']=1;
				if (!is_array($msg) || count($msg)<1)  {
					$session['message'] = translate_inline("`\$`bYou cannot move zero messages! What does this mean? You pressed \"Move Mails\" but there are no messages checked!  What sort of world is this that people press buttons that have no meaning?!?`b`0");
					break;
				}
				$sql="SELECT count(messageid) AS counter FROM ".db_prefix('mailarchive')." WHERE msgto=".$session['user']['acctid'];
				$result=db_query($sql);
				$row=db_fetch_assoc($result);
				$limit=get_module_setting('archivesize')-$row['counter'];
				if ($limit<=0) {
					output("`\$Sorry, you cannot move any more mails, free up your archive!`n`n");
					break;
				}
				if (count($msg)>$limit) {
					output("`\$Not all mails can be archived, your archive can only store %s more, you select %s to move!",$limit,count($msg));
					break;
				}
				$ids=implode(",",$msg);
				$sql="INSERT INTO ".db_prefix('mailarchive')." (messageid,msgfrom,msgto,subject,body,sent,seen,category) SELECT messageid,msgfrom,msgto,subject,body,sent,seen,0 FROM ".db_prefix('mail')." WHERE messageid IN ($ids) LIMIT $limit;";
				db_query($sql);
				//debug($sql);
				$sql = 'DELETE FROM ' . db_prefix('mail') . " WHERE msgto='".$session['user']['acctid']."' AND messageid IN ($ids)";
				db_query($sql);
				//debug($sql);
				output("`y%s message(s) moved successfully!`n`n",count($msg));
				invalidatedatacache("mail-{$session['user']['acctid']}");
			}
			break;
		case "mailform":
			$read=translate_inline("Move Checked To Archive");
			rawoutput("<input type='submit' name='move_mails' class='button' value='$read'>");
			break;
		case "mailfunctions":
			$mailarchive = translate_inline("Mail Archive");
			array_push($args, array("runmodule.php?module=mailarchive", $mailarchive));
			addnav ("","runmodule.php?module=mailarchive");

			break;

		default:

			break;
	}
	return $args;
}

function mailarchive_run(){
	global $session;
	$op=httpget('op');
	$id = httpget('id');
	$categories=7; //+1=count
	popup_header("Ye Olde Poste Office");
	modulehook("header-mailarchive",array());
	rawoutput("<table width='50%' border='0' cellpadding='0' cellspacing='2'>");
	rawoutput("<tr><td>");
	$t = translate_inline("Back to the Ye Olde Poste Office");
	$o = translate_inline("Back to the Mailarchive");
	rawoutput("<a href='mail.php'>$t</a></td><td>");
	rawoutput("<a href='runmodule.php?module=mailarchive'>$o</a>");
	addnav("","runmodule.php?module=mailarchive");
	rawoutput("</td></tr></table>");
	output_notl("`n`n");
	$table="mailarchive"; //set the table
	$ptable= db_prefix($table);
	$atable= db_prefix("accounts");
	switch ($op) {
		case "readown":
			$sql = "SELECT " . $ptable . ".*,". $atable. ".name,". $atable. ".acctid FROM " . $ptable ." LEFT JOIN " . $atable . " ON ". $atable . ".acctid=" . $ptable. ".msgfrom WHERE msgto=\"".$session['user']['acctid']."\" AND messageid=\"".$id."\"";
			$result = db_query($sql);
			if (db_num_rows($result)>0){
				$row = db_fetch_assoc($result);
				output_notl("`n");
				$tot=translate_inline("From: ");
				output_notl("`b`2$tot`b `^%s`n",$row['name']);
				if (is_array(unserialize($row['subject']))) {
					$subj=unserialize($row['subject']);
					$subj=str_replace("`%","`%%",$subj);
					$row['subject']=call_user_func_array("sprintf",$subj);
				}
				output("`b`2Subject:`b `^%s`n",$row['subject']);
				output("`b`2Sent:`b `^%s`n",$row['sent']);
				output_notl("<img src='images/uscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
				if (is_array(unserialize($row['body']))) {
					$row['body']=call_user_func_array("sprintf",unserialize($row['body']));
				}
				output_notl(sanitize_mb(str_replace("\n","`n",$row['body'])));
				output_notl("`n<img src='images/lscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
				$del = translate_inline("Delete");
				rawoutput("<table width='50%' border='0' cellpadding='0' cellspacing='5'><tr>");
				if ($allowdelete) rawoutput("<td><a href='runmodule.php?module=mailarchive&op=delown&id={$row['messageid']}&rec={$row['acctid']}' class='motd'>$del</a></td>");
				rawoutput("</tr><tr>");
				addnav("","runmodule.php?module=mailarchive&subop=delown&id={$row['messageid']}&rec={$row['acctid']}");
				$sql = "SELECT messageid FROM ".$ptable." WHERE msgto='{$session['user']['acctid']}' AND messageid < '$id' ORDER BY category, messageid DESC LIMIT 1";
				$result = db_query($sql);
				if (db_num_rows($result)>0){
					$row = db_fetch_assoc($result);
					$pid = $row['messageid'];
				}else{
					$pid = 0;
				}
				$sql = "SELECT messageid FROM ".$ptable." WHERE msgto='{$session['user']['acctid']}' AND messageid > '$id' ORDER BY category, messageid  LIMIT 1";
				$result = db_query($sql);
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
					rawoutput("<a href='runmodule.php?module=mailarchive&op=readown&id=$pid' class='motd'>".htmlentities($prev)."</a>");
					addnav("","runmodule.php?module=mailarchive&op=readown&id=$pid");
					}
				else rawoutput(htmlentities($prev));
				rawoutput("</td><td nowrap='true'>");
				if ($nid > 0) {
					rawoutput("<a href='runmodule.php?module=mailarchive&op=readown&id=$nid' class='motd'>".htmlentities($next)."</a>");
					addnav("","runmodule.php?module=mailarchive&op=readown&id=$nid");
					}
				else rawoutput(htmlentities($next));
				rawoutput("</td>");
				rawoutput("</tr></table>");
			}else{
				output("Eek, no such message was found!");
			}
			break;
		case "process":

			break;
		default:
			output("`b`iMail Archive`i`b");
			if (isset($session['message'])) {
				output($session['message']);
			}
			switch (httpget('subop')){
				case "process":
					$msg = httppost('msg');
					$cat = (int)httppost('category');
					if (httppost('movechecked')) {
						if (!is_array($msg) || count($msg)<1){
							output("`\$`bYou cannot move zero messages!  What does this mean?  You pressed \"Move Checked\" but there are no messages checked!  What sort of world is this that people press buttons that have no meaning?!?`b`0");
						}else{
							$sql = "UPDATE ".db_prefix('mailarchive')." SET category=$cat WHERE msgto='".$session['user']['acctid']."' AND messageid IN ('".join("','",$msg)."')";
							$result=db_query($sql);
						}
						if ($result) {
							output("`n`v%s message(s) moved!`n",db_affected_rows($result));
						} else {
							output("`n`\$ERROR, notify your admin about this.`n");
			
						}							
					} else {
						if (!is_array($msg) || count($msg)<1){
							output("`\$`bYou cannot delete zero messages!  What does this mean?  You pressed \"Delete Checked\" but there are no messages checked!  What sort of world is this that people press buttons that have no meaning?!?`b`0");
						}else{
							$sql = "DELETE FROM " . db_prefix("mailarchive") . " WHERE msgto='".$session['user']['acctid']."' AND messageid IN ('".join("','",$msg)."')";
							$result=db_query($sql);
						}
						if ($result) {
							output("`n`\$%s message(s) deleted!`n",db_affected_rows($result));
						} else {
							output("`n`\$ERROR, notify your admin about this.`n");
			
						}				
					}
					break;
				case "delown":
					$sql = "DELETE FROM " . $ptable . " WHERE msgto='".$session['user']['acctid']."' AND messageid='$id'";
					$result=db_query($sql);
					if ($result) {
						output("`n`\$Message deleted!`n");
					} else {
						output("`n`\$ERROR, notify your admin about this.`n");
					}
					invalidatedatacache("mail-".httpget('rec'));					
					break;
				case "move":
					$id=(int)httpget('id');
					$cat=(int)httpget('cat');
					$sql="UPDATE ".db_prefix('mailarchive')." SET category=$cat WHERE messageid=$id;";
					$result=db_query($sql);
					if ($result) {
						output("`n`RMessage moved to category %s!`n",$cat+1);
					} else {
						output("`n`\$ERROR, notify your admin about this.`n");
					}
				default:
					output_notl("`n`n");
			
			}
			$session['message']="";
			$sql = "SELECT subject,messageid,category," . $atable . ".name,msgto,msgfrom,seen,sent FROM " . $ptable . " LEFT JOIN " . $atable . " ON " . $atable . ".acctid=" . $ptable . ".msgfrom WHERE msgto=\"".$session['user']['acctid']."\" ORDER BY category,sent DESC";
			$result = db_query($sql);
			$cat='';
			for ($i=0;$i<$categories;$i++) {
				$catrange[$i]='0';
			}
			if (db_num_rows($result)>0){
				$i=-1;
				output_notl("<form action='runmodule.php?module=mailarchive&subop=process' method='POST'><table>",true);
				addnav("","runmodule.php?module=mailarchive&subop=process");
				rawoutput("<table width='100%' border='0' cellpadding='0' cellspacing='5'>");
				while ($row = db_fetch_assoc($result)) {
					$i++;
					if ($cat!=$row['category']) {
						rawoutput("<tr><td><input id='checkbox_".$row['category']."' type='checkbox' onclick='check_cat(".$row['category'].")'></td><td colspan=4 style='border: border-bottom; border-style: ridge;border-color: #FF00FF;border-width: medium;'>");
						$cattext=get_module_pref('user_category'.$row['category']);
						if ($cattext=='') $cattext="Category ".($row['category']+1);
							else $cattext.="`$(# ".($row['category']+1).")";
						output("`c`\$%s`c",$cattext);
						rawoutput("</tr>");
						$cat=$row['category'];
					}
					if ((int)$row['msgfrom']==0){
						$row['name']=translate_inline("`i`^System`0`i");
						// Only translate the subject if it's an array, ie, it came from the game.
						$row_subject = @unserialize($row['subject']);
						if ($row_subject !== false) {
							$row['subject'] = call_user_func_array("sprintf_translate", $row_subject);
						}
					} elseif ($row['name']=='') {
						$row['name']=translate_inline("`i`^Deleted User`0`i");
					}

					if ($catrange[$row['category']]=="0") {
						$catrange[$row['category']]="'".$row['messageid']."'";
					} else {
						$catrange[$row['category']].=", '".$row['messageid']."'";
					}					
					$sname=sanitize($row['name']);
					//collect sanitized names plues message IDs for later use
					if (!isset($from_list[$sname])) {
						$from_list[$sname]="'".$row['messageid']."'";
					} else {
						$from_list[$sname].=", '".$row['messageid']."'";
					}					
					output_notl("<tr>",true);
					$row['seen']=1; //always seen
					//<img src='images/".($row['seen']?"old":"new")."scroll.GIF' width='16' height='16' alt='".($row['seen']?"Old":"New")."'>
					output_notl("<td nowrap><input id='".$row['messageid']."' type='checkbox' name='msg[]' value='{$row['messageid']}'></td>",true);
					rawoutput("<td>");
					for ($k=0;$k<$categories;$k++) {
						rawoutput("[<a href='runmodule.php?module=mailarchive&subop=move&id=".$row['messageid']."&cat=$k'>".($k+1)."</a>] ");
						addnav("","runmodule.php?module=mailarchive&subop=move&id=".$row['messageid']."&cat=$k");
					}
					output_notl("<td><a href='runmodule.php?module=mailarchive&op=readown&id={$row['messageid']}'>",true);
					if (trim($row['subject'])=="")
						output("`i(No Subject)`i");
					else {
						if (is_array(unserialize($row['subject']))) {
							$row['subject']=call_user_func_array("sprintf",unserialize($row['subject']));
						}
						output_notl($row['subject']);
					}
					
					output_notl("</a></td><td><a href='runmodule.php?module=mailarchive&op=readown&id={$row['messageid']}'>",true);
					addnav("","runmodule.php?module=mailarchive&op=readown&id={$row['messageid']}");
					output_notl($row['name']);
					output_notl("</a></td><td><a href='runmodule.php?module=mailarchive&op=readown&id={$row['messageid']}'>".date("M d, h:i a",strtotime($row['sent']))."</a></td>",true);
					addnav("","runmodule.php?module=mailarchive&op=readown&id={$row['messageid']}");
					output_notl("</tr>",true);
				}
				output_notl("</table>",true);
				$checkall = htmlentities(translate_inline("Check All"));
				$out="<input type='button' id='button_check' value=\"$checkall\" class='button' onClick='check_all();";
				$out.="'>";
				output_notl($out,true);
				$script="<script language='Javascript'>
								function check_all() {
									var elements = document.getElementsByName(\"msg[]\");
									var max = elements.length;
									var Zaehler=0;
									var checktext='".translate_inline("Check all")."';
									var unchecktext='".translate_inline("Uncheck all")."';
									var check = false;
									for (Zaehler=0;Zaehler<max;Zaehler++) {
										if (elements[Zaehler].checked==true) {
											check=true;
											break;
										}
									}
									if (check==false) {
										for (Zaehler=0;Zaehler<max;Zaehler++) {
											elements[Zaehler].checked=true;
											document.getElementById('button_check').value=unchecktext;
										}
									} else {
										for (Zaehler=0;Zaehler<max;Zaehler++) {
											elements[Zaehler].checked=false;
											document.getElementById('button_check').value=checktext;
										}
									}
								}
								function check_name(who) {
									if (who=='') return;
								";
				$add='';
				$i=0;
				$option="<option value=''>---</option>
					";
				foreach ($from_list as $key=>$ids) {
					if ($add=='') {
						$add="new Array(".$ids.")";
					} else $add.=",new Array(".$ids.")";
					$option.="<option value='$i'>".$key."</option>
						";
					$i++;
				}
				$catadd='';
				foreach ($catrange as $key=>$cats) {
					if ($catadd=='') {
						$catadd="new Array(".$cats.")";
					} else $catadd.=",new Array(".$cats.")";
					$catoption.="<option value='$i'>".$key."</option>
						";
					$i++;	
				}				
				$script.="var container = new Array($add);
						var who = document.getElementById('check_name_select').value;
						var unchecktext='".translate_inline("Uncheck all")."';
						for (var i=0;i<container[who].length;i++) {
							document.getElementById(container[who][i]).checked=true;
						}
						document.getElementById('button_check').value=unchecktext;
					}
								
					function check_cat(cat) {
						var container = new Array($catadd);
						var unchecktext='".translate_inline("Uncheck all")."';
						for (var i=0;i<container[cat].length;i++) {
							document.getElementById(container[cat][i]).checked=true;
						}
						document.getElementById('button_check').value=unchecktext;
						document.getElementById('checkbox_'+cat).disabled=unchecktext;
					}									
								
								
								
								</script>";
				rawoutput($script);
				$checkall = htmlentities(translate_inline("Check All"), ENT_COMPAT, getsetting("charset", "ISO-8859-1"));
				$delchecked = htmlentities(translate_inline("Delete Checked"), ENT_COMPAT, getsetting("charset", "ISO-8859-1"));
				$checknames = htmlentities(translate_inline("`vCheck by Name"), ENT_COMPAT, getsetting("charset", "ISO-8859-1"));
				output_notl($checknames." <select onchange='check_name()' id='check_name_select'>".$option."</select><br>",true);
				$delchecked = translate_inline("Delete Checked");
				$movechecked = translate_inline("Move Checked To");
				rawoutput("<input type='submit' class='button' value=\"$delchecked\"> || ",true); 
				rawoutput("<input type='submit' name='movechecked' class='button' value=\"$movechecked\">",true); 
				rawoutput("<select name='category'>");
				for ($k=0;$k<$categories;$k++) {
					rawoutput("<option value='$k'>".htmlentities(get_module_pref('user_category'.$k),ENT_COMPAT,getsetting('charset','ISO-8859-1'))."</option>");
				}
				modulehook("mailform-archive",array());
				rawoutput("</select>");
				output_notl("</form>",true);				

			}else{
				output("`iAww, you have sent no archived mail, how sad.`i");
			}
			$am=db_num_rows($result);
			output ("`n`n`i`\$You currently have %s %s in your Mail Archive.`i`n`4The limit is `\$%s`4 messages.`n`n`4Move message in the respective category by pressing the number in the action box.`n`xMoved messages from the mailbox go to category 1.",$am,translate_inline(($am>1?"messages":"message")),get_module_setting('archivesize'));
			break;
		}
popup_footer();
}

?>
