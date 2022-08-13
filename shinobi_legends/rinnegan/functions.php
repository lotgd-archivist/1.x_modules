<?php

function check_paths() {
	global $companions;
	$paths = array("deva_path","animal_path","asura_path","preta_path","naraka_path","human_path");
	$return = array("deva_path"=>false,
				"animal_path"=>false,
				"asura_path"=>false,
				"preta_path"=>false,
				"naraka_path"=>false,
				"human_path"=>false
				);
	foreach($companions as $name=>$companion) {
		
		if(in_array($name,$paths)) {
			$return[$name]=true;
		}
		
	}
	return $return;
}

?>