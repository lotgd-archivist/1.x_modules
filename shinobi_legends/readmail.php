<?php

function readmail_getmoduleinfo() {
	$info = array
		(
		 "name"=>"Delete read mails and mark as read",
		 "version"=>"1.1",
		 "author"=>"Oliver Brendel",
		 "category"=>"Mail",
		 "download"=>"",
		);
	return $info;
}

function readmail_install() {
	module_addhook("header-mail");
	module_addhook("mailform");
	return true;
}

function readmail_uninstall() {
	return true;
}

function readmail_dohook($hookname,$args) {
	global $session;
	switch ($hookname) {
		case "header-mail":
			if (httppost('delete_readmails')) {
				$sql = 'DELETE FROM ' . db_prefix('mail') . " WHERE msgto='".$session['user']['acctid']."' AND seen=1";
				db_query($sql);
				output("`RRead messages deleted successfully!`n`n");
				$args['done']=1;
				invalidatedatacache("mail-{$session['user']['acctid']}");
			} elseif (httppost('mark_as_read')) {
				$msg=httppost('msg');
				if (!is_array($msg) || count($msg)<1)  {
					$session['message'] = translate_inline("`\$`bYou cannot mark zero messages! What does this mean? You pressed \"Mark Checked As Seen\" but there are no messages checked!  What sort of world is this that people press buttons that have no meaning?!?`b`0");
					break;
				}
				$ids=implode(",",$msg);
				$sql = 'UPDATE ' . db_prefix('mail') . " SET seen=1 WHERE msgto='".$session['user']['acctid']."' AND messageid IN ($ids)";
				db_query($sql);
				output("`y%s message(s) marked successfully!`n`n",count($msg));
				$args['done']=1;
				invalidatedatacache("mail-{$session['user']['acctid']}");
			}
			break;
		case "mailform":
			$checkread=translate_inline("Mark Checked As Seen");
			rawoutput("<input type='submit' name='mark_as_read' class='button' value='$checkread'>");
			$read=translate_inline("Delete All Read");
			rawoutput("<input type='submit' name='delete_readmails' class='button' value='$read'>");
			break;
	}
	return $args;
}

function readmail_run() {
}


?>
