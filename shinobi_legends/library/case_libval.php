<?php
			if (httpget('subop')=="xml"){
                header("Content-Type: text/xml");
                $sql = "SELECT content from ".db_prefix('librarybooks')." WHERE bookid=".httpget('bookid');
                $row = db_fetch_assoc(db_query($sql));
                echo "<xml>";
                echo "<name name=\"";
                echo rawurlencode(appoencode("`~".stripslashes($row['content'])));
                echo "\"/>";
              	echo "</xml>";
                exit();
            }
			addnav("Navigation");
			require_once("lib/superusernav.php");
			superusernav();
			addnav("Options");
			addnav("Add Book","runmodule.php?module=library&op=tell&su=1");
			addnav("Validate Books","runmodule.php?module=library&op=libval&validate=0");
			addnav("See all books","runmodule.php?module=library&op=libval&validate=1");
			rawoutput("<script language='JavaScript'>
function getUserInfo(id,divid){
	var filename='runmodule.php?module=library&op=libval&subop=xml&bookid='+id;
	//set up the DOM object
	var xmldom;
	if (document.implementation &&
			document.implementation.createDocument){
		//Mozilla style browsers
		xmldom = document.implementation.createDocument('', '', null);
	} else if (window.ActiveXObject) {
		//IE style browsers
		xmldom = new ActiveXObject('Microsoft.XMLDOM');
	}
		xmldom.async=false;
	xmldom.load(filename);
	var output='';
	for (var x=0; x<xmldom.documentElement.childNodes.length; x++){
		output = output + unescape(xmldom.documentElement.childNodes[x].getAttribute('name').replace(/\\+/g,' ')) +'<br>';
	}
	document.getElementById('user'+divid).innerHTML=output;
}
</script>
");
			$act = httpget('act');
			switch ($act){
				case "validate":
					$sql = "UPDATE ".db_prefix("librarybooks")." SET validated=1 WHERE bookid=$id";
					db_query($sql);
					output("Book has been Validated.`n`n");
					break;
				case "unvalidate":
					$sql = "UPDATE ".db_prefix("librarybooks")." SET validated=0 WHERE bookid=$id";
					db_query($sql);
					output("Book has been Unvalidated.`n`n");
					break;
				case "delete":
					$sql = "DELETE FROM ".db_prefix("librarybooks")." WHERE bookid=$id";
					db_query($sql);
					output("Book has been Deleted.`n`n");
					break;
				case "edit":
					$sql = "UPDATE ".db_prefix("librarybooks")." SET title='$title', content='$content', category='$category' WHERE bookid=$id";
					db_query($sql);
					output("Book has been edited.`n`n");
					break;
				case "add":
					if (httppost('anonymous')) $authid=0;
						else $authid=$session['user']['acctid'];
					$sql = "INSERT INTO ".db_prefix("librarybooks")." (authid, title, category,content) VALUES ('".$authid."', '".$title."', '".$category."', '".$content."')";
					db_query($sql);
					break;
				}
			page_header("Library Book Validation");
			addnav("Validate");
			addnav("Validate by Author ","runmodule.php?module=library&op=libval&subop=authorlist");
			//addnav("Check Categories","runmodule.php?module=library&op=libval&subop=categorylist");
			addnav("Validate Chronologically","runmodule.php?module=library&op=libval");
			addnav("Edit by Author","runmodule.php?module=library&op=libval&subop=authorlist&validate=1");
			addnav("Browse Chronologically","runmodule.php?module=library&op=libval&validate=1");
			output("`c`#You may wish to delete the books that do not have either a Title or a Content Body.`c`n`n");
			$pp = get_module_setting("pp");
			$pageoffset = (int)$page;
			if ($pageoffset > 0) $pageoffset--;
			$pageoffset *= $pp;
			$from = $pageoffset+1;
			$limit = "LIMIT $pageoffset,$pp";
			$sql = "SELECT COUNT(bookid) AS c FROM " . db_prefix("librarybooks") . "";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$total = $row['c'];
			$count = db_num_rows($result);
			if ($from + $pp < $total){
				$cond = $pageoffset + $pp;
			}else{
				$cond = $total;
			}
			$lib=db_prefix("librarybooks");
			$ac=db_prefix("accounts");
			$validate='';
			$author='';
			if (httpget('validate')==0) $validate="AND b.validated=0";
			if (httpget('author')!=0) {
				$auth=(int)httpget('author');
				if ($auth=='anon') $author="AND b.authid=0";
					else $author="AND b.authid=".$auth;
			}
			switch (httpget('subop')) {
				case "authorlist":
					$sql="SELECT b.authid AS acctid, a.name AS name, count(b.bookid) AS counter FROM ".db_prefix('accounts')." as a RIGHT JOIN ".db_prefix('librarybooks')." AS b ON a.acctid=b.authid WHERE 1 $validate GROUP BY b.authid ORDER BY a.login ASC;";
					$result=db_query($sql);
					addnav("Authors");
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
						rawoutput("<a href='runmodule.php?module=library&op=libval&validate=".((int)httpget('validate'))."&author={$row['acctid']}'>");
						output_notl("%s",stripslashes($row['name']));
						rawoutput("</a>");
						rawoutput("</td>");
						addnav("","runmodule.php?module=library&op=libval&validate=".((int)httpget('validate'))."&author={$row['acctid']}");
						addnav(array("%s (%s books)",$row['name'],$row['counter']),"runmodule.php?module=library&op=player&author=".$row['acctid']);
						if ((($i+1)%$columns)==0 && $i!=$leni) rawoutput("</tr>");
						$i++;
					}
					rawoutput("</tr></table>");					
					page_footer();
					break;
					
				case "categorylist":
					break;				
			}
			$sql = "SELECT b.category, b.title, b.authid, b.bookid, a.name, b.validated, b.content FROM $lib as b LEFT JOIN $ac as a ON b.authid = a.acctid WHERE 1 $validate $author ORDER BY bookid ASC $limit";
			$result = db_query($sql);
			$vque = translate_inline("Options");
			$validate = translate_inline("Validate");
			$unvalidate = translate_inline("Unvalidate");
			$delete = translate_inline("Delete");
			$edit = translate_inline("Edit");
			$title = translate_inline("Title");
			$author = translate_inline("Author");
			$length = translate_inline("Length");
			$text = translate_inline("Content");
			$category=translate_inline("Category");
			$val = translate_inline("Validated");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td>$vque</td><td>$title</td><td>$author</td><td>$category</td><td>$val</td><td>$length</td>");
			rawoutput("</tr>");
			if (db_num_rows($result)>0){
				$k=0;
				//for($i = $pageoffset; $i < $cond && $count; $i++) {
					while ($row = db_fetch_assoc($result)) {
					rawoutput("<tr class='trdark'><td>");
					if ($row['validated'] == 0){
						rawoutput("<a href='runmodule.php?module=library&op=libval&act=validate&id={$row['bookid']}&validate=0'>");
						output_notl($validate);
						rawoutput("</a><br>");
						addnav("","runmodule.php?module=library&op=libval&act=validate&id={$row['bookid']}&validate=0");
					}else{
						rawoutput("<a href='runmodule.php?module=library&op=libval&act=unvalidate&id={$row['bookid']}'>");
						output_notl($unvalidate);
						rawoutput("</a><br>");
						addnav("","runmodule.php?module=library&op=libval&act=unvalidate&id={$row['bookid']}");
					}
					rawoutput("<a href='runmodule.php?module=library&op=edit&id={$row['bookid']}'>");
					output_notl($edit);
					rawoutput("</a><br>");
					addnav("","runmodule.php?module=library&op=edit&id={$row['bookid']}");
					rawoutput("<a href='runmodule.php?module=library&op=libval&act=delete&id={$row['bookid']}'>");
					output_notl($delete);
					rawoutput("</a><br>");
					addnav("","runmodule.php?module=library&op=libval&act=delete&id={$row['bookid']}");
					rawoutput("</td><td>");
					output_notl("%s",stripslashes($row['title']));
					rawoutput("</td><td>");
					output_notl("`&%s`0",$row['name']);
					rawoutput("</td><td>");
					output_notl("`&%s`0",translate_inline($row['category']));					
					rawoutput("</td><td>");
					if ($row['validated']){
						$str = translate_inline("Yes");
					}else{
						$str = translate_inline("No");
					}
					output_notl("`c`@%s`c`0",$str);
					rawoutput("</td><td>");
					output_notl("%s Characters",strlen($row['content']));
					rawoutput("</td>");
					if (get_module_setting("showcon")){
						rawoutput("</tr><tr><td style='background-color:#000000;' colspan=6>");
						$text=translate_inline("Click here to view content");
						$book="runmodule.php?module=library&op=libval&subop=xml&bookid={$row['bookid']}";
						addnav("",$book);
						//in FF 3, the XML display stuff breaks...
						//rawoutput("<div id='user$k'><a href='$book' target='_blank' onClick=\"getUserInfo('{$row['bookid']}',$k); return false;\">");
//	output_notl("%s", $text, true);
						output_notl("`c`@%s`c`0",stripslashes($row['content']));
						rawoutput("</td>");
					}
					$k++;
					rawoutput("</tr>");
				}
			}
			rawoutput("</table>");
		if ($total>$pp){
			addnav("Pages");
			for ($p=0;$p<$total;$p+=$pp){
				addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=library&op=libval&validate=".((int)httpget('validate'))."&page=".($p/$pp+1));
			}
		}

?>
