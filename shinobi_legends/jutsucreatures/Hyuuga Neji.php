<?php
//no translation here
$creature=array();
$creature['firstroundmessage']=sprintf_translate("`\$%s shouts: \"`4Byakugan...`\$\"!`n",$args['creaturename']);
$creaturebuff=array(
			"startmsg"=>"`i`tJyuuken`\$! `q-`tHakke Rokujyuu Yonshou`\$!`i`n`qThe enemy starts to attack you faster than you imagine and hits you multiple times... your chakra is tampered with!",
			"name"=>"`tHakke Rokujyuu Yonshou`\$!",
			"rounds"=>8,
			"maxgoodguydamage"=>round(sqrt($session['user']['dragonkills'])),
			"mingoodguydamage"=>1,
			"minioncount"=>8,
			"effectmsg"=>"{goodguy} takes {damage} damage from a Jyuuken hit!",
			"schema"=>"module-jutsucreatures"
		);
increment_module_pref('uses',2,'specialtysystem');
set_module_pref("cache",'',"specialtysystem");
$args['diddamage']=1;
?>
