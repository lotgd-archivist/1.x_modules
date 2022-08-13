<?php
if ($session['user']['race'] == $race) {
				$args['chance'] = get_module_setting("minedeathchance");
				$args['racesave'] = "`n`3Es ist so weit, das Reich der Schatten ruft nach Dir! Erregt hebst Du Deine Arme und freust Dich auf Deinen Tod. Erst im letzten Moment springst Du dann doch zur Seite - und bereust es sofort wieder ...`n";
				$args['schema']="module-racevanthira";
			}
?>
