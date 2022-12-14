<?php
if ($session['user']['location'] != get_module_setting("logoutlocation"))
		set_module_pref("location_saver", $session['user']['location']);
	$sql = "SELECT name,description,ownerid FROM ".db_prefix("dwellings")." WHERE dwid=$dwid";
	$result = db_query($sql);
	$row = db_fetch_assoc($result); 
	if($row['name'] == ""){
		$name = translate_inline("Unnamed");
	}else{
		$name = $row['name'];
	}
	page_header("%s",sanitize($name));
	set_module_pref("dwelling_saver", $dwid, "dwellings");
	output_notl("`c`b%s`b`n`n%s`n`n`c`0",$row['name'],$row['description']);
	if(get_module_setting("ownersleep",$type) && $session['user']['acctid']==$row['ownerid']){
		addnav("Leave");
		addnav("Go to Sleep (Log Out)","runmodule.php?module=dwellings&op=logout&dwid=$dwid&type=$type&owner=1");
	}
	if(get_module_setting("othersleep",$type)){
		if( $session['user']['acctid'] != $row['ownerid']) 
			addnav("Go to Sleep (Log Out)","runmodule.php?module=dwellings&op=logout&dwid=$dwid&type=$type");			
		$ac = db_prefix("accounts");
		$mu = db_prefix("module_userprefs");
		$sql = "SELECT $ac.name AS name,
				$ac.acctid AS acctid,
				$mu.userid FROM $mu 
				INNER JOIN $ac ON $ac.acctid = $mu.userid 
				WHERE $mu.setting = 'dwelling_saver'
				and $mu.value = $dwid 
				and $ac.loggedin = 0";
		$res = db_query_cached($sql,"dwellings-sleepers-$dwid");
		$i = 0;
		$num = db_num_rows($res);
		while($row1 = db_fetch_assoc($res)){
			$pre = "";
			$suf = "";
			if ($i == 0) $pre = translate_inline("`nThe following people are sleeping here: ");
			elseif($i > 0 && $num > 2) $pre = ", ";
			if ($i > 0 && $i == ($num-1)) $pre = $pre."".translate_inline("and ");
			if ($i == ($num-1)) $suf=".`n`n";
			output_notl("%s%s%s",$pre,$row1['name'],$suf);
			$i++;
		}
	}
	/* 
	Removed for aesthetic purposes.
	$city_link = urlencode(get_module_pref("location_saver","dwellings"));
	if (is_module_active("cities")) 
		$link = "runmodule.php?module=cities&op=travel&city=$city_link&su=1";
	}else {
		$link ="village.php";
	}
	addnav("Leave");
	addnav(array("Return to %s",get_module_pref("location_saver","dwellings")),$link); 
	*/
	
	$session['user']['location'] = get_module_setting("logoutlocation");
	addnav("Dwellings Extras");
	modulehook("dwellings-inside", array("type"=>$type, "dwid"=>$dwid, "owner"=>$row['ownerid']));
	if(get_module_setting("enablecof") && get_module_setting("enablecof",$type)==1){
		addnav("The Coffers","runmodule.php?module=dwellings&op=coffers&dwid=$dwid");
	}
	if($session['user']['acctid'] != $row['ownerid']){
		addnav("Turn in Key","runmodule.php?module=dwellings&op=keys&subop=giveback&dwid=$dwid");
	} else {
		addnav("Dwelling Management","runmodule.php?module=dwellings&op=manage&dwid=$dwid");
	}
	require_once("lib/commentary.php");
	addcommentary();
	$tl = get_module_objpref("dwellings",$dwid,"dwidtalkline");
	viewcommentary("dwellings-".$dwid, "The walls echo past comments", 20, $tl); 
?>
