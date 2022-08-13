<?php

page_header("Dwellings");
output("`xAs you look around the dwellings, you notice a Jounin, who is keeping a ledger of all those sleeping here.");
output(" Knowing your `THuman Path `xhas certain ways of getting information from people it approaches the Nin.");
output("`n`n`\$Note using this ability costs two chakra points.");
require_once("modules/specialtysystem/functions.php");
addnav("Actions");
if (specialtysystem_availableuses()>1) addnav("Steal his knowledge (2)","runmodule.php?module=circulum_rinnegan&op=steal");
addnav("Return to Hamlet","runmodule.php?module=dwellings");

?>