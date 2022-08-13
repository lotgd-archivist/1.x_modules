<?php

function wettkampf_statuetafel_run_private($op){
	global $session;
	page_header("Der Platz der Völker");
		output ("`@Du trittst näher an den großen Sockel heran und liest die Inschrift:`n`n`n`n`n`n`n`c`^Ich erinnere mich noch gut daran, wie unvollkommen und fehlbar mir die Sterblichen`n erschienen waren, als ich noch kein Mensch war. Doch nun, da ich selbst auf diesen`n Zustand zurückgeworfen bin, habe ich viel über ihr Leben gelernt.`n`nGerade ihre Fehlbarkeit ist es, die sie zu etwas Besonderem macht: Die Zwerge, die Elfen,`n die Echsen, die Menschen, die Vanthira, die Trolle, aber auch jene, die jedem Volke`n angehören und keinem, die Kinder der Nacht. Ihre Unvollkommenheit ist der Keim`n ihrer Gemeinschaftlichkeit, das habe ich nun erkannt, und ihre Gemeinschaftlichkeit`n ist der Keim dessen, wofür ich mit meinem Leben einstehe: Für die Liebe.`n`nDieser Platz sei dem friedlichen Miteinander der Völker gewidmet. `n`nMein Dank gilt MarkAurel und insbesondere Nathan, die mir geholfen haben, diesen Traum zu verwirklichen,`n und allen Sterblichen wie Unsterblichen, die in friedlicher Absicht hierherkommen.`n`nDie Vermittlerin`c`n`n`@Darunter steht etwas kleiner:`n`n`c`^`iDiese Statue der großen Vermittlerin bereits zu Lebzeiten,`nwenngleich dies ihrer wesenseigenen Bescheidenheit widerspricht `n`nDie Fürsten der Völker`i`c");
		addnav("Zurück","runmodule.php?module=wettkampf&op1=statue");
	page_footer();
}
?>