<?php

//fuujinscript
global $enemies,$badguy;
if ($badguy['done']!=1) {
	if ($enemies[1]['dead']) {
		$badguy['done']=1;
		$session['user']['specialmisc']='fuujinraijin:done';
		output("`PYou `\$killed`P my brother %s`P! `\$AAAARRRRGHHHH I will smash you!`0`n",get_module_setting('name2','fuujinraijin'));
		$badguy['creatureattack']*=2;
	}
}

?>