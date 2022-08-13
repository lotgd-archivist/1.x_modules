<?php

function kneipenerweiterung_hooks_dohook_private($hookname,$args){
	global $session;
	
	switch($hookname){
		case "newday":
			set_module_pref("getrunken", 0, "kneipenerweiterung");
			set_module_pref("bezahlt", 0, "kneipenerweiterung");
		break;		
		// Noch die Option für das Board hinzufügen
		case "header-inn":
			$op = httpget("op");
			$act = httpget("act");
			if($op=="bartender" && $act == "") {
				tlschema("inn");
				addnav("Cedrik");
				tlschema();
				addnav("Schwarzes Brett","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add1");
			}
		break;
		case "inn":
			//Der Säufertod
			$drunk=get_module_pref("drunkeness","drinks");
			if ($drunk >= 99) redirect("runmodule.php?module=kneipenerweiterung&op=auswirkung");	
		break;	
		case "newday-runonce":
			// Das Löschen alter Boardnachrichten
			// Tageszähler bei allen Nachrichten um eins heruntersetzen
			$sql1 = "UPDATE module_userprefs SET value=value-1 WHERE modulename='kneipenerweiterung' AND setting='Tage'";
    		$result1 = db_query($sql1) or die(db_error(LINK));
    		$rows1 = db_num_rows($result1);
    		debuglog("(Kneipenerweiterung) UPDATE : ".$rows1." Einträge aktualisiert");
			// SQL-Delete Statement aufbauen 
    		$sql1 = "SELECT userid FROM module_userprefs WHERE modulename='kneipenerweiterung' AND setting='Tage' AND value<1";
			$result1 = db_query($sql1) or die(db_error(LINK));
			$rows1 = db_num_rows($result1);
			$sql2 = "DELETE FROM module_userprefs WHERE modulename='kneipenerweiterung' AND ( setting='Tage' OR setting='Nachricht') AND userid IN (";
			for ($i=0;$i<$rows1;$i++){
				$col = db_fetch_assoc($result1);
				$thisuserid = $col[userid];
				$sql2 .= $thisuserid;
				if (($i+1)<$rows1) { $sql2 .=", "; }
				}
			$sql2 .= ");";
			// Alte Nachrichten entfernen
			if ($rows1>0) {
				$result1 = db_query($sql2) or die(db_error(LINK));
			}
    		debuglog("(Kneipenerweiterung) DELETE : ".$rows1." Einträge in module_userprefs gelöscht");
		break;
		case "inn-desc":
	   		// Das Board
			$sql1 = "SELECT userid FROM module_userprefs WHERE modulename='kneipenerweiterung' AND setting='Tage' AND value>0 ORDER BY value DESC";
    		$result1 = db_query($sql1) or die(db_error(LINK));
    		$rows1 = db_num_rows($result1);
    		if ($rows1<=0){
        		output("`nAm schwarzen Brett neben der Tür ist nicht eine einzige Nachricht zu sehen.");
    		}else{
			  	output("`nAm schwarzen Brett neben der Tür flattern einige Nachrichten im Luftzug:");
			  	
		 		for ($i=0;$i<$rows1;$i++){
					$row1 = db_fetch_assoc($result1);
					$thisuserid = $row1[userid];
					$sql2 = "SELECT value FROM module_userprefs WHERE modulename='kneipenerweiterung' AND setting='Nachricht' AND userid=".$thisuserid;
					$result2 = db_query($sql2) or die(db_error(LINK));
					$rows2 = db_num_rows($result2);
					$row2 = db_fetch_assoc($result2);
					$sql3 = "SELECT name FROM accounts WHERE acctid=".$thisuserid;
					$result3 = db_query($sql3) or die(db_error(LINK));

					$row3 = db_fetch_assoc($result3);
					
					$nachricht = $row2[value];   
					$username = $row3[name];
					
 					output("`n`n<a href=\"mail.php?op=write&to=".$username."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".$username."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
 					output("`& $username`&:`n`^$nachricht`0");
		       		if ($thisuserid==$session[user][acctid]){
                		output("[<a href='runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=del'>entfernen</a>]",true);
                		addnav("","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=del");
            		}
            		if ($session['user']['superuser']&~SU_DOESNT_GIVE_GROTTO) {
	            		output("[<a href='runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=del2&uid=$thisuserid'>(SU)entfernen`0</a>]",true);
                		addnav("","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=del2&uid=$thisuserid");
            		}
        		}
			}
	
		break;	
		case "ale":
			//Der Säufertod
			$drunk=get_module_pref("drunkeness","drinks");
			if ($drunk >= 99) redirect("runmodule.php?module=kneipenerweiterung&op=auswirkung");	
		
			//Runde schmeißen
			$bezahlt=get_module_setting("bezahlt", "kneipenerweiterung");
			if ($bezahlt != 0){
				$getrunken=get_module_pref("getrunken", "kneipenerweiterung");
				output("`n`2Auf der Theke %s ... ", ($bezahlt==1?"steht nur noch `^ein einziges`2 leckeres Freiale":"stehen noch `^$bezahlt`2 leckere Freiales"));
				if ($getrunken == 0){
					blocknav("runmodule.php?module=drinks&act=buy&id=1");
					addnav("`#Freiale trinken!`0", "runmodule.php?module=kneipenerweiterung&op=trinken");
				}else output("aber Cedrick hat ein waches Auge darauf und würde es nicht zulassen, dass Du Dir noch eins nimmst.");
			}else{
				$ausgegeben=get_module_pref("ausgegeben", "kneipenerweiterung");
				if ($ausgegeben == 0) addnav("Eine Runde Ale ausgeben!", "runmodule.php?module=kneipenerweiterung&op=ausgeben");
				else output("`nNoch eine Runde Ale kannst Du heute nicht ausgeben. Cedrick will den anderen auch eine Chance dazu geben.");
			}
		break;
	}
return $args;
}	
?>