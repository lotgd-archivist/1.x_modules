<?php

function wettkampf_wkochen_wkochenergebnis_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
		require_once("lib/fert.php");	
		$kochen=get_fertigkeit(kochen);	
		$schwierigkeit=get_module_setting("schwierigkeit", "wettkampf");
		$wkochen=0;	
	
		output("`@`bDer Koch- und Backwettbewerb`b`n");
			 
	//Probe
		$t1=probe($kochen, $schwierigkeit, 0.9, 99.1, true);
		$t=array(floor($t1[wert]), $t1[ergebnis]);
		$ergebnis=$t[0];
		
		if ($t[1] == "kritischer erfolg"){
			if ($schwierigkeitsgrad == 15) $ergebnis=50;
			else if ($schwierigkeitsgrad == -15) $ergebnis=75;
			else if ($schwierigkeitsgrad == -25) $ergebnis=110;
			else if ($schwierigkeitsgrad == -40) $ergebnis=125;
		}
		else if ($t[1] == "kritischer misserfolg") $ergebnis=-95;
		
		if ($ergebnis >=0){
			if ($schwierigkeit == 25){
				$schwierigkeit_text=50;
				if ($ergebnis>50) $ergebnis=50;	
			}
			else if ($schwierigkeit == 0){
				$schwierigkeit_text=75;
				$ergebnis+=30;
				if ($ergebnis>75) $ergebnis=75;
			}
			else if ($schwierigkeit == -25){
				$schwierigkeit_text=110;
				$ergebnis+=65;
				if ($ergebnis>110) $ergebnis=110;
			}
			else if ($schwierigkeit == -50){
				$schwierigkeit_text=125;
				$ergebnis+=80;
				if ($ergebnis>125) $ergebnis=125;
			}
		}
		
		//Ergebniswahrscheinlichkeiten (ohne kritische Ergebnisse)
		//
		//Einfach: FW 25  -> 50%  Chance auf >= 0  | 25%  Chance auf >= 25  | 1%  Chance auf 50
		//		   FW 50  -> 75%  Chance auf >= 0  | 50%  Chance auf >= 25  | 25% Chance auf 50
		//		   FW 75  -> 100% Chance auf >= 0  | 75%  Chance auf >= 25  | 50% Chance auf 50
		//		   FW 100 -> 100% Chance auf >= 0  | 100% Chance auf >= 25  | 75% Chance auf 50
		//
		//Normal:  FW 50  -> 50%  Chance auf >= 30 | 25%  Chance auf >= 55  | 5%  Chance auf 75
		//		   FW 75  -> 75%  Chance auf >= 30 | 50%  Chance auf >= 55  | 30% Chance auf 75
		//		   FW 100 -> 100% Chance auf >= 30 | 75%  Chance auf >= 55  | 55% Chance auf 75
		//
		//Schwer:  FW 50  -> 1%   Chance auf == 65
		//		   FW 75  -> 50%  Chance auf >= 65 | 25% Chance auf >= 90   | 5%  Chance auf 110
		//		   FW 100 -> 75%  Chance auf >= 65 | 50% Chance auf >= 90   | 30% Chance auf 110
		//
		//Spezial: FW 50  -> 1%   Chance auf == 80
		//		   FW 75  -> 25%  Chance auf >= 80 | 1%  Chance auf == 105
		//		   FW 100 -> 50%  Chance auf >= 80 | 25% Chance auf >= 105 | 5% Chance auf 125
			
		//Ungenie�bares
		if ($ergebnis <= -95) $speise=translate_inline("eine �u�erst widerliche, schwarze Pampe, die bis zum Gemeindeplatz nach Aas stinkt und Halluzinationen verursacht");
		else if ($ergebnis <= -85 && $ergebnis > -95) $speise=translate_inline("eine �u�erst widerliche, schwarze Pampe, die �ber den ganzen Festplatz stinkt");
		else if ($ergebnis <= -75 && $ergebnis > -85) $speise=translate_inline("eine �u�erst widerliche, schwarze Pampe");
		else if ($ergebnis <= -60 && $ergebnis > -75) $speise=translate_inline("eine widerliche, dunkelbraune Pampe");
		else if ($ergebnis <= -40 && $ergebnis > -60) $speise=translate_inline("eine ungenie�bare, hellbraune Pampe");
		else if ($ergebnis <= -20 && $ergebnis > -40) $speise=translate_inline("eine hellbraune Pampe, die nur entfernt an etwas E�bares erinnert");
		else if ($ergebnis <= 0 && $ergebnis > -20) $speise=translate_inline("eine hellbraune Pampe ohne jeden Geschmack");
	
		//Einfaches
		switch ($ergebnis){
			case 1: $speise=translate_inline("eine gesch�lte Steckr�be"); break;
			case 2: $speise=translate_inline("eine gesch�lte und gekochte Steckr�be"); break;
			case 3: $speise=translate_inline("eine gesch�lte, geschnittene und gekochte Steckr�be"); break;
			case 4: $speise=translate_inline("einen etwas z�hen Haferbrei"); break;
			case 5: $speise=translate_inline("einen etwas klumpigen Haferbrei"); break;
			case 6: $speise=translate_inline("einen einfachen, aber gelungenen Haferbrei"); break;
			case 7: $speise=translate_inline("einen gezuckerten Haferbrei"); break;
			case 8: $speise=translate_inline("einen Haferbrei mit Steckr�bensalat"); break;
			case 9: $speise=translate_inline("einen gezuckerten Haferbrei mit Steckr�bensalat"); break;
			case 10: $speise=translate_inline("eine einfache Kartoffelsuppe"); break;
			case 11: $speise=translate_inline("eine mit Salz verfeinerte Kartoffelsuppe"); break;
			case 12: $speise=translate_inline("eine mit Salz verfeinerte Kartoffelsuppe mit Steckr�bensalat"); break;
			case 13: $speise=translate_inline("eine mit Salz und Kr�utern verfeinerte Kartoffelsuppe mit Steckr�bensalat"); break;
			case 14: $speise=translate_inline("eine formvollendet gew�rzte Kartoffelsuppe mit Steckr�bensalat und Haferbrei als Nachtisch"); break;
			case 15: $speise=translate_inline("eine leckere Kartoffel-Steckr�bensuppe mit gezuckertem Haferbrei als Nachtisch"); break;
			case 16: $speise=translate_inline("eine leckere Kartoffel-Steckr�bensuppe mit einem Apfel-Birnen-Salat als Nachtisch"); break;
			case 17: $speise=translate_inline("eine leckere Kartoffel-Steckr�bensuppe mit einem sch�n dekorierten Apfel-Birnen-Salat als Nachtisch"); break;
			case 18: $speise=translate_inline("eine leckere Kartoffelsuppe und einen Eintopf aus Kartoffeln und Mohrr�ben mit einem Apfel-Birnensalat als Nachtisch"); break;
			case 19: $speise=translate_inline("eine w�rzige Kartoffelsuppe und einen Eintopf aus Kartoffeln, Erbsen und Mohrr�ben mit einem Apfel-Birnensalat als Nachtisch"); break;
			case 20: $speise=translate_inline("eine vorz�gliche Kartoffelsuppe und einen Eintopf aus Kartoffeln, Erbsen, Bohnen und Mohrr�ben mit einem Apfel-Birnensalat als Nachtisch"); break;
			case 21: $speise=translate_inline("eine vorz�gliche Kartoffelsuppe und einen sch�ndekorierten Eintopf aus Kartoffeln, Erbsen, Bohnen und Mohrr�ben mit einem Apfel-Birnensalat als Nachtisch"); break;
			case 22: $speise=translate_inline("eine vorz�gliche Kartoffelsuppe und einen sch�ndekorierten Eintopf aus Kartoffeln, Erbsen, Bohnen und Mohrr�ben mit einem verzierten Apfel-Birnensalat als Nachtisch"); break;
			case 23: $speise=translate_inline("eine Erbsensuppe und Bratkartoffeln mit Spiegeleiern sowie einen vorz�glichen Apfelkompott"); break;
			case 24: $speise=translate_inline("eine feindekorierte Erbsensuppe und Bratkartoffeln mit Spiegeleiern sowie einen vorz�glichen Apfel-Birnen-Kompott"); break;
			case 25: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose Bratkartoffeln mit Spiegeleiern sowie einen vorz�glichen Apfel-Birnen-Kompott"); break;
			case 26: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichf�rmige Bratkartoffeln mit Spiegeleiern sowie einen vorz�glichen Apfel-Birnen-Kompott"); break;
			case 27: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichf�rmige Bratkartoffeln mit R�hrei sowie einen vorz�glichen Apfel-Birnen-Kompott"); break;
			case 28: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichf�rmige Bratkartoffeln mit w�rzigem R�hrei sowie einen vorz�glichen Apfel-Birnen-Kompott"); break;
			case 29: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichf�rmige Bratkartoffeln mit w�rzigem R�hrei sowie einen Bratapfel auf Birnenkompott"); break;
			case 30: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichf�rmige Bratkartoffeln mit w�rzigem R�hrei sowie einen verzierten Bratapfel auf Birnenkompott"); break;
			case 31: $speise=translate_inline("eine dekorierte M�hren-Erbsensuppe und goldbraune Eierpfannkuchen sowie einen einfachen Quark"); break;
			case 32: $speise=translate_inline("eine dekorierte M�hren-Erbsensuppe und goldbraune Eierpfannkuchen sowie einen feingezuckerten Quark"); break;
			case 33: $speise=translate_inline("eine dekorierte M�hren-Erbsensuppe und goldbraune Eierpfannkuchen mit Apfelst�ckchen sowie einen feingezuckerten Quark"); break;
			case 34: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und goldbraune Eierpfannkuchen mit Apfelst�ckchen sowie einen feingezuckerten Quark"); break;
			case 35: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und goldbraune, gleichm��ige Eierpfannkuchen mit Apfelst�ckchen sowie einen feingezuckerten Quark"); break;
			case 36: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und goldbraune, gleichm��ige Eierpfannkuchen mit Apfelst�ckchen sowie einen feingezuckerten, cremigen Quark"); break;
			case 37: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und goldbraune, gleichm��ige Eierpfannkuchen mit Apfel- und Birnenst�ckchen sowie einen feingezuckerten, cremigen Quark"); break;
			case 38: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und goldbraune, gleichm��ige Eierpfannkuchen mit Apfel- und Birnenst�ckchen sowie einen feingezuckerten, besonders cremigen Quark"); break;
			case 39: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und goldbraune, gleichm��ige Eierpfannkuchen mit gleichm��igen Apfel- und Birnenst�ckchen sowie einen feingezuckerten, besonders cremigen Quark"); break;
			case 40: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und verzierte, goldbraune, gleichm��ige Eierpfannkuchen mit gleichm��igen Apfel- und Birnenst�ckchen sowie einen feingezuckerten, besonders cremigen Quark"); break;
			case 41: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und verzierte, goldbraune, gleichm��ige Eierpfannkuchen mit gleichm��igen Apfel- und Birnenst�ckchen sowie einen feingezuckerten, besonders cremigen Quark mit Kirschen"); break;
			case 42: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und verzierte, goldbraune, gleichm��ige Eierpfannkuchen mit gleichm��igen Apfel- und Birnenst�ckchen sowie einen feingezuckerten, besonders cremigen Quark mit ausgew�hlten Kirschen"); break;
			case 43: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und verzierte, goldbraune, gleichm��ige Eierpfannkuchen mit gleichm��igen Apfel- und Birnenst�ckchen sowie einen feingezuckerten, besonders cremigen Quark mit ausgew�hlten, halbierten Kirschen"); break;
			case 44: $speise=translate_inline("eine feindekorierte M�hren-Erbsensuppe und verzierte, goldbraune, gleichm��ige Eierpfannkuchen mit gleichm��igen Apfel- und Birnenst�ckchen sowie einen feingezuckerten, besonders cremigen Quark mit ausgew�hlten, kunstvoll geschnittenen Kirschen"); break;
			case 45: $speise=translate_inline("eine dekorierte Kartoffel-M�hren-Erbsensuppe und Salzkartoffeln mit Rosenkohl sowie einen vorz�glichen Sahnequark mit Mandarinen"); break;
			case 46: $speise=translate_inline("eine feindekorierte Kartoffel-M�hren-Erbsensuppe und Salzkartoffeln mit Rosenkohl sowie einen vorz�glichen Sahnequark mit Mandarinen"); break;
			case 47: $speise=translate_inline("eine feindekorierte Kartoffel-M�hren-Erbsensuppe und Salzkartoffeln mit Rosenkohl sowie einen vorz�glichen, verzierten Sahnequark mit Mandarinen"); break;
			case 48: $speise=translate_inline("eine feindekorierte Kartoffel-M�hren-Erbsensuppe und Salzkartoffeln mit Rosenkohl und Spiegeleiern sowie einen vorz�glichen, verzierten Sahnequark mit Mandarinen"); break;
			case 49: $speise=translate_inline("eine feindekorierte Kartoffel-M�hren-Erbsensuppe und Salzkartoffeln mit Rosenkohl in einer Kr�uterso�e sowie einen vorz�glichen, verzierten Sahnequark mit Mandarinen"); break;
			case 50: $speise=translate_inline("eine feindekorierte Kartoffel-M�hren-Erbsensuppe und Salzkartoffeln mit Rosenkohl in einer besonders delikaten Kr�uterso�e sowie einen vorz�glichen, verzierten Sahnequark mit Mandarinen"); break;
		
			//Normales
			case 51: $speise=translate_inline("eine Zwiebelsuppe und einen Kartoffelauflauf mit Erbsen, M�hren und Zwiebeln sowie einen vorz�glichen Sahnequark mit Preiselbeeren"); break;
			case 52: $speise=translate_inline("eine dekorierte Zwiebelsuppe und einen mit K�se �berbackenen Kartoffelauflauf mit Erbsen, M�hren und Zwiebeln sowie einen vorz�glichen Sahnequark mit Preiselbeeren"); break;
			case 53: $speise=translate_inline("eine feindekorierte Zwiebelsuppe und einen mit K�se �berbackenen Kartoffelauflauf mit Erbsen, M�hren, Zwiebeln und erlesenen Kr�utern sowie einen vorz�glichen Sahnequark mit Preiselbeeren"); break;
			case 54: $speise=translate_inline("eine feindekorierte Zwiebelsuppe und einen mit K�se �berbackenen Kartoffelauflauf mit Erbsen, M�hren, Zwiebeln und erlesenen Kr�utern sowie einen vorz�glichen, verzierten Sahnequark mit Preiselbeeren"); break;
			case 55: $speise=translate_inline("eine dekorierte Gem�sesuppe und einen mit K�se �berbackenen Kartoffelauflauf mit Erbsen, M�hren, Zwiebeln und erlesenen Kr�utern sowie einen vorz�glichen, verzierten Sahnequark mit Preiselbeeren"); break;
			case 56: $speise=translate_inline("eine feindekorierte Gem�sesuppe und einen mit K�se �berbackenen Kartoffelauflauf mit Erbsen, M�hren, Zwiebeln und erlesenen Kr�utern sowie einen vorz�glichen, verzierten Sahnequark mit Preiselbeeren"); break;
			case 57: $speise=translate_inline("eine feindekorierte, vorz�gliche Gem�sesuppe und einen mit K�se �berbackenen Kartoffelauflauf mit Erbsen, M�hren, Zwiebeln und erlesenen Kr�utern sowie einen vorz�glichen, verzierten Sahnequark mit Preiselbeeren"); break;
			case 58: $speise=translate_inline("eine feindekorierte, vorz�gliche Gem�sesuppe und einen mit K�se �berbackenen Kartoffelauflauf mit Erbsen, M�hren, Zwiebeln und erlesenen Kr�utern sowie einen vorz�glichen, verzierten Sahnequark mit Preiselbeeren und einer Sahnehaube"); break;
			case 59: $speise=translate_inline("eine feindekorierte, vorz�gliche Gem�sesuppe und einen mit K�se �berbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit Kirschen"); break;
			case 60: $speise=translate_inline("eine vorz�gliche Tomatencremesuppe und einen mit K�se �berbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit Kirschen"); break;
			case 61: $speise=translate_inline("eine vorz�gliche Tomatencremesuppe und einen mit K�se �berbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit hei�en Kirschen"); break;
			case 62: $speise=translate_inline("eine dekorierte, vorz�gliche Tomatencremesuppe und einen mit K�se �berbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit hei�en Kirschen"); break;
			case 63: $speise=translate_inline("eine feindekorierte, vorz�gliche Tomatencremesuppe und einen mit K�se �berbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit hei�en Kirschen"); break;
			case 64: $speise=translate_inline("eine feindekorierte, vorz�gliche Tomatencremesuppe und einen mit K�se �berbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit erlesenen, hei�en Kirschen"); break;
			case 65: $speise=translate_inline("eine feindekorierte, vorz�gliche Tomatencremesuppe und selbstgemachte Bandnudeln mit einer w�rzigen K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen"); break;
			case 66: $speise=translate_inline("eine feindekorierte, vorz�gliche Tomatencremesuppe und selbstgemachte Bandnudeln mit einer feinw�rzigen K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen"); break;
			case 67: $speise=translate_inline("eine feindekorierte, vorz�gliche Tomatencremesuppe und selbstgemachte Bandnudeln mit einer feinw�rzigen K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 68: $speise=translate_inline("eine feindekorierte, vorz�gliche Tomatencremesuppe und selbstgemachte Spiralnudeln mit einer feinw�rzigen K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 69: $speise=translate_inline("eine einfache Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinw�rzigen K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 70: $speise=translate_inline("eine einfache, dekorierte Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinw�rzigen K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 71: $speise=translate_inline("eine einfache, feindekorierte Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinw�rzigen K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 72: $speise=translate_inline("eine vorz�gliche, feindekorierte Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinw�rzigen K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 73: $speise=translate_inline("eine vorz�gliche, feindekorierte Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinw�rzigen Zwei-K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 74: $speise=translate_inline("eine dekorierte Spargelcremesuppe und selbstgemachte Spiralnudeln mit einer feinw�rzigen Zwei-K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 75: $speise=translate_inline("eine feindekorierte Spargelcremesuppe und selbstgemachte Spiralnudeln mit einer feinw�rzigen Zwei-K�se-So�e sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
		
			//Schwieriges
			case 76: $speise=translate_inline("eine feindekorierte, vorz�gliche Spargelcremesuppe und selbstgemachte Gnocchi mit einer feurigen Drei-K�se-So�e sowie einen Vanillepudding mit echten Schokoladenst�ckchen"); break;
			case 77: $speise=translate_inline("eine feindekorierte, vorz�gliche Spargelcremesuppe und selbstgemachte, gleichf�rmige Gnocchi mit einer feurigen Drei-K�se-So�e sowie einen Vanillepudding mit echten Schokoladenst�ckchen"); break;
			case 78: $speise=translate_inline("eine feindekorierte, vorz�gliche Spargelcremesuppe und selbstgemachte, gleichf�rmige Gnocchi mit einer feurigen Edelk�se-So�e sowie einen Vanillepudding mit echten Schokoladenst�ckchen"); break;
			case 79: $speise=translate_inline("eine feindekorierte, vorz�gliche Spargelcremesuppe und selbstgemachte, gleichf�rmige Gnocchi mit einer feurigen Edelk�se-So�e sowie einen Vanillepudding mit echten, geraspelten Schokoladenst�ckchen"); break;
			case 80: $speise=translate_inline("eine feindekorierte, vorz�gliche Spargelcremesuppe und selbstgemachte, gleichf�rmige Gnocchi mit einer feurigen Edelk�se-So�e sowie einen Vanillepudding mit echten, feingeraspelten Schokoladenst�ckchen"); break;
			case 81: $speise=translate_inline("eine feindekorierte, vorz�gliche Spargelcremesuppe und selbstgemachte, gleichf�rmige Gnocchi mit einer feurigen So�e aus trollischem Edelk�se sowie einen Vanillepudding mit echten, feingeraspelten Schokoladenst�ckchen"); break;
			case 82: $speise=translate_inline("eine feindekorierte, vorz�gliche Spargelcremesuppe und selbstgemachte, gleichf�rmige Gnocchi mit einer feurigen So�e aus trollischem Edelk�se sowie einen Vanillepudding mit echten, feingeraspelten Schokoladenst�ckchen und einer Sahnehaube"); break;
			case 83: $speise=translate_inline("einen feindekorierten Salat und einen gemischten Teller vorz�glichster Nudeln mit einer w�rzigen Sahneso�e aus Edelk�se und Gebirgskr�utern aus dem Drassok sowie eine raffinierte Sahnespeise mit einem Hauch von Tr�ffel"); break;
			case 84: $speise=translate_inline("einen feindekorierten, vorz�glichen Salat und einen gemischten Teller vorz�glichster Nudeln mit einer w�rzigen Sahneso�e aus Edelk�se und Gebirgskr�utern aus dem Drassok sowie eine raffinierte Sahnespeise mit einem Hauch von Tr�ffel"); break;
			case 85: $speise=translate_inline("einen feindekorierten, vorz�glichen Salat und einen gemischten Teller vorz�glichster Nudeln mit einer w�rzigen Sahneso�e aus trollischem Edelk�se und Gebirgskr�utern aus dem Drassok sowie eine raffinierte Sahnespeise mit einem Hauch von Tr�ffel"); break;
			case 86: $speise=translate_inline("einen feindekorierten, vorz�glichen Salat und einen gemischten Teller vorz�glichster Nudeln mit einer w�rzigen Sahneso�e aus trollischem Edelk�se und Gebirgskr�utern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem Hauch von Tr�ffel"); break;
			case 87: $speise=translate_inline("einen feindekorierten, vorz�glichen Salat und einen gemischten Teller vorz�glichster Nudeln mit einer w�rzigen Sahneso�e aus trollischem Edelk�se und Gebirgskr�utern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem halben Tr�ffel"); break;
			case 88: $speise=translate_inline("einen feindekorierten, vorz�glichen Salat und einen gemischten Teller vorz�glichster Nudeln mit einer w�rzigen Sahneso�e aus trollischem Edelk�se und Gebirgskr�utern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem ganzen Tr�ffel"); break;
			case 89: $speise=translate_inline("einen feindekorierten, vorz�glichen Salat und einen gemischten Teller vorz�glichster Nudeln mit einer w�rzig-cremigen Sahneso�e aus trollischem Edelk�se und Gebirgskr�utern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem halben Tr�ffel"); break;
			case 90: $speise=translate_inline("einen feindekorierten, vorz�glichen Salat und einen gemischten Teller vorz�glichster Nudeln mit einer w�rzig-cremigen Sahneso�e aus trollischem Edelk�se und Gebirgskr�utern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem ganzen Tr�ffel"); break;
			case 91: $speise=translate_inline("einen feindekorierten, vorz�glichen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Waldkr�utern sowie eine Waldbeerenspeise mit einem Hauch des teuren Tr�ffelzuckers aus den �berseeischen Handelskolonien"); break;
			case 92: $speise=translate_inline("einen vorz�glichen elfischen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Waldkr�utern sowie eine Waldbeerenspeise mit einem Hauch des teuren Tr�ffelzuckers aus den �berseeischen Handelskolonien"); break;
			case 93: $speise=translate_inline("einen vorz�glichen elfischen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Kr�utern aus den W�ldern der Hochelfen sowie eine Waldbeerenspeise mit einem Hauch des teuren Tr�ffelzuckers aus den �berseeischen Handelskolonien"); break;
			case 94: $speise=translate_inline("einen vorz�glichen elfischen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Kr�utern aus den W�ldern der Hochelfen sowie eine Waldbeerenspeise mit einer Haube des teuren Tr�ffelzuckers aus den �berseeischen Handelskolonien"); break;
			case 95: $speise=translate_inline("einen vorz�glichen elfischen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Kr�utern aus den W�ldern der Hochelfen sowie eine Waldbeerenspeise mit einer entz�ndeten Haube des teuren Tr�ffelzuckers aus den �berseeischen Handelskolonien"); break;
			case 96: $speise=translate_inline("ein flambiertes Vanillesufflet und mit Fr�chten gef�llte Edelpilze mit einer vielfarbigen cremigen So�e aus Ratschukbeeren"); break;
			case 97: $speise=translate_inline("ein flambiertes Vanillesufflet und mit Fr�chten gef�llte trollische Edelpilze mit einer vielfarbigen cremigen So�e aus Ratschukbeeren"); break;
			case 98: $speise=translate_inline("ein flambiertes Vanillesufflet und mit Fr�chten gef�llte trollische Edelpilze mit einer vielfarbigen cremigen So�e aus Ratschukbeeren und eine vorz�gliche Weincreme"); break;
			case 99: $speise=translate_inline("ein flambiertes Vanillesufflet und mit Fr�chten gef�llte trollische Edelpilze mit einer vielfarbigen cremigen So�e aus Ratschukbeeren und eine vorz�gliche Weincreme nach einem Rezept der Gro�mutter von Gro�inquisitor Resal"); break;
			case 100: $speise=translate_inline("ein flambiertes Schokoladensufflet und mit Fr�chten gef�llte trollische Edelpilze mit einer vielfarbigen cremigen So�e aus Ratschukbeeren und eine vorz�gliche Weincreme nach einem Rezept der Gro�mutter von Gro�inquisitor Resal"); break;
			case 101: $speise=translate_inline("ein flambiertes Schokoladensufflet und mit elfischen Waldfr�chten gef�llte trollische Edelpilze mit einer vielfarbigen cremigen So�e aus Ratschukbeeren und eine vorz�gliche Weincreme nach einem Rezept der Gro�mutter von Gro�inquisitor Resal"); break;
			case 102: $speise=translate_inline("ein flambiertes Schokoladensufflet und mit elfischen Waldfr�chten gef�llte trollische Edelpilze mit einer vielfarbigen cremigen So�e aus Ratschukbeeren und eine vorz�gliche Weincreme nach einem Rezept der Gro�mutter von Gro�inquisitor Resal"); break;
			case 103: $speise=translate_inline("ein flambiertes Schokoladen-Vanille-Sufflet und mit elfischen Waldfr�chten gef�llte drassorianische Stollenpilze, garniert mit handverlesenen Sumbrastengeln und eine vorz�gliche Weincreme nach einem Rezept der Gro�mutter von Gro�inquisitor Resal"); break;
			case 104: $speise=translate_inline("ein flambiertes Schokoladen-Vanille-Sufflet und mit elfischen Waldfr�chten gef�llte drassorianische Stollenpilze, garniert mit handverlesenen Sumbrastengeln aus Chrizzak und eine vorz�gliche Weincreme nach einem Rezept der Gro�mutter von Gro�inquisitor Resal"); break;
			case 105: $speise=translate_inline("ein flambiertes Schokoladen-Vanille-Sufflet und mit elfischen Waldfr�chten gef�llte drassorianische Stollenpilze, garniert mit handverlesenen Sumbrastengeln aus Chrizzak und eine �hm ... 'vorz�gliche' R�bencreme nach einem Rezept der Mutter des allm�chtigen Zrarek"); break;
			case 106: $speise=translate_inline("ein flambiertes Schokoladen-Vanille-Sufflet und mit elfischen Waldfr�chten gef�llte drassorianische Stollenpilze, garniert mit handverlesenen Sumbrastengeln aus Chrizzak und eine �hm ... 'vorz�gliche' R�bencreme nach einem Rezept der Mutter des allm�chtigen Zrarek"); break;
			case 107: $speise=translate_inline("eine kunstvolle Zusammenstellung der traditionellen Lieblingsspeisen aller gro�en V�lker"); break;
			case 108: $speise=translate_inline("eine kunstvolle Zusammenstellung der traditionellen Lieblingsspeisen aller gro�en V�lker, verfeinert mit dem Wissen und K�nnen eines Meisterkochs"); break;
			case 109: $speise=translate_inline("eine kunstvolle Zusammenstellung der traditionellen Lieblingsspeisen aller gro�en V�lker, verfeinert mit dem Wissen und K�nnen eines Meisterkochs sowie �hm ... eine 'vorz�gliche' Steckr�bensahnespeise nach einem Rezept der Gemahlin des allm�chtigen Zrarek"); break;
			case 110: $speise=translate_inline("eine kunstvolle Zusammenstellung der traditionellen Lieblingsspeisen aller gro�en V�lker, verfeinert mit dem Wissen und K�nnen eines Meisterkochs sowie �hm ... eine 'vorz�gliche' Steckr�bensahnespeise nach einem Rezept der Gemahlin des allm�chtigen Zrarek mit einem Schuss Met"); break;
		
			//Spezialit�ten
			case 111: $speise=translate_inline("trollische Chr'phala-Pilze, die atemberaubendste Pilzspeise im ganzen Land"); break;
			case 112: $speise=translate_inline("echsische Zra'kras, eine Keksvariation aus den edelsten Zutaten im ganzen Land"); break;
			case 113: $speise=translate_inline("drassorianische Muldenbronks, ein ... nun ja, im Grunde sind es R�ben, aber sie schmecken selbst den Elfen vorz�glich"); break;
			case 114: $speise=translate_inline("eine raffinierte Kombination der raffiniertesten Speisen der Wald- und Hochelfen, genannt 'Je'direy'"); break;
			case 115: $speise=translate_inline("ein wahrhaft f�rstliches F�nfg�ngemen�"); break;
			case 116: $speise=translate_inline("ein wahrhaft k�nigliches Sechsg�ngemen�"); break;
			case 117: $speise=translate_inline("ein wahrhaft kaiserliches Siebeng�ngemen�"); break;
			case 118: $speise=translate_inline("ein wahrhaft gottkaiserliches Achtg�ngemen�"); break;
			case 119: $speise=translate_inline("ein wahrhaft �berirdisches Neung�ngemen�"); break;
			case 120: $speise=translate_inline("ein wahrhaft g�ttliches Zehng�ngemen�"); break;
			case 121: $speise=translate_inline("ein wahrhaft unbeschreibliches Elfg�ngemen�"); break;
			case 122: $speise=translate_inline("das beste Zw�lfg�ngemen�, das diese Welt jemals gesehen hat und sehen wird"); break;
			case 123: $speise=translate_inline("ein Dreizehng�ngemen�, das sogar Ramius' Interesse auf sich gezogen hat"); break;
			case 124: $speise=translate_inline("das Vierzehng�ngemen�, das selbst das g�ttliche Ambrosia in den Schatten stellt und wof�r Dich mancher Gott zu seinesgleichen z�hlen w�rde"); break;
			case 125: $speise=translate_inline("einen leeren Teller, den Du mit langen Worten zu Kunst erkl�rst und der als kulinarische Revolution in die Geschichte eingeht"); break;
		}
		$punkte=$ergebnis;
		if ($ergebnis<0) $punkte=0;
	
		if ($ergebnis >0) output("`@Du hast alles gegeben und Deine Pl�ne immer wieder ver�ndert, um blo� nichts Mi�lungenes zu pr�sentieren. Schlie�lich �berreichst Du der Jury `^%s`@, wof�r Du `^%s`@ von den `^%s`@ Punkten bekommst, die auf diesem Schwierigkeitsgrad m�glich sind.`n`n", $speise, $punkte, $schwierigkeit_text);
		if ($ergebnis <=0 && $ergebnis > -20) output("`@Du hast alles gegeben und Deine Pl�ne immer wieder ver�ndert, um blo� nichts Mi�lungenes zu pr�sentieren ... Aber es hat nichts gen�tzt, etwas besch�mt �berreichst Du der Jury `^%s`@, wof�r Du nat�rlich keine Punkte bekommst.`n`n", $speise);
		if ($ergebnis <= -20 && $ergebnis > -40) output("`@Du hast alles gegeben und Deine Pl�ne immer wieder ver�ndert, um blo� nichts Mi�lungenes zu pr�sentieren ... Aber es hat nichts gen�tzt, etwas besch�mt �berreichst Du der Jury `^%s`@, wof�r Du nat�rlich keine Punkte bekommst.`n`n", $speise); 
		if ($ergebnis <= -40 && $ergebnis > -60) output("`@Du hast alles gegeben und Deine Pl�ne immer wieder ver�ndert, um blo� nichts Mi�lungenes zu pr�sentieren ... Aber es hat nichts gen�tzt, mit hochrotem Gesicht �berreichst Du der Jury `^%s`@, wof�r Du nat�rlich keine Punkte bekommst.`n`n", $speise);
		if ($ergebnis <= -60 && $ergebnis > -75) output("`@Du hast alles gegeben und Deine Pl�ne immer wieder ver�ndert, um blo� nichts Mi�lungenes zu pr�sentieren ... Aber es hat nichts gen�tzt, mit hochrotem Gesicht �berreichst Du der Jury `^%s`@. Angewidert wenden sich die Fachleute von Dir ab. Die umstehenden Zuschauer buhen Dich aus.`n`n", $speise);
		if ($ergebnis <= -75 && $ergebnis > -85) output("`@Du hast alles gegeben und Deine Pl�ne immer wieder ver�ndert, um blo� nichts Mi�lungenes zu pr�sentieren ... Aber es hat nichts gen�tzt, mit hochrotem Gesicht und einem mulmigen Gef�hl �berreichst Du der Jury `^%s`@. Die Fachleute sind emp�rt und die umstehenden Zuschauer sch�tteln die K�pfe.`n`n", $speise);
		if ($ergebnis <= -85 && $ergebnis > -100){
			require_once("lib/commentary.php");
			output("`@Du hast alles gegeben und Deine Pl�ne immer wieder ver�ndert, um blo� nichts Mi�lungenes zu pr�sentieren ... Aber es hat nichts gen�tzt, mit hochrotem Gesicht und dem Gef�hl, Dich gleich �bergeben zu m�ssen �berreichst Du der Jury `^%s`@. Vor lauter Kotzerei kommt keiner der Fachleute dazu, Dir seine Meinung �ber dieses Teufelszeug zu sagen.`n`n", $speise);
			$comment=translate_inline("/me `\$�s Kochversuch verbreitet seinen widerw�rtigen Gestank �ber den gesamten Festplatz, wovon sich einige Leute �bergeben m�ssen und zum Teil sogar ohnm�chtig werden.");
			injectcommentary(wettkampf, "", $comment, $schema=false);
		}
		if ($ergebnis <= -100){
			require_once("lib/commentary.php");
			output("`@Du hast alles gegeben und Deine Pl�ne immer wieder ver�ndert, um blo� nichts Mi�lungenes zu pr�sentieren ... Aber es hat nichts gen�tzt, mit hochrotem Gesicht und dem Gef�hl, Dich gleich �bergeben zu m�ssen, das von dem seltsamen Wahnzustand begleitet wird, dass Du ein dreischw�nziges Eichh�rnchen bist, �berreichst Du der Jury `^%s`@. Ein Teil der umstehenden Zuschauer �bergibt sich, w�hrend ein anderer Teil im Boden nach N�ssen gr�bt. Die Fachleute sind allesamt ohnm�chtig geworden.`n`n", $speise);
			$comment=translate_inline("/me `\$�s Kochversuch verbreitet seinen ekligen Aasgeruch �ber den gesamten Festplatz. Die Anwesenden werden ohnm�chtig, �bergeben sich oder bekommen schwere Halluzinationen.");
			injectcommentary(wettkampf, "", $comment, $schema=false);		
			$comment=translate_inline("/me `\$�s Kochversuch verbreitet seinen ekligen Aasgeruch �ber den gesamten Gemeindeplatz. Die Anwesenden werden ohnm�chtig, �bergeben sich oder bekommen schwere Halluzinationen.");
			injectcommentary(village, "", $comment, $schema=false);		
		}
		
		//Folgende Werte werden gespeichert, damit sich die Sortierung der Bestenlisten nicht �ndert, wenn
		//jemand bspw. einen Level aufsteigt:
		set_module_pref("wkochenlevel", $session[user][level], "wettkampf");
		set_module_pref("wkochendk", $session[user][dragonkills], "wettkampf");
		set_module_pref("wkochenfw", $kochen, "wettkampf");
		
		if ($ergebnis < 0) $ergebnis=0;
		set_module_pref("wkochen", $ergebnis, "wettkampf");
		set_module_pref("letztespeise", $speise, "wettkampf");
		
		$bestkochen=get_module_pref("bestkochen", "wettkampf");
		
		if ($ergebnis > $bestkochen){
			set_module_pref("bestkochen", $ergebnis, "wettkampf");
			set_module_pref("bestespeise", $speise, "wettkampf");
			set_module_pref("bestkochenlevel", $session[user][level], "wettkampf");
			set_module_pref("bestkochendk", $session[user][dragonkills], "wettkampf");
			set_module_pref("bestkochenfw", $kochen, "wettkampf");
		}
		
		output("`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=kochen'>Weiter.</a>", true);
		addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=kochen");
		addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=kochen");
	page_footer();
}
?>