<?php

page_header("Training Grounds");
addnav("Navigation");
addnav("Back to the Academy","train.php");
output("`#`b`c`n`%R`Vinnegan`x Training`0`c`b`n`n");
output("`%Nagato`x nods at you, `4\"Go, Master this new Chakra type, and come back if you wish to learn another.\"");
require_once("modules/specialtysystem/datafunctions.php");
specialtysystem_set(array("active"=>httpget('specialty')));
if ($session['user']['specialty']!='SS') $session['user']['specialty']='SS';

?>