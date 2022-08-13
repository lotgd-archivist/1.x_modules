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
        output("`3Du packst dir den Ring und rennst so schnell du kannst wieder aus der H�hle raus.");
        give_new_loot("Ring","Ein mit Edelsteinen verzierter Ring",1000,3);
        addnews("`3".$session['user']['name']." hat in einer H�hle einen Edelstein verzierten Ring gefunden.");
      }
      else
      {
        output("`3Du nimmst dir den Edelstein verzierten Ring, doch in dem Augenblick l�sst du eine Falle aus. Ein Stalagtit l�sst sich von der Decke und erschl�gt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.9;
        $session['user']['hitpoints'] = 0;
        $session['user']['alive'] = false;
        addnav("T�gliche News","news.php");
        addnews("`3".$session['user']['name']." wurde in einer H�hle zu gierig und wurde von einem Stalagtit erschlagen.");
      $session['user']['specialinc'] = "";
      }
      break;
    case "nimmglas":
      $rand = e_rand(1,3);
      if ($rand != 3)
      {
        output("`3Du nimmst dir den Glasring und rennst so schnell du kannst aus der H�hle.");
        give_new_loot("Glasring","Ein Wundersch�ner Ring aus Glas",100,0);
        addnews("`3".$session['user']['name']." hat einen (fast) wertlosen Glasring in einer H�hle gefunden.");
      }
      else
      {
        output("`3Du nimmst den Ring, doch in dem Augenblick l�sst du eine Falle aus. Ein Stalagtit l�sst sich von der Decke und erschl�gt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.9;
        $session['user']['hitpoints'] = 0;
        $session['user']['alive'] = false;
        addnav("T�gliche News","news.php");
        addnews("`3".$session['user']['name']." wurde in einer H�hle zu gierig und wurde von einem Stalagtit erschlagen.");
      }
      $session['user']['specialinc'] = "";
      break;
    case "nimmalles":
      $rand = e_rand(1,3);
        output("`3Du packst alles in deine Tasche, doch in dem Augenblick l�sst du eine Falle aus. Ein Stalagtit l�sst sich von der Decke und erschl�gt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.9;
        $session['user']['hitpoints'] = 0;
        $session['user']['alive'] = false;
        addnav("T�gliche News","news.php");
        addnews("`3".$session['user']['name']." wurde in einer H�hle zu gierig und wurde von einem Stalagtit erschlagen.");
      $session['user']['specialinc'] = "";
      break;
    case "nimmnichts":
      $exp = e_rand(round($session['user']['experience']*0.05),round($session['user']['experience']*0.1));
      output("Du entschliesst dich, nichts zu nehmen und verl�sst die H�hle. Gerade als du beim Eingang angekommen bist, kracht ein Stalagmit auf den Altar. Du hast Gl�ck gehabt, h�ttest du was davon genommen, w�rst du sicherlich gestorben. Du bekommst $exp Erfahrungspunkte.");
      $session['user']['experience']+=$exp;
      $session['user']['specialinc'] = "";
      break;
    case "nimmgold":
      $rand = e_rand(1,3);
      if ($rand == 1)
      {
        $gold = e_rand(100,2000);
        output("`3Du nimmst dir die `^$gold Goldst�cke`3 und veschwindest so schnell du kannst wieder aus der H�hle.");
        addnews("`3".$session['user']['name']." wurde in einer H�hle reich und fand `^$gold Goldst�cke`3.");
      }
      else
      {
        output("`3Du nimmst das Gold an dir, doch in dem Augenblick l�sst du eine Falle aus. Ein Stalagtit l�sst sich von der Decke und erschl�gt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.9;
        $session['user']['hitpoints'] = 0;
        $session['user']['alive'] = false;
        addnav("T�gliche News","news.php");
        addnews("`3".$session['user']['name']." wurde in einer H�hle zu gierig und wurde von einem Stalagtit erschlagen.");
      }
      $session['user']['specialinc'] = "";
      break;
    case "altar":
      output("`3Du gehst in Mitte des Stalagnatenkreises und siehst dort auf dem Tisch ein H�ufchen Gold liegen. In der Mitte des H�ufchens liegt ein Ring aus Glas und ein mit Edelsteinen verzierter Ring.");
      addnav("Gold nehmen",$from."step=nimmgold");
      addnav("Edelsteinring nehmen",$from."step=nimmring");
      addnav("Glasring nehmen",$from."step=nimmglas");
      addnav("Alles nehmen",$from."step=nimmalles");
      addnav("Nichts nehmen",$from."step=nimmnichts");
      break;
    case "betritt":
      output("`3Du betrittst vorsichtig die H�hle. Riesige Stalagtiten h�ngen von der Decke runter, die Stalagmiten sehen aus, als ob sie nur auf unvorsichtige Wanderer warten, um sie aufzuspiessen. Im faden Licht meinst du sogar einige Blutspuren auf einem Stalagmit zu sehen. In der Mitte der H�hle bilden stattliche Stalagnate einen Kreis. In der Mitte des Kreises bildet ein Stalagmit eine Art von Tisch, auf deren Mitte etwas zu sein scheint.");
      addnav("Weiter",$from."step=altar");
      break;
    case "goforest":
      output("`3Du hast Bammel davor, die H�hle zu betreten und gehst wieder zur�ck in den Wald.");
      addnews("`3".$session['user']['name']." `3hatte Angst davor, eine H�hle zu betreten.");
      $session['user']['specialinc'] = "";
      break;
    default:
      output("`3W�hrend deiner Streifz�ge durch den Wald entdeckst du eine kleine Einbuchtung in einem Felsen.");
      switch (e_rand(1,5))
      {
        case 3:
          output("Du untersuchst den Felsen gr�ndlich und entdeckst, dass sich hinter der Einbuchtung eine H�hle befinden muss.");
          if ($session['user']['turns']>1)
          {
            $session['user']['turns']-=2;
            output("Du nimmst deine ".$session['user']['weapon']." und machst dich an der Einbuchtung zu schaffen. Die Zeit verrinnt, und als du den Eingang gross genug gemacht hast, merkst du, dass du locker 2 Monster in dieser Zeit h�ttest erledigen k�nnten.`n");
            addnav("Die H�hle betreten",$from."step=betritt");
            addnav("Den Ort verlassen",$from."step=goforest");
          }
          else
          {
            $session['user']['turns'] = 0;
            output("Du nimmst deine ".$session['user']['weapon']." und machst dich an der Einbuchtung zu schaffen. Die Zeit verrinnt und nach einer Weile brichst du ersch�pft zusammen. Du bist nun zu ersch�pft um weiter zumachen oder gar noch ein Monster zu erledigen.`n");
            $session['user']['specialinc'] = "";
          }

          break;
        default:
          $gems = e_rand(0,4);
          $gold = e_rand($session['user']['level']*50,$session['user']['level']*100);
          output("`3Du untersuchst den Felsen gr�ndlich und findest `^$gold Goldst�cke`3 und ".($gems==1?"`%1 Edelstein":"`%$gems Edelsteine")."`3. W�hrend du dich �ber den Schatz freust, h�ttest du locker ein Monster erledigen k�nnten.`n`nDu machst dich mit deinem Neuerworbenen Schatz auf zur�ck in den Wald.");
          if ($session['user']['turns']!=0) $session['user']['turns']--;
          $session['user']['gold']+=$gold;
          $session['user']['gems']+=$gems;
          $session['user']['specialinc'] = "";
          break;
      }
  endswitch;
}

?>
