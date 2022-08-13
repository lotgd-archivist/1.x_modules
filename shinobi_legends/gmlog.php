<?php

function gmlog_getmoduleinfo() {
	$info = array(
		"name"=>"GM Log (ban-based)",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Administrative",
		"download"=>"",
		"settings"=>array(
			"GM Log Settings,title",
		),
	);
	return $info;
}

function gmlog_install() {
	module_addhook_priority("header-bans",50);
	module_addhook("biotop");
	$archive=array(
		'id'=>array('name'=>'id', 'type'=>'int(11) unsigned', 'extra'=>'auto_increment'), 
		'gm_name'=>array('name'=>'gm_name', 'type'=>'varchar(255)'),
		'ipfilter'=>array('name'=>'ipfilter', 'type'=>'varchar(15)'),
		'uniqueid'=>array('name'=>'uniqueid', 'type'=>'varchar(32)'),
		'reason'=>array('name'=>'reason', 'type'=>'text'),
		'date'=>array('name'=>'date', 'type'=>'datetime', 'default'=>DATETIME_DATEMIN),
		'expiration'=>array('name'=>'expiration', 'type'=>'datetime', 'default'=>DATETIME_DATEMIN),
		'acctid'=>array('name'=>'acctid', 'type'=>'int(11) unsigned'),
		'key-PRIMARY' => array('name'=>'PRIMARY', 'type'=>'primary key', 'unique'=>'1', 'columns'=>'id'),
	);
	require_once("lib/tabledescriptor.php");
	synctable(db_prefix("gm_log"), $archive, true);
	return true;
}

function gmlog_uninstall() {
	return true;
}


function gmlog_dohook($hookname, $args) {
	global $session;
	switch ($hookname) {
		case "header-bans":
			$op=httpget('op');
			if ($op=='saveban') {
				// ban is setup, record it
$sql = "INSERT INTO " . db_prefix("gm_log") . " (gm_name,acctid,date,";
$type = httppost("type");
if ($type=="ip"){
	$sql.="ipfilter";
	$key = "lastip";
	$key_value = httppost('ip');
}else{
	$sql.="uniqueid";
	$key = "uniqueid";
	$key_value = httppost('id');
}
$sql.=",expiration,reason) VALUES ('" . addslashes($session['user']['login']) . "',||ACCTID||,'".date('Y-m-d',strtotime("now"))."',";
if ($type=="ip"){
	$sql.="\"".httppost("ip")."\"";
}else{
	$sql.="\"".httppost("id")."\"";
}
$duration = (int)httppost("duration");
if ($duration == 0) $duration=DATETIME_DATEMIN;
else $duration = date("Y-m-d", strtotime("+$duration days"));
	$sql.=",\"$duration\",";
$sql.="\"".httppost("reason")."\")";

	/* one entry for every dude found (acctid) at that time - other values can and will change! This is at bantime*/
	$ssql = "SELECT acctid FROM ".db_prefix('accounts')." WHERE $key='$key_value'";
	$result=db_query($ssql);
	while($row = db_fetch_assoc($result)) {
		$sql_replaced = str_replace('||ACCTID||',$row['acctid'],$sql);
		$res = db_query($sql_replaced);	
	}
}
		break;
	case "biotop":
		if ($session['user']['superuser'] & SU_EDIT_COMMENTS) {
			$user=httpget('char');
			addnav("See GM Log","runmodule.php?module=gmlog&op=show&userid=$user");	
		}
		break;
		default:
		break;
	}
	return $args;
}

function gmlog_run(){
	global $session;
	check_su_access(SU_EDIT_COMMENTS);
	$user=httpget('userid');
	addnav("Back to Bio","bio.php?char=$user");
	$sql = "SELECT * FROM ".db_prefix('gm_log')." WHERE acctid=".$user." order by date desc";
	$result=db_query($sql);
	page_header("GM Log");
	output("Here you see a list of log entires that were made for this account. It may or may not be from the person currently owning it.`n");
	output("`nBans that affected this char might have been 'sitted' by an offender i.e.`n`n");
	rawoutput("<table>");
	while($row = db_fetch_assoc($result)) {
		$class=($class=='trdark'?'trlight':'trdark');
		rawoutput("<tr class='$class'>");
		rawoutput(sprintf("<td>%s</td><td>%s</td><td>%s</td>",$row['date'],$row['reason'],$row['gm_name']));
		rawoutput("</tr>");
	}
	rawoutput("</table>");	
	if (db_num_rows($result)==0) 
		output("`\$Well... nothing found. Very nice - quiet char :)");
	page_footer();
}

?>
