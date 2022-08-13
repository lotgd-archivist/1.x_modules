<?php

page_header("Training Grounds");

require_once("modules/specialtysystem/datafunctions.php");

$choices=specialtysystem_getspecs();
$active=specialtysystem_get("active");
addnav("Chakra Specialties");

foreach ($choices as $key=>$data) {

	if ($data["noaddskillpoints"]!=0) continue;
	if ($data['modulename']==$active) continue;
	if ($data['modulename']=='specialtysystem_basic') continue; //Nothing else seemed to get rid of it, so I had to go this way.

	$spec=$data['spec_colour'].translate_inline($data['spec_name'],"module-".$data['modulename']);
	output_notl("%s:`n`n",$spec);
	
	addnav("Chakra Specialties");
	addnav_notl(" ?$spec","runmodule.php?module=circulum_rinnegan&op=set&specialty={$data['modulename']}");
	$t1 = appoencode(translate_inline($data['spec_shortdescription'],"module-".$data['modulename']));
	
	rawoutput("<a href='runmodule.php?module=circulum_rinnegan&op=set&specialty={$data['modulename']}>$t1</a><br>");
	addnav("","runmodule.php?module=circulum_rinnegan&op=set&specialty={$data['modulename']}");
	
	output_notl("`n");
}

?>