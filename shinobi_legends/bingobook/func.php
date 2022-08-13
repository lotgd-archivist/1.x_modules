<?php

function bingobook_clear($id=false) {
	global $session;
	if ($id===false) $id=$session['user']['acctid'];
	$sql="DELETE FROM ".db_prefix('bingobook')." WHERE bingoid=$id OR userid=$id";
	return db_query($sql);
}


function bingobook_getbingo($bingoid=false) {
	global $session;
	if ($bingoid===false) $bingoid=$session['user']['acctid'];
	$sql="SELECT a.name as username, b.userid as acctid,b.entrydate as entrydate,b.comment as comment FROM ".db_prefix('bingobook')." AS b LEFT JOIN ".db_prefix('accounts')." AS a ON b.userid=a.acctid WHERE bingoid=$bingoid ORDER BY username ASC";
	$result=db_query($sql);
	$array=array();
	while ($row=db_fetch_assoc($result)) {
		$array[]=$row;
	}
	return $array;
}

function bingobook_massget($userid=false) {
	global $session;
	if ($userid===false) $userid=$session['user']['acctid'];
	$sql="SELECT * FROM ".db_prefix('bingobook')." WHERE userid=$userid";
	$result=db_query_cached($sql,"bingobook-massget-$userid");
	$array=array();
	while ($row=db_fetch_assoc($result)) {
		$array[]=$row;
	}
	return $array;
}

function bingobook_massgetid($userid=false) {
	global $session;
	if ($userid===false) $userid=$session['user']['acctid'];
	$sql="SELECT bingoid FROM ".db_prefix('bingobook')." WHERE userid=$userid";
	$result=db_query_cached($sql,"bingobook-massget-$userid");
	$array=array();
	while ($row=db_fetch_assoc($result)) {
		$array[]=$row['bingoid'];
	}
	return $array;
}

function bingobook_massgetfull($userid=false) {
	global $session;
	if ($userid===false) $userid=$session['user']['acctid'];
	$sql="SELECT b.*,a.name as bingoname, a.login as bingologin,a.alive as bingoalive, a.loggedin as bingologgedin, a.laston as bingolaston, a.location as bingolocation FROM ".db_prefix('bingobook')." AS b LEFT JOIN ".db_prefix('accounts')." AS a ON b.bingoid=a.acctid WHERE b.userid=$userid ORDER BY bingologin ASC";
	$result=db_query($sql);
	$array=array();
	while ($row=db_fetch_assoc($result)) {
		$array[]=$row;
	}
	return $array;
}

function bingobook_getcomment($bingoid,$userid=false) {
	global $session;
	if ($userid===false) $userid=$session['user']['acctid'];
	$sql="SELECT comment FROM ".db_prefix('bingobook')." WHERE bingoid=$bingoid AND userid=$userid LIMIT 1";
	$result=db_query($sql);
	$row=db_fetch_assoc($result);
	return $row['comment'];
}

function bingobook_get($bingoid,$userid=false) {
	global $session;
	if ($userid===false) $userid=$session['user']['acctid'];
	$sql="SELECT * FROM ".db_prefix('bingobook')." WHERE bingoid=$bingoid AND userid=$userid LIMIT 1";
	$result=db_query($sql);
	return db_fetch_assoc($result);
}

function bingobook_getfull($bingoid,$userid=false) {
	global $session;
	if ($userid===false) $userid=$session['user']['acctid'];
	$sql="SELECT b.*,a.name as bingoname, a.login as bingologin,a.alive as bingoalive, a.loggedin as bingologgedin, a.laston as bingolaston, a.location as bingolocation FROM ".db_prefix('bingobook')." AS b LEFT JOIN ".db_prefix('accounts')." AS a ON b.bingoid=a.acctid WHERE b.bingoid=$bingoid AND b.userid=$userid ORDER BY bingologin ASC LIMIT 1";
	$result=db_query($sql);
	return db_fetch_assoc($result);
}

function bingobook_delete($bingoid,$userid=false) {
	global $session;
	if ($userid===false) $userid=$session['user']['acctid'];
	$sql="DELETE FROM ".db_prefix('bingobook')." WHERE bingoid=$bingoid AND userid=$userid LIMIT 1;";
	invalidatedatacache("bingobook-massget-$userid");
	return db_query($sql);
}

function bingobook_change($bingoid,$userid,$comment) {
	global $session;
	$sql="UPDATE ".db_prefix('bingobook')." SET comment='".addslashes($comment)."' WHERE bingoid=$bingoid AND userid=$userid;";
	invalidatedatacache("bingobook-massget-$userid");
	return db_query($sql);
}

function bingobook_insert($bingoid,$userid=false,$comment=false,$date=false) {
	global $session;
	if ($userid===false) $userid=$session['user']['acctid'];
	if ($comment===false) $comment='';
	if ($date===false) $date=date("Y:m:d H:i:s",strtotime("now"));
	$sql="INSERT INTO ".db_prefix('bingobook')." (bingoid,userid,comment,entrydate) VALUES ($bingoid,$userid,'".addslashes($comment)."', '$date')";
	invalidatedatacache("bingobook-massget-$userid");
	return db_query($sql);
}



?>