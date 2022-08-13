<?php
		if (httpget('skill')=="sevenstar") {
				$name=get_module_setting('name');
				switch (httpget('l')) {
					case 1:
						//note: you MUST have the pref USES set for the specialty. I cannot guess here what you called your thingy here
						if (is_module_active("specialtysystem")) {
							require_once("modules/specialtysystem/functions.php");
							$val=-specialtysystem_availableuses();
							increment_module_pref("uses",$val,"specialtysystem");
						}
						$sql="SELECT modulename FROM ".db_prefix("module_hooks")." WHERE location='choose-specialty' AND modulename!='specialtysystem';";
						$result=db_query_cached($sql,"star_specialties");
						$add="";
						if (db_num_rows($result)<1) break; //no specialties installed...
						while ($row=db_fetch_assoc($result)) {
							$add.="'".$row['modulename']."',";
						}
						$add = substr($add,0,strlen($add)-1);
						$sql="UPDATE ".db_prefix("module_userprefs")." set value=value*2 WHERE modulename IN (".$add.") AND setting='uses' AND userid=".$session['user']['acctid'].";";
						db_query($sql);
						apply_buff('star1',
							array(
								'name'=>array("%s`) Powerflow",$name),
								'rounds'=>-1,
								'startmsg'=>array("`)You let your power flow out of you and activate your %s`) ... you increase your specialty uses!",$name),
								'schema'=>"module-sevenstar",
								)
							);

						break;
					case 2:
						$today=get_module_pref('todaylevel2');
						if ($today>0) {
							$loss=e_rand(1,$today);
							if ($session['user']['maxhitpoints']>$loss) {
								output("`)Your body erodes! You lose `\$%s`7 `bpermanent`b lifepoints and one attack point!`n",$loss);
								$session['user']['maxhitpoints']-=$loss;
								if ($session['user']['attack']>0) {
									$session['user']['attack']--;
								}
							}
						} 
						increment_module_pref('todaylevel2',1);
						apply_buff('star2',
							array(
								'name'=>array("%s`\$ `bEnhanced`b",$name),
								'rounds'=>e_rand($session['user']['level'],20),
								'startmsg'=>array("`)You let the power of the %s`) flow out of you ... your chakra increases by multiple times!",$name),
								'minioncount'=>1,
								'schema'=>"module-sevenstar",
								)
							);
												if (is_module_active("specialtysystem")) {
							require_once("modules/specialtysystem/functions.php");
							$val=-specialtysystem_availableuses();
							increment_module_pref("uses",$val,"specialtysystem");
						}
						$sql="SELECT modulename FROM ".db_prefix("module_hooks")." WHERE location='choose-specialty' AND modulename!='specialtysystem';";
						$result=db_query_cached($sql,"star_specialties");
						$add="";
						if (db_num_rows($result)<1) break; //no specialties installed...
						while ($row=db_fetch_assoc($result)) {
							$add.="'".$row['modulename']."',";
						}
						$add = substr($add,0,strlen($add)-1);
						$sql="UPDATE ".db_prefix("module_userprefs")." set value=value*2 WHERE modulename IN (".$add.") AND setting='uses' AND userid=".$session['user']['acctid'].";";
						db_query($sql);

						break;
					}
			}
?>
