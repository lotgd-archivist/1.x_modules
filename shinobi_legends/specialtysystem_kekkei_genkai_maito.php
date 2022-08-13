<?php

function specialtysystem_kekkei_genkai_maito_getmoduleinfo(){
	$info = array(
		"name" => "Specialty System - Maito Kekkei Genkai",
		"author" => "`2Oliver Brendel`0",
		"version" => "1.0",
		"download" => "",
		"category" => "Specialty System",
		"requires"=> array(
			"specialtysystem"=>"1.0|Specialty System by `2Oliver Brendel",
			),
	);
	return $info;
}

function specialtysystem_kekkei_genkai_maito_install(){
	module_addhook("specialtysystem-register");
	return true;
}

function specialtysystem_kekkei_genkai_maitospecialtysystem_kekkei_genkai_maito_uninstall(){
	require_once("modules/specialtysystem/uninstall.php");
	specialtysystem_uninstall("specialtysystem_kekkei_genkai_maito");
	return true;
}

function specialtysystem_kekkei_genkai_maito_fightnav(){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$uses=specialtysystem_availableuses();
	$pers=get_module_pref("stack","circulum_maito");
	$name=translate_inline('`!H`1ard Work');
	tlschema('module-specialtysystem_kekkei_genkai_maito');
	$su=$session['user']['dragonkills'];
	$hyuuga=(int)get_module_pref("stack","circulum_hyuuga");
	$uchiha=(int)get_module_pref("stack","circulum_uchiha");
	if ($uses > 0 && $pers>0) {
		$buffs=$session['bufflist'];
		if (isset($buffs['kekkei_maito'])) $buffs=$buffs['kekkei_maito'];
		specialtysystem_addfightheadline($name, false,specialtysystem_getskillpoints("specialtysystem_kekkei_genkai_maito"));
		require_once("lib/buffs.php");
		if (!has_buff('kekkei_maito')) {
			$active=0;
		} else 
			$active=(int)$buffs['stage'];
		if ($pers>1) {
			switch ($active) {
				case 7:
					if ($uses>5 && $su>15) specialtysystem_addfightnav("`\$死門, Shimon","gate8&cost=5&drain=0",5);
					break;
				case 6:
					if ($uses>4 && $su>6) specialtysystem_addfightnav("`\$驚門, Kyōmon","gate7&cost=4&drain=0.18",4);
					break;
				case 5:
					if ($uses>3 && $su>4) specialtysystem_addfightnav("`\$景門, Keimon","gate6&cost=3&drain=0.22",3);
					break;
			}		
		}
		switch ($active) {

			case 4:
				if ($uses>2 && $su>2) specialtysystem_addfightnav("`y杜門, Tomon","gate5&cost=3&drain=0.19",3);
				break;
			case 3:
				if ($uses>2 && $su>2) specialtysystem_addfightnav("`y傷門, Shōmon","gate4&cost=2&drain=0.16",2);
				break;
			case 2:
				if ($uses>1 && $su>1) specialtysystem_addfightnav("`y生門, Seimon","gate3&cost=2&drain=0.13",2);
				break;
			case 1:
				if ($uses>0) specialtysystem_addfightnav("`y休門, Kyūmon","gate2&cost=1&drain=0",1);
				break;
			case 0:
				if ($uses>0) specialtysystem_addfightnav("`y開門, Kaimon","gate1&cost=1&drain=0.1",1);
		}
		if ($active>0) { 
			    if ($uses>0) specialtysystem_addfightnav("`\$表蓮華, `yOmote Renge","minilotus&cost=1",1);
		}
		{
		if (has_buff('kekkei_maito') && $uses >=0) specialtysystem_addfightnav("`gClose the `\$G`yates","closethegates&cost=0",0);
		}
		if ($active>=1 && $uses>0 && $hyuuga>0 && has_buff("kekkei_genkai_hyuuga_1")) {
			specialtysystem_addfightnav("`VO`%ugi: `@S`2aikyō `xGouken","byakugouken&cost=1",1);
		}
		if ($active>=1 && $uses>0 && $uchiha>0 && (has_buff("kekkei_genkai_uchiha_1") || has_buff("kekkai_genkai_uchiha_2") || has_buff("susanoo_1"))) {
			specialtysystem_addfightnav("`)S`4aik`\$yō `3R`Ke`gn`Kd`3a","sharigouken&cost=1",1);
		}
		if ($active>2) {  
				if ($uses>2) specialtysystem_addfightnav("`\$裏蓮華, Ura Renge","lotus&cost=3",3);
		}
		if ($active>3) {
				if ($uses>2) specialtysystem_addfightnav("`\$木ノ葉龍神, `#K`lon`voha `#R`lyū`vjin","ryujin&cost=3",3);
		}
		if ($active>5) { 
				if ($uses>3) specialtysystem_addfightnav("`\$朝孔雀, Asa Kujaku","asa&cost=4",4);
		}		
		if ($active>6) { 
				specialtysystem_addfightnav("`\$昼虎, `)H`jiru`)d`jor`)a","hiru&cost=0",0);
		}
		if ($active>7) { 
				specialtysystem_addfightnav("`\$夕象, `7S`ve`jki`vz`7ō","sekizo&cost=0",0);
				specialtysystem_addfightnav("`\$夜ガイ, `)Y`4a`\$g`4a`)i","yagai&cost=0",0);
		}
		
			
	}
	tlschema();
	return specialtysystem_getfightnav();
}

