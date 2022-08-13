<?php

function pdvdiebstahl_dohook_private($hookname,$args=false){
	if ($hookname == "footer-hof"){
			addnav("Andere Helden");
			addnav("Verbrecherliste", "runmodule.php?module=pdvdiebstahl&op1=hof&page=1");
	}else if ($hookname == "pointsdesc"){
		$cost = get_module_setting("immun_kosten", "pdvdiebstahl");
		if ($cost > 0){
			$args['count']++;
			$format = $args['format'];
			$str = translate("Die Möglichkeit, gegen Taschendiebe immun zu sein, kostet %s Punkte.");
			$str = sprintf($str,$cost);
			output($format, $str, true);
		}
	}else{
		require_once("modules/pdvdiebstahl/pdvdiebstahl_hooks_".$hookname.".php");
		$args = func_get_args();
		call_user_func_array("pdvdiebstahl_hooks_dohook_".$hookname."_private",$args);
	}
	return $args;
}

?>