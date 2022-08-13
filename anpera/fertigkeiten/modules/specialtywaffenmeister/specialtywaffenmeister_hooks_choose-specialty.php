<?php
if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			if ($session['user']['dragonkills']<get_module_setting("mindk")) break;
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Kampfausbildung seit der Kindheit und perfekter Umgang mit allen Waffen.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
?>
