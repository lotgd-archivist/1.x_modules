<?php
function rassengaerten_getmoduleinfo(){
    $info = array(
        "name"=>"Gaerten der Rassen",
        "version"=>"1.0",
        "author"=>"Apollon",
        "category"=>"gardens",
        "download"=>"",
		),
        "settings"=>array(
			"Der Garten der Elfen,title",
			"garten1"=>"Wie soll der Elfengarten heissen?,text|Garten der singenden Waelder",
			"garten11"=>"Wo soll er erscheinen?,location|".getsetting("villagename", LOCATION_FIELDS)
			"Der Garten der Menschen,title",
			"garten2"=>"Wie soll der Menschengarten heissen?,text|Stadtpark",
			"garten22"=>"Wo soll er erscheinen?,location|".getsetting("villagename", LOCATION_FIELDS)
			"Der Garten der Zwerge,title",
			"garten3"=>"Wie soll der Zwergengarten heissen?,text|Garten der klingenden Edelsteine",
			"garten33"=>"Wo soll er erscheinen?,location|".getsetting("villagename", LOCATION_FIELDS)
			"Der Garten der Trolle,title",
			"garten4"=>"Wie soll der Trollgarten heissen?,text|Garten der tiefen Schlammloecher",
			"garten44"=>"Wo soll er erscheinen?,location|".getsetting("villagename", LOCATION_FIELDS),
			"Der Garten der Dunkelelfen,title",
			"garten5"=>"Wie soll der Dunkelelfengarten heissen?,text|Garten der dunklen Schatten",
			"agarten55"=>"Wo soll er erscheinen?,location|".getsetting("villagename", LOCATION_FIELDS)
		),
    );
    return $info;
}

function rassengaerten_install(){
    output("Das Rassengaerten Modul wird instaliert.");
	module_addhook("gardens");
	module_addhook("changesetting");
    return false;
	
}

function rassengaerten_uninstall(){
	return true;
}

function rassengaerten_dohook($hookname, $args){
	global $session;
	require_once("lib/http.php");
	switch($hookname){
	case "gardens":
	$garden=$session['user']['location'];
			if ($session['user']['location'] == get_module_setting("garten11")){
			tlschema($args['schemas']['gardensnav']);
    		addnav($args['gardensnav']);
    		tlschema();
			addnav(array("%s'",get_module_setting('garten1')),"runmodule.php?module=1garten");
			}
			if ($session['user']['location'] == get_module_setting("garten22")){
			tlschema($args['schemas']['gardensnav']);
    		addnav($args['gardensnav']);
    		tlschema();
			addnav(array("%s",get_module_setting('garten2')),"runmodule.php?module=2garten");
			}
			if ($session['user']['location'] == get_module_setting("garten33")){
			tlschema($args['schemas']['gardensnav']);
    		addnav($args['gardensnav']);
    		tlschema();
			addnav(array("%s",get_module_setting('garten3')),"runmodule.php?module=3garten");
			}
    			if ($session['user']['location'] == get_module_setting("garten44")){
			tlschema($args['schemas']['gardensnav']);
    		addnav($args['gardensnav']);
    		tlschema();
			addnav(array("%s",get_module_setting('garten4')),"runmodule.php?module=4garten");
			}
			if ($session['user']['location'] == get_module_setting("garten55")){
			tlschema($args['schemas']['gardensnav']);
    		addnav($args['gardensnav']);
    		tlschema();
			addnav(array("%s",get_module_setting('garten5')),"runmodule.php?module=5garten");
			}
	break;
	}
	return $args;
}


