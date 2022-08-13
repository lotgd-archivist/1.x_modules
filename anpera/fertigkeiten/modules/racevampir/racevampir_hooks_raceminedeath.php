<?php
if ($session['user']['race'] == $race) {
            $args['chance'] = get_module_setting("minedeathchance");
            $args['racesave'] = "`n`4Geschwind verwandelst Du Dich in eine Fledermaus und entkommst auf diese Weise dem Mineneinsturz.`n";
            $args['schema']="module-racevampir";
        }
?>
