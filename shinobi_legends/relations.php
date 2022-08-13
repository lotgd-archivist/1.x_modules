<?php

function relations_getmoduleinfo(){
$info = array(
	"name"=>"Relations",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel",
	"category"=>"Diplomacy",
	"download"=>"",
	
	);
	return $info;
}

function relations_install(){
	module_addhook_priority("village",1);
	module_addhook_priority("index",1);
	return true;
}

function relations_uninstall(){
	return true;
}

class warmatrix {
	
	private $dimensions;
	private $matrix='';
	const NONE=0;
	const ALLY=1;
	const FRIENDLY=2;
	const NEUTRAL=3;
	const UNFRIENDLY=4;
	const HOSTILE=5;
	const WAR=6;
	private $table=array(
		"Ninja Central"=>0,
		"Konohagakure"=>1,
		"Kirigakure"=>2,
		"Sunagakure"=>3,
		"Otogakure"=>4,
		);	
	
	function output_all($city=false) {
		$towns=array_keys($this->table);
		if ($city!=false) {
			if (!array_key_exists($city,$this->table)) return;
			$from=$this->table[$city];
			for ($i=0;$i<$this->dimensions;$i++) {
				if ($i==$from) continue;
				$out=translate_inline($this->output($this->getRelations($from,$i)));
				output_notl($out."`n",$towns[$from],$towns[$i]);
			}
		} else {
			foreach ($this->table as $from) {
				for ($i=$from+1;$i<$this->dimensions;$i++) {
					$out=translate_inline($this->output($this->getRelations($from,$i)));
					output_notl($out."`n",$towns[$from],$towns[$i]);
				}
			}
		}
		return;
	}
	
	function getRelations($from,$to) {
		return $this->matrix[$from][$to];	
	}
	
	function setRelations($from,$to,$status) {
		$this->matrix[$from][$to]=$status;
		$this->matrix[$to][$from]=$status;
	}
	
	function __construct($dimensions) {
		$this->dimensions=$dimensions;
		$matrix=$dummy=array();
		
		for ($i=0;$i<$this->dimensions;$i++) {
			$dummy[$i]=0;
		}
		for ($i=0;$i<$this->dimensions;$i++) {
			$matrix[]=$dummy;
		}
		$this->matrix=$matrix;
		//fill it
		
		$this->setRelations(0,1,self::NEUTRAL);
		$this->setRelations(0,2,self::NEUTRAL);
		$this->setRelations(0,3,self::NEUTRAL);
		$this->setRelations(0,4,self::NEUTRAL);
		$this->setRelations(1,2,self::UNFRIENDLY);
		$this->setRelations(1,3,self::ALLY);
		$this->setRelations(1,4,self::HOSTILE);
		$this->setRelations(2,3,self::UNFRIENDLY);
		$this->setRelations(2,4,self::NEUTRAL);
		$this->setRelations(3,4,self::HOSTILE);
		//done		
	}
	
	function output_w($from,$to) {
		if (array_key_exists($from,$this->table)) {
			$f=(int)$this->table[$from];
		} else return '';
		if (array_key_exists($to,$this->table)) {
			$t=(int)$this->table[$to];
		} else return '';
		$status=$this->getRelations($f,$t);
		return $this->output($status);
	}
	
	function output($status) {
		switch ($status) {
			case self::ALLY:
				$out="`q%s`q is allied with %s`q.";
				break;
			case self::FRIENDLY:
				$out="`@%s`@ has friendly relations with %s`@.";
				break;
			case self::UNFRIENDLY:
				$out="`g%s`g is at bad terms with %s`g.";
				break;
			case self::HOSTILE:
				$out="`4%s`4 is hostile towards %s`4.";
				break;
			case self::WAR:
				$out="`\$%s `\$ is at war with %s`\$!";
				break;
			case self::NEUTRAL:
			default:
				$out="`y%s`y has no special relations to %s`y.";
		
		}
		return "$out";
	}
}





function relations_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "village":
			$matrix=new warmatrix(5);
			$home=get_module_pref('homecity','cities');
			if ($session['user']['location']==$home) break;
			$out=translate_inline($matrix->output_w($session['user']['location'],$home));
			if ($out!=="") {
				output_notl("`n`n`c$out`c`n`n",$session['user']['location'],$home);
			}
			break;
		case "index":
			$matrix=new warmatrix(5);
			output_notl("`n");
			$matrix->output_all("Konohagakure");
			output_notl("`n");
			break;
	}
	return $args;
}

function relations_run(){

}



?>
