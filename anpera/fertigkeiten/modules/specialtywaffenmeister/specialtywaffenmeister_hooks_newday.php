<?php
$bonus = getsetting("specialtybonus", 1);
		if($session['user']['specialty'] == $spec) {
			$name = translate_inline($name);
			if ($bonus == 1) {
				output("`n`2Als %s%s `2erh�lst Du heute `^eine `2zus�tzliche Anwendung.`n",$ccode,$name);
			} else {
				output("`n`2Als %s%s `2erh�lst Du heute `^%s `2zus�tzliche Anwendungen.`n",$ccode,$name,$bonus);
			}
		}
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt = $amt + $bonus;
		set_module_pref("uses", $amt);
?>
