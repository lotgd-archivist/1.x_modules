<?php
	if ($session['user']['gems'] >= get_module_setting("gemcost")){
		$session['user']['gems'] -= get_module_setting("gemcost");
		$url = httpget("url");
		output("%s`5 looks at you, \"`&I shall file your request right now.`7\"",getsetting('clanregistrar','`%Karissa'));
		if ($url!='') output("`n`nA moderator has to validate your avatar. Please be patient.");
		set_module_objpref("clans",$session['user']['clanid'],"filename",$url);
		set_module_objpref("clans",$session['user']['clanid'],"validate",0);
		set_module_objpref("clans",$session['user']['clanid'],"days",get_module_setting("days"));
	}else{
		output("%s`5 looks at you, \"`&I apologize, but you do not have the required gems to make this transaction.`5\"",getsetting('clanregistrar','`%Karissa'));
	}
?>