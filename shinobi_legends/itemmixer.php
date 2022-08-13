<?php

function itemmixer_getmoduleinfo() {
	$info = array(
	    "name"=>"Item Mixer",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Items",
		"download"=>"",
		"settings"=>array(
				"Itemmixer - Preferences,title",
				"owner"=>"Name of the Owner (female),text|`jT`&emari",
				"modificator"=>"Dragonkills of the recipe * X +99 == Cost,floatrange,0.5,200.0,0.5|100.0",
			),
		"prefs"=>array(
			    "Item Mixer - User prefs,title",
				//"recipes"=>"Which recipes does the user have (comma seperated)?,text",
			),
		);
    return $info;
}

function itemmixer_install() {
	module_addhook_priority("village-Kirigakure",50);
	require_once("lib/tabledescriptor.php");
	$components=array(
		'recipeid'=>array('name'=>'recipeid', 'type'=>'int(11) unsigned', 'extra'=>'auto_increment'),
		'itemid'=>array('name'=>'itemid','type'=>'int(11) unsigned'),
		'quantity'=>array('name'=>'quantity','type'=>'int(11) unsigned','default'=>'1'),
		'key-PRIMARY' => array('name'=>'PRIMARY', 'type'=>'primary key', 'unique'=>'1', 'columns'=>'recipeid,itemid'),
		);
	synctable(db_prefix("itemmixer_components"), $components, true);
	return true;
}

function itemmixer_uninstall() {
	if(db_table_exists(db_prefix("itemmixer_components"))){
		db_query("DROP TABLE ".db_prefix("itemmixer_components"));
	}
  return true;
}


function itemmixer_dohook($hookname, $args){
	global $session;

	switch ($hooksname) {
	
		default:
		tlschema($args['schemas']['marketnav']);
    	addnav($args['marketnav']);
    	tlschema();		
		addnav(array("W?%s`2's Workshop",get_module_setting('owner')),"runmodule.php?module=itemmixer");	
	}
	return $args;
}

function itemmixer_run(){
	global $session;

	/* Lazy rules, pre setup here */
	$components=db_prefix('itemmixer_components');
	
	$user=&$session['user'];
	
	$run="runmodule.php?module=itemmixer";
	
	$owner=get_module_setting('owner');
	
	/* finish */
	
	$op=httpget('op');
	
	page_header('Mix it Baby!');
	
	output("`c`\$%s`2's `4Workshop`c`n`n",$owner);
	
	villagenav();
	addnav("Actions");

	$mixer=new itemmixer();

	
	switch ($op) {
	
		case "forge":
			addnav("Back to the Workshop",$run);
			
			$what=(int)httpget('recipe');
			$recipes=$mixer->getrawinventory();
			$mod=get_module_setting('modificator');
			output("`2You choose to create the following item: `\$%s.`n`n`2As you have all the ingredients, it takes not too long, and you have finished.",$recipes[$what]['name']);
			$cost=round($recipes[$what]['dragonkills']*$mod)+99;			
			$session['user']['gold']-=$cost;
			$mixer->forge($what);
			break;
	
		case "recipes":
			addnav("Back to the Workshop",$run);
			$r=(int)httppost('recipe');
			$recipes=$mixer->getrawinventory();
			output("`2Your recipes:`n`n");
			rawoutput("<form action='".$run."&op=recipes' method='POST'><select name='recipe'>");
			addnav("",$run."&op=recipes");
			foreach ($recipes as $val) {
				rawoutput("<option value='".$val['itemid']."'>".$val['name']."</option>");
			}
			rawoutput("</select><br><br><input class='button' type='submit' value='".translate_inline("View")."'></form><br>");
			if ($r!==0) {
				rawoutput("<table cellspacing='10' cellpadding='10'><tr><td>");
				output("`\$Recipe: `n`n%s",$recipes[$r]['name']);
				rawoutput("</td><td>");
				output("`\$Description:`n`n%s",$recipes[$r]['description']);
				rawoutput("</td></tr><td colspan='2'>");
				output("`n`n`4Ingredients:`n`n");
				$ingredients=$mixer->getrecipes($r);
				$names=$mixer->itemnames();
				foreach ($ingredients[$r] as $type=>$quantity) {
					output("%s x %s`n`n",$quantity,$names[$type]);
				}
				rawoutput("</td></tr></table>");				
			}
			break;
		case "check":
			addnav("Back to the Workshop",$run);
			output("`2You start to pry out all the recipes you have, and check your inventory if you have all ingredients necessary... this takes a while.`n`n");
			output("Additionally, you have to pay a certain fee for using the furniture and tools hosted here. It is not cheap mostly, but as you can see they have only the most sophisticated stuff here, ready at your disposal and with a 100% guarantee you get what you try to create.`n`n");
			$recipes=$mixer->checkandget();
			// debug($recipes);
			if (count($recipes)>0) output("`4You have enough ingredients for: `n`n");
				else output("`4`b`iSadly, you have no recipes that can be done with your current inventory now...`i`b`n`n`\$Go get some recipes, and come back after that.");
			addnav("Recipes");
			$mod=get_module_setting('modificator');
			foreach ($recipes as $id=>$recipe) {
				
				if ($recipe['dragonkills']<=$session['user']['dragonkills']){
					output("`j-> `4%s`n`n",$recipe['name']);
					$cost=round($recipe['dragonkills']*$mod)+99;
					if ($session['user']['gold']>=$cost) {
						addnav(array("Create %s (%s gold)",$recipe['name'],$cost),$run."&op=forge&recipe=".$recipe['itemid']."&cost=".$cost);
					} else {
						addnav(array("Create %s (`\$%s gold necessary!`0)",$recipe['name'],$cost),"");
					}
				} else {
					output("`j-> `4%s `4(`\$Not enough experience! You need to level up and fight -you-know-whom- more often!`4)`n`n",$recipe['name']);
					addnav(array("Create %s",$recipe['name']));
				}
			}
			break;
	
		default:
		
			output("`2You enter the small workshop of %s`2, where you see lots of people working on workbenches, ovens and much more to improve their weaponry or other items.`n`nThere is another section where you can mix herbs, create potions and more.`n`nAs you recall, you need to know the exact recipe on what to mix, a security measure here is that you cannot experiment. A half-heartedly fixed hole in the roof reminds you of the last \"incident\" with somebody not knowing what exactly was necessary for time-delayed explosive tag...`n`n",$owner);
			//debug($mixer->getrecipes());
			$show=$mixer->getrawinventory();
			//debug($show);
			if (count($show)>0) output("`jYou carry the following recipes:`n`n");
			foreach ($show as $key=>$val) {
				output_notl("%s`n",$val['name']);		
			}
			addnav("Check what you can forge",$run."&op=check");
			addnav("Review your recipes",$run."&op=recipes");
	
	}
	

	page_footer();
}


