<?php

require_once("lib/superusernav.php");
superusernav();
$restrict = get_module_setting("restrictsize");
$maxwidth = get_module_setting("maxwidth");
$maxheight = get_module_setting("maxheight");
$mode = httpget('mode');
switch ($mode){
	case "val":
		$id = httpget('clanid');
		set_module_objpref("clans",$id,"validate",1);
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")." 
				WHERE clanid='$id' 
				AND clanrank >= '".CLAN_LEADER."'";
		$res = db_query($sql);
		$subj = array("Regarding Clan Avatar");
		$msg = "Your clan's avatar has been validated. You may view it in the clan hall and in your clan member's bio.`n`nSincerely,`n%s";
		require_once("lib/systemmail.php");
		while($row = db_fetch_assoc($res)){
			systemmail($row['acctid'],$subj,array($msg,$session['user']['name']));
		}
		break;
	case "notify":
		$id = httpget('clanid');
		if (httppost('submit')){
			$sql = "SELECT acctid,name FROM ".db_prefix("accounts")." 
					WHERE clanid='$id' 
					AND clanrank >= '" . CLAN_LEADER . "'";
			$res = db_query($sql);
			$subj = array("Regarding Clan Avatar");
			$msg = "%s`n`nSincerely,`n%s";
			require_once("lib/systemmail.php");
			while($row = db_fetch_assoc($res)){
				systemmail($row['acctid'],$subj,array($msg,httppost("msg"),$row['name']));
			}
			output("`%Leaders or higher notified.`n");
			if (httppost('deny')) {
				output("Avatar reset to default.`n");
				set_module_objpref("clans",$id,"filename","modules/clan_avatars/default.jpg");
				set_module_objpref("clans",$id,"validate",1);				
			}
		}else{
			$deny=httpget('deny');
			rawoutput("<form action='runmodule.php?module=clan_avatars&op=validate&mode=notify&clanid=$id' method='post'>");
			rawoutput("<textarea cols='60' rows='20' name='msg'></textarea>");
			rawoutput("<input type='submit' name='submit' class='button' value='".translate_inline("Send")."'><input type='hidden' name='deny' value='$deny'></form>");
			addnav("","runmodule.php?module=clan_avatars&op=validate&mode=notify&clanid=$id");
		}
		break;
}

$sql="SELECT t.*, u.* FROM ".db_prefix("module_objprefs")." AS u INNER JOIN ".db_prefix('module_objprefs')." AS t ON u.objid=t.objid WHERE u.modulename='clan_avatars' AND u.objtype='clans' AND u.setting='validate' AND u.value!='1' AND t.setting='filename' AND t.value !='';";
$check=db_query($sql);

$sql = "SELECT a.value AS filename, clanname, clanid, a.objid AS secondary 
		FROM ".db_prefix("clans")." 
		RIGHT JOIN ".db_prefix("module_objprefs")." AS a ON clanid = a.objid 
		RIGHT JOIN ".db_prefix("module_objprefs")." AS b ON a.objid = b.objid 
		WHERE (a.modulename = 'clan_avatars' AND a.objtype = 'clans' AND a.setting = 'filename') 
		AND (b.modulename = 'clan_avatars' AND b.objtype = 'clans' AND b.setting = 'validate' AND b.value='0') 
		LIMIT 25";
//debug($sql);
$res = db_query($sql);
$i = 0;
$pic = translate_inline("Image");
$clan_name = translate_inline("Clan Name");
$ops = translate_inline("Ops");
$val = translate_inline("Validate");
$not = translate_inline("Notify");
$deny = translate_inline("Notify+Set to Default");
rawoutput("<table style='width:100%;' border=0 cellpadding=1 cellspacing=1 bgcolor='#999999'>");
rawoutput("<tr class='trhead'><td>$pic</td><td>$clan_name</td><td>$ops</td></tr>");
while($row = db_fetch_assoc($res)){
	$i++;
	//cover a rare case: uploaded avatar, then deleted clan before we could validate it...
	if (!$row['clanid']) $row['clanid']=$row['secondary']; 

	rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td align='center'>");
	$img = "<img src='{$row['filename']}' ";
	if ($restrict){
		$pic_size = @getimagesize($row['filename']); // GD2 required here - else size always is recognized as 0
		$pic_width = $pic_size[0];
		$pic_height = $pic_size[1];
		$resizedwidth=$pic_width;
		$resizedheight=$pic_height;
		if ($pic_height > $maxheight) {
			$resizedheight=$maxheight;
			$resizedwidth=round($pic_width*($maxheight
/$pic_height));
		}
		if ($resizedwidth > $maxwidth) {
			$resizedheight=round($resizedheight*($maxwidth
/$resizedwidth));
			$resizedwidth=$maxwidth;
			
		}
		$img.=" height=\"$resizedheight\"  width=\"$resizedwidth\" ";
	}
	$img .= ">";
	rawoutput($img);
	rawoutput("</td><td align='center'>");
	output_notl($row['clanname']);
	rawoutput("</td><td align='center'>");
	rawoutput("[ <a href='runmodule.php?module=clan_avatars&op=validate&mode=val&clanid={$row['clanid']}'>$val</a> |");
	addnav("","runmodule.php?module=clan_avatars&op=validate&mode=val&clanid={$row['clanid']}");
	rawoutput("<a href='runmodule.php?module=clan_avatars&op=validate&mode=notify&clanid={$row['clanid']}'>$not</a> |");
	addnav("","runmodule.php?module=clan_avatars&op=validate&mode=notify&clanid={$row['clanid']}");
	rawoutput("<a href='runmodule.php?module=clan_avatars&op=validate&mode=notify&deny=1&clanid={$row['clanid']}'>$deny</a> ]<br>");
	addnav("","runmodule.php?module=clan_avatars&op=validate&mode=notify&deny=1&clanid={$row['clanid']}");	
	rawoutput("</td></tr>");
}
rawoutput("</table>");
require_once("lib/commentary.php");
addcommentary();	
commentdisplay("`n`n`@Validation Discussions`n","ClanAvatarVal","Talk",10,"says");

?>
