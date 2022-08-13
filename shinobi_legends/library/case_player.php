<?php
	$subop=httpget('subop');
	//default nav
	addnav("Navigation");
	addnav("Return to the Shelves","runmodule.php?module=library&op=shelves");
	addnav("Novels");
	addnav("Check Author List","runmodule.php?module=library&op=player&subop=authorlist");
	addnav("Check Categories","runmodule.php?module=library&op=player&subop=categorylist");
	addnav("Browse Chronologically","runmodule.php?module=library&op=player");
	switch ($subop) {
		case "authorlist":
			output("Pick one either on the left hand side or in the table to see books!`n`n");
			$sql="SELECT b.authid AS acctid, a.name AS name, count(b.bookid) AS counter FROM ".db_prefix('accounts')." as a RIGHT JOIN ".db_prefix('librarybooks')." AS b ON a.acctid=b.authid GROUP BY b.authid ORDER BY a.login ASC;";
			$result=db_query($sql);
			addnav("Authors");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			$per=5;
			$len=db_num_rows($result);
			$columns=5;//ceil($len/$per);
			$leni=$len-1;
			rawoutput("<tr class='trhead'>");
			$i=0;
			while ($row=db_fetch_assoc($result)) {
				if ($row['acctid']==0)  {
					$row['name']=translate_inline('Anonymous');
					$row['acctid']='anon';
				} elseif ($row['name']=='') {
					continue;
					
				}
				if ($i%$columns==0) rawoutput("<tr>");
				if ($i%2) $class='class="trlight"';
					else $class='class="trdark"';
				rawoutput("<td $class>");
				rawoutput("<a href='runmodule.php?module=library&op=player&author={$row['acctid']}'>");
				output_notl("%s",stripslashes($row['name']));
				rawoutput("</a>");
				rawoutput("</td>");
				addnav("","runmodule.php?module=library&op=player&author={$row['acctid']}");
				addnav(array("%s (%s books)",$row['name'],$row['counter']),"runmodule.php?module=library&op=player&author=".$row['acctid']);
				if ((($i+1)%$columns)==0 && $i!=$leni) rawoutput("</tr>");
				$i++;
			}
			rawoutput("</tr></table>");
			break;
		case "categorylist":
			output("Pick one either on the left hand side or in the table!`n`n");
			$sql="SELECT b.category AS category, count(b.bookid) AS counter FROM ".db_prefix('librarybooks')." AS b GROUP BY b.category ORDER BY b.category ASC;";
			$result=db_query($sql);
			addnav("Categories");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			$per=15;
			$len=db_num_rows($result);
			$columns=(int)($len/$per)+1;
			$leni=$len-1;
			rawoutput("<tr class='trhead'>");
			$i=0;
			while ($row=db_fetch_assoc($result)) {
				if ($row['acctid']==0) {
					$row['name']=translate_inline('Anonymous');
					$row['acctid']='anon';
				}
				if ($i%$columns==0) rawoutput("<tr>");
				if ($i%2) $class='class="trlight"';
					else $class='class="trdark"';
				rawoutput("<td $class>");
				rawoutput("<a href='runmodule.php?module=library&op=player&category=".htmlentities($row['category'],ENT_COMPAT,getsetting('charset','ISO-8859-1'))."'>");
				output_notl("`$%s`0",stripslashes($row['category']));
				rawoutput("</a>");
				rawoutput("</td>");
				addnav("","runmodule.php?module=library&op=player&category=".htmlentities($row['category'],ENT_COMPAT,getsetting('charset','ISO-8859-1')));
				addnav(array("%s (%s books)",$row['category'],$row['counter']),"runmodule.php?module=library&op=player&category=".$row['category']);
				if ((($i+1)%$columns)==0 && $i!=$leni) rawoutput("</tr>");
				$i++;
			}
			rawoutput("</tr></table>");
			break;
		case "read":
			//display the book
			$sql = "SELECT l.title AS title, l.content AS content, a.name AS name,a.acctid AS acctid FROM ".db_prefix("librarybooks")." AS l LEFT JOIN ".db_prefix("accounts")." AS a ON authid=acctid WHERE bookid=$id";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			if ($row['name']=='') {
				$row['name']=translate_inline("Anonymous");
				$row['acctid']='anon';
			}
			page_header(array("%s by %s",stripslashes(color_sanitize($row['title'])),color_sanitize($row['name'])));
			output("`c`b%s`0 by %s.`b`c`0",stripslashes($row['title']),$row['name']);
			output("`n`n`c%s`c`0",nltoappon(stripslashes(stripslashes($row['content']))));
			addnav(array("More books from %s",$row['name']),"runmodule.php?module=library&op=player&author=".$row['acctid']);
			if (get_module_setting("allow")) addnav("Return to Player Written Novels","runmodule.php?module=library&op=player");			
			break;
	
		default:
			output("`2Looking around the Library, you find a bunch of novels written by some fellow warriors.");
			output("You glance over all of the titles, and smile.");
			output("\"`^Would you care to read one?`2\"`n`n");
			$author=httpget('author');
			$category=httpget('category');
			if ($author=='anon') {
				$authorstring="AND authid=0";
				$navstring.="&author=0";
				$limit='';
			} elseif (is_numeric($author)) {
				$authorstring="AND authid=$author";
				$navstring.="&author=$author";
				$limit='';
			}	else $authorstring='';
			
			if ($category!='') {
				$categorystring="AND category='$category'";
				$navstring.="&category=$category";
				$limit='';
			}	else $categorystring='';
			$lib=db_prefix("librarybooks");
			$ac=db_prefix("accounts");
			$pp = get_module_setting("pp");
			$pageoffset = (int)$page;

			if ($pageoffset > 0) $pageoffset--;
			$pageoffset *= $pp;
			$from = $pageoffset+1;
			$limit = "LIMIT $pageoffset,$pp";
			$sql = "SELECT $lib.title, $lib.authid, $lib.bookid, $ac.name, $lib.validated, $lib.content,$lib.category,$lib.date  FROM $lib LEFT JOIN $ac ON authid = acctid WHERE validated=1 $categorystring $authorstring ORDER BY bookid DESC $limit";
			$result = db_query($sql);debug($sql);

			$sql = "SELECT COUNT(bookid) AS c FROM " . db_prefix("librarybooks") . " WHERE validated=1 $categorystring $authorstring";
			$result2 = db_query($sql);
			$row2 = db_fetch_assoc($result2);
			$total = $row2['c'];
			$count = $total;
			if ($from + $pp < $total){
				$cond = $pageoffset + $pp;
			}else{
				$cond = $total;
			}
			$title = translate_inline("Title");
			$author = translate_inline("Author");
			$category=translate_inline("Category");
			$date=translate_inline("Date");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td>$title</td><td>$author</td><td>$category</td><td>$date</td></tr>");
			if (db_num_rows($result)>0){
			//	for($i = $pageoffset; $i < $cond && $count; $i++) {
				//	$row = db_fetch_assoc($result);
				$i=0;
				while ($row=db_fetch_assoc($result)) {
					if ($row['name']=='') $row['name']=translate_inline("Anonymous");
					$i++;
					rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
					rawoutput("<a href='runmodule.php?module=library&op=player&subop=read&id={$row['bookid']}'>");
					output_notl("%s",stripslashes($row['title']));
					rawoutput("</a>");
					addnav("","runmodule.php?module=library&op=player&subop=read&id={$row['bookid']}");
					rawoutput("</td><td>");
					output_notl("`&%s`0",$row['name']);
					rawoutput("</td><td>");
					output_notl("`&%s`0",translate_inline($row['category']));
					rawoutput("</td><td>");
					output_notl("`&%s`0",$row['date']);
					rawoutput("</td></tr>");
				}
			}
			rawoutput("</table>");
	debug("t:$total p:$pp");
			if ($total>$pp){
				addnav("Pages");
				for ($p=0;$p<$total;$p+=$pp){
					addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=library&op=player$navstring&page=".($p/$pp+1));
				}
			}

		}

			

?>
