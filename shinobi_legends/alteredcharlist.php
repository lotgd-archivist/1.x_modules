<?php

function alteredcharlist_getmoduleinfo(){
$info = array(
	"name"=>"Altered Charlist (Shorten names)",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel",
	"override_forced_nav"=>true,
	"category"=>"Administrative",
	"download"=>"",
	"settings"=> array(
		"differmods"=>"Differ between normal users & superusers?,bool|1",
		),
	);
	return $info;
}

function alteredcharlist_install(){
	module_addhook_priority("onlinecharlist",50);
	module_addhook_priority("index",50);
	return true;
}

function alteredcharlist_uninstall(){
	return true;
}

function alteredcharlist_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "index":
			if (is_module_active("stafflist")) {
				$sql2="SELECT a.acctid as userid ,b.value AS text FROM ".db_prefix("accounts")." AS a INNER JOIN ".db_prefix("module_userprefs")." AS b ON a.acctid=b.userid WHERE b.modulename='stafflist' AND b.setting='desc';";
				$result2=db_query_cached($sql2,"stafflist-onlinecharlist");
				$staff=array();
				while ($row2=db_fetch_assoc($result2)) {
					rawoutput("<div class='tooltip' id=\"MOD".$row2['userid']."\">");
					rawoutput(appoencode($row2['text']));
					rawoutput("</div>");
				}
				rawoutput("<script>
					<!--
					wmtt = null;

					document.onmousemove = updateWMTT;

					function updateWMTT(e) {
						x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX;
						y = (document.all) ? window.event.y + document.body.scrollTop  : e.pageY;
						if (wmtt != null) {
							wmtt.style.left = (x + 20) + \"px\";
							wmtt.style.top 	= (y + 20) + \"px\";
						}
					}

					function showWMTT(id) {
						wmtt = document.getElementById(id);
						wmtt.style.display = \"block\";
					}

					function hideWMTT() {
						wmtt.style.display = \"none\";
					}
					//-->
					</script>
					<style type=\"text/css\">
					.tooltip {
						position: absolute;
						display: none;
						background-color: #FFFFFF;
					}
					</style>");
			}
			break;
		case "onlinecharlist":
			$args=array("list"=>"","count"=>0,"handled"=>1);
			$sql="SELECT acctid,name,superuser FROM " . db_prefix("accounts") . " WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s", strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY level DESC";
			$result = db_query($sql);
			$mod=array();
			$player=array();
			$moddiffer=get_module_setting('differmods');
			$staff=array();
			if (is_module_active("visalogin")) {
				if (get_module_setting("superuser","visalogin")) $countadmins=false;
			} else $countadmins=true;
			while ($row = db_fetch_assoc($result)) {
				$row['name']=holidayize($row['name']);
				$name=substr($row['name'],0,27+(strlen($row['name'])-strlen(sanitize($row['name']))));
				if ($name[strlen($name)-1]=="`") $name=substr($name,0,strlen($name)-1); //drop a ` at the end which is possible
				if ($name!=$row['name']) $name.="...`0";
				$name=str_replace("`i","",$name);
				$name=appoencode("`^$name`0`n");
				if (($row['superuser']>0 && $row['superuser']!=SU_GIVE_GROTTO&& $row['superuser']!=SU_NEVER_EXPIRE)&& $moddiffer) {
					if ($countadmins) $args['count']++;
					if (is_module_active("stafflist")) $name=appoencode("`0")."<span onMouseOver=\"showWMTT('MOD".$row['acctid']."')\" onMouseOut=\"hideWMTT()\">".$name.appoencode("`0")."</span>";
					array_push($mod,$name);
				} else {
					array_push($player,$name);
					$args['count']++;
				}
			}

			if (get_module_setting('differmods')) {
				$args['list'].=appoencode(sprintf(translate_inline("`bStaff Characters (%s players):`b`n"),count($mod)));
				$args['list'].=implode("",$mod)."<br>";
			}
			$args['list'].=appoencode(sprintf(translate_inline("`bOnline Characters (%s players):`b`n"),count($player)));
			$args['list'].=implode("",$player);
			db_free_result($result);
			if ($args['count']==0) $args['list'].=appoencode(translate_inline("`iNone`i"));
			system("echo '".date("Y-m-d H:i:s").": ".(count($player)+count($mod))."'>> /var/log/naruto/Onlineplayers.log ",$ak);
			break;
	}
	return $args;
}

function alteredcharlist_run(){
}

?>
