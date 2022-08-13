<?php
//no translation here
$creature=array();
$creature['firstroundmessage']=sprintf_translate("`\$%s whispers: \"`4Byakugan...`\$\"!`n",$args['creaturename']);
$creaturebuff=array(
			"startmsg"=>"`i`tJyuuken`\$! `q-`tShugohakke Rokujūyon Shō`\$!`i`n`qHinata gets into kamae and engulfs you with pinpointed chakra hits!",
			"name"=>"`tShugohakke Rokujūyon Shō`\$!",
			"rounds"=>8,
			"maxgoodguydamage"=>round(sqrt($session['user']['dragonkills'])),
			"mingoodguydamage"=>1,
			"minioncount"=>8,
			"atkmod"=>0.8,
			"defmod"=>0.8,
			"effectmsg"=>"{goodguy} takes {damage} damage from a Jyuuken hit!",
			"schema"=>"module-jutsucreatures"
		);
increment_module_pref('uses',2,'specialtysystem');
set_module_pref("cache",'',"specialtysystem");
$args['diddamage']=1;
?>
