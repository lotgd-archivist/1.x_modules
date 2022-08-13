<?php
//function showform and most code extracted and adapted from XChrisX item system
function backpack_getmoduleinfo(){
$info = array(
	"name"=>"Backpack for Inventory (XChrisX)",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel, `@most code extracted and adapted from XChrisX item system",
	"override_forced_nav"=>true,
	"category"=>"Inventory",
	"download"=>"",
	);
	return $info;
}

function backpack_install(){
	module_addhook("charstats");
	return true;
}

function backpack_uninstall(){
	return true;
}

function backpack_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "charstats":
		if (get_module_setting("withcharstats","inventory")==true) {
			$open = translate_inline("Open Inventory");
			addnav("runmodule.php?module=backpack&op=charstat");
			addcharstat("Equipment Info");
			addcharstat("Inventory", "<center><a href='runmodule.php?module=backpack&op=charstat' target='inventory' onClick=\"".popup("runmodule.php?module=backpack&op=charstat").";return false;\">$open</a></center>");
		}
			break;
	}
	return $args;
}

function backpack_run(){
	global $session;
	require_once("lib/sanitize.php");
	define("OVERRIDE_FORCED_NAV", true);
	$item = db_prefix("item");
	$inventory = db_prefix("inventory");
	$op2 = httpget('op2');
	$id = httpget('id');
	switch($op2) {
		case "show":
			$sql = "SELECT $item.name, $item.description FROM $item WHERE itemid=$id;";
			$row = db_fetch_assoc(db_query($sql));
			output("`vDescription of %s`v:`n",$row['name']);
			output_notl("%s`0`n`n",$row['description']);
			break;
		case "equip":
			$thing = get_item((int)$id);
			$sql = "SELECT $inventory.itemid FROM $inventory INNER JOIN $item ON $inventory.itemid = $item.itemid WHERE $item.equipwhere = '".$thing['equipwhere']."' AND $inventory.equipped = 1";
			$result = db_query($sql);
			while ($row = db_fetch_assoc($result)) $wh[] = $row['itemid'];
			if (is_array($wh) && count($wh)) {
				modulehook("unequip-item", array("ids"=>$wh));
				$sql = "UPDATE $inventory SET equipped = 0 WHERE itemid IN (".join(",",$wh).")";
				db_query($sql);
			}
			modulehook("equip-item", array("id"=>$id));
			$sql = "UPDATE $inventory SET equipped = 1 WHERE itemid = $id AND userid = {$session['user']['acctid']} LIMIT 1";
			$result = db_query($sql);
			break;
		case "unequip":
			modulehook("unequip-item", array("ids"=>array($id)));
			$sql = "UPDATE $inventory SET equipped = 0 WHERE itemid = $id AND userid = {$session['user']['acctid']}";
			$result = db_query($sql);
			break;
		case "activate":
			if (!$session['user']['alive']) {
				output("`\$Sorry, but you are not alive! Dead people can't use their backpack contents...`n`n");
				break;
			}
			$id = httpget('id');
			require_once("modules/inventory/lib/itemhandler.php");
			$acitem = get_inventory_item((int)$id);
			require_once("lib/buffs.php");
			apply_buff($acitem['name'], get_buff($acitem['buffid']));
			if ($acitem['charges'] > 1) {
				debug("uncharged -1 item $id");
				uncharge_item((int)$id);
			} else {
				debug("removed item $id");
				remove_item((int)$id);
			}
			if ($acitem['execvalue'] > "") {
				if ($acitem['exectext'] > "") {
					output($acitem['exectext'], $acitem['name']);
				} else {
					output("You activate %s!", $acitem['name']);
				}
				require_once("modules/inventory/lib/itemeffects.php");
				output_notl("`n`n%s", get_effect($acitem));
			}
			break;
	}
	popup_header("Your Inventory ");
	output("You are currently wearing the following items:`n`n");
	$layout = array(
/*		"Weapons,title",
			"righthand",
			"lefthand",
		"Armor,title",
			"head",
			"body",
			"arms",
			"legs",
			"feet",
		"Miscellaneous,title",
			"ring1",
			"ring2",
			"ring3",
			"neck",
			"belt",
*/		"Inventory,title",
			"unequipables",
	);
	$sql = "SELECT $item.*, inv.equipped, inv.quantity FROM $item INNER JOIN (
			SELECT itemid, MAX($inventory.equipped) AS equipped, COUNT($inventory.equipped) AS quantity FROM $inventory WHERE $inventory.userid = {$session['user']['acctid']} GROUP BY $inventory.itemid 
			) AS inv ON inv.itemid = $item.itemid ORDER BY $item.equipwhere,$item.class";
	/*$item.equippable = 0 AND*/
	$result = db_query($sql);debug($sql);
	$inventory = array();
	while($row = db_fetch_assoc($result)) {
		if ($row['equippable'] == false)
			$inventory['unequipables'][] = $row;
		else
			$inventory[$row['equipwhere']][] = $row;
	}
	backpack_showform($layout, $inventory);
	popup_footer();
}

