<?php

function halloween_monsters_getmoduleinfo(){
	$info = array(
		"name"=>"Holiday - Monsters at Halloween",
		"version"=>"1.0",
		"author"=>"Oliver Brendel",
		"category"=>"Holidays|Halloween",
		"download"=>"",
		"settings"=>array(
			"Monster Settings,title",
			"start"=>"Activation start date (mm-dd)|10-31",
			"end"=>"Activation end date (mm-dd)|10-08",
		),
	);
	return $info;
}

function halloween_monsters_install(){
	module_addhook("forestfight-start");
	$monsters=array(
		'id'=>array('name'=>'id', 'type'=>'int(11) unsigned', 'extra'=>'auto_increment'),
		'creaturename'=>array('name'=>'creaturename', 'type'=>'varchar(255)'),
		'creaturecategory'=>array('name'=>'creaturecategory', 'type'=>'varchar(255)'),
		'creatureweapon'=>array('name'=>'creatureweapon', 'type'=>'varchar(255)'),
		'creaturedying'=>array('name'=>'creaturedying', 'type'=>'varchar(255)'),
		'creaturewin'=>array('name'=>'creaturewin', 'type'=>'varchar(255)'),
		'key-PRIMARY' => array('name'=>'PRIMARY', 'type'=>'primary key', 'unique'=>'1', 'columns'=>'id'),
		'key-one'=> array('name'=>'cat', 'type'=>'key', 'unique'=>'1', 'columns'=>'id,creaturecategory'),
		);
	require_once("lib/tabledescriptor.php");
	synctable(db_prefix("monsters"), $monsters, true);
	 if (!is_module_installed("halloween_monsters")) halloween_monsters_fill();
	return true;
}

