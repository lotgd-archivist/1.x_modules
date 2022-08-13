<?php
global $session,$newenemies;
if ($session['user']['dragonkills']>=100) {
	$array=array('`$','`%','`R','`^','`g','`t','`1','`q','`)');
	$followers=array(15,16,23,26,36,69,73,84,96,106,112,114,243,250,251,347);
	$reinforcements=min(round(log($session['user']['dragonkills']),0)+3,count($followers));
	//$sql="SELECT * FROM ".db_prefix('creatures')." WHERE creaturelevel>10 ORDER BY RAND() LIMIT $reinforcements;";
	//$result=db_query($sql);
	$follower=array_rand($followers,$reinforcements);debug($follower);
	output("`@Orochimaru is not alone!`n"); 
	require_once("lib/extended-battle.php");
	require_once("lib/forestoutcomes.php");
	//while ($row=db_fetch_assoc($result)) {
	foreach ($follower as $row) {
		battle_spawn($followers[$row]);
		$bad=$newenemies[count($newenemies)-1];
		$bad=buffbadguy($bad);
		$newenemies[count($newenemies)-1]=$bad;
		//output("%s%s`2 joins him!",$array[e_rand(0,$len)],$row['creaturename']);
	}
	output_notl("`n");
}
?>
