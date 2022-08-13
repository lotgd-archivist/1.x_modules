<?php

function elis_fightspec_getmoduleinfo()
{
	$info = array(
		"name"=>"Kampfspezialisierung - Basismodul",
		"category"=>"Eliwoods Module",
		"author"=>"`QEliwood",
		"version"=>"0.4",
		"download"=>"http://eliwood.dyndns.org/board/download.php?id=4",
		"prefs"=>array(
      "Kampfspezialisierung - Basismodul Usereinstellungen,title",
      "`0Schreib \"`#Unbekannt`0\" oder nichts in das Feld um die Spezialisierung zurück zu setzen.,note",
      "fightspec"=>"Kampfspezialisierung|Unbekannt"
			)
		);
	return $info;
}

function elis_fightspec_install()
{
	module_addhook("newday-intercept");
	module_addhook("biostat");
	module_addhook("dragonkill");
	return true;
}

function elis_fightspec_uninstall()
{
	return true;
}

function elis_fightspec_dohook($hookname,$args)
{
	global $session,$race_unknown;
	switch($hookname)
	{
    case "dragonkill":
      set_module_pref("fightspec","Unbekannt");
      break;
    case "biostat":
      tlschema("fightspec");
      $fightspec = get_module_pref("fightspec",false,$args['acctid']);
			output("`^Kampfspezialisierung: `@%s`n",$fightspec);
			break;
		case "newday-intercept":
      $dp = count($session['user']['dragonpoints']);
$dkills = $session['user']['dragonkills'];
$pdk=httpget("pdk");

$pdkhp=httppost("hp");
$pdkff=httppost("ff");
$pdkat=httppost("at");
$pdkde=httppost("de");

if ($pdk==1){
	if ($pdkhp+$pdkff+$pdkat+$pdkde == $dkills-$dp &&
			$pdkhp>=0 && $pdkff>=0 && $pdkat >=0 && $pdkde >=0) {
		$dp += $pdkhp+$pdkff+$pdkat+$pdkde;
		$session['user']['maxhitpoints'] += (5 * $pdkhp);
		$session['user']['attack'] += $pdkat;
		$session['user']['defense'] += $pdkde;
		while($pdkhp){
			array_push($session['user']['dragonpoints'],"hp");
			$pdkhp--;
		}
		while($pdkff){
			array_push($session['user']['dragonpoints'],"ff");
			$pdkff--;
		}
		while($pdkat){
			array_push($session['user']['dragonpoints'],"at");
			$pdkat--;
		}
		while($pdkde){
			array_push($session['user']['dragonpoints'],"de");
			$pdkde--;
		}
	}else{
		output("`\$Error: Please spend the correct total amount of dragon points.`n`n");
	}
}
if (!($dp < $dkills) && (($session['user']['race']!=RACE_UNKNOWN) || ($session['user']['race']!=false)) && $session['user']['specialty']!="") {
      $fightspec = get_module_pref("fightspec");
			if($fightspec == "Unbekannt" || $fightspec == "" || $fightspec == translate_inline("Unbekannt"))
			{
				page_header("Wie kämpfst du?");
				tlschema("fightspec");
				if(!isset($_GET['setfightspec']))
				{
					modulehook("chosefightspec");
					if (navcount()==0)
					{ 
						addnav("Weiter","newday.php?continue=1$resline");
						output("`\$Uuuh... Wir haben ein Problem.`n");
						output("Der Administrator hat zwar das Kampfspezialisierungsbasismodul installiert, ");
						output("Aber keine einzige Kampfspezialsierung selbst... Wir machen dich mal zu einem Schwertkämpfer...");
						set_module_pref("fightspec","Schwertkampf");
					}
				}
				else
				{
					set_module_pref("fightspec",URLDecode($_GET['setfightspec']));
					modulehook("setfightspec");
					addnav("Weiter","newday.php?continue=1$resline");
				}
				page_footer();
			}
			}
			break;
	}
	return $args;
}
?>
