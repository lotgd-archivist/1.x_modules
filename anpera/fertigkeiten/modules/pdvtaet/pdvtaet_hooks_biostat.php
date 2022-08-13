<?php

		$char = httpget('char');
		$sql = "SELECT acctid FROM ".db_prefix("accounts")." WHERE login='$char'";
		$results = db_query($sql);
		$row = db_fetch_assoc($results);
		$koerper = get_module_pref('koerper', 'pdvtaet', $row['acctid']);
					            
		output("`^Tätowierungen: ");
		$keine="`2Keine.";
		
		if ($koerper != 1){
			$koerper=createarray($koerper);
			for ($i=0; $i<=34; $i++){
				if ($koerper[$i]['motiv'] != ""){
					$keine="";
					output("`n`@%s: `2%s", $koerper[$i]['ort'], $koerper[$i]['motiv'] );
				}
			}
		}
		output("%s`n", $keine);
	return $args;
?>
