<?php
require_once("lib/addnews.php");

function kneipenerweiterung_brett_run_private($args){
	global $session;
	$from = "runmodule.php?module=kneipenerweiterung";
		page_header("Das schwarze Brett");
		switch($args){
			case "del":
    			output("Du reisst deine eigene Nachricht vom schwarzen Brett ab. Der Fall hat sich für dich erledigt.");
           		set_module_pref("Tage",0);
           		set_module_pref("Nachricht","");
 				addnav("Neue Nachricht","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add1");
			break;	
			// für Superuser
			case "del2":
    			require_once("lib/systemmail.php");
			
				output("Die Nachricht wurde entfernt und der Spieler via Systemmail benachrichtigt.");
    			$uid = $_GET[uid];
           		set_module_pref("Tage",0,"kneipenerweiterung",$uid);
           		set_module_pref("Nachricht","","kneipenerweiterung",$uid);
				systemmail($uid, "`0Nachricht gelöscht","`0Ein Administrator hat deine Nachricht vom schwarzen Brett genommen.`n`nWenn du darüber diskutieren willst, wende dich an ".$session[user][name]."`0, oder benutze bitte den Link zur Hilfeanfrage.");
				addnav("Neue Nachricht","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add1");
 				if ($session['user']['superuser']&~SU_DOESNT_GIVE_GROTTO) {
 					addnav("G?Zurück zur Grotte","superuser.php");
					addnav("W?Zurück zum Weltlichen","village.php");
				}
			break;	
			case "add1":
				$preis_mod=get_module_setting("preis_mod", "kneipenerweiterung");
				$msgprice=$session[user][level]*$preis_mod;
        		$msgdays=(int)getsetting("daysperday",4);
			    output("\"`%Du möchtest eine Nachricht am schwarzen Brett hinterlassen, ja? Wie lang soll die Nachricht denn dort zu sehen sein?`0\" fragt dich Cedrik fordernd und nennt die Preise.");
			    $altenachricht = get_module_pref("Tage", "kneipenerweiterung", $session[user][acctid]);
			    if ($altenachricht>0) output("`nEr macht dich noch darauf aufmerksam, dass er deine alte Nachricht entfernen wird, wenn du jetzt eine neue anbringen willst.");
			    addnav("$msgdays Tage (`^$msgprice`0 Gold)","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add2&amt=$msgdays");
			    addnav("".($msgdays*2)." Tage (`^".($msgprice*2)."`0 Gold)","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add2&amt=$msgdays*2");
				addnav("".($msgdays*3)." Tage (`^".($msgprice*3)."`0 Gold)","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add2&amt=$msgdays*3");			    
				addnav("".($msgdays*7)." Tage (`^".($msgprice*7)."`0 Gold)","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add2&amt=$msgdays*7");
				addnav("".($msgdays*14)." Tage (`^".($msgprice*14)."`0 Gold)","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add2&amt=$msgdays*14");
			break;
			case "add2":
				$preis_mod=get_module_setting("preis_mod", "kneipenerweiterung");
    			$msgprice=$session[user][level]*$preis_mod*(int)$_GET[amt];
				output("Cedrik kramt einen Zettel und einen Stift unter der Theke hervor und schaut dich fragend an, was er für dich schreiben soll. Offenbar ");
        		output("sind viele seiner Kunden der Kunst des Schreibens nicht mächtig. \"`%Das macht dann `^$msgprice`% Gold. Wie soll die Nachricht lauten?`0\"`n`n");			
    			output("<form action=\"runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add3&amt=$_GET[amt]\" method='POST'>",true);
    		    output("`nGib deine Nachricht ein:`n<input name='msg' maxlength='250' size='50'>`n",true);
        		output("<input type='submit' class='button' value='Ans schwarze Brett'>",true);
	            addnav("","runmodule.php?module=kneipenerweiterung&op=schwarzesbrett&act=add3&amt=$_GET[amt]");
			break;
			case "add3":
				$tage=(int)$_GET[amt];
				$preis_mod=get_module_setting("preis_mod", "kneipenerweiterung");
				$msgprice=$session[user][level]*$preis_mod*$tage;	
				if ($session[user][gold]<$msgprice){
				    output("Als Cedrik bemerkt, dass du offensichtlich nicht genug Gold hast, schnauzt er dich an: \"`%So kommen wir nicht ins Geschäft, Kleine".($session[user][sex]?"":"r").". Sieh zu, dass du Land gewinnst. Oder im Lotto.`0\"");
				}else{        
    	            output("Mürrisch nimmt Cedrik dein Gold, schreibt deinen Text auf den Zettel und ohne ihn nochmal durchzulesen, heftet er ihn zu den anderen an das schwarze Brett neben der Eingangstür.");
            		$nachricht=stripslashes($_POST[msg]);
            		set_module_pref("Tage",$tage);
            		set_module_pref("Nachricht",$nachricht);
            		$session[user][gold] -= $msgprice;
        		}
        	break;
    	}	
		addnav("Zurück zur Schenke","inn.php");
		page_footer();
    }

?>