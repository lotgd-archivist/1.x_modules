<?php
	page_header("Check Module Versions");
	$verbose = httpget('verbose');
	addnav("Navigation");
	require_once("lib/superusernav.php");
	superusernav();
	addnav("Actions");
	if ($verbose=='') {
		addnav("1?Normal","runmodule.php?module=lotgdutil&mode=checkmodvers&verbose=0");
		addnav("2?Verbose","runmodule.php?module=lotgdutil&mode=checkmodvers&verbose=1");
		addnav("3?`bSUPER`b-Verbose","runmodule.php?module=lotgdutil&mode=checkmodvers&verbose=2");
		output("`&Please choose your verbose status.");
		output("`n`@%s `^Normal `&just shows you uninstalled modules.","&#149;",true);
		output("`n`@%s `^Verbose `&shows you some details, but, again, can be spammy.","&#149;",true);
		output("`n`@%s `^`bSUPER`b-Verbose `&shows you every detail, but can be spammy.","&#149;",true);
	} else {
		$versiondownload=array(); // contains the vertxtlocs
		$modules=array(); // contains modules and the Vertxtloc they point to
		$oldmodules=array(); // contains the current version
		$sql = "SELECT modulename FROM ".db_prefix("modules")." WHERE download <> 'core_module' AND download <> '' ORDER BY modulename";
		$result = db_query($sql);
		$count = db_num_rows($result);
		output("`c`QScanning Through %s installed, non-core modules.`n`n`c`0",$count);
		
		while($row = db_fetch_assoc($result)){
			$n=$row['modulename'];
			if (file_exists('modules/'.$n.'.php')) {
				require_once('modules/'.$n.'.php');
				if (function_exists($n."_getmoduleinfo")) {
					$info = $n."_getmoduleinfo";
					$info = $info();
					$oldmodules[$n]=$info['version'];
					if ($info['vertxtloc']!='') {
						$modules[$n]=$info['vertxtloc'];
					}
					if ($verbose >= 2) output("`2%s Getting %s module data.`n","&#149;",$n,true);
				} else {
					if ($verbose >= 1) output("`\$%s is missing it's %s_getmoduleinfo() function.",$module,$module);
				}
			} else {
				if ($verbose >= 1) output("`3%s not found in modules directory.`n","&#149;",$n,true);
			}
		}
		
		foreach ($modules as $name=>$verloc) {
			if ($verbose >= 1) output("`6%s Downloading the version.txt for %s","&#149;",$name,true);
			if ($verbose >= 2) rawoutput(" - $verloc");
			if ($verbose >= 1) rawoutput("<br/>");
			if (!isset($versiondownload[$verloc])){
				$verfile = fopen($verloc."version.txt", "r");
				if ($verfile){
					$vers="";
					while (!feof($verfile)) {
  						$vers .= fread($verfile, 8192); // we don't want a CSV array
					}
					fclose($verfile);

					$verlen = strlen($vers)-1;
					if (substr($vers,$verlen,1) <> ","){
						if ($verbose >= 1) output("`\$%s Version file from %s is missing it's trailing comma!.`n","&#149;",$verloc,true);
						$vers .= ",";
					}
					if ($verbose >= 1) output("`3%s Downloaded version.txt.`n","&#149;",true);
					if ($vers!='') {
						$vers = explode(",", $vers);
						$num = count($vers);
						if ($num % 2&&$num) {
							$num--;
							$x=array();
							for ($i = 0; $i < $num / 2; $i++) {
								$x[$vers[$i * 2]] = $vers[$i * 2 + 1];
							}
							$versiondownload[$verloc]=$x;
						} else {
							if ($verbose >= 1) output("`3%s version.txt hasn't got the right amount of fields...`n","&#149;",true);
							$versiondownload[$verloc]=array();
						}
					}
				} else {
					if ($verbose >= 1) output("`\$%s version.txt missing from `^%s`\$.`n","&#149;",$verloc,true);
				}
			}else{
				if ($verbose >= 2) output("`#%s version.txt already downloaded.`n","&#149;",true);
			}
		}

		if ($verbose >= 1){
			output ("`n`b`%Files and versions that can be checked:`b`n");
			foreach($versiondownload as $url => $y){
				output("`3%s `!URL `#%s`! Versions:`n","&#149;",$url,true);
				foreach ($y as $name=>$version) {
					output("%s`#%s `!Module: `@%s `!- Newest version: `@%s`n","&nbsp;&nbsp;&nbsp;","&#149;",$name,$version,true);
				}
				rawoutput("<br/>");
			}
		}

		output("`c`b`#Modules that can be upgraded.`b`n");
		output("`@NOTE: If you are running some older modules Download Links may be broken!`n");
		output("`@If this is the case please visit www.dragonprime.net to download Manually.`n`n`c`0");
		$x=0;
		foreach ($modules as $module=>$verloc) {
			if (isset($versiondownload[$verloc])) {
				$y=$versiondownload[$verloc];
				if (isset($y[$module])) {
					if (isset($oldmodules[$module])||empty($oldmodules[$module])) {
						if ($oldmodules[$module]<$y[$module]) {
							if (function_exists($module."_getmoduleinfo")) {
								$info = $module."_getmoduleinfo";
								$info = $info();
								rawoutput("<span class='colLtYellow'>&#149; <a class='colLtYellow'  href='{$info['download']}' target='_blank'>");
								output("%s is now at version %s.  Version %s is available.`n",$info['name'],$info['version'],$y[$module]);
								rawoutput("</a></span>");
								$x++;
							} else {
								if ($verbose >= 1) output("`\$%s %s is missing it's %s_getmoduleinfo() function.`n","&#149;",$module,$module,true);
							}
						}
					} else {
						if ($verbose >= 1) output("`\$%s %s has no version set.`n","&#149;",$module,true);
					}
				} else {
					if ($verbose >= 1) output("`\$%s The %sverloc%s which `^%s`\$ points to doesn't mention the `^%s`\$ module.`n","&#149;","<a href='$verloc' target='_blank' class='colLtCyan'>","</a>",$module,$module,true);
				}
				unset($versiondownload[$verloc][$module]);
			} else {
				if ($verbose >= 1) output("`\$%s The verloc which `^%s`\$ points to is the wrong URL.`n","&#149;",$module,true);
			}
		}

		if (httpget('op')=='nonexist') {
			output("`n`b`c`#Uninstalled Modules.`c`b`n");
			foreach ($versiondownload as $url=>$modules) {
				foreach ($modules as $m=>$ver) {
					output("`\$%s v`6%s`\$ - `^%s.php`\$.`n%sFrom: ","&#149;",$ver,$m,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",true);
					rawoutput("<a href='$url' target='_blank'>".str_replace("http://","",str_replace("http://www.","",$url))."</a>");
					output("`\$.`n");
				}
			}
		}

		if ($x == 0) output("`4You are up to date on supported modules!`n`0");
	
		output("`n`@Checked against the database of %s modules!`n",$count);
		output("Please report errors to their respective author (ex. debug errors, broken links, module at target location not the version that is reported here).`nNon-core modules can also be found at www.dragonprime.com .`nRemember the search function!");
		addnav("R?Refresh List","runmodule.php?module=lotgdutil&mode=checkmodvers&verbose=$verbose");
		addnav("U?Refresh List (Show Uninstalled)","runmodule.php?module=lotgdutil&mode=checkmodvers&verbose=$verbose&op=nonexist");
		addnav("V?Rechoose Verbose Status","runmodule.php?module=lotgdutil&mode=checkmodvers");
	}
?>