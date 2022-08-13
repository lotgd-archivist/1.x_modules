<?php

function curse_seal_getmoduleinfo()
{
	$info = array(
	"name"=>"Curse Seals",
	"author"=>"`2Oliver Brendel",
	"version"=>"1.0",
	"category"=>"Extraordinary Abilities",
	"download"=>"",
	"settings"=>array(
		"Curse Seal - Preferences, title",
		"`isupports the alignment module`i,note",
		"Get and set curse seal (2 Levels),note",
		"name"=>"Name of the seal,text|`)C`~u`)r`~s`)e `)Seal",
		"dk"=>"How many dks before you can see it at the vampire,floatrange,2,15,1|8",
		"Note: The cost is only deducted if the survives the seal and is equal to the vampirelords cost setting,note",
		"survival"=>"Out of x how many survive the procedure?,floatrange,1,25,1|10",
		"level2"=>"How many days has he to train successfully with the Sound Five in order to get Level 2?,floatrange,1,25,1|10",
		"soundfive"=>"Name of the Sound Five,text|`)S`~ou`)nd `tFi`gve",
		"locationfive"=>"Where are the Sound Five?,location",
	),
	"prefs" => array(
		"hasseal"=>"Has this user a curse seal(0=no 1=level 1 and 2=level2)?,int",
		"days"=>"How many days does he have it?,int|0",
		"sparring"=>"How many times successfully trained with the Sound 5,int|0",
		"todaylevel2"=>"How often used Level 2 today?,int|0",
		),
	"requires"=>array(
		"vampirelord"=>"1.1|Mike Counts, conversion by XChrisX, translated back by `2Oliver Brendel",
		),
	);
	return $info;
}
function curse_seal_install() {
	module_addhook_priority("bioinfo",20);
	module_addhook_priority("vampirelord_offering",50);
	module_addhook_priority("fightnav-specialties",1);
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addeventhook("forest", "require_once(\"modules/curse_seal/curse_seal_chance.php\"); return curse_seal_chance(\"forest\");");
	return true;
}
function curse_seal_uninstall() {
	return true;
}

function curse_seal_dohook($hookname,$args) {
	global $session;
	switch($hookname) {
		case "bioinfo":
			$seal=get_module_pref("hasseal","curse_seal",$args['acctid']);
			if ($seal>0) {
				$name=get_module_setting('name');
				if ($seal>1) $name.=translate_inline('`) Level 2');
				output("`^Seal: %s`^`n",$name);
			}
			break;
		case "vampirelord_offering":
			if (is_module_active("alignment")) {
				if (curse_seal_get()!=0) break;
			}
			if (get_module_setting("dk")>$session['user']['dragonkills']) break;
			if (get_module_pref("hasseal")>=1) break;
			output("`n`n`7You sense that he can grant you more power in your doing... because you are `b`\$evil`7`b to the bone... he can even grant you more power.");
			if (get_module_pref("hasseal")<0) output(" You have tried it before... maybe you have now more luck, maybe not...");
			addnav(array("Offer %s hitpoints for more power", $args['cost']),"runmodule.php?module=curse_seal&op=offer");
			break;
		case "apply-specialties":
			require("modules/curse_seal/apply.php");
			break;
		case "fightnav-specialties":
			$seals=get_module_pref("hasseal");
			if ($seals>=1) {
				$skill="curse_seal";
				$sealname=get_module_setting('name');
				addnav(array("%s",sanitize($sealname)));
				if (!has_buff('curseseal1')) addnav(array("%s`) Level 1",$sealname),$args['script']."op=fight&skill=$skill&l=1");
				if ($seals==2 && has_buff('curseseal1') && !has_buff('curseseal2')) {
					if ($session['user']['maxhitpoints']>$session['user']['level']*10) {
						addnav(array("%s`) Level 2",$sealname),$args['script']."op=fight&skill=$skill&l=2");
					} else {
						addnav(array("%s`) Level 2 (Body too fragile!)",$sealname),"");
					}
				}
			}
			break;
		case "newday":
			if (get_module_pref('hasseal')>0) {
				increment_module_pref("days",1,"curse_seal");
				set_module_pref("todaylevel2",0,"curse_seal");
			}
			break;
	}
	return $args;
}
function curse_seal_runevent($type,$link) {
	global $session;
	require("modules/curse_seal/runevent.php");
}

function curse_seal_run(){
	global $session;
	require("modules/curse_seal/run.php");
	page_footer();
}

function curse_seal_get() {
	$evilalign = get_module_setting('evilalign','alignment');
	$goodalign = get_module_setting('goodalign','alignment');
	$useralign = get_module_pref('alignment','alignment');
	//0 equals evil, 1 equals neutral, 2 equals good alignment
	if ($useralign <= $evilalign) return 0;
	if ($useralign >= $goodalign) return 2;
	return 1;
}
?>