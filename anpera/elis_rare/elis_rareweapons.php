<?php
#-----------------------#
#	~~Rare Weapons~~	    #
#	3. Aug 2005, MESZ	    #
#	by Eliwood			      #
#-----------------------#

function elis_rareweapons_getmoduleinfo()
{
	$info = array(
		"name"=>"Seltene Waffen",
		"category"=>"Eliwoods Module",
		"author"=>"`QEliwood",
		"version"=>"1.2",
		"settings"=>array(
      "Generelle Einstellungen,title",
      "hasrare"=>"Seltene Waffen kaufen,bool|0",
			"chance1"=>"Welche Zahl muss gewürfelt werden?,int|18",
			"chance2"=>"Eine Zufallszahl zwischen 1 und wieviel muss obige Zahl ergeben?,int|20",
			"Waffennamen,title",
			"weapon1"=>"Waffe 1|Giftiger Speer",
			"weapon2"=>"Waffe 2|Heilige Lanze",
			"weapon3"=>"Waffe 3|Schwert der Dunkelheit",
			"weapon4"=>"Waffe 4|Edelsteinverziertes Kurzschwert",
			"weapon5"=>"Waffe 5|Breitschwert aus Titan",
			"weapon6"=>"Waffe 6|Axt der Kampfengel",
			"weapon7"=>"Waffe 7|Dolch der Grossen Fee",
			"weapon8"=>"Waffe 8|Bogen der Quintessenz",
			"weapon9"=>"Waffe 9|Parasitäres Langschwert",
			"weapon10"=>"Waffe 10|Langbogen des Feuers",
			"weapon11"=>"Waffe 11|Stab der Ungerechten",
			"weapon12"=>"Waffe 12|Excalibur",
			"weapon13"=>"Waffe 13|Schwert der Geister",
			"weapon14"=>"Waffe 14|Mysthische Axt Armads",
			"weapon15"=>"Waffe 15|Mysthisches Schwert Durandal"
		)
	);
	return $info;
}

function elis_rareweapons_install()
{
	module_addhook("newday-runonce");
	module_addhook("modify-weapon");
	module_addhook("newday");
	return true;
}

function elis_rareweapons_uninstall()
{
	return true;
}

function elis_rareweapons_dohook($hookname, $args)
{
	switch($hookname):
		case "newday-runonce":
      output(get_module_setting("chance1").get_module_setting("chance2"));
      if(e_rand(1,(int)get_module_setting("chance2")) == (int)get_module_setting("chance1"))
				set_module_setting("hasrare",true);
      else
        set_module_setting("hasrare",false);
      break;
		case "newday":
			if(get_module_setting("hasrare")==true)
			{
				rawoutput("<br>");
				output("`#MightyE hat heute `^seltene`# und `^starke`# Waffen im Angebot!");
				rawoutput("<br>");
			}
			break;
		case "modify-weapon":
			if(get_module_setting("hasrare"))
			{
        $id = $args['weaponid'];
        switch($args['damage']):
          case 1:
            $args = array(
							"damage"=>16,
							"weaponname"=>translate_inline(get_module_setting("weapon1")),
							"value"=>25000,
							"weaponid"=>$id
						);
						break;
					case 2:
						$args = array(
							"damage"=>17,
							"weaponname"=>translate_inline(get_module_setting("weapon2")),
							"value"=>36000,
							"weaponid"=>$id
						);
						break;
					case 3:
						$args = array(
							"damage"=>18,
							"weaponname"=>translate_inline(get_module_setting("weapon3")),
							"value"=>47500,
							"weaponid"=>$id
						);
						break;
					case 4:
						$args = array(
							"damage"=>19,
							"weaponname"=>translate_inline(get_module_setting("weapon4")),
							"value"=>60000,
							"weaponid"=>$id
						);
						break;
					case 5:
						$args = array(
							"damage"=>20,
							"weaponname"=>translate_inline(get_module_setting("weapon5")),
							"value"=>84300,
							"weaponid"=>$id
						);
						break;
					case 6:
						$args = array(
							"damage"=>21,
							"weaponname"=>translate_inline(get_module_setting("weapon6")),
							"value"=>95000,
							"weaponid"=>$id
						);
						break;
					case 7:
						$args = array(
							"damage"=>22,
							"weaponname"=>translate_inline(get_module_setting("weapon7")),
							"value"=>107000,
							"weaponid"=>$id
						);
						break;
					case 8:
						$args = array(
							"damage"=>23,
							"weaponname"=>translate_inline(get_module_setting("weapon8")),
							"value"=>117000,
							"weaponid"=>$id
						);
						break;
					case 9:
						$args = array(
							"damage"=>24,
							"weaponname"=>translate_inline(get_module_setting("weapon9")),
							"value"=>132000,
							"weaponid"=>$id
						);
						break;
					case 10:
						$args = array(
							"damage"=>25,
							"weaponname"=>translate_inline(get_module_setting("weapon10")),
							"value"=>157000,
							"weaponid"=>$id
						);
						break;
					case 11:
						$args = array(
							"damage"=>26,
							"weaponname"=>translate_inline(get_module_setting("weapon11")),
							"value"=>176000,
							"weaponid"=>$id
						);
						break;
					case 12:
						$args = array(
							"damage"=>27,
							"weaponname"=>translate_inline(get_module_setting("weapon12")),
							"value"=>210500,
							"weaponid"=>$id
						);
						break;
					case 13:
						$args = array(
							"damage"=>28,
							"weaponname"=>translate_inline(get_module_setting("weapon13")),
							"value"=>234000,
							"weaponid"=>$id
						);
						break;
					case 14:
						$args = array(
							"damage"=>29,
							"weaponname"=>translate_inline(get_module_setting("weapon14")),
							"value"=>250500,
							"weaponid"=>$id
						);
						break;
					case 15:
						$args = array(
							"damage"=>30,
							"weaponname"=>translate_inline(get_module_setting("weapon15")),
							"value"=>300000,
							"weaponid"=>$id
						);
						break;
				endswitch;
			}
			break;
	endswitch;
	return $args;
}
?>
