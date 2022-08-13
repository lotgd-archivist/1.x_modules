<?php

function wettkampf_best_run_private($op, $subop=false){
	global $session;
	
	$subop2=$_GET[subop2];
		
//DIE BESTENLISTEN

//Tabellen-Funktion
function display_table($title, $sql, $none=false, $foot=false, $data_header=false, $tag=false){
    global $session, $from, $to, $page;

	$fest=get_module_setting("fest");
		
	$page=$_GET[page];
	if ($page == "" || $page == 0 || $page == 1){
		$page=1;
		$from=1;
		$to=$from+49;
	}else{
		$from=$page*50-49;
		$to=$page*50;
	}
			
	$title = translate_inline($title);
    if ($foot !== false) $foot = translate_inline($foot);
    if ($none !== false) $none = translate_inline($none);
    else $none = translate_inline("Keine Einträge vorhanden.");
    if ($data_header !== false) {
        $data_header = translate_inline($data_header);
        reset ($data_header);
    }
    if ($tag !== false) $tag = translate_inline($tag);
    $rank = translate_inline("Rang");
    $name = translate_inline("Name");

    output("`c`b`^%s`0`b `7(Seite %s: %s-%s)`0`c`n", $title, $page, $from, $to);
    rawoutput("<table cellspacing='0' cellpadding='2' align='center'>");
    rawoutput("<tr class='trhead'>");
    output_notl("<td>`b$rank`b</td><td>`b$name`b</td>", true);
    if ($data_header !== false) {
        for ($i = 0; $i < count($data_header); $i++) {
            output_notl("<td>`b{$data_header[$i]}`b</td>", true);
        }
    }
    $result = db_query($sql);
    if (db_num_rows($result)==0){
        $size = ($data_header === false) ? 2 : 2+count($data_header);
        output_notl("<tr class='trlight'><td colspan='$size' align='center'>`&$none`0</td></tr>",true);
    } else {
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            if ($row['name']==$session['user']['name']){
                output("<tr bgcolor='#506050'>",true);
            } else {
                rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
            }
            
            //output_notl("<td>%s</td><td>`&%s`0</td>",($i+$from), $row['name'], true);
            
            output_notl("<td>%s</td><td>`&", ($i+$from), true);
			output_notl("<a href='bio.php?char=".rawurlencode($row['login'])."&ret=hhof.php'>`&".$row['name']."</a>", true);
			output_notl("`0</td>", true);
			addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=hhof.php");
            
            if ($data_header !== false) {
                for ($j = 0; $j < count($data_header); $j++) {
                    $id = "data" . ($j+1);
                    $val = $row[$id];
                    if ($tag !== false) $val = $val . " " . $tag[$j];
					$op1= httpget('subop2');
					if ($op1 == "wkletternbest" || $op1 == "wkochenbest" || $op1 == "wreitenbest" || $op1 == "wschwimmenbest" || $op1 == "wmusikbest"){
						output_notl("<td align='right'>%s</td>", ($val==0?"`\$Disqualifiziert`&":"$val"), true);
					}else if ($op1 == "wschleichenbest"){
						output_notl("<td align='right'>%s</td>", ($val==9999?"`\$Disqualifiziert`&":"$val"), true);
                	}else{
						output_notl("<td align='right'>%s</td>", $val, true);
					}								
                }
            }
            rawoutput("</tr>");
        }
    }
    rawoutput("</table>");
    if ($foot !== false) output_notl("`n`c%s`c", $foot);
}

	switch ($subop2){
		case "wbogenbest": $typ="bogen"; break;
		case "wschwimmenbest": $typ="schwimmen"; break;
		case "wkletternbest": $typ="klettern"; break;
		case "wmusikbest": $typ="musik"; break;
		case "wschleichenbest": $typ="schleichen"; break;
		case "wreitenbest": $typ="reiten"; break;
		case "wkochenbest": $typ="kochen"; break;
	}
	
	$args=$op;
	require_once("modules/wettkampf/wettkampf_best_".$typ.".php");
	return call_user_func_array("wettkampf_best_".$typ."_run_private",$args);
	
}
?>