function rassengaerten_run(){
	global $session;
	require_once("lib/commentary.php");
	$op = httpget('op');
	if($op=="1garten"){
		if ($session[user][race]=elf){
		page_header("%s"$garten1);
		output("`c`b`2%s`b`c`n`n",$garten1);
		output("`2Du betrittst den %s mit einem Laecheln und und blickst Dich um: Waelder so weit das Auge reicht, hier und da ein Lichtung mit saftigem, gruenen Gras, Heilkraeuter spriessen zwischen Blumen und bluehenden Straeuchern.`n`n",$garten1); 
		output("`2Ein kleiner Bach schlaengelt sich durch das Idyll, der in einem Teich endet, auf dem die wundervollsten Wasserblumen bluehen und Fische munter umherspringen, zwischen Enten die friedllich ihre Bahnen ziehen.`n`n"); 
		output("`2Das Sonnenlicht spielt in den Wipfeln der Baeume und Voegel singen froehlich ihre Lieder, waehrend Schmetterlinge, mit kleinen Feen spielend, um die Wette fliegen.`n`n");
		output("`2Die Luft ist klar und duftet nach Tannennadeln und Laub, was ein ungemein angenehmes Klima erzeugt.`n`n");
		output("`3Eine der Feen, die hier umherschwirren, erinnert dich daran, dass der %s ein Platz für Rollenspiel ist, und dass dieser Bereich vollständig auf charakterspezifische Kommentare beschränkt ist.`n`n"$garten1);
		addcommentary();
		viewcommentary("%s","haucht laechelnd",20,"fluestert",$garten1);
		addnav("Zurück zum Garten","gardens.php");
		}
		else{
		output("Tut mir leid, diser Garten ist nur fuer Elfen zugaenglich.");
		addnav("Zurück zum Garten","gardens.php");
		}
	if($op=="2garten"){
		if ($session[user][race]=human){
		page_header("%s",$garten2);
		output("`c`b`2%s`b`c`n`n",$garten2);
		output("`2Du betrittst den %s und gehst ueber sauber angelegte Wege in einen Garten, der sich sehen lassen kann: weite Wiesen, auf denen die schoensten Blumen bluehen, dazwischen vereinzelte Baeume oder kleine Haine und durch den ganzen %s fliesst ein Bach, der von kleinen Bruecken hier und da ueberquert wird und in einem Teich muendet, auf dem Enten munter ihre Runden drehen.`n`n",$garten2,$garten2); 
		output("`2Voegel singen froh ihr Lied und Schmetterlinge schwirren umher und bilden bunte Bilder in der Luft, die von den kleinen Feen gemalt zu werden scheinen, die lustig von einem zum anderen Bild fliegen und miteinander wetteifern, wer wohl die schoensten Figuren fliegen kann.`n`n");
		output("`3Eine der Feen, die hier umherschwirren, erinnert dich daran, dass der %s ein Platz für Rollenspiel ist, und dass dieser Bereich vollständig auf charakterspezifische Kommentare beschränkt ist.`n`n",$garten2);
		addcommentary();
		viewcommentary("%s","sagt laechelnd",20,"fluestert",$garten2);
		addnav("Zurück zum Garten","gardens.php");
		}
		else{
		output("Tut mir leid, diser Garten ist nur fuer Menschen zugaenglich.");
		addnav("Zurück zum Garten","gardens.php");
		}
	if($op=="3garten"){
		if ($session[user][race]=dwarf){
		page_header("%s",$garten3);
		output("`c`b`2%s`b`c`n`n",$garten3);
		output("`2Du betrittst den %s und blickst Dich laechelnd um: kleine Huegel und Berge, gespickt mit kleinen Hoehlen, Gras waechst ueberall und vereinzelte Baeume, hier und da gar ein kleines Waeldchen, Blumen bluehen und ein kleiner Bach fliesst munter vor sich hin, bis zu einem Teich, auf dem Enten lustig ihre Runden drehen.`n`n",$garten3);
		output("`2In der Mitte des %s kannst Du eine Gruppe riesiger Dioder erkennen, um die der Wind spielt und sein Lied singt waehrend sie in der Sonne funkeln und jedes Zwergenherz hoeher schlagen lassen.`n`n",$garten3);
		output("`2Zwischen den Diodern schwirren lustig kleine Feen umher, vertieft in ihr Spiel.`n`n");
		output("`3Eine der Feen, die hier umherschwirren, erinnert dich daran, dass der %s ein Platz für Rollenspiel ist, und dass dieser Bereich vollständig auf charakterspezifische Kommentare beschränkt ist.`n`n",$garten3);
		addcommentary();
		viewcommentary("%s","prahlt laechelnd",20,"fluestert",$garten3);
		addnav("Zurück zum Garten","gardens.php");
		}
		else{
		output("Tut mir leid, diser Garten ist nur fuer Zwerge zugaenglich.");
		addnav("Zurück zum Garten","gardens.php");
		}
	if($op=="4garten"){
		if ($session[user][race]=troll){
		page_header("%s",$garten4);
		output("`c`b`2%s`b`c`n`n",$garten4);
		output("`2Du betrittst den %s und blickst Dich laechelnd um.`n`n",$garten4);
		output("`2Das ist ein Platz, der das Herz eines jeden, echten Trolls hoeher schlagen laesst: vereinzelte Baeume stehen auf Wiesen, die man schon kaum noch als solche erkennen kann, da sie von Schlammloechern durchzogen werden, in denen ein wunderbarer, braun Schlamm glitzert, der zum suhlen einlaed.`n`n");
		output("`2Zwischen all dem fliesst ein Bach, der die Schlammloecher immer wieder mit neuem Wasser versorgt, damit sie nicht austrocknen und sich am Ende in einen Tuempel ergiesst, in dem ein barunes, morastigesWassser, dass eher an einen Sumpf erinnert, zum baden einlaed.`n`n");
		output("`2Ueber diesem Idyll schweben kleine Feen, die ueberwachen,dass auch wirklich alles seine Richtigkeit hat mit dem Schlamm.`n`n");
		output("`3Eine der Feen, die hier umherschwirren, erinnert dich daran, dass der %s ein Platz für Rollenspiel ist, und dass dieser Bereich vollständig auf charakterspezifische Kommentare beschränkt ist.`n`n",$garten4);
		addcommentary();
		viewcommentary("%s","grunzt laechelnd",20,"fluestert",$garten4);
		addnav("Zurück zum Garten","gardens.php");
		}
		else{
		output("Tut mir leid, diser Garten ist nur fuer Trolle zugaenglich.");
		addnav("Zurück zum Garten","gardens.php");
		}
	if($op=="5garten"){
		if ($session[user][race]=darkelf){
		page_header("%s",$garten5);
		output("`c`b`2%s`b`c`n`n",$garten5);
		output("`2Du betrittst den %s und blickst Dich laechelnd um. Das ist ein Platz, der das Herz eines jeden, echten Dunkelelfen hoeher schlagen laesst: dunkle, dichte Waelder, die auf Boden stehen, der vor urzeiten einmal guter, fruchtbarer Ackerboden gewesen sein mag und jetzt, ausser Moos, Pilzen, Flechten und den Baeumen nichts mehr spriessen laesst.`n`n",$garten4);
		output("`2 Duch diese dichten Waelder zieht sich ein Bach, dessen Wasser eben so dunkel zu sein scheint, wie das dichte Unterholz der Waelder, die nur ab und an eine kleine Lichtung frei geben, die aber voellig vom Schatten der sie umgebenden Baeume verdunkelt werden.`n`n");
		output("`2Ueber den Wipfeln schweben kleine Feen, die darauf achten, dass auch an diesen dunklen Orten alles seine Richtigkeit hat.`n`n");
		output("`3Eine der Feen, die hier umherschwirren, erinnert dich daran, dass der %s ein Platz für Rollenspiel ist, und dass dieser Bereich vollständig auf charakterspezifische Kommentare beschränkt ist.`n`n",$garten5);
		addcommentary();
		viewcommentary("%s","raunt laechelnd",20,"fluestert",$garten5);
		addnav("Zurück zum Garten","gardens.php");
		}
		else{
		output("Tut mir leid, diser Garten ist nur fuer Dunkelelfen zugaenglich.");
		addnav("Zurück zum Garten","gardens.php");
		}

	page_footer();			

}

?>