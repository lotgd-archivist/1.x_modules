<?php
	if ($session['user']['superuser'] & SU_EDIT_COMMENTS){
			$sql="SELECT count(u.objid) AS counter FROM ".db_prefix("module_objprefs")." AS u INNER JOIN ".db_prefix('module_objprefs')." AS t ON u.objid=t.objid WHERE u.modulename='clan_avatars' AND u.objtype='clans' AND u.setting='validate' AND u.value!='1' AND t.setting='filename' AND t.value !='';";
			$result=db_query($sql);
			$num=db_fetch_assoc($result);
			if ($num['counter']>0) {
				$return=array("`\$`bCurrently there are `v%s`\$ clanavatars waiting for validation.`b`0",$num['counter']);
				$args[]=sprintf_translate($return);
			}
		}

?>
