<?php

function wettkampf_wkochen_wkochenergebnis_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
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
			
		//Ungenießbares
		if ($ergebnis <= -95) $speise=translate_inline("eine äußerst widerliche, schwarze Pampe, die bis zum Gemeindeplatz nach Aas stinkt und Halluzinationen verursacht");
		else if ($ergebnis <= -85 && $ergebnis > -95) $speise=translate_inline("eine äußerst widerliche, schwarze Pampe, die über den ganzen Festplatz stinkt");
		else if ($ergebnis <= -75 && $ergebnis > -85) $speise=translate_inline("eine äußerst widerliche, schwarze Pampe");
		else if ($ergebnis <= -60 && $ergebnis > -75) $speise=translate_inline("eine widerliche, dunkelbraune Pampe");
		else if ($ergebnis <= -40 && $ergebnis > -60) $speise=translate_inline("eine ungenießbare, hellbraune Pampe");
		else if ($ergebnis <= -20 && $ergebnis > -40) $speise=translate_inline("eine hellbraune Pampe, die nur entfernt an etwas Eßbares erinnert");
		else if ($ergebnis <= 0 && $ergebnis > -20) $speise=translate_inline("eine hellbraune Pampe ohne jeden Geschmack");
	
		//Einfaches
		switch ($ergebnis){
			case 1: $speise=translate_inline("eine geschälte Steckrübe"); break;
			case 2: $speise=translate_inline("eine geschälte und gekochte Steckrübe"); break;
			case 3: $speise=translate_inline("eine geschälte, geschnittene und gekochte Steckrübe"); break;
			case 4: $speise=translate_inline("einen etwas zähen Haferbrei"); break;
			case 5: $speise=translate_inline("einen etwas klumpigen Haferbrei"); break;
			case 6: $speise=translate_inline("einen einfachen, aber gelungenen Haferbrei"); break;
			case 7: $speise=translate_inline("einen gezuckerten Haferbrei"); break;
			case 8: $speise=translate_inline("einen Haferbrei mit Steckrübensalat"); break;
			case 9: $speise=translate_inline("einen gezuckerten Haferbrei mit Steckrübensalat"); break;
			case 10: $speise=translate_inline("eine einfache Kartoffelsuppe"); break;
			case 11: $speise=translate_inline("eine mit Salz verfeinerte Kartoffelsuppe"); break;
			case 12: $speise=translate_inline("eine mit Salz verfeinerte Kartoffelsuppe mit Steckrübensalat"); break;
			case 13: $speise=translate_inline("eine mit Salz und Kräutern verfeinerte Kartoffelsuppe mit Steckrübensalat"); break;
			case 14: $speise=translate_inline("eine formvollendet gewürzte Kartoffelsuppe mit Steckrübensalat und Haferbrei als Nachtisch"); break;
			case 15: $speise=translate_inline("eine leckere Kartoffel-Steckrübensuppe mit gezuckertem Haferbrei als Nachtisch"); break;
			case 16: $speise=translate_inline("eine leckere Kartoffel-Steckrübensuppe mit einem Apfel-Birnen-Salat als Nachtisch"); break;
			case 17: $speise=translate_inline("eine leckere Kartoffel-Steckrübensuppe mit einem schön dekorierten Apfel-Birnen-Salat als Nachtisch"); break;
			case 18: $speise=translate_inline("eine leckere Kartoffelsuppe und einen Eintopf aus Kartoffeln und Mohrrüben mit einem Apfel-Birnensalat als Nachtisch"); break;
			case 19: $speise=translate_inline("eine würzige Kartoffelsuppe und einen Eintopf aus Kartoffeln, Erbsen und Mohrrüben mit einem Apfel-Birnensalat als Nachtisch"); break;
			case 20: $speise=translate_inline("eine vorzügliche Kartoffelsuppe und einen Eintopf aus Kartoffeln, Erbsen, Bohnen und Mohrrüben mit einem Apfel-Birnensalat als Nachtisch"); break;
			case 21: $speise=translate_inline("eine vorzügliche Kartoffelsuppe und einen schöndekorierten Eintopf aus Kartoffeln, Erbsen, Bohnen und Mohrrüben mit einem Apfel-Birnensalat als Nachtisch"); break;
			case 22: $speise=translate_inline("eine vorzügliche Kartoffelsuppe und einen schöndekorierten Eintopf aus Kartoffeln, Erbsen, Bohnen und Mohrrüben mit einem verzierten Apfel-Birnensalat als Nachtisch"); break;
			case 23: $speise=translate_inline("eine Erbsensuppe und Bratkartoffeln mit Spiegeleiern sowie einen vorzüglichen Apfelkompott"); break;
			case 24: $speise=translate_inline("eine feindekorierte Erbsensuppe und Bratkartoffeln mit Spiegeleiern sowie einen vorzüglichen Apfel-Birnen-Kompott"); break;
			case 25: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose Bratkartoffeln mit Spiegeleiern sowie einen vorzüglichen Apfel-Birnen-Kompott"); break;
			case 26: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichförmige Bratkartoffeln mit Spiegeleiern sowie einen vorzüglichen Apfel-Birnen-Kompott"); break;
			case 27: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichförmige Bratkartoffeln mit Rührei sowie einen vorzüglichen Apfel-Birnen-Kompott"); break;
			case 28: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichförmige Bratkartoffeln mit würzigem Rührei sowie einen vorzüglichen Apfel-Birnen-Kompott"); break;
			case 29: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichförmige Bratkartoffeln mit würzigem Rührei sowie einen Bratapfel auf Birnenkompott"); break;
			case 30: $speise=translate_inline("eine feindekorierte Erbsensuppe und makellose, gleichförmige Bratkartoffeln mit würzigem Rührei sowie einen verzierten Bratapfel auf Birnenkompott"); break;
			case 31: $speise=translate_inline("eine dekorierte Möhren-Erbsensuppe und goldbraune Eierpfannkuchen sowie einen einfachen Quark"); break;
			case 32: $speise=translate_inline("eine dekorierte Möhren-Erbsensuppe und goldbraune Eierpfannkuchen sowie einen feingezuckerten Quark"); break;
			case 33: $speise=translate_inline("eine dekorierte Möhren-Erbsensuppe und goldbraune Eierpfannkuchen mit Apfelstückchen sowie einen feingezuckerten Quark"); break;
			case 34: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und goldbraune Eierpfannkuchen mit Apfelstückchen sowie einen feingezuckerten Quark"); break;
			case 35: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und goldbraune, gleichmäßige Eierpfannkuchen mit Apfelstückchen sowie einen feingezuckerten Quark"); break;
			case 36: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und goldbraune, gleichmäßige Eierpfannkuchen mit Apfelstückchen sowie einen feingezuckerten, cremigen Quark"); break;
			case 37: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und goldbraune, gleichmäßige Eierpfannkuchen mit Apfel- und Birnenstückchen sowie einen feingezuckerten, cremigen Quark"); break;
			case 38: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und goldbraune, gleichmäßige Eierpfannkuchen mit Apfel- und Birnenstückchen sowie einen feingezuckerten, besonders cremigen Quark"); break;
			case 39: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und goldbraune, gleichmäßige Eierpfannkuchen mit gleichmäßigen Apfel- und Birnenstückchen sowie einen feingezuckerten, besonders cremigen Quark"); break;
			case 40: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und verzierte, goldbraune, gleichmäßige Eierpfannkuchen mit gleichmäßigen Apfel- und Birnenstückchen sowie einen feingezuckerten, besonders cremigen Quark"); break;
			case 41: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und verzierte, goldbraune, gleichmäßige Eierpfannkuchen mit gleichmäßigen Apfel- und Birnenstückchen sowie einen feingezuckerten, besonders cremigen Quark mit Kirschen"); break;
			case 42: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und verzierte, goldbraune, gleichmäßige Eierpfannkuchen mit gleichmäßigen Apfel- und Birnenstückchen sowie einen feingezuckerten, besonders cremigen Quark mit ausgewählten Kirschen"); break;
			case 43: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und verzierte, goldbraune, gleichmäßige Eierpfannkuchen mit gleichmäßigen Apfel- und Birnenstückchen sowie einen feingezuckerten, besonders cremigen Quark mit ausgewählten, halbierten Kirschen"); break;
			case 44: $speise=translate_inline("eine feindekorierte Möhren-Erbsensuppe und verzierte, goldbraune, gleichmäßige Eierpfannkuchen mit gleichmäßigen Apfel- und Birnenstückchen sowie einen feingezuckerten, besonders cremigen Quark mit ausgewählten, kunstvoll geschnittenen Kirschen"); break;
			case 45: $speise=translate_inline("eine dekorierte Kartoffel-Möhren-Erbsensuppe und Salzkartoffeln mit Rosenkohl sowie einen vorzüglichen Sahnequark mit Mandarinen"); break;
			case 46: $speise=translate_inline("eine feindekorierte Kartoffel-Möhren-Erbsensuppe und Salzkartoffeln mit Rosenkohl sowie einen vorzüglichen Sahnequark mit Mandarinen"); break;
			case 47: $speise=translate_inline("eine feindekorierte Kartoffel-Möhren-Erbsensuppe und Salzkartoffeln mit Rosenkohl sowie einen vorzüglichen, verzierten Sahnequark mit Mandarinen"); break;
			case 48: $speise=translate_inline("eine feindekorierte Kartoffel-Möhren-Erbsensuppe und Salzkartoffeln mit Rosenkohl und Spiegeleiern sowie einen vorzüglichen, verzierten Sahnequark mit Mandarinen"); break;
			case 49: $speise=translate_inline("eine feindekorierte Kartoffel-Möhren-Erbsensuppe und Salzkartoffeln mit Rosenkohl in einer Kräutersoße sowie einen vorzüglichen, verzierten Sahnequark mit Mandarinen"); break;
			case 50: $speise=translate_inline("eine feindekorierte Kartoffel-Möhren-Erbsensuppe und Salzkartoffeln mit Rosenkohl in einer besonders delikaten Kräutersoße sowie einen vorzüglichen, verzierten Sahnequark mit Mandarinen"); break;
		
			//Normales
			case 51: $speise=translate_inline("eine Zwiebelsuppe und einen Kartoffelauflauf mit Erbsen, Möhren und Zwiebeln sowie einen vorzüglichen Sahnequark mit Preiselbeeren"); break;
			case 52: $speise=translate_inline("eine dekorierte Zwiebelsuppe und einen mit Käse überbackenen Kartoffelauflauf mit Erbsen, Möhren und Zwiebeln sowie einen vorzüglichen Sahnequark mit Preiselbeeren"); break;
			case 53: $speise=translate_inline("eine feindekorierte Zwiebelsuppe und einen mit Käse überbackenen Kartoffelauflauf mit Erbsen, Möhren, Zwiebeln und erlesenen Kräutern sowie einen vorzüglichen Sahnequark mit Preiselbeeren"); break;
			case 54: $speise=translate_inline("eine feindekorierte Zwiebelsuppe und einen mit Käse überbackenen Kartoffelauflauf mit Erbsen, Möhren, Zwiebeln und erlesenen Kräutern sowie einen vorzüglichen, verzierten Sahnequark mit Preiselbeeren"); break;
			case 55: $speise=translate_inline("eine dekorierte Gemüsesuppe und einen mit Käse überbackenen Kartoffelauflauf mit Erbsen, Möhren, Zwiebeln und erlesenen Kräutern sowie einen vorzüglichen, verzierten Sahnequark mit Preiselbeeren"); break;
			case 56: $speise=translate_inline("eine feindekorierte Gemüsesuppe und einen mit Käse überbackenen Kartoffelauflauf mit Erbsen, Möhren, Zwiebeln und erlesenen Kräutern sowie einen vorzüglichen, verzierten Sahnequark mit Preiselbeeren"); break;
			case 57: $speise=translate_inline("eine feindekorierte, vorzügliche Gemüsesuppe und einen mit Käse überbackenen Kartoffelauflauf mit Erbsen, Möhren, Zwiebeln und erlesenen Kräutern sowie einen vorzüglichen, verzierten Sahnequark mit Preiselbeeren"); break;
			case 58: $speise=translate_inline("eine feindekorierte, vorzügliche Gemüsesuppe und einen mit Käse überbackenen Kartoffelauflauf mit Erbsen, Möhren, Zwiebeln und erlesenen Kräutern sowie einen vorzüglichen, verzierten Sahnequark mit Preiselbeeren und einer Sahnehaube"); break;
			case 59: $speise=translate_inline("eine feindekorierte, vorzügliche Gemüsesuppe und einen mit Käse überbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit Kirschen"); break;
			case 60: $speise=translate_inline("eine vorzügliche Tomatencremesuppe und einen mit Käse überbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit Kirschen"); break;
			case 61: $speise=translate_inline("eine vorzügliche Tomatencremesuppe und einen mit Käse überbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit heißen Kirschen"); break;
			case 62: $speise=translate_inline("eine dekorierte, vorzügliche Tomatencremesuppe und einen mit Käse überbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit heißen Kirschen"); break;
			case 63: $speise=translate_inline("eine feindekorierte, vorzügliche Tomatencremesuppe und einen mit Käse überbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit heißen Kirschen"); break;
			case 64: $speise=translate_inline("eine feindekorierte, vorzügliche Tomatencremesuppe und einen mit Käse überbackenen Kartoffel-Auberginen-Auflauf sowie einen Vanillequark mit erlesenen, heißen Kirschen"); break;
			case 65: $speise=translate_inline("eine feindekorierte, vorzügliche Tomatencremesuppe und selbstgemachte Bandnudeln mit einer würzigen Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen"); break;
			case 66: $speise=translate_inline("eine feindekorierte, vorzügliche Tomatencremesuppe und selbstgemachte Bandnudeln mit einer feinwürzigen Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen"); break;
			case 67: $speise=translate_inline("eine feindekorierte, vorzügliche Tomatencremesuppe und selbstgemachte Bandnudeln mit einer feinwürzigen Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 68: $speise=translate_inline("eine feindekorierte, vorzügliche Tomatencremesuppe und selbstgemachte Spiralnudeln mit einer feinwürzigen Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 69: $speise=translate_inline("eine einfache Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinwürzigen Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 70: $speise=translate_inline("eine einfache, dekorierte Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinwürzigen Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 71: $speise=translate_inline("eine einfache, feindekorierte Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinwürzigen Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 72: $speise=translate_inline("eine vorzügliche, feindekorierte Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinwürzigen Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 73: $speise=translate_inline("eine vorzügliche, feindekorierte Spargelsuppe und selbstgemachte Spiralnudeln mit einer feinwürzigen Zwei-Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 74: $speise=translate_inline("eine dekorierte Spargelcremesuppe und selbstgemachte Spiralnudeln mit einer feinwürzigen Zwei-Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
			case 75: $speise=translate_inline("eine feindekorierte Spargelcremesuppe und selbstgemachte Spiralnudeln mit einer feinwürzigen Zwei-Käse-Soße sowie einen Vanillepudding mit erlesenen Kirschen aus den Hochebenen von Chrizzak"); break;
		
			//Schwieriges
			case 76: $speise=translate_inline("eine feindekorierte, vorzügliche Spargelcremesuppe und selbstgemachte Gnocchi mit einer feurigen Drei-Käse-Soße sowie einen Vanillepudding mit echten Schokoladenstückchen"); break;
			case 77: $speise=translate_inline("eine feindekorierte, vorzügliche Spargelcremesuppe und selbstgemachte, gleichförmige Gnocchi mit einer feurigen Drei-Käse-Soße sowie einen Vanillepudding mit echten Schokoladenstückchen"); break;
			case 78: $speise=translate_inline("eine feindekorierte, vorzügliche Spargelcremesuppe und selbstgemachte, gleichförmige Gnocchi mit einer feurigen Edelkäse-Soße sowie einen Vanillepudding mit echten Schokoladenstückchen"); break;
			case 79: $speise=translate_inline("eine feindekorierte, vorzügliche Spargelcremesuppe und selbstgemachte, gleichförmige Gnocchi mit einer feurigen Edelkäse-Soße sowie einen Vanillepudding mit echten, geraspelten Schokoladenstückchen"); break;
			case 80: $speise=translate_inline("eine feindekorierte, vorzügliche Spargelcremesuppe und selbstgemachte, gleichförmige Gnocchi mit einer feurigen Edelkäse-Soße sowie einen Vanillepudding mit echten, feingeraspelten Schokoladenstückchen"); break;
			case 81: $speise=translate_inline("eine feindekorierte, vorzügliche Spargelcremesuppe und selbstgemachte, gleichförmige Gnocchi mit einer feurigen Soße aus trollischem Edelkäse sowie einen Vanillepudding mit echten, feingeraspelten Schokoladenstückchen"); break;
			case 82: $speise=translate_inline("eine feindekorierte, vorzügliche Spargelcremesuppe und selbstgemachte, gleichförmige Gnocchi mit einer feurigen Soße aus trollischem Edelkäse sowie einen Vanillepudding mit echten, feingeraspelten Schokoladenstückchen und einer Sahnehaube"); break;
			case 83: $speise=translate_inline("einen feindekorierten Salat und einen gemischten Teller vorzüglichster Nudeln mit einer würzigen Sahnesoße aus Edelkäse und Gebirgskräutern aus dem Drassok sowie eine raffinierte Sahnespeise mit einem Hauch von Trüffel"); break;
			case 84: $speise=translate_inline("einen feindekorierten, vorzüglichen Salat und einen gemischten Teller vorzüglichster Nudeln mit einer würzigen Sahnesoße aus Edelkäse und Gebirgskräutern aus dem Drassok sowie eine raffinierte Sahnespeise mit einem Hauch von Trüffel"); break;
			case 85: $speise=translate_inline("einen feindekorierten, vorzüglichen Salat und einen gemischten Teller vorzüglichster Nudeln mit einer würzigen Sahnesoße aus trollischem Edelkäse und Gebirgskräutern aus dem Drassok sowie eine raffinierte Sahnespeise mit einem Hauch von Trüffel"); break;
			case 86: $speise=translate_inline("einen feindekorierten, vorzüglichen Salat und einen gemischten Teller vorzüglichster Nudeln mit einer würzigen Sahnesoße aus trollischem Edelkäse und Gebirgskräutern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem Hauch von Trüffel"); break;
			case 87: $speise=translate_inline("einen feindekorierten, vorzüglichen Salat und einen gemischten Teller vorzüglichster Nudeln mit einer würzigen Sahnesoße aus trollischem Edelkäse und Gebirgskräutern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem halben Trüffel"); break;
			case 88: $speise=translate_inline("einen feindekorierten, vorzüglichen Salat und einen gemischten Teller vorzüglichster Nudeln mit einer würzigen Sahnesoße aus trollischem Edelkäse und Gebirgskräutern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem ganzen Trüffel"); break;
			case 89: $speise=translate_inline("einen feindekorierten, vorzüglichen Salat und einen gemischten Teller vorzüglichster Nudeln mit einer würzig-cremigen Sahnesoße aus trollischem Edelkäse und Gebirgskräutern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem halben Trüffel"); break;
			case 90: $speise=translate_inline("einen feindekorierten, vorzüglichen Salat und einen gemischten Teller vorzüglichster Nudeln mit einer würzig-cremigen Sahnesoße aus trollischem Edelkäse und Gebirgskräutern aus dem Drassok sowie eine raffinierte Sahneerdbeerspeise mit einem ganzen Trüffel"); break;
			case 91: $speise=translate_inline("einen feindekorierten, vorzüglichen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Waldkräutern sowie eine Waldbeerenspeise mit einem Hauch des teuren Trüffelzuckers aus den überseeischen Handelskolonien"); break;
			case 92: $speise=translate_inline("einen vorzüglichen elfischen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Waldkräutern sowie eine Waldbeerenspeise mit einem Hauch des teuren Trüffelzuckers aus den überseeischen Handelskolonien"); break;
			case 93: $speise=translate_inline("einen vorzüglichen elfischen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Kräutern aus den Wäldern der Hochelfen sowie eine Waldbeerenspeise mit einem Hauch des teuren Trüffelzuckers aus den überseeischen Handelskolonien"); break;
			case 94: $speise=translate_inline("einen vorzüglichen elfischen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Kräutern aus den Wäldern der Hochelfen sowie eine Waldbeerenspeise mit einer Haube des teuren Trüffelzuckers aus den überseeischen Handelskolonien"); break;
			case 95: $speise=translate_inline("einen vorzüglichen elfischen Fruchtsalat und eine zweiteilige Hauptspeise aus elfischen Cabuc-Kartoffeln und Kräutern aus den Wäldern der Hochelfen sowie eine Waldbeerenspeise mit einer entzündeten Haube des teuren Trüffelzuckers aus den überseeischen Handelskolonien"); break;
			case 96: $speise=translate_inline("ein flambiertes Vanillesufflet und mit Früchten gefüllte Edelpilze mit einer vielfarbigen cremigen Soße aus Ratschukbeeren"); break;
			case 97: $speise=translate_inline("ein flambiertes Vanillesufflet und mit Früchten gefüllte trollische Edelpilze mit einer vielfarbigen cremigen Soße aus Ratschukbeeren"); break;
			case 98: $speise=translate_inline("ein flambiertes Vanillesufflet und mit Früchten gefüllte trollische Edelpilze mit einer vielfarbigen cremigen Soße aus Ratschukbeeren und eine vorzügliche Weincreme"); break;
			case 99: $speise=translate_inline("ein flambiertes Vanillesufflet und mit Früchten gefüllte trollische Edelpilze mit einer vielfarbigen cremigen Soße aus Ratschukbeeren und eine vorzügliche Weincreme nach einem Rezept der Großmutter von Großinquisitor Resal"); break;
			case 100: $speise=translate_inline("ein flambiertes Schokoladensufflet und mit Früchten gefüllte trollische Edelpilze mit einer vielfarbigen cremigen Soße aus Ratschukbeeren und eine vorzügliche Weincreme nach einem Rezept der Großmutter von Großinquisitor Resal"); break;
			case 101: $speise=translate_inline("ein flambiertes Schokoladensufflet und mit elfischen Waldfrüchten gefüllte trollische Edelpilze mit einer vielfarbigen cremigen Soße aus Ratschukbeeren und eine vorzügliche Weincreme nach einem Rezept der Großmutter von Großinquisitor Resal"); break;
			case 102: $speise=translate_inline("ein flambiertes Schokoladensufflet und mit elfischen Waldfrüchten gefüllte trollische Edelpilze mit einer vielfarbigen cremigen Soße aus Ratschukbeeren und eine vorzügliche Weincreme nach einem Rezept der Großmutter von Großinquisitor Resal"); break;
			case 103: $speise=translate_inline("ein flambiertes Schokoladen-Vanille-Sufflet und mit elfischen Waldfrüchten gefüllte drassorianische Stollenpilze, garniert mit handverlesenen Sumbrastengeln und eine vorzügliche Weincreme nach einem Rezept der Großmutter von Großinquisitor Resal"); break;
			case 104: $speise=translate_inline("ein flambiertes Schokoladen-Vanille-Sufflet und mit elfischen Waldfrüchten gefüllte drassorianische Stollenpilze, garniert mit handverlesenen Sumbrastengeln aus Chrizzak und eine vorzügliche Weincreme nach einem Rezept der Großmutter von Großinquisitor Resal"); break;
			case 105: $speise=translate_inline("ein flambiertes Schokoladen-Vanille-Sufflet und mit elfischen Waldfrüchten gefüllte drassorianische Stollenpilze, garniert mit handverlesenen Sumbrastengeln aus Chrizzak und eine ähm ... 'vorzügliche' Rübencreme nach einem Rezept der Mutter des allmächtigen Zrarek"); break;
			case 106: $speise=translate_inline("ein flambiertes Schokoladen-Vanille-Sufflet und mit elfischen Waldfrüchten gefüllte drassorianische Stollenpilze, garniert mit handverlesenen Sumbrastengeln aus Chrizzak und eine ähm ... 'vorzügliche' Rübencreme nach einem Rezept der Mutter des allmächtigen Zrarek"); break;
			case 107: $speise=translate_inline("eine kunstvolle Zusammenstellung der traditionellen Lieblingsspeisen aller großen Völker"); break;
			case 108: $speise=translate_inline("eine kunstvolle Zusammenstellung der traditionellen Lieblingsspeisen aller großen Völker, verfeinert mit dem Wissen und Können eines Meisterkochs"); break;
			case 109: $speise=translate_inline("eine kunstvolle Zusammenstellung der traditionellen Lieblingsspeisen aller großen Völker, verfeinert mit dem Wissen und Können eines Meisterkochs sowie ähm ... eine 'vorzügliche' Steckrübensahnespeise nach einem Rezept der Gemahlin des allmächtigen Zrarek"); break;
			case 110: $speise=translate_inline("eine kunstvolle Zusammenstellung der traditionellen Lieblingsspeisen aller großen Völker, verfeinert mit dem Wissen und Können eines Meisterkochs sowie ähm ... eine 'vorzügliche' Steckrübensahnespeise nach einem Rezept der Gemahlin des allmächtigen Zrarek mit einem Schuss Met"); break;
		
			//Spezialitäten
			case 111: $speise=translate_inline("trollische Chr'phala-Pilze, die atemberaubendste Pilzspeise im ganzen Land"); break;
			case 112: $speise=translate_inline("echsische Zra'kras, eine Keksvariation aus den edelsten Zutaten im ganzen Land"); break;
			case 113: $speise=translate_inline("drassorianische Muldenbronks, ein ... nun ja, im Grunde sind es Rüben, aber sie schmecken selbst den Elfen vorzüglich"); break;
			case 114: $speise=translate_inline("eine raffinierte Kombination der raffiniertesten Speisen der Wald- und Hochelfen, genannt 'Je'direy'"); break;
			case 115: $speise=translate_inline("ein wahrhaft fürstliches Fünfgängemenü"); break;
			case 116: $speise=translate_inline("ein wahrhaft königliches Sechsgängemenü"); break;
			case 117: $speise=translate_inline("ein wahrhaft kaiserliches Siebengängemenü"); break;
			case 118: $speise=translate_inline("ein wahrhaft gottkaiserliches Achtgängemenü"); break;
			case 119: $speise=translate_inline("ein wahrhaft überirdisches Neungängemenü"); break;
			case 120: $speise=translate_inline("ein wahrhaft göttliches Zehngängemenü"); break;
			case 121: $speise=translate_inline("ein wahrhaft unbeschreibliches Elfgängemenü"); break;
			case 122: $speise=translate_inline("das beste Zwölfgängemenü, das diese Welt jemals gesehen hat und sehen wird"); break;
			case 123: $speise=translate_inline("ein Dreizehngängemenü, das sogar Ramius' Interesse auf sich gezogen hat"); break;
			case 124: $speise=translate_inline("das Vierzehngängemenü, das selbst das göttliche Ambrosia in den Schatten stellt und wofür Dich mancher Gott zu seinesgleichen zählen würde"); break;
			case 125: $speise=translate_inline("einen leeren Teller, den Du mit langen Worten zu Kunst erklärst und der als kulinarische Revolution in die Geschichte eingeht"); break;
		}
		$punkte=$ergebnis;
		if ($ergebnis<0) $punkte=0;
	
		if ($ergebnis >0) output("`@Du hast alles gegeben und Deine Pläne immer wieder verändert, um bloß nichts Mißlungenes zu präsentieren. Schließlich überreichst Du der Jury `^%s`@, wofür Du `^%s`@ von den `^%s`@ Punkten bekommst, die auf diesem Schwierigkeitsgrad möglich sind.`n`n", $speise, $punkte, $schwierigkeit_text);
		if ($ergebnis <=0 && $ergebnis > -20) output("`@Du hast alles gegeben und Deine Pläne immer wieder verändert, um bloß nichts Mißlungenes zu präsentieren ... Aber es hat nichts genützt, etwas beschämt überreichst Du der Jury `^%s`@, wofür Du natürlich keine Punkte bekommst.`n`n", $speise);
		if ($ergebnis <= -20 && $ergebnis > -40) output("`@Du hast alles gegeben und Deine Pläne immer wieder verändert, um bloß nichts Mißlungenes zu präsentieren ... Aber es hat nichts genützt, etwas beschämt überreichst Du der Jury `^%s`@, wofür Du natürlich keine Punkte bekommst.`n`n", $speise); 
		if ($ergebnis <= -40 && $ergebnis > -60) output("`@Du hast alles gegeben und Deine Pläne immer wieder verändert, um bloß nichts Mißlungenes zu präsentieren ... Aber es hat nichts genützt, mit hochrotem Gesicht überreichst Du der Jury `^%s`@, wofür Du natürlich keine Punkte bekommst.`n`n", $speise);
		if ($ergebnis <= -60 && $ergebnis > -75) output("`@Du hast alles gegeben und Deine Pläne immer wieder verändert, um bloß nichts Mißlungenes zu präsentieren ... Aber es hat nichts genützt, mit hochrotem Gesicht überreichst Du der Jury `^%s`@. Angewidert wenden sich die Fachleute von Dir ab. Die umstehenden Zuschauer buhen Dich aus.`n`n", $speise);
		if ($ergebnis <= -75 && $ergebnis > -85) output("`@Du hast alles gegeben und Deine Pläne immer wieder verändert, um bloß nichts Mißlungenes zu präsentieren ... Aber es hat nichts genützt, mit hochrotem Gesicht und einem mulmigen Gefühl überreichst Du der Jury `^%s`@. Die Fachleute sind empört und die umstehenden Zuschauer schütteln die Köpfe.`n`n", $speise);
		if ($ergebnis <= -85 && $ergebnis > -100){
			require_once("lib/commentary.php");
			output("`@Du hast alles gegeben und Deine Pläne immer wieder verändert, um bloß nichts Mißlungenes zu präsentieren ... Aber es hat nichts genützt, mit hochrotem Gesicht und dem Gefühl, Dich gleich übergeben zu müssen überreichst Du der Jury `^%s`@. Vor lauter Kotzerei kommt keiner der Fachleute dazu, Dir seine Meinung über dieses Teufelszeug zu sagen.`n`n", $speise);
			$comment=translate_inline("/me `\$´s Kochversuch verbreitet seinen widerwärtigen Gestank über den gesamten Festplatz, wovon sich einige Leute übergeben müssen und zum Teil sogar ohnmächtig werden.");
			injectcommentary(wettkampf, "", $comment, $schema=false);
		}
		if ($ergebnis <= -100){
			require_once("lib/commentary.php");
			output("`@Du hast alles gegeben und Deine Pläne immer wieder verändert, um bloß nichts Mißlungenes zu präsentieren ... Aber es hat nichts genützt, mit hochrotem Gesicht und dem Gefühl, Dich gleich übergeben zu müssen, das von dem seltsamen Wahnzustand begleitet wird, dass Du ein dreischwänziges Eichhörnchen bist, überreichst Du der Jury `^%s`@. Ein Teil der umstehenden Zuschauer übergibt sich, während ein anderer Teil im Boden nach Nüssen gräbt. Die Fachleute sind allesamt ohnmächtig geworden.`n`n", $speise);
			$comment=translate_inline("/me `\$´s Kochversuch verbreitet seinen ekligen Aasgeruch über den gesamten Festplatz. Die Anwesenden werden ohnmächtig, übergeben sich oder bekommen schwere Halluzinationen.");
			injectcommentary(wettkampf, "", $comment, $schema=false);		
			$comment=translate_inline("/me `\$´s Kochversuch verbreitet seinen ekligen Aasgeruch über den gesamten Gemeindeplatz. Die Anwesenden werden ohnmächtig, übergeben sich oder bekommen schwere Halluzinationen.");
			injectcommentary(village, "", $comment, $schema=false);		
		}
		
		//Folgende Werte werden gespeichert, damit sich die Sortierung der Bestenlisten nicht ändert, wenn
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