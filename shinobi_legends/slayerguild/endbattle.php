<?php

function slayerguild_endbattle(){
	global $session;
	global $badguy;
	$hploss = get_module_setting("hploss");
	$apply = get_module_pref("apply");
	$holding = get_module_pref("holding");
	$maxhold = get_module_setting("maxhold");
	if ($apply && $holding<$maxhold){
		$id=array();
		foreach ($badguy as $guy) {
			$id[]=$guy['creatureid'];
		}
		$sql = "SELECT graveyard FROM ".db_prefix("creatures")." WHERE creatureid IN (".implode(",",$id)."";
		$result = db_query($sql);
		while ($row = db_fetch_assoc($result)) {
			if (!isset($args['creatureid'])){
					$row['graveyard']==0;
				}
				if ($row['graveyard']){
					output("`n`b`)You have rended their soul!`b`n`n");
					$holding++;
					set_module_pref("holding",$holding);
				}else{
					output("`n`b`&You have spilt the blood of an innocent!`b`n`n");
					$session['user']['hitpoints']-=$hploss;
					if ($session['user']['hitpoints']<=$hploss){
						debuglog("died from spilling the blood of an innocent");
						$session['user']['hitpoints']=0;
						$session['user']['alive']=false;
						redirect("runmodule.php?module=slayerguild&op=dead&op2=forest");
					}
					if (is_module_active('alignment')) {
					require_once("./modules/alignment/func.php");
					align("-1");
				}
			}
		}
	}
}
?>