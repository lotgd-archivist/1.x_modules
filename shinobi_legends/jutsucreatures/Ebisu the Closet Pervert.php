<?php
//no translation here
$creature=array();
$creature['firstroundmessage']=sprintf_translate("`\$%s shouts: \"`4You won't get me...`\$\"!`n",$args['creaturename']);
$creaturebuff=array(
			"startmsg"=>"`i`qKa`\$ton `q-`\$ Goukakyuu no Jutsu!`i`n`qEbisu `\$utilizes some chakra and exhales a large ball of `4flame `\$from his mouth!",
			"name"=>"`\$Katon `4- `\$Goukyaku `4no `\$Jutsu",
			"rounds"=>1,
			"mingoodguydamage"=>15+e_rand(5,$session['user']['dragonkills'])+$session['user']['level'],
			"maxgoodguydamage"=>20+e_rand(5,$session['user']['dragonkills'])+$session['user']['level'],
			"minioncount"=>1,
			"effectmsg"=>"{goodguy} suffers {damage} damage from burns!",
			"minioncount"=>1,
			"schema"=>"module-jutsucreatures"
		);
$args['diddamage']=1;
?>
