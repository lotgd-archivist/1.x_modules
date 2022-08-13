<?php
	$sql="SELECT o.objid,c.clanname FROM ".db_prefix('module_objprefs')." AS o INNER JOIN ".db_prefix('clans')." AS c ON o.objid=c.clanid WHERE o.modulename='clan_avatars' AND o.objtype='clans' AND o.setting='filename';";
	$result=db_query($sql);
	$pic = translate_inline("Clan Avatar");
	$clan_name = translate_inline("Clan Name");
	$ops = translate_inline("Ops");
	$val = translate_inline("Validate");
	$not = translate_inline("Notify");
	rawoutput("<table style='width:100%;' border=0 cellpadding=1 cellspacing=1>");
	rawoutput("<tr class='trhead'><td>$pic</td><td>$clan_name</td></tr>");
	while ($row=db_fetch_assoc($result)) {
		$i++;
		rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td align='center'>");
		$image = clan_avatar_getimage($row['objid']);
		rawoutput("$image</td><td>");
		output_notl("%s",$row['clanname']);
		rawoutput("</td></tr>");	
	}
	rawoutput("</table>");
?>