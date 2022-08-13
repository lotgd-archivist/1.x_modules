<?php
	page_header("Letter openner");
	require_once("common.php");
	require_once("lib/systemmail.php");
	require_once("lib/sanitize.php");
	require_once("lib/http.php");
	$op = httpget('op');
	$order = "acctid";
	if ($sort!="") $order = "$sort";
	$display = 0;
	$query = httppost('q');
	if ($query === false) $query = httpget('q');
	addnav("Back to the grotto","superuser.php");
	if ($op=="read"){
	$id = httpget('id');
	$sql = "SELECT msgfrom,msgto from " . db_prefix("mail") . " where messageid=\"".$id."\"";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$acctid = $row['msgto'];
	$sqlz = "SELECT login from " . db_prefix("accounts") . " where acctid=\"".$acctid."\"";
	$result = db_query($sqlz);
	$rowz = db_fetch_assoc($result);
	$login = $rowz['login'];
	
	addnav("Read Someone else's mail","runmodule.php?module=lotgdutil&mode=letteropenner");
	addnav("Back to Inbox","runmodule.php?module=lotgdutil&mode=letteropenner&op=inbox&to=$login");
		$sql = "SELECT " . db_prefix("mail") . ".*,". db_prefix("accounts"). ".name FROM " . db_prefix("mail") ." LEFT JOIN " . db_prefix("accounts") . " ON ". db_prefix("accounts") . ".acctid=" . db_prefix("mail"). ".msgfrom WHERE msgto=\"".$acctid."\" AND messageid=\"".$id."\"";
		$result = db_query($sql);
		if (db_num_rows($result)>0){
			$row = db_fetch_assoc($result);
			if ((int)$row['msgfrom']==0){
				$row['name']=translate_inline("`i`^System`0`i");
				if (is_array(unserialize($row['subject']))) {
					$row['subject'] = unserialize($row['subject']);
					$row['subject'] =
						call_user_func_array("sprintf_translate", $row['subject']);
				}
				if (is_array(unserialize($row['body']))) {
					$row['body'] = unserialize($row['body']);
					$row['body'] =
						call_user_func_array("sprintf_translate", $row['body']);
				}
			}
			if (!$row['seen']) output("`b`#NEW`b`n");
			else output("`n");
			output("`b`2From:`b `^%s`n",$row['name']);
			output("`b`2Subject:`b `^%s`n",$row['subject']);
			output("`b`2Sent:`b `^%s`n",$row['sent']);
			output_notl("<hr>`n",true);
			output_notl(str_replace("\n","`n",$row['body']));
			output_notl("`n<hr>`n",true);
	
			rawoutput("<table width='50%' border='0' cellpadding='0' cellspacing='5'><tr>");
	
				rawoutput("<td align='right'>&nbsp;</td>");
	
			rawoutput("</tr><tr>");
			$sql = "SELECT messageid FROM ".db_prefix("mail")." WHERE msgto='{$acctid}' AND messageid < '$id' ORDER BY messageid DESC LIMIT 1";
			$result = db_query($sql);
			if (db_num_rows($result)>0){
				$row = db_fetch_assoc($result);
				$pid = $row['messageid'];
			}else{
				$pid = 0;
			}
			$sql = "SELECT messageid FROM ".db_prefix("mail")." WHERE msgto='{$acctid}' AND messageid > '$id' ORDER BY messageid  LIMIT 1";
			$result = db_query($sql);
			if (db_num_rows($result)>0){
				$row = db_fetch_assoc($result);
				$nid = $row['messageid'];
			}else{
				$nid = 0;
			}
			$prev = translate_inline("< Previous");
			$next = translate_inline("Next >");
			rawoutput("<td nowrap='true'>");
			if ($pid > 0){ rawoutput("<a href='runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$pid' class='motd'>".htmlentities($prev)."</a>");
			addnav("","runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$pid");}
	        else{ rawoutput(htmlentities($prev));}
			rawoutput("</td><td nowrap='true'>");
			if ($nid > 0){ rawoutput("<a href='runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$nid' class='motd'>".htmlentities($next)."</a>");
			addnav("","runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$nid");}
	        else{ rawoutput(htmlentities($next));}
			rawoutput("</td>");
			rawoutput("</tr></table>");
	}
	}elseif($op==""){
	;
		output("Whose mail would you like to read?`n");
	rawoutput("<form action='runmodule.php?module=lotgdutil&mode=letteropenner' method='POST'>");
	rawoutput("<input name='q' id='q'>");
	$se = translate_inline("Search");
	rawoutput("<input type='submit' class='button' value='$se'>");
	rawoutput("</form>");
	rawoutput("<script language='JavaScript'>document.getElementById('q').focus();</script>");
	addnav("","runmodule.php?module=lotgdutil&mode=letteropenner");
		
		$searchresult = false;
		$where = "";
		$op="";
		$sql = "SELECT acctid,login,name FROM " . db_prefix("accounts");
		if ($query != "") {
			$where = "WHERE login='$query' OR name='$query'";
			$searchresult = db_query($sql . " $where  ORDER BY '$order' LIMIT 2");
		}
	
		if ($query !== false || $searchresult) {
			if (db_num_rows($searchresult) != 1) {
				$where="WHERE login LIKE '%$query%' OR acctid LIKE '%$query%' OR name LIKE '%$query%' OR emailaddress LIKE '%$query%' OR lastip LIKE '%$query%' OR uniqueid LIKE '%$query%' OR gentimecount LIKE '%$query%' OR level LIKE '%$query%'";
				$searchresult = db_query($sql . " $where  ORDER BY '$order' LIMIT 101");
			}
			if (db_num_rows($searchresult)<=0){
				output("`\$No results found`0");
				$where="";
			}elseif (db_num_rows($searchresult)>100){
				output("`\$Too many results found, narrow your search please.`0");
				$op="";
				$where="";
			}else{
				$op="";
				$display=1;
			}
		}
	    
	    	if ($display == 1){
			$q = "";
			if ($query) {
				$q = "&q=$query";
			}
	
			$acid =translate_inline("AcctID");
			$login =translate_inline("Login");
			$nm =translate_inline("Name");
	
			rawoutput("<table>");
			rawoutput("<tr class='trhead'><td>$acid</td><td>$login</td><td>$nm</td></tr>");
			$rn=0;
			$oorder = "";
			for ($i=0;$i<db_num_rows($searchresult);$i++){
				$row=db_fetch_assoc($searchresult);
				$laston = relativedate($row['laston']);
				$loggedin =
					(date("U") - strtotime($row['laston']) <
					 getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
				if ($loggedin)
					$laston=translate_inline("`#Online`0");
				$row['laston']=$laston;
				if ($row[$order]!=$oorder) $rn++;
				$oorder = $row[$order];
				rawoutput("<tr class='".($rn%2?"trlight":"trdark")."'>");
				rawoutput("<td nowrap>");
				addnav("","runmodule.php?module=lotgdutil&mode=letteropenner&op=inbox&to={$row['login']}");
				output_notl("<a href='runmodule.php?module=lotgdutil&mode=letteropenner&op=inbox&to={$row['login']}'>%s</a>", $row['acctid'],true);
				rawoutput("</td><td>");
				output_notl("<a href='runmodule.php?module=lotgdutil&mode=letteropenner&op=inbox&to={$row['login']}'>%s</a>", $row['login'],true);
				rawoutput("</td><td>");
				output_notl("<a href='runmodule.php?module=lotgdutil&mode=letteropenner&op=inbox&to={$row['login']}'>`&%s`0</a>", $row['name'],true);
				rawoutput("</td></tr>");
			}rawoutput("</table>"); 
	  } 
	}elseif($op=="inbox"){
	            
		$subject="";
		$body="";
		$row = "";
	    addnav("Read someone else's mail","runmodule.php?module=lotgdutil&mode=letteropenner");
		$to = httpget('to');
		if ($to!=""){
			$sql = "SELECT acctid,login,name superuser FROM " . db_prefix("accounts") . " WHERE login=\"$to\"";
			$result = db_query($sql);
	
				$row = db_fetch_assoc($result);
	
	$sql = "SELECT acctid FROM " . db_prefix("accounts") . " WHERE login='".$row['login']."'";
			$result = db_query($sql);
	        $row2 = db_fetch_assoc($result);
	$acctid=$row2['acctid'];
	            output("`b`iMail Box`i`b");
		output_notl("<table>",true);
		$session['message']="";
		$sql = "SELECT subject,messageid," . db_prefix("accounts") . ".name,msgfrom,seen,sent FROM " . db_prefix("mail") . " LEFT JOIN " . db_prefix("accounts") . " ON " . db_prefix("accounts") . ".acctid=" . db_prefix("mail") . ".msgfrom WHERE msgto=\"".$acctid."\" ORDER BY sent DESC";
		$result = db_query($sql);
		if (db_num_rows($result)>0){
	    		for ($i=0;$i<db_num_rows($result);$i++){
	                $row = db_fetch_assoc($result);
	                if ((int)$row['msgfrom']==0){
	                    $row['name']=translate_inline("`i`^System`0`i");
	                    if (is_array(unserialize($row['subject']))) {
	                        $row['subject'] = unserialize($row['subject']);
	                        $row['subject'] =
	                            call_user_func_array("sprintf_translate",
	                                    $row['subject']);
	                    }
	                }
	                $id=$row['messageid'];
	                output_notl("<tr>",true);
	                output_notl("<td nowrap><img src='images/".($row['seen']?"old":"new")."scroll.GIF' width='16' height='16' alt='".($row['seen']?"Old":"New")."'></td>",true);
	                output_notl("<td><a href='runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$id&login=$to'>",true);
	                addnav("","runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$id&login=$to");			
	                if (trim($row['subject'])==""){	output("`i(No Subject)`i");}
	                else{				output_notl($row['subject']);}
	                output_notl("</a></td><td><a href='runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$id&login=$to'>",true);
	                            addnav("","runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$id&login=$to");
	                output_notl($row['name']);
	                output_notl("</a></td><td><a href='runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$id&login=$to'>".date("M d, h:i a",strtotime($row['sent']))."</a></td>",true);
	                addnav("","runmodule.php?module=lotgdutil&mode=letteropenner&op=read&id=$id&login=$to");
	                output_notl("</tr>",true);
	            }

		}else{
			output("`iAww, you have no mail, how sad.`i");
		}
			}elseif (db_num_rows($result)==0){
				output("`@No one was found who matches \"%s\".  ",stripslashes($to));
				$try = translate_inline("Please try again");
				output_notl("<a href='runmodule.php?module=lotgdutil&mode=letteropenner'>$try</a>.",true);
				popup_footer();
				exit();
			}else{
				output_notl("<select name='to' id='to' onChange='check_su_warning();'>",true);
				$superusers = array();
				for ($i=0;$i<db_num_rows($result);$i++){
					$row = db_fetch_assoc($result);
					output_notl("<option value=\"".HTMLEntities($row['login'])."\">",true);
					output_notl("%s", full_sanitize($row['name']));
					if (($row['superuser'] & SU_GIVES_YOM_WARNING) &&
	                        !($row['superuser'] & SU_OVERRIDE_YOM_WARNING)) {
						array_push($superusers,$row['login']);
	                }
				}
			output_notl("</select>`n",true);
			}
	        output_notl("</table>",true);
        }
?>