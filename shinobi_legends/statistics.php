<?php

function statistics_getmoduleinfo(){
	$info = array(
		"name"=>"Datenerfassung Verbrauch",
		"version"=>"1.0",
		"author"=>"Christian Rutsch",
		"category"=>"Administrative",
	);
	return $info;
}

function statistics_install(){
	module_addhook("footer-weapons");
	module_addhook("footer-armor");
	module_addhook("footer-drinks");
	module_addhook("footer-kitchen");
	module_addhook("footer-wayofthehero");
	
	module_addhook("superuser");

	$statistics = array(
		'type'=> array('name'=>'type', 'type'=>'varchar(255)'),
		'value' => array('name'=>'value', 'type'=>'varchar(255)'),
		'date'  => array('name'=>'date', 'type'=>'varchar(255)'),
		'count'=> array('name'=>'count', 'type'=>'varchar(255)'),
		'key-PRIMARY' => array('name'=>'PRIMARY', 'type'=>'primary key', 'unique'=>'1', 'columns'=>'type,value,date'));
	require_once("lib/tabledescriptor.php");

	synctable(db_prefix("statistics"), $statistics, true);
	return true;
}

function statistics_uninstall(){
	return true;
}

function statistics_dohook($hookname,$args){
	if ($hookname == "footer-wayofthehero") {
		if ($blubb = httpget('op3')) {
			if (is_numeric($blubb)) {
				httpset("id", $blubb, true);
				$op = httpget('op2');
				if ($op == 'forgeweapon')
					$hookname = "footer-weapons";
				else
					$hookname = "footer-armor";
			}
		}
	}
	switch ($hookname) {
		case "footer-weapons":
			$id = httpget('id');
			if ($id !== false) {
				$id = ($id % 15);
				if ($id == 0) $id = 15;
				$date = date("Ymd");
				$sql = "INSERT INTO statistics (type, value, date, count) VALUES ('weapon', '$id', '$date', '1') ON DUPLICATE KEY UPDATE count = count+1";
				db_query($sql);
			}
			break;
		case "footer-armor":
			$id = httpget('id');
			if ($id !== false) {
				$id = ($id % 15);
				if ($id == 0) $id = 15;
				$date = date("Ymd");
				$sql = "INSERT INTO statistics (type, value, date, count) VALUES ('armor', '$id', '$date', '1') ON DUPLICATE KEY UPDATE count = count+1";
				db_query($sql);
			}
			break;
		case "footer-drinks":
			$act = httpget('act');
			if ($act == 'buy') {
				$id = httpget('id');
				$date = date("Ymd");
				$sql = "INSERT INTO statistics (type, value, date, count) VALUES ('drinks', '$id', '$date', '1') ON DUPLICATE KEY UPDATE count = count+1";
				db_query($sql);
			}
			break;
		case "footer-kitchen":
			$op = httpget('op');
			if ($op != "" && $op != "food") {
				$date = date("Ymd");
				$sql = "INSERT INTO statistics (type, value, date, count) VALUES ('kitchen', '$op', '$date', '1') ON DUPLICATE KEY UPDATE count = count+1";
				db_query($sql);
			}
			break;
		case "superuser":
			global $session;
			if ($session['user']['superuser'] & SU_MEGAUSER) {
				addnav("Admin Tools");
				addnav("Statisken", "runmodule.php?module=statistics");
			}
			break;
	}
	return $args;
}

function statistics_run() {
	global $session;
	page_header("Statistiken");
	
	// Navigation
	addnav("Zurück");
	require_once("lib/superusernav.php");
	superusernav();
	addnav("Ein Datum wählen...");
	$sql = "SELECT DISTINCT date FROM statistics ORDER BY date ASC";
	$result = db_query($sql);
	while ($row = db_fetch_assoc($result)) {
		addnav(array("%s", $row['date']), "runmodule.php?module=statistics&date=".$row['date']);
	}
	
	// Inhalt
	$date = httpget("date");
	if ($date !== false) {
		$sql = "SELECT * FROM statistics WHERE date = '$date' ORDER BY type ASC, value+0 ASC";
		$result = db_query($sql);
		$type = "";
		$i = 0;
		rawoutput("<table>");
		while ($row = db_fetch_assoc($result)) {
			if ($row['type'] != $type) {
				rawoutput("<tr class='trhead'><td colspan='2'>");
				output("`^%s", $row['type']);
				rawoutput("</td></tr>");
				$type=$row['type'];
			}
			rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
			output("%s", $row['value']);
			rawoutput("</td><td>");
			output("%s", $row['count']);
			rawoutput("</td></tr>");
			++$i;
		}
		rawoutput("</table>");
	} else {
		// Durchschnitt über alle Tage!
		$sql = "SELECT type, value, count FROM statistics order by type,value+0 ASC";
		$result = db_query($sql);
		$stats=array();
		while($row = db_fetch_assoc($result)) {
//			if (isset($stats[$row['type']][$row['value']]['count'])) {
//				$stats[$row['type']][$row['value']]['count'] = $row['count'];
//				$stats[$row['type']][$row['value']]['qty'] = 1;
//			} else {
				$stats[$row['type']][$row['value']]['count'] += $row['count'];
				$stats[$row['type']][$row['value']]['qty']++;
//			}
		}
		$type = "";
		$i = 0;
		rawoutput("<table>");
		foreach($stats as $newtype => $content) {
			if ($newtype != $type) {
				rawoutput("<tr class='trhead'><td colspan='2'>");
				output("`^%s", $newtype);
				rawoutput("</td></tr>");
				$type=$newtype;
			}
			foreach($content as $value=>$array) {
			  rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
			  output("%s", $value);
			  rawoutput("</td><td>");
			  output("%s Items wurden an insgesamt %s Tagen verkauft. (~%f / Tag)", $array['count'], $array['qty'], round($array['count']/$array['qty'], 6));
			  rawoutput("</td></tr>");
			}
			++$i;
		}
		rawoutput("</table>");
	}
	page_footer();
}
?>
