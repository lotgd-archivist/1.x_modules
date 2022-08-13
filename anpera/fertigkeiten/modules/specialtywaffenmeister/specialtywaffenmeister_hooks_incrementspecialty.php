<?php
if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$c = $args['color'];
			$name = translate_inline($name);
			output("`n%sDu steigst ein Stufe als `&%s%s auf `#%s%s auf!", $c, $name, $c, $new, $c);
			$x = $new % 3;
			if ($x == 0){
				output("`n`^Du erhälst eine zusätzliche Anwendung!`n");
				set_module_pref("uses", get_module_pref("uses") + 1);
			}else{
				if (3-$x == 1) {
					output("`n`^Nur noch eine Stufe, bis Du eine zusätzliche Anwendung bekommst!`n");
				} else {
					output("`n`^Nur noch %s Stufen, bis Du eine zusätzliche Anwendung bekommst!`n", (3-$x));
				}
			}
			output_notl("`0");
		}
?>
