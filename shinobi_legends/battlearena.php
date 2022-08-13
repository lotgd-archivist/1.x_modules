<?php
//Origial 0.9.8 Conversion by Frederic Hutow
function battlearena_getmoduleinfo(){
	$info = array(
		"name"=>"Battle Arena",
		"version"=>"3.0",
		"author"=>"`#Lonny Luberts `2modified by Oliver Brendel",
		"category"=>"Village",
		//"download"=>"http://dragonprime.net/dls/battlearena_modified.zip",
		"prefs"=>array(
			"Battle Arena User Preference,title",
			"battlepoints"=>"Number of Battle Points Received,int|0",
			"healthtemp"=>"Creature Original Health,int|0",
			"who"=>"Who did they battle last,text",
			"entryhealth"=>"Entered the arena with that health,int",
			"health"=>"Users in battle health,int",
			"crhealth"=>"Creaturs in battle health,int",
			"newfight"=>"newfight flag,bool|0",
		),
		"settings"=>array(
			"Battle Arena Settings,title",
			"`iArena stats will be reset each month!`i,note",
			"homearena"=>"Arenas appear in home towns too(turn cities-module on!), bool|0",
			"fee"=>"How much do you charge for a fight,int|50",
			"The arena automatically shows up in the capital but you may activate here also a seperate arena for every other town people call their home town,note",
			"allowspecial"=>"Allow specialties in fight?, bool|0",
			"indexstats"=>"Show Leader on Login screen,bool|1",
			"Do not change the month! it is for the monthly clearup!,note",
			"currentmonth"=>"Current month,viewonly",
		)
	);
	return $info;
}

function battlearena_install(){
	module_addhook("village");
	module_addhook("index");
	module_addhook("namechange");
	module_addhook("dragonkill");
	module_addhook("newday-runonce");
	module_addhook("insertcomment");
	return true;
}

function battlearena_uninstall(){
	return true;
}

function battlearena_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "village":
		$city = getsetting("villagename", LOCATION_FIELDS);
		$capital = $session['user']['location']==$city;
		if (!is_module_active("cities") || $capital) {
			tlschema($args['schemas']['fightnav']);
    		addnav($args['fightnav']);
    		tlschema();
			addnav("B?Battle Arena","runmodule.php?module=battlearena");
			$statue=true;
		} else if(get_module_setting("homearena")) {
				tlschema($args['schemas']['fightnav']);
		   		addnav($args['fightnav']);
		   		tlschema();
				addnav(array("B?Battle Arena of %s",$session['user']['location']),"runmodule.php?module=battlearena");
				$statue=true;
		}
		if ($statue) {
			$leadername = battlearena_getleader($session['user']['location']);
			if ($leadername) {
				output("`n`@The current Battle Arena Leader in %s is: `&%s`@.`0`n",$session['user']['location'],$leadername['name']);
			} else {
				output("`n`@There is `&no`@ leader in the Battle Arena here.`0`n");
			}
		}
		break;
	case "index":
		if (get_module_setting("indexstats") == 1){
			$town=getsetting("villagename", LOCATION_FIELDS);
			$leadername = battlearena_getleader($town);
			if ($leadername) {
				output("`@The current Battle Arena Leader in `%%s`@ is: `&%s`@.`0`n",$town,$leadername['name']);
			} else {
				output("`@There is `&no`@ leader in the Battle Arena. Will you be the first one?`0`n");
			}
		}
		break;
	case "insertcomment":
		if ($args['section']=='battlearena-news') {
			$args=array_merge($args,array("mute"=>1,"mutemsg"=>translate_inline("`@You see the Battle Arena Fight News...`n`n")));
		}
		break;
	case "dragonkill":
	case "namechange":
			$homecity=get_module_pref("homecity", "cities");
			invalidatedatacache("battleleader-$homecity");
			invalidatedatacache("battleleader");
		break;
	case "newday-runonce":
		$day=getdate(time());
		$delete=FALSE;
		if (get_module_setting('currentmonth')!=$day['mon']) {
			$delete=TRUE;
		   	set_module_setting('currentmonth',$day['mon']);
		}
		//call the clearup function that handles the monthly stats
		require_once("modules/battlearena/battlearena_monthly.php");
		battlearena_monthly($day['mon'],$day['year'],$delete);
		invalidatedatacache("battleleader-$homecity");
		invalidatedatacache("battleleader");
		break;
	}
	return $args;
}

function battlearena_runevent($type){
}

function battlearena_run(){
	global $session;
	require("modules/battlearena/battlearena_main.php");
}

function battlearena_getleader($town=false) {
		if ($town) {
			$sql = "SELECT a.objid as acctid, a.value as battlepoints, b.name as name FROM " . db_prefix('module_objprefs') . " as a INNER JOIN ".db_prefix('accounts')." as b ON b.acctid=a.objid WHERE modulename='battlearena' AND objtype='$town' AND setting='battlepoints' AND value <> '' ORDER BY value + 0 DESC LIMIT 1";
			$result = db_query_cached($sql,"battlearena-$town");
			$row = db_fetch_assoc($result);
			return $row;
		} else {
			$sql = "SELECT a.objid as acctid, max(a.value) as battlepoints,objtype as location, b.name as name FROM " . db_prefix('module_objprefs') . " as a INNER JOIN ".db_prefix('accounts')." as b ON b.acctid=a.objid WHERE modulename='battlearena' AND setting='battlepoints' AND value <> '' GROUP BY objtype ORDER BY objtype DESC";
			$result = db_query_cached($sql,"battlearena-leaders");
			while ($row = db_fetch_assoc($result)) {
				$return[]=$row;
			}
			return $return;
		}
}

function battlearena_showrank($town=false) {
	output("`3The following warriors have proven themselves in battle:`n`n");
	if ($town) {
		$sql = "SELECT a.name,o.value FROM " . db_prefix('module_objprefs') . " as o LEFT JOIN " . db_prefix('accounts') . " as a ON a.acctid = o.objid WHERE o.modulename='battlearena' AND o.setting='battlepoints' and o.objtype='".addslashes($town)."' and o.value > 0 ORDER BY value + 0 DESC,name";
	} else {
		$sql = "SELECT a.name,o.value FROM " . db_prefix('module_objprefs') . " as o LEFT JOIN " . db_prefix('accounts') . " as a ON a.acctid = o.objid WHERE o.modulename='battlearena' AND o.setting='battlepoints' and o.value > 0 ORDER BY value + 0 DESC,name";
	}
	$result = db_query($sql);
	if (db_num_rows($result)==0) {
		output("No one yet...`n");
	}
	while ($row = db_fetch_assoc($result)) {
		output("%s `7has %s `7battlepoints.`n",$row['name'],number_format($row['value']));
	}
}

?>
