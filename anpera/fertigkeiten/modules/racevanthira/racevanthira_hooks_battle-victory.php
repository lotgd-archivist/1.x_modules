<?php
			if ($session['user']['race']==$race){
				$Vorteil = (e_rand(0,100));
				$senden=get_module_setting("senden");
				$chancesehnen=get_module_setting("sehnen");
				$sehnen=$senden+$chancesehnen;
				if ($Vorteil <= $senden){
					$sendendauer=get_module_setting ("sendendauer");
					$sendenstärke=get_module_setting ("sendenstärke");
					output("`3`n`bNach diesem Kampf verspürst Du den starken Drang, ein weiteres Wesen ins Schattenreich zu `isenden`i, wie es in Deinem Volke heißt.`n`n`b");
					apply_buff("racialbenefit",array(
					"name"=>"`3Drang zu `isenden`i`0",
					"atkmod"=>"$sendenstärke",
					"roundmsg"=>"`3Du flüsterst: `#'Das Schattenreich hat viel zu bieten ... komm, lass Dich senden!'`3.",
					"wearoff"=>"`3`bDein Drang zu `isenden`i ist vorüber. Du kämpfst normal weiter.`b",
					"allowinpvp"=>0,
					"allowintrain"=>0,
					"rounds"=>"$sendendauer",
					"schema"=>"module-racevanthira",
					"activate"=>"offense")
					);
				}
				else if ($Vorteil > $senden && $Vorteil <= $sehnen){
					$sehnendauer=get_module_setting ("sehnendauer");
					$sehnenstärke=get_module_setting ("sehnenstärke");
					output("`3`n`bWehmütig siehst Du Deinen Gegner zu Boden gehen ... Ach, wie gern wärst Du an seiner Stelle! Du wirst von dem Drang befallen, ins Schattenreich zurückzukehren.`n`n`b");
					apply_buff("racialbenefit",array(
						"name"=>"`3Sehnsucht nach `iRückkehr`i`0",
						"atkmod"=>"$sehnenstärke",
						"defmod"=>"$sehnenstärke",
						"roundmsg"=>"`3Du rufst flehentlich: `#'Ich bitte Dich, bring mich ins Schattenreich!'`3.",
						"wearoff"=>"`3`bDein Drang zur `iRückkehr`i ist plötzlich vorüber. Du kämpfst normal weiter.`b",
						"allowinpvp"=>0,
						"allowintrain"=>0,
						"rounds"=>"$sehnendauer",
						"schema"=>"module-racevanthira",
						"activate"=>"offense",
						));
				}
			}
?>
