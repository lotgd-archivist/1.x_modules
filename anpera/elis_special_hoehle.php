<?php
#-----------#
# Copyright #
#-----------#

function elis_special_hoehle_getmoduleinfo()
{
  $info = array(
    "name"=>"Die Hoehle (Waldspecial)",
    "category"=>"Eliwoods Module",
    "author"=>"`QEliwood",
    "Version"=>"1.0"
  );
  return $info;
}

function elis_special_hoehle_install()
{
  module_addeventhook("forest", "return 100;");
	return true;
}

function elis_special_hoehle_uninstall()
{
  return true;
}

function elis_special_hoehle_dohook($args)
{
  return $args;
}

function elis_special_hoehle_runevent($type,$link)
{
  global $session;
  $from = "forest.php?";
  $session['user']['specialinc'] = "module:elis_special_hoehle";
  switch($_GET['step']):
    case "nimmring":
      $rand = e_rand(1,3);
      if ($rand == 1)
      {
        output("`3Du packst dir den Ring und rennst so schnell du kannst wieder aus der Höhle raus.");
        give_new_loot("Ring","Ein mit Edelsteinen verzierter Ring",1000,3);
        addnews("`3".$session['user']['name']." hat in einer Höhle einen Edelstein verzierten Ring gefunden.");
      }
      else
      {
        output("`3Du nimmst dir den Edelstein verzierten Ring, doch in dem Augenblick lösst du eine Falle aus. Ein Stalagtit lösst sich von der Decke und erschlägt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.9;
        $session['user']['hitpoints'] = 0;
        $session['user']['alive'] = false;
        addnav("Tägliche News","news.php");
        addnews("`3".$session['user']['name']." wurde in einer Höhle zu gierig und wurde von einem Stalagtit erschlagen.");
      $session['user']['specialinc'] = "";
      }
      break;
    case "nimmglas":
      $rand = e_rand(1,3);
      if ($rand != 3)
      {
        output("`3Du nimmst dir den Glasring und rennst so schnell du kannst aus der Höhle.");
        give_new_loot("Glasring","Ein Wunderschöner Ring aus Glas",100,0);
        addnews("`3".$session['user']['name']." hat einen (fast) wertlosen Glasring in einer Höhle gefunden.");
      }
      else
      {
        output("`3Du nimmst den Ring, doch in dem Augenblick lösst du eine Falle aus. Ein Stalagtit lösst sich von der Decke und erschlägt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.9;
        $session['user']['hitpoints'] = 0;
        $session['user']['alive'] = false;
        addnav("Tägliche News","news.php");
        addnews("`3".$session['user']['name']." wurde in einer Höhle zu gierig und wurde von einem Stalagtit erschlagen.");
      }
      $session['user']['specialinc'] = "";
      break;
    case "nimmalles":
      $rand = e_rand(1,3);
        output("`3Du packst alles in deine Tasche, doch in dem Augenblick lösst du eine Falle aus. Ein Stalagtit lösst sich von der Decke und erschlägt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.9;
        $session['user']['hitpoints'] = 0;
        $session['user']['alive'] = false;
        addnav("Tägliche News","news.php");
        addnews("`3".$session['user']['name']." wurde in einer Höhle zu gierig und wurde von einem Stalagtit erschlagen.");
      $session['user']['specialinc'] = "";
      break;
    case "nimmnichts":
      $exp = e_rand(round($session['user']['experience']*0.05),round($session['user']['experience']*0.1));
      output("Du entschliesst dich, nichts zu nehmen und verlässt die Höhle. Gerade als du beim Eingang angekommen bist, kracht ein Stalagmit auf den Altar. Du hast Glück gehabt, hättest du was davon genommen, wärst du sicherlich gestorben. Du bekommst $exp Erfahrungspunkte.");
      $session['user']['experience']+=$exp;
      $session['user']['specialinc'] = "";
      break;
    case "nimmgold":
      $rand = e_rand(1,3);
      if ($rand == 1)
      {
        $gold = e_rand(100,2000);
        output("`3Du nimmst dir die `^$gold Goldstücke`3 und veschwindest so schnell du kannst wieder aus der Höhle.");
        addnews("`3".$session['user']['name']." wurde in einer Höhle reich und fand `^$gold Goldstücke`3.");
      }
      else
      {
        output("`3Du nimmst das Gold an dir, doch in dem Augenblick lösst du eine Falle aus. Ein Stalagtit lösst sich von der Decke und erschlägt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.9;
        $session['user']['hitpoints'] = 0;
        $session['user']['alive'] = false;
        addnav("Tägliche News","news.php");
        addnews("`3".$session['user']['name']." wurde in einer Höhle zu gierig und wurde von einem Stalagtit erschlagen.");
      }
      $session['user']['specialinc'] = "";
      break;
    case "altar":
      output("`3Du gehst in Mitte des Stalagnatenkreises und siehst dort auf dem Tisch ein Häufchen Gold liegen. In der Mitte des Häufchens liegt ein Ring aus Glas und ein mit Edelsteinen verzierter Ring.");
      addnav("Gold nehmen",$from."step=nimmgold");
      addnav("Edelsteinring nehmen",$from."step=nimmring");
      addnav("Glasring nehmen",$from."step=nimmglas");
      addnav("Alles nehmen",$from."step=nimmalles");
      addnav("Nichts nehmen",$from."step=nimmnichts");
      break;
    case "betritt":
      output("`3Du betrittst vorsichtig die Höhle. Riesige Stalagtiten hängen von der Decke runter, die Stalagmiten sehen aus, als ob sie nur auf unvorsichtige Wanderer warten, um sie aufzuspiessen. Im faden Licht meinst du sogar einige Blutspuren auf einem Stalagmit zu sehen. In der Mitte der Höhle bilden stattliche Stalagnate einen Kreis. In der Mitte des Kreises bildet ein Stalagmit eine Art von Tisch, auf deren Mitte etwas zu sein scheint.");
      addnav("Weiter",$from."step=altar");
      break;
    case "goforest":
      output("`3Du hast Bammel davor, die Höhle zu betreten und gehst wieder zurück in den Wald.");
      addnews("`3".$session['user']['name']." `3hatte Angst davor, eine Höhle zu betreten.");
      $session['user']['specialinc'] = "";
      break;
    default:
      output("`3Während deiner Streifzüge durch den Wald entdeckst du eine kleine Einbuchtung in einem Felsen.");
      switch (e_rand(1,5))
      {
        case 3:
          output("Du untersuchst den Felsen gründlich und entdeckst, dass sich hinter der Einbuchtung eine Höhle befinden muss.");
          if ($session['user']['turns']>1)
          {
            $session['user']['turns']-=2;
            output("Du nimmst deine ".$session['user']['weapon']." und machst dich an der Einbuchtung zu schaffen. Die Zeit verrinnt, und als du den Eingang gross genug gemacht hast, merkst du, dass du locker 2 Monster in dieser Zeit hättest erledigen könnten.`n");
            addnav("Die Höhle betreten",$from."step=betritt");
            addnav("Den Ort verlassen",$from."step=goforest");
          }
          else
          {
            $session['user']['turns'] = 0;
            output("Du nimmst deine ".$session['user']['weapon']." und machst dich an der Einbuchtung zu schaffen. Die Zeit verrinnt und nach einer Weile brichst du erschöpft zusammen. Du bist nun zu erschöpft um weiter zumachen oder gar noch ein Monster zu erledigen.`n");
            $session['user']['specialinc'] = "";
          }

          break;
        default:
          $gems = e_rand(0,4);
          $gold = e_rand($session['user']['level']*50,$session['user']['level']*100);
          output("`3Du untersuchst den Felsen gründlich und findest `^$gold Goldstücke`3 und ".($gems==1?"`%1 Edelstein":"`%$gems Edelsteine")."`3. Während du dich über den Schatz freust, hättest du locker ein Monster erledigen könnten.`n`nDu machst dich mit deinem Neuerworbenen Schatz auf zurück in den Wald.");
          if ($session['user']['turns']!=0) $session['user']['turns']--;
          $session['user']['gold']+=$gold;
          $session['user']['gems']+=$gems;
          $session['user']['specialinc'] = "";
          break;
      }
  endswitch;
}

?>
