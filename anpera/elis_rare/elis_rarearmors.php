<?php
#-----------------------#
#	~~Rare armors~~	      #
#	3. Aug 2005, MESZ	    #
#	by Eliwood			      #
#-----------------------#

function elis_rarearmors_getmoduleinfo()
{
	$info = array(
		"name"=>"Seltene R�stungen",
		"category"=>"Eliwoods Module",
		"author"=>"`QEliwood",
		"version"=>"1.3",
		"settings"=>array(
      "Generelle Einstellungen,title",
      "hasrare"=>"Seltene R�stungen kaufen,bool|0",
			"chance1"=>"Welche Zahl muss gew�rfelt werden?,int|18",
			"chance2"=>"Eine Zufallszahl zwischen 1 und wieviel muss obige Zahl ergeben?,int|20",
			"Name der R�stungen,title",
			"armor1"=>"R�stung 1|Gift�berzogener Brustpanzer",
			"armor2"=>"R�stung 2|Heilige Kopfbedeckung",
			"armor3"=>"R�stung 3|Geweihter Schild",
			"armor4"=>"R�stung 4|Edelsteinverzierter Panzer",
			"armor5"=>"R�stung 5|Schild der Dunkelheit",
			"armor6"=>"R�stung 6|Scharf geschliffene Handschuhe aus Eisen",
			"armor7"=>"R�stung 7|Helm der Finsternis",
			"armor8"=>"R�stung 8|Handschuhe aus Titan",
			"armor9"=>"R�stung 9|Kapmfengeluniform",
			"armor10"=>"R�stung 10|Mumbane",
			"armor11"=>"R�stung 11|Mystischer Schild",
			"armor12"=>"R�stung 12|Geweihte Hosen der G�tter",
			"armor13"=>"R�stung 13|Eisernes Shirt",
			"armor14"=>"R�stung 14|Antimagische R�stung",
			"armor15"=>"R�stung 15|Mystischer Schild der alten Magie"
		)
	);
	return $info;
}

function elis_rarearmors_install()
{
	module_addhook("newday-runonce");
	module_addhook("modify-armor");
	module_addhook("newday");
	return true;
}

function elis_rarearmors_uninstall()
{
	return true;
}

function elis_rarearmors_dohook($hookname, $args)
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
				output("`#Pegasus hat heute `^seltene`# und `^starke`# R�stungen im Angebot!");
				rawoutput("<br>");
			}
			break;
		case "modify-armor":
			if(get_module_setting("hasrare"))
			{
        $id = $args['armorid'];
        switch($args['defense']):
					case 1:
						$args = array(
							"defense"=>16,
							"armorname"=>translate_inline(get_module_setting("armor1")),
							"value"=>25000,
							"armorid"=>$id
						);
						break;
					case 2:
						$args = array(
							"defense"=>17,
							"armorname"=>translate_inline(get_module_setting("armor2")),
							"value"=>36000,
							"armorid"=>$id
						);
						break;
					case 3:
						$args = array(
							"defense"=>18,
							"armorname"=>translate_inline(get_module_setting("armor3")),
							"value"=>47500,
							"armorid"=>$id
						);
						break;
					case 4:
						$args = array(
							"defense"=>19,
							"armorname"=>translate_inline(get_module_setting("armor4")),
							"value"=>60000,
							"armorid"=>$id
						);
						break;
					case 5:
						$args = array(
							"defense"=>20,
							"armorname"=>translate_inline(get_module_setting("armor5")),
							"value"=>84300,
							"armorid"=>$id
						);
						break;
					case 6:
						$args = array(
							"defense"=>21,
							"armorname"=>translate_inline(get_module_setting("armor6")),
							"value"=>95000,
							"armorid"=>$id
						);
						break;
					case 7:
						$args = array(
							"defense"=>22,
							"armorname"=>translate_inline(get_module_setting("armor7")),
							"value"=>107000,
							"armorid"=>$id
						);
						break;
					case 8:
						$args = array(
							"defense"=>23,
							"armorname"=>translate_inline(get_module_setting("armor8")),
							"value"=>117000,
							"armorid"=>$id
						);
						break;
					case 9:
						$args = array(
							"defense"=>24,
							"armorname"=>translate_inline(get_module_setting("armor9")),
							"value"=>132000,
							"armorid"=>$id
						);
						break;
					case 10:
						$args = array(
							"defense"=>25,
							"armorname"=>translate_inline(get_module_setting("armor10")),
							"value"=>157000,
							"armorid"=>$id
						);
						break;
					case 11:
						$args = array(
							"defense"=>26,
							"armorname"=>translate_inline(get_module_setting("armor11")),
							"value"=>176000,
							"armorid"=>$id
						);
						break;
					case 12:
						$args = array(
							"defense"=>27,
							"armorname"=>translate_inline(get_module_setting("armor12")),
							"value"=>210500,
							"armorid"=>$id
						);
						break;
					case 13:
						$args = array(
							"defense"=>28,
							"armorname"=>translate_inline(get_module_setting("armor13")),
							"value"=>234000,
							"armorid"=>$id
						);
						break;
					case 14:
						$args = array(
							"defense"=>29,
							"armorname"=>translate_inline(get_module_setting("armor14")),
							"value"=>250500,
							"armorid"=>$id
						);
						break;
					case 15:
						$args = array(
							"defense"=>30,
							"armorname"=>translate_inline(get_module_setting("armor15")),
							"value"=>300000,
							"armorid"=>$id
						);
						break;
				endswitch;
			}
			break;
	endswitch;
	return $args;
}
?>
