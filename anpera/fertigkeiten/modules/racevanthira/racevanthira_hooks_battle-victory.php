<?php
			if ($session['user']['race']==$race){
				$Vorteil = (e_rand(0,100));
				$senden=get_module_setting("senden");
				$chancesehnen=get_module_setting("sehnen");
				$sehnen=$senden+$chancesehnen;
				if ($Vorteil <= $senden){
					$sendendauer=get_module_setting ("sendendauer");
					$sendenst�rke=get_module_setting ("sendenst�rke");
					output("`3`n`bNach diesem Kampf versp�rst Du den starken Drang, ein weiteres Wesen ins Schattenreich zu `isenden`i, wie es in Deinem Volke hei�t.`n`n`b");
					apply_buff("racialbenefit",array(
					"name"=>"`3Drang zu `isenden`i`0",
					"atkmod"=>"$sendenst�rke",
					"roundmsg"=>"`3Du fl�sterst: `#'Das Schattenreich hat viel zu bieten ... komm, lass Dich senden!'`3.",
					"wearoff"=>"`3`bDein Drang zu `isenden`i ist vor�ber. Du k�mpfst normal weiter.`b",
					"allowinpvp"=>0,
					"allowintrain"=>0,
					"rounds"=>"$sendendauer",
					"schema"=>"module-racevanthira",
					"activate"=>"offense")
					);
				}
				else if ($Vorteil > $senden && $Vorteil <= $sehnen){
					$sehnendauer=get_module_setting ("sehnendauer");
					$sehnenst�rke=get_module_setting ("sehnenst�rke");
					output("`3`n`bWehm�tig siehst Du Deinen Gegner zu Boden gehen ... Ach, wie gern w�rst Du an seiner Stelle! Du wirst von dem Drang befallen, ins Schattenreich zur�ckzukehren.`n`n`b");
					apply_buff("racialbenefit",array(
						"name"=>"`3Sehnsucht nach `iR�ckkehr`i`0",
						"atkmod"=>"$sehnenst�rke",
						"defmod"=>"$sehnenst�rke",
						"roundmsg"=>"`3Du rufst flehentlich: `#'Ich bitte Dich, bring mich ins Schattenreich!'`3.",
						"wearoff"=>"`3`bDein Drang zur `iR�ckkehr`i ist pl�tzlich vor�ber. Du k�mpfst normal weiter.`b",
						"allowinpvp"=>0,
						"allowintrain"=>0,
						"rounds"=>"$sehnendauer",
						"schema"=>"module-racevanthira",
						"activate"=>"offense",
						));
				}
			}
?>
