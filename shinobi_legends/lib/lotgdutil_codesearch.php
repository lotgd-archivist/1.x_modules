<?php
	$searchstr = $_POST['searchstr'];
	require_once("lib/dump_item.php");
	page_header("Code Search");
	output("`c`@Search Active Modules for string.`c");
	if ($op == "list"){
		$sql = "SELECT modulename FROM ".db_prefix("modules")." WHERE active = 1 ORDER BY modulename";
		$result = db_query($sql);
		for($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("%s, ",$row['modulename']);
		}
		addnav("Search Code","runmodule.php?module=lotgdutil&mode=codesearch");
		addnav("Back to the grotto","superuser.php");
	}else{
	if ($searchstr == ""){
		output("What would you like to search for?`n");
			$linkcode="<form action='runmodule.php?module=lotgdutil&mode=codesearch' method='POST'>";
			output("%s",$linkcode,true);
			$linkcode="<p><input type=\"text\" name=\"searchstr\" size=\"37\"></p>";
			output("%s",$linkcode,true);
			$linkcode="<p><input type=\"submit\" value=\"Submit\" name=\"B1\"><input type=\"reset\" value=\"Reset\" name=\"B2\"></p>";
			output("%s",$linkcode,true);
			$linkcode="</form>";
			output("%s",$linkcode,true);
			addnav("","runmodule.php?module=lotgdutil&mode=codesearch");
			addnav("List Active Modules","runmodule.php?module=lotgdutil&mode=codesearch&op=list");
	}else{
		$searchstr = stripslashes($searchstr);
		//open all files and search for string
		$sql = "SELECT modulename FROM ".db_prefix("modules")." WHERE active = 1";
		$result = db_query($sql);
		for($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$mods[$i]=$row['modulename'];
		}
		//open files and scan
		output("Files Containing %s.`n`n",$searchstr);
		for($i=0;$i<count($mods);$i++){
			if (file_exists('modules/'.$mods[$i].'.php')){
				$found = 0;
				$filecontents = fopen("modules/".$mods[$i].".php", "r");
				while(!feof($filecontents)){
					$currentline = fgets($filecontents,255);
					if (strstr($currentline,$searchstr)) $found = 1;
				}
				if ($found == 1){
					if ($session['user']['superuser'] & SU_VIEW_SOURCE){
						$dir = preg_replace("/[?].*/","",($_SERVER['REQUEST_URI']));
						$dir = str_replace("runmodule.php","",$dir);
						output("<a href=\"source.php?url=%smodules/%s.php\" target=\"_blank\"> %s `n",$dir,$mods[$i],$mods[$i],true);
					}else{
						output("%s`n",$mods[$i]);
					}
				}
			}
		}
		addnav("Search Again","runmodule.php?module=lotgdutil&mode=codesearch");
	}
	addnav("Back to the grotto","superuser.php");
	}
?>