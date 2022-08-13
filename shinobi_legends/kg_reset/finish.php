<?php

foreach ($kekkei as $category => $cat) {
	foreach ($cat as $kg){
		$points = (int)httppost($kg['modulename']);
		set_module_pref('stack', $points, $kg['modulename']);
		debug($kg['modulename']." had ".$points." set as its new value.");
		debuglog($kg['modulename']." had ".$points." set as its new value.",$session['user']['acctid'],$session['user']['acctid'],"Kekkei Genkai");
	}
}
increment_module_pref("timespurchased",1);
$session['user']['donationspent'] += $cost;
if (get_module_setting("givenewday")){
	addnav("Awake to a newday","newday.php");
	output("`^Kekkei Genkai switch is complete. You will now awake with your new abilities.`0");
}else{
	addnav("L?Return to the HQ","lodge.php");
	output("`^Your resets have been changed. Some of the changes may not full change over until the newday.`0");
}

?>
