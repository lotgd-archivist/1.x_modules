<?php

function susanoo_getmoduleinfo()
{
	$info = array(
	"name"=>"Susanoo, Gyururu Memorial",
	"author"=>"`2Oliver Brendel",
	"version"=>"1.0",
	"category"=>"Extraordinary Abilities",
	"download"=>"",
	"settings"=>array(
		"Susanoo Gyururu Memorial - Preferences, title",
		"Get and set curse seal (2 Levels),note",
		//"name"=>"Name of the seal,text|`xS`lusan`)oo",
		"name"=>"Name of the seal,text|`xE`lternal `xM`langekyō `xS`lharingan`J (永遠の万華鏡写輪眼)",
		"clanname"=>"Name of the clan in colour,text|`!U`Jchi`lcha",
		"reset_uchiha"=>"How many resets in Uchiha to get this?,range,1,5,1|2",
		"extrauses"=>"How many uses per specialty does it grant per newday?,range,1,15,1|2",
		"dk"=>"How many dks before you can see it at the vampire,floatrange,2,55,1|12",
		"Note: The cost is only deducted if the survives the seal and is equal to the vampirelords cost setting,note",
		"survival"=>"Out of x how many survive the procedure?,floatrange,1,25,1|12",
		"level2"=>"How many days has he to train successfully with the Gyu in order to get Level 2?,floatrange,1,25,1|12",
		"gyururu"=>"Name of the Gyu,text|`4Sensatsu no Gyururu",
		"locationfive"=>"Where are the Gyu 5 training?,location",
	),
	"prefs" => array(
		"hasseal"=>"Has this user a curse seal(0=no 1=level 1 and 2=level2)?,int",
		"days"=>"How many days does he have it?,int|0",
		"sparring"=>"How many times successfully trained with the Sound 5,int|0",
		"todaylevel2"=>"How often used Level 2 today?,int|0",
		),
	"requires"=>array(
		"vampirelord"=>"1.1|Mike Counts, conversion by XChrisX, translated back by `2Oliver Brendel",
		"circulum_uchiha"=>"1.0|`2Oliver Brendel",
		),
	);
	return $info;
}
function susanoo_install() {
	module_addhook_priority("bioinfo",20);
	module_addhook_priority("vampirelord_offering",60);
	module_addhook_priority("fightnav-specialties",100);
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addeventhook("forest", "require_once(\"modules/susanoo/chance.php\"); return susanoo_chance(\"forest\");");
	return true;
}
function susanoo_uninstall() {
	return true;
}

function susanoo_dohook($hookname,$args) {
	global $session;
	$gyu=get_module_setting("gyururu");
	$name=get_module_setting("name");
	$skill="susanoo";
	switch($hookname) {
		case "bioinfo":
			$seal=get_module_pref("hasseal","susanoo",$args['acctid']);
			if ($seal>0) {
				$name=get_module_setting('name');
				if ($seal>1) $name.=translate_inline('`) Level '.$seal);
				output("`^Advanced Kekkei Genkai: %s`^`n",$name);
			}
			break;
		case "vampirelord_offering":
			if (susanoo_get()!=1) break;
			if (get_module_setting("dk")>$session['user']['dragonkills']) break;
			if (get_module_pref("hasseal")>=1) break;
			
			output("`n`n`7The power is very tempting. What carrier of the `7%s`7... it can only be `\$%s`7!`n",$name,$gyu);
			if (get_module_pref("hasseal")<0) output(" You have tried the surgery before... maybe luck will be on your side, maybe not...");
			addnav(array("Offer %s hitpoints for contact to %s", $args['cost'],$gyu),"runmodule.php?module=susanoo&op=offer");
			break;
/*		case "apply-specialties":
			require("modules/susanoo/apply.php");
//integrated in specialtysystem_kekkei_genkai_uchiha.php
*/			break;
		case "fightnav-specialties":
			$seals=get_module_pref("hasseal");
			if ($seals>=1) {
				
				/* if (!has_buff('curseseal1')) addnav(array("%s`) Level 1",$sealname),$args['script']."op=fight&skill=$skill&l=1");
				if ($seals==2 && has_buff('curseseal1') && !has_buff('curseseal2')) {
					if ($session['user']['maxhitpoints']>$session['user']['level']*10) {
						addnav(array("%s`) Level 2",$sealname),$args['script']."op=fight&skill=$skill&l=2");
					} else {
						addnav(array("%s`) Level 2 (Body too fragile!)",$sealname),"");
					}
				} */
				}
			break;
		case "newday":
			if (get_module_pref('hasseal')>0) {
				increment_module_pref("days",1,"susanoo");
				set_module_pref("todaylevel2",0,"susanoo");
				$extrauses = get_module_setting("extrauses");
				// give extra uses
						if (is_module_active("specialtysystem")) {
							require_once("modules/specialtysystem/functions.php");
							//$val=-specialtysystem_availableuses();
							$val = -$extrauses;
							increment_module_pref("uses",$val,"specialtysystem");
						}
/*						// old, used for all specialties
						$sql="SELECT modulename FROM ".db_prefix("module_hooks")." WHERE location='choose-specialty' AND modulename!='specialtysystem';";
						$result=db_query_cached($sql,"susa_specialties");
						$add="";
						if (db_num_rows($result)<1) break; //no specialties installed...
						while ($row=db_fetch_assoc($result)) {
							$add.="'".$row['modulename']."',";
						}
						$add = substr($add,0,strlen($add)-1);
						$sql="UPDATE ".db_prefix("module_userprefs")." set value=value+$extrauses WHERE modulename IN (".$add.") AND setting='uses' AND userid=".$session['user']['acctid'].";";
						db_query($sql);
*/						output("`n`n`lDue to your mastery over %s`l, you gain %s more chakra uses for today!",$name,$extrauses);
			}
			break;
	}
	return $args;
}
function susanoo_runevent($type,$link) {
	global $session;
	require("modules/susanoo/runevent.php");
}

function susanoo_run(){
	global $session;
	require("modules/susanoo/run.php");
	page_footer();
}

function susanoo_get() {
/* 	$evilalign = get_module_setting('evilalign','alignment');
	$goodalign = get_module_setting('goodalign','alignment');
	$useralign = get_module_pref('alignment','alignment');
	//0 equals evil, 1 equals neutral, 2 equals good alignment
	if ($useralign <= $evilalign) return 0;
	if ($useralign >= $goodalign) return 2; */
	$uchiha = get_module_setting("reset_uchiha");
	$pers=get_module_pref("stack","circulum_uchiha");
	if ($uchiha<=$pers) {
		// may have it
		return 1;
	} else {
		return 0;
	}
	return 1;
}
?>