function specialtysystem_kekkei_genkai_maito_apply($skillname){
	global $session;
	require_once("modules/specialtysystem/functions.php");
	$cost=httpget('cost');
	$drain=httpget('drain');
	$uses=specialtysystem_availableuses();
	$pers=get_module_pref("stack","circulum_maito");
	$buffs=$session['bufflist'];
	if (isset($buffs['kekkei_maito'])) $buffs=$buffs['kekkei_maito'];
	require_once("lib/buffs.php");
	if (!has_buff('kekkei_maito')) {
		$stage=0;
	} else 
		$stage=(int)$buffs['stage'];
	if (!has_buff('kekkei_maito') && $stage>1) {
		output("`\$You need to initate the Hachimon in order to proceed! Your inner gates have closed...`n");
		return;
	}
	switch($skillname){
		case "closethegates":
			require_once("lib/buffs.php");
			strip_buff('kekkei_maito');
			output("`@You cool off as your gates close.`n");
			break;
		case "byakugouken": // hyuga+gates combo move, total cost 2
			apply_buff('kekkei_byakugouken',array(
				"startmsg"=>"`VO`%ugi: - `@S`2aikyō `xGouken`b`4!`b `yUtilizing your Hyūga traits, you empower your strikes with Jyūken!",
				"name"=>array("`@S`2aikyō `xGouken"),
				"rounds"=>10,
				"effectmsg"=>"The chakra shockwaves of your blows deal `b{damage} damage`b to {badguy}!",
				"minbadguydamage"=>$session['user']['dragonkills']+40,
				"maxbadguydamage"=>$session['user']['dragonkills']+60,
				"atkmod"=>2.0,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;
		case "sharigouken": // uchiha+gates combo move, total cost 2
			apply_buff('kekkei_sharigouken',array(
				"startmsg"=>"`)S`4aik`\$yō `3R`Ke`gn`Kd`3a`b`4!`b `yAvoiding attacks thanks to your Sharingan, you execute a combo without equal!",
				"name"=>array("`)S`4aik`\$yō `3R`Ke`gn`Kd`3a!"),
				"rounds"=>10,
				"effectmsg"=>"Your combo deals `b{damage} damage`b to {badguy}!",
				"minbadguydamage"=>$session['user']['dragonkills']+40,
				"maxbadguydamage"=>$session['user']['dragonkills']+60,
				"defmod"=>2.0,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;
		case "minilotus": // total cost 2
			apply_buff('kekkei_minilotus',array(
				"startmsg"=>"`\$表蓮華, `yOmote Renge`b`4!`b `yWith your brain's limiter released, you perform a devastating series of kicks!",
				"name"=>array("`\$表蓮華, `yOmote Renge!"),
				"rounds"=>10,
				"effectmsg"=>"Your kicks deal `b{damage} damage`b to {badguy}!",
				"minbadguydamage"=>$session['user']['dragonkills']+25,
				"maxbadguydamage"=>$session['user']['dragonkills']+40,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;
		case "lotus": //total cost 7
			apply_buff('kekkei_ura',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - `\$`b裏蓮華, Ura Renge`b`4!`b `yYou deliver a single, yet devastating strike to your enemy!",
				"name"=>array("`\$裏蓮華, Ura Renge!"),
				"rounds"=>1,
				"effectmsg"=>"You smash {badguy} with extreme force for `b{damage} damage`b!",
				"minbadguydamage"=>$session['user']['dragonkills']+100,
				"maxbadguydamage"=>$session['user']['dragonkills']*$stage+500,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;
			case "ryujin": //total cost 9
			apply_buff('kekkei_ryujin',array(
				"startmsg"=>"`\$木ノ葉龍神, `#K`lon`voha `#R`lyū`vjin`b`4!`b `yLaunching into a vicious spin, you reach enough velocity to form a tornado!",
				"name"=>array("`\$木ノ葉龍神, `#K`lon`voha `#R`lyū`vjin!"),
				"rounds"=>15,
				"effectmsg"=>"The roaring winds tear into {badguy} for `b{damage} damage`b!",
				"areadamage"=>true,
				"minbadguydamage"=>$session['user']['dragonkills']+40,
				"maxbadguydamage"=>$session['user']['dragonkills']+60,
				"minioncount"=>e_rand(3,5),
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;
		case "asa": // total cost 16
			apply_buff('kekkei_asa',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - `\$`b朝孔雀, Asa Kujaku`b`4!`b `yYou begin punching at such speed your fists catch on fire!",
				"name"=>array("`\$朝孔雀, Asa Kujaku!"),
				"rounds"=>3,
				"effectmsg"=>"Your blows scorch {badguy} for `b{damage} damage`b!",
				"areadamage"=>true,
				"minbadguydamage"=>$session['user']['dragonkills']+50,
				"maxbadguydamage"=>$session['user']['dragonkills']*$stage+50,
				"minioncount"=>e_rand(10,20),
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;
        case "hiru": // total cost 16
		    // require_once("lib/buffs.php"); not sure if needed
			apply_buff('kekkei_hiru',array(
				"startmsg"=>"`LH`loero, `LW`laga `LS`leishun - `\$昼虎, `)H`jiru`)d`jor`)a`b`4!`b `yStriking your palm to create tremendous air pressure, you launch it with a blindingly fast thrust!",
				"name"=>array("`\$昼虎, `)H`jiru`)d`jor`)a"),
				"rounds"=>1,
				"effectmsg"=>"The tiger head's explosion deals `b{damage} damage`b to {badguy}!",
				"areadamage"=>true,
				"minbadguydamage"=>$session['user']['dragonkills']+400,
				"maxbadguydamage"=>$session['user']['dragonkills']*$stage+1000,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			if ($session['user']['hitpoints']>50){
				output("`4Your body feels the strain of such a powerful technique... `\$You lose some health!`n");
				$session['user']['hitpoints']-=50;
			}
			if ($session['user']['hitpoints']<=50){
				output("`4You have pushed yourself beyond your limits... `\$Your gates close after your attack!`n");
				$session['user']['hitpoints']=10;
				strip_buff('kekkei_maito');
			}
			break;
        case "sekizo": // total cost 21
		    // require_once("lib/buffs.php"); not sure if needed
			apply_buff('kekkei_sekizo',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō `@N`2o `@J`2in - `\$夕象, `7S`ve`jki`vz`7ō!`b`4!`b `yYou deliver five punches with such force that each one is a veritable air cannon!",
				"name"=>array("`\$夕象, `7S`ve`jki`vz`7ō!"),
				"rounds"=>5,
				"effectmsg"=>"You blast {badguy} for `b{damage} damage`b!",
				"areadamage"=>true, // maybe disable if too strong
				"minbadguydamage"=>$session['user']['dragonkills']+200,
				"maxbadguydamage"=>$session['user']['dragonkills']*$stage+800,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			if ($session['user']['hitpoints']>70){
				output("`4You suffer bone fractures from the power of your technique... `\$You lose some health!`n");
				$session['user']['hitpoints']-=70;
			}
			if ($session['user']['hitpoints']<=70){
				output("`4You have pushed yourself beyond your limits... `\$Your gates close after your attack!`n");
				$session['user']['hitpoints']=10;
				strip_buff('kekkei_maito');
			}
			break;
        case "yagai": // total cost 21
		    // require_once("lib/buffs.php"); not sure if needed
			apply_buff('kekkei_yagai',array( 
				"startmsg"=>"`4S`\$eki - `4R`\$yū - `\$夜ガイ, `)Y`4a`\$g`4a`)i`b`4!`b `yAmassing your boiling chakra into a dragon shaped shroud around yourself, you soar towards your enemy!",
				"name"=>array("`\$夜ガイ, `)Y`4a`\$g`4a`)i!"),
				"rounds"=>1,
				"effectmsg"=>"You obliterate your enemy with a kick powerful enough to bend space!",
				"areadamage"=>true,
				"minbadguydamage"=>$session['user']['dragonkills']+5000,
				"maxbadguydamage"=>$session['user']['dragonkills']*$stage+10000,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			$dkhp=0;
			foreach ($session['user']['dragonpoints'] as $val){
				if ($val=="hp") $dkhp++;
			}
			$minhp=10*$session['user']['level']+5*$dkhp;
			if ($minhp<=$session['user']['maxhitpoints']) {
				output("`4You wield power beyond comprehension... barely clinging to life as your gates close afterward. `\$Ashen scars remind you of the price you paid for it!`n");
				$session['user']['maxhitpoints']--;
				strip_buff('kekkei_maito');
			}
			if ($session['user']['hitpoints']>10) $session['user']['hitpoints']=1;	
			break;			
		case "gate1":
			apply_buff('kekkei_maito',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - Dai-ichi Kaimon `iKAI`i!",
				"name"=>array("`x`@H`2achimon `@T`2onkō Gate %s",1),
				"stage"=>1,
				//"rounds"=>10,
				"rounds"=>-1,
				"regen"=>-$session['user']['hitpoints']*$drain,
				"effectmsg"=>"",
				"atkmod"=>1.5,
				"defmod"=>1.2,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;
		case "gate2":
			apply_buff('kekkei_maito',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - Dai-Ni Kyūmon `iKAI`i!",
				"name"=>array("`x`@H`2achimon `@T`2onkō Gate %s",2),
				"stage"=>2,
				"effectmsg"=>"Your wounds close...",
				"rounds"=>10,
				"regen"=>$session['user']['level'],
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])	$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			break;
		case "gate3":
			apply_buff('kekkei_maito',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - Dai-San Seimon `iKAI`i!",
				"name"=>array("`x`@H`2achimon `@T`2onkō Gate %s",3),
				"stage"=>3,
				"effectmsg"=>"",
				//"rounds"=>10,
				"rounds"=>-1,
				"regen"=>-$session['user']['hitpoints']*$drain,
				"atkmod"=>1.7,
				"defmod"=>1.5,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;
		case "gate4":
			apply_buff('kekkei_maito',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - Dai-Yon Shōmon `iKAI`i!",
				"name"=>array("`x`@H`2achimon `@T`2onkō Gate %s",4),
				"stage"=>4,
				"effectmsg"=>"",
				"atkmod"=>1.8,
				"defmod"=>1.7,
				//"rounds"=>20,
				"rounds"=>-1,
				"regen"=>-$session['user']['hitpoints']*$drain,
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;
		case "gate5":
			apply_buff('kekkei_maito',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - Dai-Go Tomon `iKAI`i!",
				"name"=>array("`x`@H`2achimon `@T`2onkō Gate %s",5),
				"stage"=>5,
				"effectmsg"=>"",
				//"rounds"=>20,
				"rounds"=>-1,
				"regen"=>-$session['user']['hitpoints']*$drain,
				"atkmod"=>2.0,
				"defmod"=>2.5,				
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			break;			
		case "gate6":
			apply_buff('kekkei_maito',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - Dai-Roku Keimon `iKAI`i!",
				"name"=>array("`x`@H`2achimon `@T`2onkō Gate %s",6),
				"stage"=>6,
				"effectmsg"=>"",
				//"rounds"=>20,
				"rounds"=>-1,
				"regen"=>-$session['user']['hitpoints']*$drain,
				"atkmod"=>3.0,
				"defmod"=>2.6,					
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));			
			break;
		case "gate7":
			apply_buff('kekkei_maito',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - Dai-Nana Kyōmon `iKAI`i!",
				"name"=>array("`x`@H`2achimon `@T`2onkō Gate %s",7),
				"stage"=>7,
				"effectmsg"=>"",
				//"rounds"=>20,
				"rounds"=>-1,
				//"regen"=>max(1,round(log($session['user']['dragonkills']+1),0)), //$session['user']['dragonkills']/2, //maybe disable or nerf if hirudora too spammable
				"regen"=>-$session['user']['hitpoints']*$drain,
				"atkmod"=>3.0,
				"defmod"=>2.7,					
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])	$session['user']['hitpoints']=$session['user']['maxhitpoints'];			
			break;
		case "gate8":
			apply_buff('kekkei_maito',array(
				"startmsg"=>"`@H`2achimon `@T`2onkō - Dai-Hachi Shimon `iKAI`i!",
				"name"=>array("`x`@H`2achimon `@T`2onkō Gate %s",8),
				"stage"=>8,
				"effectmsg"=>"",
				"rounds"=>20,
				"atkmod"=>4.0,
				"defmod"=>3.5,					
				"minioncount"=>1,
				"schema"=>"module-specialtysystem_kekkei_genkai_maito"
			));
			$dkhp=0;
			foreach ($session['user']['dragonpoints'] as $val){
				if ($val=="hp") $dkhp++;
			}
			$minhp=10*$session['user']['level']+5*$dkhp;
			if ($minhp<=$session['user']['maxhitpoints']) {
				output("`4You open the last gate... power rushes through you, but your life force also get damaged. `\$You lose one permanent hitpoint!`n");
				$session['user']['maxhitpoints']--;
			}
			if ($session['user']['hitpoints']>10) $session['user']['hitpoints']=10;			
			break;	


	}
	specialtysystem_incrementuses("specialtysystem_kekkei_genkai_maito",$cost);
	return;
}

function specialtysystem_kekkei_genkai_maito_dohook($hookname,$args){
	switch ($hookname) {
	case "specialtysystem-register":
		$args[]=array(
			"spec_name"=>'Kekkei Genkai Hachimon Tonkou',
			"spec_colour"=>'`x',
			"spec_shortdescription"=>'-internal-',
			"spec_longdescription"=>'-internal-',
			"modulename"=>'specialtysystem_kekkei_genkai_maito',
			"fightnav_active"=>'1',
			"newday_active"=>'0',
			"dragonkill-active"=>'0',
			"noaddskillpoints"=>1,
			"dragonkill_minimum_requirement"=>-1
			);
		break;
	}
	return $args;
}

function specialtysystem_kekkei_genkai_maito_run(){
}
?>