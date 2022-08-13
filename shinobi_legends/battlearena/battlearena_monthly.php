<?php
function battlearena_monthly($month,$year,$delete=FALSE) {
	$sql = "SELECT objtype FROM ".db_prefix('module_objprefs')." WHERE setting='battlepoints' GROUP BY objtype;";
	$result=db_query($sql);
	$cities=array();
	while ($row=db_fetch_assoc($result)) {
		$cities[]=$row['objtype'];
	}
	if ($cities==$array) return;
	$highscore=array();
	while ($city=array_shift($cities)) {
		$temp=array();
		$sql = "SELECT a.name as name,o.value as points,a.acctid as acctid FROM " . db_prefix('module_objprefs') . " as o LEFT JOIN " . db_prefix('accounts') . " as a ON a.acctid = o.objid WHERE o.modulename='battlearena' AND o.setting='battlepoints' and o.objtype='".addslashes($city)."' and o.value > 0 ORDER BY value + 0 DESC,name LIMIT 50";
		if ($delete)
			$result = db_query($sql);
			else
			$result = db_query($sql);
			//$result = db_query_cached($sql,"b-arena-$city-10min",600); //commented out due to permission problems at the cache
		if (db_num_rows($result)>0) {
			while ($row = db_fetch_assoc($result)) {
				$temp[]=$row;
			}
		}
		$highscore=array_merge($highscore,array($city=>$temp));
	}
	set_module_objpref('highscore',$month,$year,serialize($highscore),"battlearena");
	//now clear it up!
	if ($delete) {
		$sql = "DELETE FROM ".db_prefix('module_objprefs')." WHERE modulename='battlearena' AND setting='battlepoints';";
		$result=db_query($sql);
		$number=db_affected_rows();
		require_once("lib/gamelog.php");
		gamelog("Delete $number rows and setup the monthly statistics for $month / $year");
	}
}
?>
