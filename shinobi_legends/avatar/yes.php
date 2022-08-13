<?php
		$cost = httpget("cost");
		$set = httppost("set");
		$url = httpget("url");
		output("`7J. C. Petersen grins broadly, \"`&Excellent.  I'll take care of that for you right now.`7\"");
		if ($url!='') output("`n`nA moderator has to validate your avatar. Please be patient.");
		debug("avatar bought for $cost points");
		$session['user']['donationspent'] += $cost;
		set_module_pref("bought", 1);
		if ($set) set_module_pref("setname", $set);
		set_module_pref("avatar",$url);
		set_module_pref("validated",0);
		set_module_pref('avatar_resize_dimensions','','avatar',$user); //new sizes
?>