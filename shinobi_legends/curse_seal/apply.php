<?php
if (httpget('skill')=="curse_seal") {
	$name=get_module_setting("name");
	switch (httpget('l')) {
		case 1:
			//note: you MUST have the pref USES set for the specialty. I cannot guess here what you called your thingy here
			if (is_module_active("alignment")) increment_module_pref("alignment",-3,"alignment");
			if (is_module_active("specialtysystem")) {
				require_once("modules/specialtysystem/functions.php");
				$val=-specialtysystem_availableuses();
				increment_module_pref("uses",$val,"specialtysystem");
			}
			$sql="SELECT modulename FROM ".db_prefix("module_hooks")." WHERE location='choose-specialty' AND modulename!='specialtysystem';";
			$result=db_query_cached($sql,"seal_specialties");
			$add="";
			if (db_num_rows($result)<1) break; //no specialties installed...
			while ($row=db_fetch_assoc($result)) {
				$add.="'".$row['modulename']."',";
			}
			$add = substr($add,0,strlen($add)-1);
			$sql="UPDATE ".db_prefix("module_userprefs")." set value=value*2 WHERE modulename IN (".$add.") AND setting='uses' AND userid=".$session['user']['acctid'].";";
			db_query($sql);
			apply_buff('curseseal1',
					array(
						'name'=>array("%s`) Level 1",$name),
						'rounds'=>-1,
						'startmsg'=>array("`)You let your power flow out of you and activate %s`) Level 1... you increase your specialty uses!",$name),
						'schema'=>"module-curse_seal",
					     )
				  );

			break;
		case 2:
			$today=get_module_pref('todaylevel2');
			if ($today>0) {
				$loss=e_rand(1,$today);
				if (($session['user']['maxhitpoints']-$loss)<$session['user']['level']*10) {
					output("`)Your body cannot take the Level 2 of the %s`)...",$name);
					break;
				}							
				if ($session['user']['maxhitpoints']>$loss) {
					output("`)Your body erodes! You lose `\$%s`7 `bpermanent`b lifepoints and one attack point!`n",$loss);
					$session['user']['maxhitpoints']-=$loss;
					debuglog("lost $loss permanent hitpoints due to excessive curse seal use!");
					//check if the user has greater than 0 attack, if so, subtract a point
					if ($session['user']['attack']>0) {
						output("`)Your body erodes! You lose `\$%s`7 `bpermanent`b attack points!`n",$loss);
						$session['user']['attack']--;
					}
				}
			} else {
				$loss=e_rand(0,1);
				if ($loss && $session['user']['maxhitpoints']>$session['user']['level']*10) {
					output("`)Your body starts to erode! You lose `\$one`7 `bpermanent`b hitpoint!`n",$loss);
					debuglog("lost 1 permanent hitpoint due to excessive curse seal use!");
					$session['user']['maxhitpoints']--;
				}
			}
			increment_module_pref('todaylevel2',1);
			apply_buff('curseseal2',
					array(
						'name'=>array("%s`\$ `bLevel 2`b",$name),
						'rounds'=>e_rand($session['user']['level'],20),
						'startmsg'=>array("`)You let your power flow out of you and activate %s`) Level 2... you get stronger by multiple times!",$name),
						"effectmsg"=>"`)You rip {badguy}`) almost apart with your attack! The enemy suffers `^{damage}`) damage points!",
						"atkmod"=>2,
						"defmod"=>2,
						"minioncount"=>1,
						"maxbadguydamage"=>round($session['user']['attack'],0),
						"minbadguydamage"=>round($session['user']['attack']/2,0),
						'schema'=>"module-curse_seal",
					     )
				  );
			if (is_module_active("alignment")) increment_module_pref("alignment",-6,"alignment");
			break;
	}
}
?>
