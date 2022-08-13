<?php
//no translation here
$creature=array();
$creature['firstroundmessage']='';
$creaturebuff=array(
			"startmsg"=>"`i`b`tChi`1dori`b`4!`i`n`qKakashi starts to attack you faster than you imagined and hits you with his lighting chidori!",
			"name"=>"`tChi`1dori",
			"rounds"=>1,
			"mingoodguydamage"=>80+e_rand(5,$session['user']['dragonkills']),
			"maxgoodguydamage"=>135+e_rand(5,$session['user']['dragonkills']),
			"minioncount"=>1,
			"effectmsg"=>"{goodguy} is hit by a brutal attack for {damage} damage!",
			"schema"=>"module-jutsucreatures"
		);
$args['diddamage']=1;
?>