function halloween_monsters_fill() {
$sql = "INSERT INTO ".db_prefix('monsters')." VALUES 
(\"0\",\"Bloody Eye\",\"A\",\"Murderous Glare\",\"It definitely needed some eyedrops.\",\"\"),
(\"0\",\"Creepy Cat\",\"A\",\"Creepy Smile\",\"Something is off about that cat.\",\"\"),
(\"0\",\"Creepy Kid\",\"A\",\"Creepy Stare\",\"I am not giving you any candies.\",\"\"),
(\"0\",\"Creepy Owl\",\"A\",\"Sharp Teeth\",\"Do owls have teeth?!\",\"\"),
(\"0\",\"Creepy Painting\",\"A\",\"Creepiness\",\"It moved, I swear!\",\"\"),
(\"0\",\"Curious Ghost\",\"A\",\"Curiousity\",\"Curiousity killed the c-.ghost.\",\"\"),
(\"0\",\"Evil Bat\",\"A\",\"Red Glowing Eyes\",\"Still just a bat.\",\"\"),
(\"0\",\"Fallen Head\",\"A\",\"Chomping Mouth\",\"I wonder who dropped it.\",\"\"),
(\"0\",\"Fiery Ghost\",\"A\",\"Fiery Body\",\"A bucket of water did the job.\",\"\"),
(\"0\",\"Flaming Pumpkin\",\"A\",\"Flaming Body\",\"Who would like some pumpkin pie?\",\"\"),
(\"0\",\"Fluffy Ghost\",\"A\",\"Fluffy Body\",\"This will make a great stuffing for my pillow.\",\"\"),
(\"0\",\"Ghost Flame\",\"A\",\"Ghostly Flames\",\"Those flames can't even light a paper on fire.\",\"\"),
(\"0\",\"Ghost\",\"A\",\"Boo!\",\"Boo!!!\",\"\"),
(\"0\",\"Ghostly Hands\",\"A\",\"Chilly Grip\",\"Get your hands off me!\",\"\"),
(\"0\",\"Ghostly Image\",\"A\",\"Creepy Face\",\"It couldn't beat me at making faces.\",\"\"),
(\"0\",\"Giggling Mummy\",\"A\",\"Giggles\",\"What is so funny?\",\"\"),
(\"0\",\"Glowing Red Eyes\",\"A\",\"Glares\",\"Please tell me those weren't rats.\",\"\"),
(\"0\",\"Goofy Spirit\",\"A\",\"Goofy Smile\",\"Stop goofing around.\",\"\"),
(\"0\",\"Hands Of The Dead\",\"A\",\"Death Grip\",\"You need to work on your grip.\",\"\"),
(\"0\",\"Hanging Ghost\",\"A\",\"Kicking Legs\",\"Sorry, but I am not going to hang around.\",\"\"),
(\"0\",\"Joyful Shadow\",\"A\",\"Smile\",\"That smile did not fool me.\",\"\"),
(\"0\",\"Konan In Frog Costume\",\"A\",\"Trick-or-treat\",\"Sorry, all out of candies.\",\"\"),
(\"0\",\"Lazy Ghost\",\"A\",\"Laziness\",\"It was too lazy to put up a fight.\",\"\"),
(\"0\",\"Little Girl Spirit\",\"A\",\"Sorrow\",\"Poor thing.\",\"\"),
(\"0\",\"Little Witch\",\"A\",\"Broom Stick\",\"Try again when you know a spell or two.\",\"\"),
(\"0\",\"Lonely Shadow\",\"A\",\"Loliness\",\"Go find some shades to play with.\",\"\"),
(\"0\",\"Malicious Shadow\",\"A\",\"Nasty Prank\",\"Now that wasn't very nice.\",\"\"),
(\"0\",\"Masked Zombie Squirrel\",\"A\",\"Cuteness\",\"Awww.\",\"\"),
(\"0\",\"Misty Ghost\",\"A\",\"Mist\",\"That cleared the mist.\",\"\"),
(\"0\",\"Nagato In Frog Costume\",\"A\",\"Trick-or-treat\",\"Sorry, all out of candies.\",\"\"),
(\"0\",\"Paper Ghost\",\"A\",\"Paper Cut\",\"It's just a paper cut.\",\"\"),
(\"0\",\"Playful Ghost\",\"A\",\"Sticky Syrup\",\"Ewww..now I am sticky all over.\",\"\"),
(\"0\",\"Possessed Bat\",\"A\",\"Tiny Claws\",\"Now it's just a regular bat.\",\"\"),
(\"0\",\"Angry Possessed Cat\",\"A\",\"Sharp Claws\",\"I guess it didn't like me.\",\"\"),
(\"0\",\"Possessed Cat\",\"A\",\"Angry Hissing\",\"Now it's just a regular cat.\",\"\"),
(\"0\",\"Possessed Mouse\",\"A\",\"Tiny Bites\",\"Now it's just a regular mouse.\",\"\"),
(\"0\",\"Rotting Head\",\"A\",\"Bad Breath\",\"When was the last time you brush?!\",\"\"),
(\"0\",\"Sad Shadow\",\"A\",\"Sadness\",\"I wonder what made it sad.\",\"\"),
(\"0\",\"Scary Shadow\",\"A\",\"Fangs and Claws\",\"I do not want to see what made that shadow.\",\"\"),
(\"0\",\"Severed Hand\",\"A\",\"Sharp Nails\",\"I wander who it belonged to.\",\"\"),
(\"0\",\"Severed Head\",\"A\",\"His Saliva\",\"I guess it couldn't bite without a lower jaw.\",\"\"),
(\"0\",\"Shadow People\",\"A\",\"Shadows\",\"Look more like stick figures to me.\",\"\"),
(\"0\",\"Skeleton\",\"A\",\"A Bone\",\"Akamaru would love this guy.\",\"\"),
(\"0\",\"Skull\",\"A\",\"Headbutt\",\"Good thing I have my forehead protector on.\",\"\"),
(\"0\",\"Sleeping Ghost\",\"A\",\"ZZZzzz\",\"It didn't wake up.\",\"\"),
(\"0\",\"Smiling Mummy\",\"A\",\"Creepy Smile\",\"His smile was kind of creepy.\",\"\"),
(\"0\",\"Smiling Shadow\",\"A\",\"Smile\",\"That smile did not fool me.\",\"\"),
(\"0\",\"Cute Kitty Costume\",\"A\",\"A Bag of Candies\",\"These candies are mine now.\",\"\"),
(\"0\",\"Kitty Costume\",\"A\",\"A Bag of Candies\",\"These candies are mine now.\",\"\"),
(\"0\",\"Spider Mouse\",\"A\",\"Creepy Legs\",\"A spider what?!\",\"\"),
(\"0\",\"Spooky Ghost\",\"A\",\"Flapping Arms\",\"It just disappeared.\",\"\"),
(\"0\",\"Spooky Hands\",\"A\",\"Chilly Touch\",\"That was spooky.\",\"\"),
(\"0\",\"Mushroom-head Ghost\",\"A\",\"Ghostly Spores\",\"That was a very strange ghost.\",\"\"),
(\"0\",\"Vampire Bat\",\"A\",\"Blood-sucking Fangs\",\"I was bitten! Am I going to turn into a vampire?\",\"\"),
(\"0\",\"Water Ghost\",\"A\",\"Wet Body\",\"I need a towel.\",\"\"),
(\"0\",\"Werehog\",\"A\",\"Hunger\",\"You are not getting my candies!\",\"\"),
(\"0\",\"Werehare\",\"A\",\"Huge Fangs\",\"It still only eat carrots thou.\",\"\"),
(\"0\",\"White Figure\",\"A\",\"Blood-red Eyes\",\"Those eyes are creepy.\",\"\"),
(\"0\",\"Wicked Spider\",\"A\",\"Sticky Webs\",\"Still just a spider.\",\"\"),
(\"0\",\"Yahiko In Frog Costume\",\"A\",\"Trick-or-treat\",\"Sorry, all out of candies.\",\"\"),
(\"0\",\"Floating Mummy Head\",\"A\",\"Wide Open Mouth\",\"This would make a perfect soccer ball.\",\"\"),
(\"0\",\"Zombie Squirrel\",\"A\",\"Cuteness\",\"Awww.\",\"\"),
(\"0\",\"Angry Lipless Zombie\",\"B\",\"Nasty Bite\",\"Stop your moaning.\",\"\"),
(\"0\",\"Bald Zombie\",\"B\",\"Hunger For Hair\",\"Here, try this shampoo.\",\"\"),
(\"0\",\"Balding Zombie\",\"B\",\"Walking Stick\",\"Now he is completely bald.\",\"\"),
(\"0\",\"Beared Zombie\",\"B\",\"Hairy Bite\",\"Stop, that tickles!\",\"\"),
(\"0\",\"Blonde Mummy\",\"B\",\"Dangling Bandages\",\"I have never seen a blonde mummy before.\",\"\"),
(\"0\",\"Blonde Zombie\",\"B\",\"Hunger For Brains\",\"Still just another zombie.\",\"\"),
(\"0\",\"Bloody Eyes\",\"B\",\"Vicious Glare\",\"That made me felt very uncomfortable.\",\"\"),
(\"0\",\"Bunny-ear Zombie\",\"B\",\"His Horns\",\"Oh, they are horns! My bad.\",\"\"),
(\"0\",\"Cone-head Zombie\",\"B\",\"A Head Drill\",\"Don't zombies usually bite?\",\"\"),
(\"0\",\"Creepy Murderer\",\"B\",\"Kitchen Knife\",\"Next time, use a bigger knife.\",\"\"),
(\"0\",\"Cursed Doll\",\"B\",\"A Curse\",\"Hey look, it is stuff with candies inside.\",\"\"),
(\"0\",\"Dancing Imp\",\"B\",\"Dealy Dance Moves\",\"It needs more dancing lessons.\",\"\"),
(\"0\",\"Disco Zombie\",\"B\",\"Disco Ball\",\"The party is over.\",\"\"),
(\"0\",\"Drunk Zombie Pirate\",\"B\",\"A Bottle of Rum\",\"His hangover is going to kill him.\",\"\"),
(\"0\",\"Evil Snow Spirit\",\"B\",\"Snow Balls\",\"I never lost a snow ball battle before.\",\"\"),
(\"0\",\"Fat Mummy\",\"B\",\"His Weight\",\"Aren't mummies supposed to be dried and skinny?\",\"\"),
(\"0\",\"Fiery Ghost\",\"B\",\"Raging Fire\",\"Noooo.my chocolates melted!\",\"\"),
(\"0\",\"Frowning Ghost\",\"B\",\"Wrinkled Forehead\",\"Don't frown, it will give you wrinkles.\",\"\"),
(\"0\",\"Ghost Duo\",\"B\",\"Boo! Boo!\",\"Boo just isn't scary anymore.\",\"\"),
(\"0\",\"Ghost Painter\",\"B\",\"Bloody Paint Brush\",\"You really should wash that brush.\",\"\"),
(\"0\",\"Ghostface\",\"B\",\"Hunting Knife\",\"Wait.the mask isn't coming off.\",\"\"),
(\"0\",\"Glaring Mummy\",\"B\",\"Old Bandages\",\"He needs new bandages.\",\"\"),
(\"0\",\"Goofy Spirits\",\"B\",\"Goofy Attacks\",\"Come at me seriously.\",\"\"),
(\"0\",\"Haunted Painting\",\"B\",\"Long Neck\",\"Now that's what I call 3D.\",\"\"),
(\"0\",\"Head-lifting Zombie\",\"B\",\"Detached Head\",\"Now it's a headless zombie.\",\"\"),
(\"0\",\"Jack-In-The-Room\",\"B\",\"Surprise Attack\",\"It made me jump..a little.\",\"\"),
(\"0\",\"Lipless Zombie\",\"B\",\"Nasty Bite\",\"Stop your moaning.\",\"\"),
(\"0\",\"Long-headed Mummy\",\"B\",\"Old Bandages\",\"He needs new bandages.\",\"\"),
(\"0\",\"Long-headed Zombie\",\"B\",\"Elongated Head\",\"I wonder if he is some kind of alien zombie.\",\"\"),
(\"0\",\"Masked Zombie Squirrel\",\"B\",\"Small Axe\",\"It's not so cute now.\",\"\"),
(\"0\",\"Mischievous Imp\",\"B\",\"Nasty Prank\",\"Now that wasn't very nice.\",\"\"),
(\"0\",\"Monster\",\"B\",\"Large Body\",\"Call me the Monster Hunter.\",\"\"),
(\"0\",\"Licking Mummy\",\"B\",\"Wet Tongue\",\"Stop that, I am not a candy!\",\"\"),
(\"0\",\"Moaning Mummy\",\"B\",\"Eerie Moaning\",\"Stop your moaning.\",\"\"),
(\"0\",\"Stinky Mummy\",\"B\",\"Smelly Bandages\",\"Eww.when was the last time they were washed.\",\"\"),
(\"0\",\"Murder Victim\",\"B\",\"The Axe In His Head\",\"Poor guy.\",\"\"),
(\"0\",\"Murderous Clown\",\"B\",\"Kitchen Knives\",\"It wasn't clowning around.\",\"\"),
(\"0\",\"Mushroom-head Zombie\",\"B\",\"Big Head\",\"I wonder if mushroom was his favorite food.\",\"\"),
(\"0\",\"Old Man Zombie\",\"B\",\"Loose Teeth\",\"His remaining teeth fell off.\",\"\"),
(\"0\",\"Pointy-head Zombie\",\"B\",\"A Head Drill\",\"Don't zombies usually bite?\",\"\"),
(\"0\",\"Possessed Girl\",\"B\",\"Eerie Eyes\",\"Kids should be out trick-or-treating.\",\"\"),
(\"0\",\"Red-eye Zombie\",\"B\",\"Big Hands\",\"Now stay dead.\",\"\"),
(\"0\",\"Red-head Zombie\",\"B\",\"Large Jaws\",\"Still just another zombie.\",\"\"),
(\"0\",\"Rising Dead\",\"B\",\"Hunger\",\"Now it's back in the ground where it belongs.\",\"\"),
(\"0\",\"Rising Zombie\",\"B\",\"Long Fingers\",\"Now it's back in the ground where it belongs.\",\"\"),
(\"0\",\"Silver-hair Zombie\",\"B\",\"Huge Bite\",\"I wonder if this is how Kakashi would look like as a zombie.\",\"\"),
(\"0\",\"Skeleton\",\"B\",\"Sharp Teeth\",\"How long did it spend sharpening those teeth?\",\"\"),
(\"0\",\"Skulls\",\"B\",\"Headbutts\",\"Good thing I have my forehead protector on.\",\"\"),
(\"0\",\"Spider Monkey\",\"B\",\"Sticky Web\",\"Looks more like a monkey spider.\",\"\"),
(\"0\",\"Spooky Ghost Duo\",\"B\",\"Double Spooky\",\"They just disappeared.\",\"\"),
(\"0\",\"Starving Zombie\",\"B\",\"Hunger\",\"Here, have some candies.\",\"\"),
(\"0\",\"Swarming Hands\",\"B\",\"Death Grip\",\"Hey, watch where you are grabbing!\",\"\"),
(\"0\",\"Top Hat Mummy\",\"B\",\"Walking Stick\",\"I thought he was going to pull something out of his hat.\",\"\"),
(\"0\",\"Undead Ronin\",\"B\",\"Katana\",\"His sword skill was a bit rusty.\",\"\"),
(\"0\",\"Upset Ghost\",\"B\",\"Angry Blow\",\"I wonder what is upsetting it.\",\"\"),
(\"0\",\"Vampire Bats\",\"B\",\"Blood-sucking Fangs\",\"I was bitten! Am I going to turn into a vampire?\",\"\"),
(\"0\",\"Werehare\",\"B\",\"Deadly Claws\",\"I wouldn't want one in my garden.\",\"\"),
(\"0\",\"Werewolf\",\"B\",\"Woof-Fu\",\"Nice moves, but can't beat me.\",\"\"),
(\"0\",\"Zombie Bandit\",\"B\",\"Rusty Saber\",\"You are not taking my candies.\",\"\"),
(\"0\",\"Zombie Chef\",\"B\",\"Brains And Beans\",\"Sorry, not my kind of dish.\",\"\"),
(\"0\",\"Zombie Guide\",\"B\",\"Lit Lantern\",\"Sorry, I was taught not to follow the undead.\",\"\"),
(\"0\",\"Zombie Squirrel\",\"B\",\"Small Saw\",\"It's not so cute now.\",\"\"),
(\"0\",\"Zombie\",\"B\",\"Hunger For Brains\",\"Here, how about a candy instead.\",\"\"),
(\"0\",\"Angry Snow Spirit\",\"C\",\"Deadly Blizzard\",\"That was a chilly experience.\",\"\"),
(\"0\",\"Big Fluffy Ghost\",\"C\",\"Big Fluffy Body\",\"I was attacked by an oversize cotton candy.\",\"\"),
(\"0\",\"Black Witch\",\"C\",\"Black Magic\",\"Pfff..hocus pocus.\",\"\"),
(\"0\",\"Blue Killer Clown\",\"C\",\"Huge Left Claw\",\"This is the reason why I hate clowns.\",\"\"),
(\"0\",\"Bronze Ghost Knight\",\"C\",\"Rusty Spear\",\"Just a pile of broken armor now.\",\"\"),
(\"0\",\"Cerberus\",\"C\",\"Triple Attack\",\"Something is off about one of the heads.\",\"\"),
(\"0\",\"Classic Vampire\",\"C\",\"Blood-sucking Fangs\",\"I was bitten! Am I going to turn into a vampire?\",\"\"),
(\"0\",\"Crazy Mummy\",\"C\",\"Crazy Attack\",\"That's one less mummy to worry about.\",\"\"),
(\"0\",\"Creature\",\"C\",\"Strong Body\",\"I have no idea what that thing is.\",\"\"),
(\"0\",\"Creepy Clown\",\"C\",\"Rotten Candies\",\"I hate clowns!\",\"\"),
(\"0\",\"Curse of Hatred\",\"C\",\"Deep Hatred\",\"What is this hatred that I feel?\",\"\"),
(\"0\",\"Curse of Insanity\",\"C\",\"Insanity\",\"What is this insanity that I feel?\",\"\"),
(\"0\",\"Death\",\"C\",\"Death Scythe\",\"I managed to cheat Death.\",\"\"),
(\"0\",\"Demonic Shadow\",\"C\",\"Shadow Claws\",\"The light killed it.\",\"\"),
(\"0\",\"Evil Imp\",\"C\",\"A Deal With The Devil\",\"That's a rotten deal.\",\"\"),
(\"0\",\"Evil Spirits\",\"C\",\"Yearning For Life\",\"Rest in peace.\",\"\"),
(\"0\",\"Fallen Bishop\",\"C\",\"Book Of Evil\",\"Now go read something else.\",\"\"),
(\"0\",\"Fear\",\"C\",\"Absolute Terror\",\"I am fearless!\",\"\"),
(\"0\",\"Fiery Ghost\",\"C\",\"Flames Of Hell\",\"Noooo.all my candies have melted!\",\"\"),
(\"0\",\"Four-arm Gunslinger\",\"C\",\"Rain Of Bullets\",\"Having four guns isn't going to help with bad aim.\",\"\"),
(\"0\",\"Franken-Hulk\",\"C\",\"Bone-crushing Fist\",\"I wish I can punch like that.\",\"\"),
(\"0\",\"Franken-Lady\",\"C\",\"Flying Dishes\",\"I guess she hate doing the dishes.\",\"\"),
(\"0\",\"Friendly Vampire\",\"C\",\"Blood Wine\",\"Sorry, not my kind of wine.\",\"\"),
(\"0\",\"Furious Werewolf\",\"C\",\"Immortal Body\",\"Not so immortal now, is he?\",\"\"),
(\"0\",\"Ghost Armor\",\"C\",\"Old Sword\",\"That thing can't even cut through a piece of paper.\",\"\"),
(\"0\",\"Ghost Parade\",\"C\",\"All Sorts Of Ghosts\",\"They come in all shapes and sizes don't they?\",\"\"),
(\"0\",\"Ghost Ship\",\"C\",\"Huge Mouth\",\"How did it get all the way here?!\",\"\"),
(\"0\",\"Ghost Swarm\",\"C\",\"Hunger For Soul\",\"I lost count after the 106th kill.\",\"\"),
(\"0\",\"Golden Ghost Knight\",\"C\",\"Expensive Sword\",\"Just because it's expensive doesn't mean that it's good.\",\"\"),
(\"0\",\"Horned Ghost Armor\",\"C\",\"Spinning Spear Attack\",\"More like Useless Spear Attack.\",\"\"),
(\"0\",\"Horrid Evil Spirit\",\"C\",\"Ghastly Appearances\",\"I hope this is the last time I encounter it.\",\"\"),
(\"0\",\"Huge Mummy\",\"C\",\"Crushing Weight\",\"Must have took tons of bandages to wrap him up.\",\"\"),
(\"0\",\"Huge Zombie\",\"C\",\"Huge Bite\",\"He could have easily biten a man in half!\",\"\"),
(\"0\",\"Hungry Mummy\",\"C\",\"Feeding Frenzy\",\"Sorry, but I am not on the menu.\",\"\"),
(\"0\",\"Hungry Zombie\",\"C\",\"Feeding Frenzy\",\"Sorry, but I am not on the menu.\",\"\"),
(\"0\",\"Jack The Reaper\",\"C\",\"Claws Of Death\",\"Now the streets are finally safe.\",\"\"),
(\"0\",\"Leaping Werewolf\",\"C\",\"Claws Of Steel\",\"It ran off with a broken nail.\",\"\"),
(\"0\",\"Leaping Zombie\",\"C\",\"Surprise Attack\",\"I have never seen such a lively zombie before.\",\"\"),
(\"0\",\"Monster\",\"C\",\"Chainsaw\",\"Call me the Monster Slayer.\",\"\"),
(\"0\",\"Mummies\",\"C\",\"Mummification\",\"I am not dead yet!\",\"\"),
(\"0\",\"Nightmare Unicorn\",\"C\",\"Evil Horn\",\"Is this thing real?\",\"\"),
(\"0\",\"Old Witch\",\"C\",\"Nasty potion\",\"Have a taste of your own potion!\",\"\"),
(\"0\",\"Pharao\",\"C\",\"Heavy Coffin\",\"I wonder if it's real gold?\",\"\"),
(\"0\",\"Awakened Pharao\",\"C\",\"Glowing Red Eyes\",\"I hope I am not cursed for touching it.\",\"\"),
(\"0\",\"Berserk Pharao\",\"C\",\"Ancient Curse\",\"I have no idea what he was mumbling about.\",\"\"),
(\"0\",\"Reaper\",\"C\",\"Scythe\",\"Go cut some grass with that thing.\",\"\"),
(\"0\",\"Red Killer Clown\",\"C\",\"Huge Right Claw\",\"This is the reason why I hate clowns.\",\"\"),
(\"0\",\"Crimson Ghost Knight\",\"C\",\"Deep Red Cape\",\"He must be very pround of his cape.\",\"\"),
(\"0\",\"Rusty Ghost Armor\",\"C\",\"Long Sword\",\"Should have taken better care of your armor.\",\"\"),
(\"0\",\"Sadako\",\"C\",\"Deadly Curse\",\"I pulled the plug.\",\"\"),
(\"0\",\"Sealed Bag Of Evil\",\"C\",\"Evil Within\",\"Turns out to be just a bag of rotten candies.\",\"\"),
(\"0\",\"Servant Of Evil\",\"C\",\"Demonic Aura\",\"Now you serve me.\",\"\"),
(\"0\",\"Shielded Ghost Armor\",\"C\",\"Rusty Round Shield\",\"Should have taken better care of your shield.\",\"\"),
(\"0\",\"Silver Ghost Armor\",\"C\",\"Unholy Armor\",\"I guess I can sell these and make some money.\",\"\"),
(\"0\",\"Stripped Ghost Armor\",\"C\",\"Glowing Red Eyes\",\"I wonder what's inside.\",\"\"),
(\"0\",\"Ugly Witch\",\"C\",\"Ugly Curse\",\"I need a mirror!\",\"\"),
(\"0\",\"Undead Ronin\",\"C\",\"Ultimate Battojutsu\",\"How did you like my Ninjutsu?\",\"\"),
(\"0\",\"Vampire\",\"C\",\"Blood-sucking Fangs\",\"I was bitten! Am I going to turn into a vampire?\",\"\"),
(\"0\",\"Wicked Imp\",\"C\",\"Wicked Smile\",\"I have a feeling that this is not the last of him.\",\"\"),
(\"0\",\"Wicked Witch\",\"C\",\"Crystal Ball\",\"I looked into the ball and saw your defeat.\",\"\"),
(\"0\",\"Witch\",\"C\",\"Horrible Spell\",\"Her spell was horribly slow.\",\"\"),
(\"0\",\"Zombie Horde\",\"C\",\"Feeding Frenzy\",\"I defeated the horde!\",\"\"),
(\"0\",\"Zombie Squirrel Duo\",\"C\",\"Double Deadly Attack\",\"Nasty little fellas.\",\"\");";

db_query($sql);

}
function halloween_monsters_uninstall(){
	return true;
}

function halloween_monsters_datecheck() {
		$mytime = get_module_setting("start");
		$start = strtotime(date("Y")."-".$mytime);
		$mytime = get_module_setting("end");
		$end = strtotime(date("Y")."-".$mytime);	
		
		$now = strtotime("now");
		
		if ($start<=$now && $now<=$end) {
			return true;
		} else {
			return false;
		}

}

function halloween_monsters_dohook($hookname,$args){
	global $session;
	$u =& $session['user'];
	switch($hookname){
		case "forestfight-start":
	//determine if we need to activate
		if (halloween_monsters_datecheck()===false) {
			return $args;
		}
	// we need to activate and replace foes
		$newenemies=array();
		$level = $u['level'];
		$sql = "SELECT * from ".db_prefix('monsters')." WHERE ";
		foreach ($args['enemies'] as $key=>$value) {
			if ($u['level']<6) {
				$group = "A";
			} elseif ($u['level']<11) {
				$group = "B";
			} else {
				$group = "C";
			}
			$result=db_query($sql." creaturecategory='$group' ORDER by RAND();");
			$row=db_fetch_assoc($result);
			if (db_num_rows($result)<1) return $args; // something went wrong, return the original
			$value['creaturename']=$row['creaturename'];
			$value['creatureweapon']=$row['creatureweapon'];
			$value['creaturelose']=$row['creaturedying'];
			$value['creaturewin']=$row['creaturewin'];
			$value['image']="modules/halloween_monsters/images/Group$group/$group"."_".$row['creaturename'].".gif";
			$newenemies[]=$value;
		}
		$args['enemies'] = $newenemies; // put new in
	}
	return $args;
}

function halloween_monsters_run(){

}
?>
