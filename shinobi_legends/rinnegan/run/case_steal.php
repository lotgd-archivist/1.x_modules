<?php

page_header("Dwellings");
require_once("modules/specialtysystem/functions.php");
specialtysystem_incrementuses("specialtysystem_kekkei_genkai_rinnegan",2);

output("`THuman Path `xsteps up to the Nin, and before he can even react, it lays a hand on the Nin's head, then, after a brief moment, `THuman Path `xrapidly withdraws it's hand, pulling with it the poor Jounin's soul.");
output(" As the Nin topples in a heap to to ground dead, your mind is full of all his knowledge, and suddenly you know who is here, and what dwelling they are in.`n`n");

$location=get_module_pref("location_saver",'dwellings');
$sql = "SELECT A1.name AS acctname, D.name AS dwname, A2.name AS ownername
		FROM ".db_prefix("module_userprefs")." M1, ".db_prefix("module_userprefs")." M2, ".db_prefix("accounts")." A1, ".db_prefix("accounts")." A2, ".db_prefix("dwellings")." D
		WHERE M1.modulename = 'dwellings'
		AND M1.modulename = M2.modulename
		AND M1.userid = M2.userid
		AND M1.setting = 'dwelling_saver'
		AND M1.value > 0
		AND M2.setting = 'location_saver'
		AND M2.value = '$location'
		AND A1.acctid = M1.userid
		AND D.dwid = M1.value
		AND A2.acctid = D.ownerid;";
$result = db_query($sql);
$list=array();
while ($row=db_fetch_assoc($result)) {
	$list[]=$row;
}
tlschema("rinnegan_dwelling");
$n = translate_inline("Name");
$d = translate_inline("Dwelling Name");
$do = translate_inline("Dwelling Owner");

rawoutput("<table border='0' cellpadding='3' cellspacing='0'>");
rawoutput("<tr class='trhead'><td>$n</td><td>$d</td><td>$do</td></tr>");
$num = count($list);
$j = 0;
for ($i=0;$i<$num;$i++){
	$row = $list[$i];
	$j++;
	rawoutput("<tr class='".($j%2?"trlight":"trdark")."'>");
	rawoutput("<td>");
	output_notl("%s`0", $row['acctname']);
	rawoutput("</td>");
	rawoutput("<td>");
	if ($row['dwname']!='') output_notl("%s", $row['dwname']);
	else output_notl("Unnamed");
	rawoutput("</td>");
	rawoutput("<td>");
	output_notl("%s", $row['ownername']);
	rawoutput("</td>");
	rawoutput("</tr>");
}	
if ($num==0){
	$noone = translate_inline("`iYou find there was no one even staying here.`i");
	output_notl("<tr><td align='center' colspan='4'>$noone</td></tr>", true);
}
rawoutput("</table>",true);
addnav("Return to Hamlet","runmodule.php?module=dwellings");

?>