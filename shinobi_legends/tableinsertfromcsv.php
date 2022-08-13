<?php


function tableinsertfromcsv_getmoduleinfo() {
	$info = array(
	    "name"=>"Insert into tables from CSV",
		"description"=>"This module YOMs for help in a comment section to all online moderators",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Administrative",
		"download"=>"",
		"prefs" => array(
			"hasaccess"=>"Has this user access,bool|0",
			"statements"=>"Yes means user uses REPLACE INTO while no means he has only INSERT INTO,bool|0",
			),
		);
    return $info;
}

function tableinsertfromcsv_install() {
	module_addhook_priority("superuser",50);
	return true;
}

function tableinsertfromcsv_uninstall() {
	return true;
}


function tableinsertfromcsv_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
	case "superuser":
		if (get_module_pref("hasaccess") || ($session['user']['superuser']&SU_EDIT_USERS)==SU_EDIT_USERS) {
			addnav("Insert csv statements","runmodule.php?module=tableinsertfromcsv");
		}
	break;
	}
	return $args;
}

function tableinsertfromcsv_run(){
	global $session;
	page_header("Insert CSV");
	$op=httpget('op');
	$statements=get_module_pref("statements");
	require_once("lib/superusernav.php");
	superusernav();
	switch ($op) {
		case "insert":
			require_once("lib/pullurl.php");
			$file=pullurl(httppost('file'));
			$sql = "DESCRIBE " . db_prefix(httppost('table'));
			$result = db_query($sql);
			if (db_num_rows($result)<1) {
				output("Error while fetching the table fields.");
				break;
			}
			while ($row = db_fetch_assoc($result)) {
				$fields[]=$row['Field'];
			}
			//debug($fields); //currently not used
			$sql=($statements?"REPLACE INTO":"INSERT INTO")." ".db_prefix(httppost('table'))." VALUES ";
			while (list($key,$val)=each($file)) {
				$statement=explode(";",$val);
				if ($key>0) $sql.=",";
				$sql.="(";
				$num=count($statement);
				for ($i=0;$i<$num;$i++) {
					$row=array_shift($statement);
					if (!is_numeric ($row))
						$sql.="'".addslashes($row)."',";
						else
						$sql.=$row.",";
				}
				$sql=substr($sql,0,strlen($sql)-1);
				$sql.=")";
			}
			$sql.=";";
			output_notl($sql);
			$result=db_query($sql);
			$res=($result?"`2Okay":"`\$Failed");
			output_notl("Result: %s ",translate_inline($res));

			break;
		default:
			output("`2This module let's you insert statements from a csv file into a table. specify the table you want to have executed below.");
			output("Also note that you the statements will be REPLACE INTO or INSERT INTO according to the admin rights you were given.");
			output("`nYou have currently permission for: `\$%s`2",($statements?"REPLACE INTO":"INSERT INTO"));
			output_notl("`n`n");
			rawoutput("<form action='runmodule.php?module=tableinsertfromcsv&op=insert' method='post'>");
			addnav("", "runmodule.php?module=tableinsertfromcsv&op=insert");
			output("Please the URL of the CSV file (it must be readable):`n");
			rawoutput("<input type='input' name='file'><br>");
			output("Please the table you want to take care of:`n");
			rawoutput("<input type='input' name='table'><br>");
			rawoutput("<br><input type='submit' value='". translate_inline("Execute") ."' class='button'></form>");
			break;
	}
	page_footer();
}

?>
