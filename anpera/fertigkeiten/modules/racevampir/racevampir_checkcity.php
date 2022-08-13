<?php
    $race="Vampir";
    if (is_module_active("racehuman")) {
        $city = get_module_setting("villagename", "racehuman");
    } else {
        $city = getsetting("villagename", LOCATION_FIELDS);
    }
    
    if ($session['user']['race']==$race && is_module_active("cities")){
        //if they're this race and their home city isn't right, set it up.
        if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
            set_module_pref("homecity",$city,"cities");
        }
    } 
?>
