<?php
	$user = httpget('user');
	$where = httpget('where');
	$id = httpget('id');
	$whom = $_POST['whom'];
	$whom = stripslashes(rawurldecode($whom));
        $name="%";
        for ($x=0;$x<strlen($whom);$x++){
            $name.=substr($whom,$x,1)."%";
        }
        $whom = addslashes($name);
	page_header("Fix Navs");
	output("`c`b`&Fix Navigation for a stuck user.`0`b`c`n`n");
	if ($op == ""){
		output("`n`nSearch for Name of user to fix.`n");
		output("<form action='runmodule.php?module=lotgdutil&mode=fixnavs&op=step1' method='POST'>",true);
		output("<p><input type=\"text\" name=\"whom\" size=\"37\"></p>",true);
		output("<p><input type=\"submit\" value=\"Submit\" name=\"B1\"><input type=\"reset\" value=\"Reset\" name=\"B2\"></p>",true);
		output("</form>",true);
		addnav("","runmodule.php?module=lotgdutil&mode=fixnavs&op=step1");
	}
	if ($op == "step1" ){
		$sql = "SELECT login,name,level,acctid FROM accounts WHERE name LIKE '%".$whom."%' and acctid <> '".$session['user']['acctid']."' ORDER BY level,login LIMIT 100";
		$result = db_query($sql);
		    if (db_num_rows($result) < 1) output ("No on matching that name found.");
				output("Choose who's navs to fix:`n");
		        output("<table cellpadding='3' cellspacing='0' border='0'>",true);
		        output("<tr class='trhead'><td>Name</td><td>Level</td></tr>",true);
		          for ($i=0;$i<db_num_rows($result);$i++){
			      $row = db_fetch_assoc($result);
			      output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='runmodule.php?module=lotgdutil&mode=fixnavs&op=step2&user=".$row['acctid']."'>",true);
			      output($row['name']);
			      output("</a></td><td>",true);
			      output($row['level']);
			      output("</td></tr>",true);
			      addnav("","runmodule.php?module=lotgdutil&mode=fixnavs&op=step2&user=".$row['acctid']);
		          }
		          output("</table>",true);
		          output("`n");
		          addnav("Go Back","runmodule.php?module=lotgdutil&mode=fixnavs");
	}	
	if ($op == "step2" ){
		$sql = "UPDATE " . db_prefix("accounts") . " SET allowednavs='',output=\"\", restorepage =\"\" WHERE acctid='$user'";
		db_query($sql);
		if (db_affected_rows()>0) $msg = db_affected_rows()." account had navs cleared."; else $msg = "No update was performed for clear navs request - ".db_affected_rows()." rows affected.";
		output("$msg `n");
		if ($where == "petition"){
			addnav("Go Back","viewpetition.php?op=view&id=".$id);
		}else{
			addnav("Go Back","runmodule.php?module=lotgdutil&mode=fixnavs");
		}
	}
	villagenav();
	addnav("Back to the Grotto","superuser.php");
?>