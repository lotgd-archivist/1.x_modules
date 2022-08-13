<?php
//no translation here
$creature=array();
$creature['firstroundmessage']=sprintf_translate("`\$%s shouts: \"`4Byakugan...`\$\"!`n",$args['creaturename']);
$creaturebuff=array(
			"startmsg"=>"`i`tJyuuken`\$! `q-`tKaiten`\$!`i`n`qHizashi starts to rotate which makes it virtually impossible to hit him!",
			"name"=>"`tKaiten`\$!",
			"rounds"=>-1,
			"atkmod"=>0.5,
			"defmod"=>0.8,
			"minioncount"=>1,
			"effectmsg"=>"{goodguy} can't attack {badguy} with full strength!",
			"schema"=>"module-jutsucreatures"
		);
increment_module_pref('uses',1,'specialtysystem');
set_module_pref("cache",'',"specialtysystem");
?>
