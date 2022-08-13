<?php

$paths=array("deva_path"=>false,
				"animal_path"=>false,
				"asura_path"=>false,
				"preta_path"=>false,
				"naraka_path"=>false,
				"human_path"=>false
				);
$serialize=serialize($paths);
set_module_pref("pathsused",$serialize);
apply_buff('kekkei_genkai_rinnegan',array(
	"startmsg"=>"`x`b`i`%R`Vinnegan`i`b `4- `yYour dojutsu is active as always, enabling you to share your field of sight, and see some normally invisble things.",
	"name"=>"`%R`Vinnegan",
	"rounds"=>-1,
	"badguyatkmod"=>0.95*(1/sqrt($stack)),
	"badguydefmod"=>0.95*(1/sqrt($stack)),
	"minioncount"=>1,
	"schema"=>"module-specialtysystem_kekkei_genkai_rinnegan",
));
$animals=array("animal_attack"=>false,"animal_defend"=>false,"animal_zofuku"=>false);
$remove=$paths+$animals;
$comp_was_removed=strip_companion(array_keys($remove)); //return value unused for now
?>