function backpack_showform($layout,$row){
	global $session;
 	static $showform_id=0;
 	static $title_id=0;
 	$showform_id++;
 	$formSections = array();
	rawoutput("<table width='100%' cellpadding='0' cellspacing='0'><tr><td>");
	rawoutput("<div id='showFormSection$showform_id'></div>");
	rawoutput("</td></tr><tr><td>&nbsp;</td></tr><tr><td>");
	rawoutput("<table cellpadding='2' cellspacing='0'>");
	$i = 0;
	$wheres = translate(array("righthand"=>"Right Hand","lefthand"=>"Left Hand","head"=>"Your Head","body"=>"Upper Body","arms"=>"Yor Arms","legs"=>"Lower Body","feet"=>"Your Feet","ring1"=>"First Ring","ring2"=>"Second Ring","ring3"=>"Third Ring","neck"=>"Around your Neck","belt"=>"Around your Waist","unequipables"=>"Standard Gear"));
	$equip = translate_inline("Equip");
	$unequip = translate_inline("Unequip");
	$activate = translate_inline("Activate");
	$show = translate_inline("Description");
	foreach ($layout as $key=>$val) {
		if (is_array($val)) {
			$v = $val[0];
			$info = explode(",", $v);
			$val[0] = $info[0];
			$info[0] = $val;
		} else {
			$info = explode(",",$val);
		}
		if (is_array($info[0])) {
			$info[0] = call_user_func_array("sprintf_translate", $info[0]);
		} else {
			$info[0] = translate($info[0]);
		}
		if (isset($info[1])) $info[1] = trim($info[1]);
		else $info[1] = "";

		if ($info[1]=="title"){
		 	$title_id++;
		 	rawoutput("</table>");
		 	$formSections[$title_id] = $info[0];
		 	rawoutput("<table id='showFormTable$title_id' cellpadding='2' cellspacing='0'>");
			rawoutput("<tr><td colspan='2' class='trhead'>",true);
			output_notl("`b%s`b", $info[0], true);
			rawoutput("</td></tr>",true);
			$i=0;
		} else {
			if (isset($row[$val])) {
				$item = $row[$val];
				rawoutput("<tr class='".($i%2?'trlight':'trdark')."'><td valign='top'>");
				output_notl("%s ->", $wheres[$info[0]],true);
				rawoutput("</td><td valign='top'>");
				$lastcat='';
				while(list($itskey, $itsval) = each($item)) {
					if ($lastcat!=$itsval['class']) {
						if ($lastcat!='') output_notl("`n");
						output_notl("`c`\$%s`c",$itsval['class']);
						$lastcat=$itsval['class'];
						
					}
					output_notl("%s`7%s`7 (%s)", $itsval['equipped']?"`^*":"", $itsval['name'], $itsval['quantity']);
					if ($itsval['equipped'] && $itsval['equippable']) {
						rawoutput("[ <a href='runmodule.php?module=backpack&op=charstat&op2=unequip&id={$itsval['itemid']}'>$unequip</a> ]");
						addnav("", "runmodule.php?module=backpack&op=charstat&op2=unequip&id={$itsval['itemid']}");
					} else if ($itsval['equippable'] == 1) {
						rawoutput("[ <a href='runmodule.php?module=backpack&op=charstat&op2=equip&id={$itsval['itemid']}'>$equip</a> ]");
						addnav("", "runmodule.php?module=backpack&op=charstat&op2=equip&id={$itsval['itemid']}");
					} else if (($itsval['activationhook'] & 64) && $session['user']['alive']) {
						rawoutput("[ <a href='runmodule.php?module=backpack&op=charstat&op2=activate&id={$itsval['itemid']}'>$activate</a> ]");
						addnav("", "runmodule.php?module=backpack&op=charstat&op2=activate&id={$itsval['itemid']}");
					} else {
						//output("(Gold value: %s, Gem Value: %s)", $itsval['gold'], $itsval['gems']);
					}
					rawoutput("[ <a href='runmodule.php?module=backpack&op=charstat&op2=show&id={$itsval['itemid']}'>$show</a> ]");
                        addnav("", "runmodule.php?module=backpack&op=charstat&op2=show&id={$itsval['itemid']}");
					output_notl("`n");
				}
				$i++;
			}
		}
		rawoutput("</td></tr>",true);
	}
	rawoutput("</table><br>",true);
	if ($showform_id==1){
		$startIndex = (int)httppost("showFormTabIndex");
		if ($startIndex == 0){
			$startIndex = 1;
		}
		if (isset($session['user']['prefs']['tabconfig']) && $session['user']['prefs']['tabconfig'] == 0) {
		} else {
		 	rawoutput("
		 	<script language='JavaScript'>
		 	function prepare_form(id){
		 		var theTable;
		 		var theDivs='';
		 		var x=0;
		 		var weight='';
		 		for (x in formSections[id]){
		 			theTable = document.getElementById('showFormTable'+x);
		 			if (x != $startIndex ){
			 			theTable.style.visibility='hidden';
			 			theTable.style.display='none';
			 			weight='';
			 		}else{
			 			theTable.style.visibility='visible';
			 			theTable.style.display='inline';
			 			weight='color: yellow;';
			 		}
			 		theDivs += \"<div id='showFormButton\"+x+\"' class='trhead' style='\"+weight+\"float: left; cursor: pointer; cursor: hand; padding: 5px; border: 1px solid #000000;' onClick='showFormTabClick(\"+id+\",\"+x+\");'>\"+formSections[id][x]+\"</div>\";
		 		}
		 		theDivs += \"<div style='display: block;'>&nbsp;</div>\";
				theDivs += \"<input type='hidden' name='showFormTabIndex' value='$startIndex' id='showFormTabIndex'>\";
		 		document.getElementById('showFormSection'+id).innerHTML = theDivs;
		 	}
		 	function showFormTabClick(formid,sectionid){
		 		var theTable;
		 		var theButton;
		 		for (x in formSections[formid]){
		 			theTable = document.getElementById('showFormTable'+x);
		 			theButton = document.getElementById('showFormButton'+x);
		 			if (x == sectionid){
		 				theTable.style.visibility='visible';
		 				theTable.style.display='inline';
		 				theButton.style.fontWeight='normal';
		 				theButton.style.color='yellow';
						document.getElementById('showFormTabIndex').value = sectionid;
		 			}else{
		 				theTable.style.visibility='hidden';
		 				theTable.style.display='none';
		 				theButton.style.fontWeight='normal';
		 				theButton.style.color='';
		 			}
		 		}
		 	}
		 	formSections = new Array();
			</script>");
		}
	}
	if (isset($session['user']['prefs']['tabconfig']) && $session['user']['prefs']['tabconfig'] == 0) {
	} else {
		rawoutput("<script language='JavaScript'>");
		rawoutput("formSections[$showform_id] = new Array();");
		reset($formSections);
		while (list($key,$val)=each($formSections)){
			rawoutput("formSections[$showform_id][$key] = '".addslashes($val)."';");
		}
		rawoutput("
		prepare_form($showform_id);
		</script>");
	}
	rawoutput("</td></tr></table>");
}
	
?>
