<?php

page_header("Training Grounds");
addnav("Navigation");
addnav("Back to the Academy","train.php");
addnav("Actions");
output("`#`b`c`n`%R`Vinnegan`x Training`0`c`b`n`n");
$heard=array(
	'I have heard there is a war the Sound coming up... unsettling...',
	'Naruto should be headed for Sasuke right now, hopefully he will find him',
	'Sakura must have cut her hair. Hinata has grown such a nice one.',
	'Chouji has started to eat Chakra. He says it is good for the liver. Poor boy, that won\'t reduce his weight.',
	'Lately, there have been no news.',
	'Somebody was in search for the ink required for the Seven Star Tattoo... interesting thingie... but a kid could paint it better...',
	'Orochimaru has no idea of style...',
	'When a person has something important they want to protect, that\'s when they can become truly strong.',
	'Ino? Well, at least she knows how to wear her hair...',
	'If Neji would learn how to use his fists with more power... hilarious...',
	'The Sound Five? Well, Orochimaru simply had not enough people to form a \'Dirty Dozen\'.',
	'Tsunade... she is cute when she is asleep... ups...',
	'Can you understand? Not having a dream, not being needed by anyone, the pain of merely being alive',
	'Jiraiya... beneath the surface, there lies a sensible and lonely man...',
	'Shino... well, creepy buglover, but I said nothin\' at all.',
	);
$heard=translate_inline($heard);
$choice=array_rand($heard);
output("`%Nagato`x thinks a bit and then says, \"`4%s`x\"",$heard[$choice]);

?>