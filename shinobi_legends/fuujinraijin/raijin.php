<?php

//raijinscript
global $enemies,$badguy;
if ($badguy['done']!=1) {
	if ($enemies[0]['dead']) {
		$badguy['done']=1;
		output("`PYou `\$killed`P my brother %s`P! `\$AAAARRRRGHHHH I will smash you!`0`n",get_module_setting('name1','fuujinraijin'));
		$badguy['creatureattack']*=2;
	}
}

?>