<?php
function showlastregistered_getmoduleinfo(){
	$info = array(
		"name"=>"Show Last Regs for Grotto",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Administrative",
		"download"=>"",

	);
	return $info;
}

function showlastregistered_install(){
	module_addhook("superuser");
	return true;
}

function showlastregistered_uninstall(){
	return true;
}

function showlastregistered_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
	case "superuser":
		if (($session['user']['superuser'] & SU_EDIT_COMMENTS)== SU_EDIT_COMMENTS || ($session['user']['superuser'] & SU_EDIT_PETITIONS)== SU_EDIT_PETITIONS) {
			addnav("Mechanics");
			addnav("Show Last Registered Players","runmodule.php?module=showlastregistered");
		}

	break;
	}
	return $args;
}

function showlastregistered_run(){
	global $session;
	$op=httpget('op');
	require_once("lib/superusernav.php");
	superusernav();
	page_header("Latest Registrations Overview");
	switch ($op) {
		default:
			addnav("Actions");
			addnav("Refresh","runmodule.php?module=showlastregistered");
			$sql="SELECT a.name AS name, a.acctid as acctid, a.regdate as regdate, a.lastip as lastip, a.uniqueid as uniqueid FROM ".db_prefix('accounts')." as a ORDER BY regdate desc LIMIT 30";
			$result = db_query ($sql);
			$user=translate_inline("Username");
			$date=translate_inline("Registerdate");
			$lastip=translate_inline("Last IP");
			$lastid=translate_inline("Last ID");
			$edit=translate_inline("Edit");
			rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999' align=center>");
			rawoutput("<tr class='trhead' height=30px><td><b>$user</b></td><td><b>$date</b></td><td><b>$lastip</b></td><td><b>$lastid</b></td><td></td></tr>");
			$class="trlight";
			$col="`&";
			output("Latest Registrations:`n`n");
			while ($row=db_fetch_assoc($result)) {
				$class=($class=='trlight'?'trdark':'trlight');
				rawoutput("<tr height=30px class='$class'>");
				rawoutput("<td>");
				output_notl($row['name']);
				rawoutput("</td><td>");
				output_notl($row['regdate']);
				rawoutput("</td><td>");
				output_notl($row['lastip']);
				rawoutput("</td><td>");
				output_notl($row['uniqueid']);
				rawoutput("</td><td>");
				if (($session['user']['superuser']&SU_MEGAUSER)==SU_MEGAUSER) {
					rawoutput("<a href='user.php?op=edit&userid=".$row['acctid']."'>".$edit."</a>");
					addnav("","user.php?op=edit&userid=".$row['acctid']."");
				}
				rawoutput("</td></tr>");

			}
			rawoutput("</table>");
			break;
	}
	page_footer();
}


?>
