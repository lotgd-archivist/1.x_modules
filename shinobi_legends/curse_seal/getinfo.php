<?php
	$info = array(
	"name"=>"Curse Seals",
	"author"=>"`2Oliver Brendel",
	"version"=>"1.0",
	"category"=>"Extrordinary Abilities",
	"download"=>"http://dragonprime.net/dls/curse_seal.zip",
	"settings"=>array(
		"Curse Seal - Preferences, title",
		"`isupports the alignment module`i,note",
		"Get and set curse seal (2 Levels),note",
		"name"=>"Name of the seal,text|`)C`~u`)r`~s`)e `)Seal",
		"dk"=>"How many dks before you can see it at the vampire,floatrange,2,15,1|8",
		"Note: The cost is only deducted if the survives the seal and is equal to the vampirelords cost setting,note",
		"survival"=>"Out of x how many survive the procedure?,floatrange,1,25,1|10",
		"level2"=>"How many days has he to train successfully with the Sound Five in order to get Level 2?,floatrange,1,25,1|10",
		"soundfive"=>"Name of the Sound Five,text|`)S`~ou`)nd `tFi`gve",
		"locationfive"=>"Where are the Sound Five?,location",
	),
	"prefs" => array(
		"hasseal"=>"Has this user a curse seal(0=no 1=level 1 and 2=level2)?,int",
		"days"=>"How many days does he have it?,int|0",
		"sparring"=>"How many times successfully trained with the Sound 5,int|0",
		"todaylevel2"=>"How often used Level 2 today?,int|0",
		),
	"requires"=>array(
		"vampirelord"=>"1.1|Mike Counts, conversion by XChrisX, translated back by `2Oliver Brendel",
		),
	);
?>