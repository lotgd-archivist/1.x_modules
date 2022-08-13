<?php
function racevampir_uninstall_private(){
global $session;
    // Force anyone who was a Vampire to rechoose race
    $sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Vampir'";
    db_query($sql);
    if ($session['user']['race'] == 'Vampir')
        $session['user']['race'] = RACE_UNKNOWN;
    return true;
}	
?>
