<?php

function wettkampf_best_bogen_run_private($args){
	global $session;
		
	page_header("Die Wettbewerbsergebnisse im Bogenschießen");
	
	$page=$_GET[page];
	
	//Folgendes wurde z.T. von hof.php übernommen:
		
		$playersperpage = 50;
		
		$op2= httpget('op2');
		
		$subop = httpget('subop');
		if ($subop == "") $subop = "most";
		
		$fertigkeit=$op2;
		$zahl=0;
		if ($op2 == "wbogen0" || $op2 == "wbogen1" || $op2 == "wbogen2" || $op2 == "wbogen3") $zahl=10000;
		
		$sql = "SELECT count(*) AS c FROM ".db_prefix("module_userprefs")." AS t1 LEFT JOIN ".db_prefix("accounts")." AS t2 ON t2.acctid=t1.userid WHERE locked=0 AND setting='$fertigkeit' AND (value+0)!=$zahl";
		$extra = "";
		
		$fest=get_module_setting("fest");
		if ($fest==0) addnav("Letzter Wettbewerb");
		if ($fest==1) addnav("Laufender Wettbewerb");
		
		$result = db_query($sql.$extra);
		$row = db_fetch_assoc($result);
		$totalplayers = $row['c'];
		
		addnav("Reiterschießen", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=wbogen0&subop=$subop&page=$page");
		addnav("Blindschießen", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=wbogen1&subop=$subop&page=$page");
		addnav("Schnellschießen", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=wbogen2&subop=$subop&page=$page");
		addnav("Gesamtwertung", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=wbogen3&subop=$subop&page=$page");
		addnav("Bestleistungen");
		addnav("Reiterschießen", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=bestbogen0&subop=$subop&page=$page");
		addnav("Blindschießen", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=bestbogen1&subop=$subop&page=$page");
		addnav("Schnellschießen", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=bestbogen2&subop=$subop&page=$page");
		addnav("Gesamtwertung", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=bestbogen3&subop=$subop&page=$page");
		if ($session['user']['superuser']&~SU_DOESNT_GIVE_GROTTO)addnav("Superuser", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=bogen&subop=$subop&page=$page");
		addnav("Sortierung");
		addnav("Die besten", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=$op2&subop=most&page=$page");
		addnav("Die schlechtesten", "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=$op2&subop=least&page=$page");
		addnav("Seiten");
		
		$page = (int)httpget('page');
		if ($page == 0) $page = 1;
		$pageoffset = $page;
		if ($pageoffset > 0) $pageoffset--;
		$pageoffset *= $playersperpage;
		$from = $pageoffset+1;
		$to = min($pageoffset+$playersperpage, $totalplayers);
		$limit = "$pageoffset,$playersperpage";
		
		for($i = 0; $i < $totalplayers; $i+= $playersperpage) {
		    $pnum = ($i/$playersperpage+1);
		    $min = ($i+1);
		    $max = min($i+$playersperpage,$totalplayers);
		    addnav(array("Seite %s (%s-%s)", $pnum, $min, $max), "runmodule.php?module=wettkampf&op1=aufruf&subop1=best&subop2=wbogenbest&op2=$op2&subop=$subop&page=$pnum");
		}
		
		if ($subop == "least") {
	        $ow = "DESC";
			$order = "ASC";
	        $meop = "<=";
	    }else{
	        $ow = "ASC";
			$order = "DESC";
	        $meop = ">=";
	    }
	
	    include("modules/wettkampf/wettkampf_best_bogen_".$op2.".php");
	    		
		if (isset($table) && is_array($table)){
		    call_user_func_array("display_table",$table);
		}
		addnav("Zurück");
		addnav("Zur Ruhmeshalle", "hof.php");
		page_footer();
}
?>