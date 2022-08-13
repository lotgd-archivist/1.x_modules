<?php
//no translation here
$creature=array();
$creature['firstroundmessage']=sprintf_translate("`\$%s shouts: \"`4I will avenge my parents...! And Obi-Wan!`\$\"`n",$args['creaturename']);
$creaturebuff=array(
			"startmsg"=>"`i`b`tChi`1dori`b`4!`i`n`qSasuke starts to attack you faster than you imagined and hits you with his lighting chidori!",
			"name"=>"`tChi`1dori",
			"rounds"=>1,
			"mingoodguydamage"=>60+e_rand(5,$session['user']['dragonkills']),
			"maxgoodguydamage"=>90+e_rand(5,$session['user']['dragonkills']),
			"minioncount"=>1,
			"effectmsg"=>"{goodguy} is hit by a brutal attack for {damage} damage!",
			"schema"=>"module-jutsucreatures"
		);
$args['diddamage']=1;
?>
