<?php

//Ein Vampir startet mächtig und wird im Laufe des Tages schwach,
//da er seinen Blutdurst irgendwann gestillt hat und es dann keinen Grund mehr für die Jagd gibt.
	
//HP-Bonus zu Tagesanfang. Wächst mit dem 'Alter' des Vampirs.

if ($session ['user']['dragonkills']>=50){
	$hpbonus=1.5;
}
if ($session ['user']['dragonkills']>10 && $session ['user']['dragonkills']<=49){
	$hpbonus=1+$session['user']['dragonkills']/100;
}
if ($session['user']['dragonkills']<=10){
	$hpbonus=1.05;
}

	$points = 0;
	while(list($key,$val)=each($session['user']['dragonpoints'])){
		if ($val=="ff") $points++;
	}

	$Durst=(($session[user][turns]-$points)/0.3);
	$runden=(e_rand($Durst,$Durst+$points));
   
   	       if ($session['user']['race']==$race){
	            racevampir_checkcity();
				apply_buff("Blutdurst",array(
	                "name"=>"`4Adrenalinschub und Blutdurst`0",
					"activate"=>"offense",
					"rounds"=> "".$runden."",
					"roundmsg"=>"`4Der Geruch des Blutes Deines Gegners macht Dich wild!",
					"wearoff"=>"`4`bFür heute hast Du Deinen Blutdurst gestillt, so dass Dein Adrenalinschub aufhört.`n Nach diesem Kampf wird Dein Interesse an der weiteren Jagd so sehr gesunken und Deine Erschöpfung so groß sein, dass Deine Kampfwerte sogar nachlassen.`b",
					"atkmod"=>"(<attack>?(1+((1+floor(<level>/5))/<attack>)):0)",
					"defmod"=>"(<defense>?(1+((1+floor(<level>/10))/<defense>)):0)",
	                "allowinpvp"=>1,
	                "allowintrain"=>1,
					"schema"=>"module-racevampir",
				)
				);
				$session[user][hitpoints]*="".$hpbonus."";
	            output("`4`nAls Du in Deinem Versteck erwachst, ist es noch hell draußen.`n Aber Dein Durst ist gewaltig, die Jagd muss `ijetzt`i stattfinden ...`n");
	        
	           //Es gibt keine "guten" Vampire
				if  (is_module_active('alignment')){
					$alignment = get_align();
					$good=get_module_setting('goodalign','alignment');
					if ($alignment > 50 && $alignment <= $good){
							output("`4`bAber Moment, irgendetwas stimmt nicht ... Du spürst, dass Dein Körper unter Deinem Karma zu leiden beginnt und beschließt, weniger Gutes zu tun.`b`n");
							align("-2");
					}else if ($alignment > $good){
							output("`4`bAber Moment, irgendetwas stimmt nicht ... Du spürst einen großen Schmerz! Dein Körper leidet unter Deinem Karma - das Gute beginnt Dich zu zersetzen! Du verlierst einen Waldkampf und gehst geschwächt in den Tag.`b`n");
							$session[user][turns]--;
							$session[user][hitpoints]*=0.5;
							align("-3");
					}
				}
			}
?>
