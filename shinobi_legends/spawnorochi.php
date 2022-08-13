<?php

function spawnorochi_getmoduleinfo(){
    $info = array(
        "name"=>"Spawn Orochimaru",
        "version"=>"1.0",
        "author"=>"`2Oliver Brendel, some code+idea from XChrisX",
        "category"=>"Dragon",
        "download"=>"",
        "settings"=>array(
            "Orochi Spawns! - Settings,title",
			"minhp"=>"Min HP he or a clone needs to reproduce, range,100,1000,50|100",
			"multiplierhp"=>"Multiplier to HP he gets before he produces the clone?,floatrange,0.1,1.5,0.1|0.6",
			"killdk"=>"Minimum DKs for reinforcements?,range,50,500,5|100",
			),

        
    );
    return $info;
}


function spawnorochi_install(){
	module_addhook_priority("buffdragon",INT_MAX);
    return true;
}

function spawnorochi_uninstall(){
    return true;
}

function spawnorochi_dohook($hookname,$args){
    global $session;
    switch($hookname){
		case "buffdragon":
			if ($session['user']['dragonkills']<25) return $args;
			$args['creatureaiscript']='global $badguy;
if (e_rand(0,3)==3 && $badguy[\'creaturehealth\']>'.get_module_setting('minhp').') {
  output("`@Orochimaru`$ uses a forbidden jutsu to activate a human clone of himself!`0`n
");
  $badguy[\'creaturehealth\']=round($badguy[\'creaturehealth\']*'.get_module_setting('multiplierhp').');
  $clone=$badguy;
  $clone[\'essentialleader\']=0;
  $clone[\'istarget\']=false;
  battle_spawn($clone);
  
}';
			$args['essentialleader']=true;
			if ($session['user']['dragonkills']>get_module_setting('killdk'))  require("modules/spawnorochi/oro_reinforcements.php");
				break;
		}
    return $args;
}
?>
