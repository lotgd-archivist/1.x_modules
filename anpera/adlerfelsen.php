<?php

require_once("lib/http.php");

function adlerfelsen_getmoduleinfo(){
        $info = array(
                "name"=>"Der Adlerfelsen",
                "author"=>"LordMontekar",
                "version"=>"1.0",
                "category"=>"Forest",
                "download"=>"core_module",
                "settings"=>array(
                        "Adlerfelsen Einstellungen,title",
                        "pay"=>"Kosten um das Gold an die Bank zu senden,int|5"
                )
        );
        return $info;
}

function adlerfelsen_install() {

        global $session;
        debug("Hooks hinnzufügen");
        module_addhook("forest");
        return true;
}

function adlerfelsen_uninstall(){
        output("Modul deinstalliert.`n");
        return true;
}

function adlerfelsen_dohook($hookname, $args){
        if ($hookname=="forest"){
                addnav("A?Der Adlerfelsen","runmodule.php?module=adlerfelsen");
        }
        return $args;
}

function adlerfelsen_run() {

    global $session;
    $pay = get_module_setting("pay");
    $op = httpget('op');
    
    page_header("Der Adlerfelsen");
    
    if ($op == "") {

        output("`c`b`7Der Adlerfelsen`b`c`n");
        output("`2Du kommst an eine große Felsklippe. An einem Baum in der Nähe ist ein Zettel angebracht. Darauf steht:`n`n");
        output("`&Wir bringen ihr Gold zur Bank. Einfach Gold in die Tasche legen und drei Mal pfeifen. ");
        if ($pay>0) output("`n`7`iNur %s Gold bearbeitungskosten!`i",$pay);
        
        if ($session['user']['gold']>0 || $session['user']['gold']>$pay) {
            addnav("Gold versenden","runmodule.php?module=adlerfelsen&op=send");
        } else if ($gold<$pay && $gold!=0) {
            output("`n`n`2Leider kannst du die Bearbeitungskosten nicht bezahlen.");
        } else {
            output("`2`n`nDu hast nichts, was du versenden könntest...");
        }
        
                        
        addnav("Zurück", "runmodule.php?module=adlerfelsen&op=back");
        page_footer();
        
    } elseif ($op == "back") {
        
        output("`c`b`7Der Adlerfelsen`b`c`n");
        output("`2Du kehrst in den Wald zurück.");
        require_once("lib/forest.php");
        forest(true);
        page_footer();

    } elseif ($op == "send"){

        output("`c`b`7Der Adlerfelsen`b`c`n");
        $session['user']['gold']-=$pay;
        output("`2Du tust all dein Gold in den Beutel und pfeifst drei mal. Nach kurzer Zeit stößt ein Adler vom Himmel und fliegt ", $session['user']['gold']);
        $session['user']['goldinbank']+=$session['user']['gold'];
        output("mit der Tasche davon. `n`n`7Du deponierst `^%s`7 Gold auf der Bank. Du hast jetzt insgesamt `^%s `7Gold auf der Bank.",$session['user']['gold'],$session['user']['goldinbank']);
        $session['user']['gold']=0;
        require_once("lib/forest.php");
        forest(true);
        page_footer();
    }
}
?>