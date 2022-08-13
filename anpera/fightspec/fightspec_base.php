<?php

function fightspec_base_getmoduleinfo()
{
	$info = array(
		"name"=>"Kampfspezialisierung - Grundkampfarten",
		"category"=>"Kampfspezialisierung",
		"author"=>"`QEliwood",
		"version"=>"0.6",
		"download"=>"http://eliwood.dyndns.org/board/download.php?id=4",
		"requires"=>array(
      "elis_fightspec"=>"Beta|By Eliwood, http://dragonprime.net/users/Eliwood/fightspec.zip"
		),
		"settings"=>array(
      "Grundkampfarten (Schwertkampf & Axtkampf),title",
      "schwert"=>"Kann man den Schwertkampf ausw�hlen?,bool|1",
      "axt"=>"Kann man den Axtkampf ausw�hlen?,bool|1"
		)
		);
	return $info;
}

function fightspec_base_install()
{
	module_addhook("chosefightspec");
	module_addhook("setfightspec");
	module_addhook("dragonkill");
	return true;
}

function fightspec_base_uninstall()
{
	return true;
}

function fightspec_base_dohook($hookname,$args)
{
	global $session;
	switch($hookname)
	{
		case "dragonkill":
			set_module_pref("fightspec","Unbekannt");
			break;
		case "chosefightspec":
			/* Schwertkampf */
			if(get_module_setting("schwert") == 1)
			{
			   rawoutput("<a href='newday.php?setfightspec=Schwertkampf$resline'>");
			   output("`&Mit dem Schwert als ein Schwertk�mpfer (`#Schwertkampf`&)`n");
			   rawoutput("</a>");
			   addnav("","newday.php?setfightspec=Schwertkampf$resline");
			   addnav("Schwertkampf","newday.php?setfightspec=Schwertkampf$resline");
      }
      if(get_module_setting("axt") == 1)
			/* Axtkampf */
			{
			   rawoutput("<a href='newday.php?setfightspec=Axtkampf$resline'>");
			   output("`&Mit der Axt als ein rebuster Axtk�mpfer (`#Axtkampf`&)`n");
			   rawoutput("</a>");
			   addnav("","newday.php?setfightspec=Axtkampf$resline");
			   addnav("Axtkampf","newday.php?setfightspec=Axtkampf$resline");
			}
			break;
		case "setfightspec":
			tlschema("fightspec");
			/* Schwertkampf */
			if(get_module_pref("fightspec","elis_fightspec")==translate_inline("Schwertkampf"))
			{
				output("`&Dein Schwert ist dein ein und alles.");
				output(" Jeden Morgen polierst du deine Klinge und mindestens einmal im Monat sch�rfst du sie.`n");
				output("Deine Feinde erzittern vor deinen verschiedenen Schwertern, denn egal ob Breitschwert, Langschwert, Kurzschwert, "
					  ."Katana, Rapier oder sonst was, du weisst, wie man ein Schwert zu f�hren hat!");
			}
			/* Axtkampf */
			if(get_module_pref("fightspec","elis_fightspec")=="Axtkampf")
			{
				output("Ob einbl�ttrige oder zweibl�ttrige Axt, ob schwer oder leicht, du spaltest trotzdem jeden Baustamm und ");
				output("schaffst es, jeden Gegner zu erledigen.`n");
				output("Dein Gut trainierter K�rper ist robuster als die der anderen, denn eine schwere Axt erfordert einen starken K�rper.");
			}
			break;
	}
	return $args;
}
?>