class itemmixer {

	/* Lazy rules, pre setup here */
	const components='itemmixer_components';
	
	private $user, $owner,$recipes,$rawrecipes,$inv;
	
	/* finish */
	
	public function __construct() {
		global $session;
		$this->owner=get_module_setting('owner','itemmixer');	
		$this->user=&$session['user'];
		require_once("modules/inventory/lib/itemhandler.php");
		$this->recipes=-1;
		$this->rawrecipes=-1;
		invalidatedatacache("itemmixer-itemnames");
	}

	public function getinventory() {
		if ($this->rawrecipes==-1) {
			$this->rawrecipes=$this->getrecipe_ids($this->user['acctid']);
		}
		return array_keys($this->rawrecipes);
	}
	
	public function getrawinventory() {
		if ($this->rawrecipes==-1) {
			$this->rawrecipes=$this->getrecipe_ids($this->user['acctid']);
		}
		return $this->rawrecipes;
	}
	
	public function getrecipes($recipe=0) {
		if ($recipe==0) {
			$ids=$this->getrawinventory();
		} else $ids=array($recipe=>$recipe);
		
		if ($ids==array()) return array();
		if ($this->recipes!=-1) return $this->recipes;
		
		$list=array_keys($ids);
		
		$sql="SELECT * FROM ".(self::components)." WHERE recipeid IN (".implode(",",$list).");";
		$result=db_query($sql);
		$tmp=array();
		while ($row=db_fetch_assoc($result)) {
			$tmp[$row['recipeid']][$row['itemid']]=$row['quantity'];
		}
		$this->recipes=$tmp;
		return $tmp;
	}
	
	public function checkandget() {
		$inv=$this->getfullinventory($this->user['acctid']);
		$recipes=$this->getrecipes();
		$return=array();
		// debug($recipes);
		// debug($inv);
		foreach ($recipes as $recipeid=>$ingredients) {
			$valid=true;
			foreach ($ingredients as $itemid=>$quantity) {
				if (isset($inv[$itemid])) {
					if ($quantity<=$inv[$itemid]['quantity']) continue;
				}
				$valid=false;
				break;
			}
			if ($valid===true) {
				$return[]=$inv[$recipeid];
			}
		}
		return $return;
	}
	
	public function itemnames() {
		$sql="SELECT itemid,name FROM ".db_prefix('item')." ORDER BY itemid ASC;";
		$result=db_query_cached($sql,"itemmixer-itemnames",120);
		$return=array();
		while ($row=db_fetch_assoc($result)) {
			$return[$row['itemid']]=$row['name'];
		}
		return $return;
	
	}
	
	public function forge($recipeid) {
		$recipe=$this->getrecipes();
		foreach ($recipe[$recipeid] as $itemid=>$quantity) {
			remove_item_by_id($itemid,$quantity);
		}
		//removed the ingredients
		$inv=$this->getfullinventory($this->user['acctid']);
		
		add_item_by_id($inv[$recipeid]['customvalue']);
		
	}

	private function getrecipe_ids($id) {
		$recipes=get_inventory($id,false,"Recipes");
		if (db_num_rows($recipes)==0) return array();
		$recipe=array();
		while ($row=db_fetch_assoc($recipes)) {
			$recipe[$row['itemid']]=$row;
		}
		//debug($recipe);
		return $recipe;
	}
	
	private function getfullinventory($id) {
		$recipes=get_inventory($id,false);
		if (db_num_rows($recipes)==0) return array();
		$recipe=array();
		while ($row=db_fetch_assoc($recipes)) {
			$recipe[$row['itemid']]=$row;
		}
		//debug($recipe);
		return $recipe;
	}

}


?>
